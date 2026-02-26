<?php

namespace App\Http\Controllers;

use App\Models\ContactLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LandingController extends Controller
{
    /**
     * Mostrar el landing page (versión oscura/comercial)
     */
    public function index()
    {
        return view('landing.index');
    }

    /**
     * Mostrar el landing page alternativo (versión clara/informativa)
     */
    public function indexAlt()
    {
        return view('landing-alt.index');
    }

    /**
     * Procesar el formulario de contacto
     */
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'nullable|string|max:50',
            'empresa' => 'nullable|string|max:255',
            'cargo' => 'nullable|string|max:255',
            'mensaje' => 'required|string|max:2000',
            'acepta_terminos' => 'accepted',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'Ingresa un email válido.',
            'mensaje.required' => 'El mensaje es obligatorio.',
            'acepta_terminos.accepted' => 'Debes aceptar los términos y condiciones.',
        ]);

        try {
            // Crear el lead en la base de datos
            $lead = ContactLead::create([
                'nombre' => $validated['nombre'],
                'email' => $validated['email'],
                'telefono' => $validated['telefono'] ?? null,
                'empresa' => $validated['empresa'] ?? null,
                'cargo' => $validated['cargo'] ?? null,
                'mensaje' => $validated['mensaje'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'nuevo',
            ]);

            // Enviar email de notificación a ventas
            $this->sendNotificationEmail($lead);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '¡Gracias por contactarnos! Nos comunicaremos contigo pronto.',
                ]);
            }

            return back()->with('success', '¡Gracias por contactarnos! Nos comunicaremos contigo pronto.');
        } catch (\Exception $e) {
            Log::error('Error al guardar lead de contacto: '.$e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hubo un error al enviar tu mensaje. Por favor, intenta nuevamente.',
                ], 500);
            }

            return back()->with('error', 'Hubo un error al enviar tu mensaje. Por favor, intenta nuevamente.');
        }
    }

    /**
     * Enviar email de notificación a ventas
     */
    private function sendNotificationEmail(ContactLead $lead)
    {
        try {
            $salesEmail = config('mail.sales_email', 'ventas@cyhsur.com');

            Mail::send('emails.contact-lead-notification', ['lead' => $lead], function ($message) use ($lead, $salesEmail) {
                $message->to($salesEmail)
                    ->subject('Nuevo Lead de Contacto - '.$lead->nombre);
            });
        } catch (\Exception $e) {
            Log::warning('No se pudo enviar email de notificación de lead: '.$e->getMessage());
        }
    }
}
