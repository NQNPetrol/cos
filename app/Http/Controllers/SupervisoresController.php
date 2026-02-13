<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Personal;
use App\Models\Patrulla;
use App\Models\SupervisorPatrulla;
use App\Models\SupervisorEmpresaAsociada;
use App\Models\EmpresaAsociada;

class SupervisoresController extends Controller
{
    /**
     * Display a listing of supervisors for the logged-in user's client.
     */
    public function index()
    {
        $user = auth()->user();
        $cliente = $user->clientes()->first();

        if (!$cliente) {
            abort(403, 'No tiene un cliente asociado.');
        }

        $clienteId = $cliente->id;

        // Get all users with role clientsupervisor that belong to this client
        $supervisores = User::role('clientsupervisor')
            ->whereHas('clientes', fn($q) => $q->where('clientes.id', $clienteId))
            ->with([
                'personal.supervisorPatrulla.patrulla',
                'personal.empresasAsociadas',
            ])
            ->get();

        $conPersonal = $supervisores->filter(fn($s) => $s->personal !== null)->count();
        $conPatrulla = $supervisores->filter(fn($s) => $s->personal && $s->personal->supervisorPatrulla !== null)->count();

        // Empresas asociadas del cliente para el modal de asignación
        $empresasAsociadas = $cliente->empresasAsociadas ?? collect();

        return view('supervisores.index', compact(
            'supervisores',
            'conPersonal',
            'conPatrulla',
            'empresasAsociadas',
            'clienteId'
        ));
    }

