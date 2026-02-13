<?php

namespace App\Console\Commands;

use App\Mail\AlertaNotificationMail;
use App\Models\AlertaAdmin;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcesarAlertas extends Command
{
    protected $signature = 'alertas:procesar';

    protected $description = 'Evalua alertas activas y dispara notificaciones, correos y dashboard cuando se acerca la fecha configurada';

    /**
     * Default days of anticipation if not configured on the alert.
     */
    const DEFAULT_DIAS_ANTICIPACION = 3;

    public function handle(): int
    {
        $hoy = Carbon::today();
        $procesadas = 0;
        $disparadas = 0;

        $alertas = AlertaAdmin::with(['user', 'servicioUsuario', 'destinatarioUser', 'rodado', 'cliente'])
            ->where('activa', true)
            ->get();

        foreach ($alertas as $alerta) {
            $procesadas++;

            if ($this->debeDisparar($alerta, $hoy)) {
                $this->dispararAcciones($alerta);
                $this->marcarEjecutada($alerta, $hoy);
                $disparadas++;

                $this->line("  [DISPARADA] #{$alerta->id} - {$alerta->titulo}");
            }
        }

        $this->info("Alertas procesadas: {$procesadas} | Disparadas: {$disparadas}");
        Log::info("ProcesarAlertas: {$procesadas} procesadas, {$disparadas} disparadas.");

        return Command::SUCCESS;
    }

    /**
     * Determine if an alert should fire today based on its type and configuration.
     */
    private function debeDisparar(AlertaAdmin $alerta, Carbon $hoy): bool
    {
        // Skip if already fired in this cycle
        if ($this->yaEjecutadaEnCiclo($alerta, $hoy)) {
            return false;
        }

        return match ($alerta->tipo) {
            'vencimiento', 'pago_servicio', 'vencimiento_pago' => $this->evaluarVencimiento($alerta, $hoy),
            'cobro_cliente' => $this->evaluarCobroCliente($alerta, $hoy),
            'personalizada' => $this->evaluarPersonalizada($alerta, $hoy),
            // km_vehiculo se evalúa por kilometraje, no por fecha — se omite aquí
            default => false,
        };
    }

    /**
     * Evaluate date-based alerts (vencimiento / pago de servicio).
     *
     * Logic: fires when today >= (fecha_alerta - dias_anticipacion)
     * For recurring: fecha_alerta represents the next due date.
     */
    private function evaluarVencimiento(AlertaAdmin $alerta, Carbon $hoy): bool
    {
        if (! $alerta->fecha_alerta) {
            return false;
        }

        $fechaVencimiento = $alerta->fecha_alerta->copy()->startOfDay();
        $diasAnticipacion = $alerta->dias_anticipacion ?? self::DEFAULT_DIAS_ANTICIPACION;
        $fechaDisparo = $fechaVencimiento->copy()->subDays($diasAnticipacion);

        // Are we within the alert window? (between disparo date and vencimiento date)
        return $hoy->gte($fechaDisparo) && $hoy->lte($fechaVencimiento);
    }

    /**
     * Evaluate cobro_cliente alerts.
     *
     * Logic: fires X days before the configured day of the month.
     * E.g., dia_mes=15, dias_anticipacion=3 → fires on day 12.
     */
    private function evaluarCobroCliente(AlertaAdmin $alerta, Carbon $hoy): bool
    {
        $diaMes = $alerta->dia_mes ?? ($alerta->trigger_config['dia_mes'] ?? null);
        if (! $diaMes) {
            return false;
        }

        $diasAnticipacion = $alerta->dias_anticipacion ?? self::DEFAULT_DIAS_ANTICIPACION;

        // Calculate the target date for this month
        $mesActual = $hoy->copy()->startOfMonth();
        $diaReal = min($diaMes, $mesActual->daysInMonth);
        $fechaCobro = $mesActual->copy()->day($diaReal);

        $fechaDisparo = $fechaCobro->copy()->subDays($diasAnticipacion);

        return $hoy->gte($fechaDisparo) && $hoy->lte($fechaCobro);
    }

    /**
     * Evaluate personalizada alerts — they may or may not have a date.
     */
    private function evaluarPersonalizada(AlertaAdmin $alerta, Carbon $hoy): bool
    {
        // If it has a date, use vencimiento logic
        if ($alerta->fecha_alerta) {
            return $this->evaluarVencimiento($alerta, $hoy);
        }

        // If no date, it was a one-time alert that should have fired at creation.
        // We don't auto-fire alerts without dates from the scheduler.
        return false;
    }

    /**
     * Check if the alert was already executed in the current firing window.
     */
    private function yaEjecutadaEnCiclo(AlertaAdmin $alerta, Carbon $hoy): bool
    {
        if (! $alerta->ultima_ejecucion) {
            return false;
        }

        // If it was executed today, skip
        return $alerta->ultima_ejecucion->isToday();
    }

    /**
     * Fire all configured actions for the alert.
     */
    private function dispararAcciones(AlertaAdmin $alerta): void
    {
        if ($alerta->tieneAccion(AlertaAdmin::ACCION_NOTIFICACION)) {
            $this->crearNotificacion($alerta);
        }

        if ($alerta->tieneAccion(AlertaAdmin::ACCION_CORREO)) {
            $this->enviarCorreo($alerta);
        }

        // Dashboard action is passive — the dashboard reads alerts directly.
        // No action needed here for 'dashboard'.
    }

    /**
     * Mark the alert as executed and advance to next cycle if recurring.
     */
    private function marcarEjecutada(AlertaAdmin $alerta, Carbon $hoy): void
    {
        $alerta->update(['ultima_ejecucion' => now()]);

        // For recurring alerts, advance fecha_alerta to the next cycle
        // only AFTER the current date has passed the vencimiento
        if ($alerta->recurrente && $alerta->fecha_alerta && $hoy->gte($alerta->fecha_alerta)) {
            $nuevaFecha = match ($alerta->frecuencia_recurrencia) {
                'mensual' => $alerta->fecha_alerta->copy()->addMonth(),
                'semanal' => $alerta->fecha_alerta->copy()->addWeek(),
                'diaria' => $alerta->fecha_alerta->copy()->addDay(),
                default => $alerta->fecha_alerta->copy()->addMonth(),
            };

            $alerta->update([
                'fecha_alerta' => $nuevaFecha,
                'ultima_ejecucion' => null, // Reset for next cycle
            ]);

            $this->line("    → Recurrente: proxima fecha = {$nuevaFecha->format('Y-m-d')}");
        }

        // For non-recurring alerts that have passed, deactivate
        if (! $alerta->recurrente && $alerta->fecha_alerta && $hoy->gt($alerta->fecha_alerta)) {
            $alerta->update(['activa' => false]);
            $this->line('    → No recurrente: alerta desactivada');
        }
    }

    /**
     * Create in-app notifications.
     */
    private function crearNotificacion(AlertaAdmin $alerta): void
    {
        // Notification for the creator (admin)
        if (in_array($alerta->destinatario_tipo, ['admin', 'ambos'])) {
            Notification::create([
                'title' => 'Recordatorio: '.$alerta->titulo,
                'message' => $alerta->descripcion ?? $alerta->titulo,
                'type' => 'user',
                'priority' => 'NORMAL',
                'is_active' => true,
                'user_id' => $alerta->user_id,
            ]);
        }

        // Notification for the client user
        if (in_array($alerta->destinatario_tipo, ['cliente', 'ambos']) && $alerta->destinatario_user_id) {
            Notification::create([
                'title' => 'Recordatorio: '.$alerta->titulo,
                'message' => $alerta->descripcion ?? $alerta->titulo,
                'type' => 'user',
                'priority' => 'NORMAL',
                'is_active' => true,
                'user_id' => $alerta->destinatario_user_id,
            ]);
        }
    }

    /**
     * Send email notifications.
     */
    private function enviarCorreo(AlertaAdmin $alerta): void
    {
        // Email to admin (creator)
        if (in_array($alerta->destinatario_tipo, ['admin', 'ambos'])) {
            $user = $alerta->user;
            if ($user && $user->email) {
                try {
                    Mail::to($user->email)->send(new AlertaNotificationMail($alerta, $user->name));
                    $this->line("    → Correo enviado a {$user->email}");
                } catch (\Exception $e) {
                    Log::error("ProcesarAlertas: Error email admin #{$alerta->id}: {$e->getMessage()}");
                    $this->error("    → Error email: {$e->getMessage()}");
                }
            }
        }

        // Email to client user
        if (in_array($alerta->destinatario_tipo, ['cliente', 'ambos']) && $alerta->destinatario_user_id) {
            $destinatario = $alerta->destinatarioUser;
            if ($destinatario && $destinatario->email) {
                try {
                    Mail::to($destinatario->email)->send(new AlertaNotificationMail($alerta, $destinatario->name));
                    $this->line("    → Correo enviado a {$destinatario->email}");
                } catch (\Exception $e) {
                    Log::error("ProcesarAlertas: Error email cliente #{$alerta->id}: {$e->getMessage()}");
                    $this->error("    → Error email: {$e->getMessage()}");
                }
            }
        }
    }
}