    /**
     * Assign a personal record to a supervisor user.
     */
    public function asignarPersonal(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'personal_id' => 'required|exists:personal,id',
        ]);

        $user = auth()->user();
        $cliente = $user->clientes()->first();

        if (!$cliente) {
            return response()->json(['error' => 'No tiene un cliente asociado.'], 403);
        }

        $personal = Personal::where('id', $request->personal_id)
            ->whereNull('user_id')
            ->where('cliente_id', $cliente->id)
            ->first();

        if (!$personal) {
            return response()->json(['error' => 'Registro de personal no encontrado o ya asignado.'], 422);
        }

        // Verify the target user belongs to same client and is a supervisor
        $targetUser = User::find($request->user_id);
        if (!$targetUser || !$targetUser->hasRole('clientsupervisor') || !$targetUser->perteneceACliente($cliente->id)) {
            return response()->json(['error' => 'Usuario supervisor no válido.'], 422);
        }

        // Verify the user doesn't already have a personal assigned
        $existingPersonal = Personal::where('user_id', $request->user_id)->first();
        if ($existingPersonal) {
            return response()->json(['error' => 'Este supervisor ya tiene un registro de personal asignado.'], 422);
        }

        $personal->update(['user_id' => $request->user_id]);

        return response()->json([
            'success' => true,
            'message' => 'Personal asignado correctamente al supervisor.',
            'personal' => $personal->fresh(),
        ]);
    }

    /**
     * Search available personal records by DNI.
     */
    public function getPersonalDisponible(Request $request)
    {
        $user = auth()->user();
        $cliente = $user->clientes()->first();

        if (!$cliente) {
            return response()->json([]);
        }

        $query = Personal::whereNull('user_id')
            ->where('cliente_id', $cliente->id);

        if ($request->has('dni') && !empty($request->dni)) {
            $query->where(function ($q) use ($request) {
                $q->where('nro_doc', 'LIKE', "%{$request->dni}%")
                  ->orWhere('nombre', 'LIKE', "%{$request->dni}%")
                  ->orWhere('apellido', 'LIKE', "%{$request->dni}%");
            });
        }

        $personal = $query->limit(10)->get();

        return response()->json($personal);
    }

    /**
     * Assign a patrol to a supervisor.
     */
    public function asignarPatrulla(Request $request)
    {
        $request->validate([
            'supervisor_id' => 'required|exists:personal,id',
            'patrulla_id' => 'required|exists:patrullas,id',
        ]);

        $user = auth()->user();
        $cliente = $user->clientes()->first();

        if (!$cliente) {
            return response()->json(['error' => 'No tiene un cliente asociado.'], 403);
        }

        // Verify patrulla belongs to client
        $patrulla = Patrulla::where('id', $request->patrulla_id)
            ->where('cliente_id', $cliente->id)
            ->first();

        if (!$patrulla) {
            return response()->json(['error' => 'Patrulla no encontrada o no pertenece al cliente.'], 422);
        }

        // Verify patrulla is not already assigned to another supervisor
        $yaAsignada = SupervisorPatrulla::where('patrulla_id', $request->patrulla_id)->exists();
        if ($yaAsignada) {
            return response()->json(['error' => 'La patrulla ya está asignada a otro supervisor.'], 422);
        }

        // Verify supervisor belongs to client
        $personal = Personal::where('id', $request->supervisor_id)
            ->where('cliente_id', $cliente->id)
            ->first();

        if (!$personal) {
            return response()->json(['error' => 'Supervisor no encontrado.'], 422);
        }

        DB::transaction(function () use ($request, $user) {
            // Remove any existing patrol assignment for this supervisor
            SupervisorPatrulla::where('supervisor_id', $request->supervisor_id)->delete();

            // Create new assignment
            SupervisorPatrulla::create([
                'supervisor_id' => $request->supervisor_id,
                'patrulla_id' => $request->patrulla_id,
                'user_id' => $user->id,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Patrulla asignada correctamente.',
        ]);
    }

    /**
     * Change the patrol assigned to a supervisor.
     */
    public function cambiarPatrulla(Request $request)
    {
        $request->validate([
            'supervisor_id' => 'required|exists:personal,id',
            'patrulla_id' => 'required|exists:patrullas,id',
        ]);

        $user = auth()->user();
        $cliente = $user->clientes()->first();

        if (!$cliente) {
            return response()->json(['error' => 'No tiene un cliente asociado.'], 403);
        }

        // Verify new patrulla belongs to client
        $patrulla = Patrulla::where('id', $request->patrulla_id)
            ->where('cliente_id', $cliente->id)
            ->first();

        if (!$patrulla) {
            return response()->json(['error' => 'Patrulla no encontrada o no pertenece al cliente.'], 422);
        }

        // Verify new patrulla is not assigned to another supervisor
        $yaAsignada = SupervisorPatrulla::where('patrulla_id', $request->patrulla_id)
            ->where('supervisor_id', '!=', $request->supervisor_id)
            ->exists();

        if ($yaAsignada) {
            return response()->json(['error' => 'La patrulla ya está asignada a otro supervisor.'], 422);
        }

        DB::transaction(function () use ($request, $user) {
            // Remove old assignment
            SupervisorPatrulla::where('supervisor_id', $request->supervisor_id)->delete();

            // Create new assignment
            SupervisorPatrulla::create([
                'supervisor_id' => $request->supervisor_id,
                'patrulla_id' => $request->patrulla_id,
                'user_id' => $user->id,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Patrulla cambiada correctamente.',
        ]);
    }

    /**
     * Get available patrols for the client.
     */
    public function getPatrullasDisponibles(Request $request)
    {
        $user = auth()->user();
        $cliente = $user->clientes()->first();

        if (!$cliente) {
            return response()->json([]);
        }

        $query = Patrulla::disponibles()->delCliente($cliente->id);

        // If changing patrol for an existing supervisor, also include their current one
        if ($request->has('supervisor_id')) {
            $currentPatrullaId = SupervisorPatrulla::where('supervisor_id', $request->supervisor_id)
                ->value('patrulla_id');

            if ($currentPatrullaId) {
                $query = Patrulla::where('cliente_id', $cliente->id)
                    ->where(function ($q) use ($currentPatrullaId) {
                        $q->whereDoesntHave('supervisorPatrulla')
                          ->orWhere('id', $currentPatrullaId);
                    });
            }
        }

        return response()->json($query->get());
    }

    /**
     * Assign empresas asociadas to a supervisor.
     */
    public function asignarEmpresas(Request $request)
    {
        $request->validate([
            'supervisor_id' => 'required|exists:personal,id',
            'empresa_ids' => 'present|array',
            'empresa_ids.*' => 'exists:empresas_asociadas,id',
        ]);

        $user = auth()->user();
        $cliente = $user->clientes()->first();

        if (!$cliente) {
            return response()->json(['error' => 'No tiene un cliente asociado.'], 403);
        }

        $personal = Personal::where('id', $request->supervisor_id)
            ->where('cliente_id', $cliente->id)
            ->first();

        if (!$personal) {
            return response()->json(['error' => 'Supervisor no encontrado.'], 422);
        }

        // Build sync data with user_id pivot
        $syncData = [];
        foreach ($request->empresa_ids as $empresaId) {
            $syncData[$empresaId] = ['user_id' => $user->id];
        }

        $personal->empresasAsociadas()->sync($syncData);

        return response()->json([
            'success' => true,
            'message' => 'Clientes asignados correctamente.',
        ]);
    }
}
