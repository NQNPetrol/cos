<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use App\Models\UserCliente;
use Illuminate\Http\Request;

class UserClienteController extends Controller
{
    public function index()
    {
        $usuarios = User::with(['clientes'])->get();
        $clientes = Cliente::all();

        return view('usuarios.asignar-clientes', compact('usuarios', 'clientes'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'clientes' => 'required|array|min:1',
                'clientes.*' => 'exists:clientes,id',
            ]);

            $userId = $request->user_id;
            $clientesSeleccionados = $request->clientes;

            $user = User::findOrFail($userId);
            $asignacionesCreadas = [];
            $asignacionesExistentes = [];

            // Procesar cada cliente seleccionado
            foreach ($clientesSeleccionados as $clienteId) {
                // Verificar si la asignación ya existe
                if (UserCliente::exists($userId, $clienteId)) {
                    $cliente = Cliente::find($clienteId);
                    $asignacionesExistentes[] = $cliente->nombre;

                    continue;
                }

                // Crear nueva asignación
                UserCliente::create([
                    'user_id' => $userId,
                    'cliente_id' => $clienteId,
                ]);

                $cliente = Cliente::find($clienteId);
                $asignacionesCreadas[] = $cliente->nombre;
            }

            // Preparar mensaje de respuesta
            $mensaje = '';
            if (! empty($asignacionesCreadas)) {
                $clientesCreados = implode(', ', $asignacionesCreadas);
                $mensaje .= "Clientes asignados correctamente: {$clientesCreados} al usuario {$user->name}.";
            }

            if (! empty($asignacionesExistentes)) {
                $clientesExistentes = implode(', ', $asignacionesExistentes);
                if (! empty($mensaje)) {
                    $mensaje .= ' ';
                }
                $mensaje .= "Los siguientes clientes ya estaban asignados: {$clientesExistentes}.";
            }

            if (empty($asignacionesCreadas) && ! empty($asignacionesExistentes)) {
                return redirect()->route('user-cliente.index')
                    ->with('info', $mensaje);
            }

            return redirect()->route('user-cliente.index')
                ->with('success', $mensaje);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('user-cliente.index')
                ->with('error', 'Por favor, seleccione al menos un cliente válido.')
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            return redirect()->route('user-cliente.index')
                ->with('error', 'Error al asignar clientes: '.$e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'cliente_id' => 'required|exists:clientes,id',
            ]);

            $userCliente = UserCliente::where('user_id', $request->user_id)
                ->where('cliente_id', $request->cliente_id)
                ->first();

            if (! $userCliente) {
                return redirect()->route('user-cliente.index')
                    ->with('error', 'La asignación no existe.');
            }

            $user = User::find($request->user_id);
            $cliente = Cliente::find($request->cliente_id);

            $userCliente->delete();

            return redirect()->route('user-cliente.index')
                ->with('success', "Cliente {$cliente->nombre} removido correctamente del usuario {$user->name}.");

        } catch (\Exception $e) {
            return redirect()->route('user-cliente.index')
                ->with('error', 'Error al remover la asignación: '.$e->getMessage());
        }
    }

    public function getClientesPorUsuario($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $clientes = $user->clientes()->select('id', 'nombre')->get();

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'clientes' => $clientes,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener clientes del usuario: '.$e->getMessage(),
            ], 500);
        }
    }

    public function getUsuariosPorCliente($clienteId)
    {
        try {
            $cliente = Cliente::findOrFail($clienteId);
            $usuarios = $cliente->usuarios()->select('id', 'name', 'email')->get();

            return response()->json([
                'success' => true,
                'cliente' => [
                    'id' => $cliente->id,
                    'nombre' => $cliente->nombre,
                ],
                'usuarios' => $usuarios,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuarios del cliente: '.$e->getMessage(),
            ], 500);
        }
    }

    public function removeAllClientesFromUser(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            $userId = $request->user_id;
            $user = User::findOrFail($userId);

            $asignaciones = UserCliente::where('user_id', $userId)->get();

            if ($asignaciones->isEmpty()) {
                return redirect()->route('user-cliente.index')
                    ->with('info', 'El usuario no tiene clientes asignados.');
            }

            $cantidadEliminada = $asignaciones->count();

            // Eliminar todas las asignaciones
            UserCliente::where('user_id', $userId)->delete();

            return redirect()->route('user-cliente.index')
                ->with('success', "Se removieron {$cantidadEliminada} asignaciones del usuario {$user->name}.");

        } catch (\Exception $e) {
            return redirect()->route('user-cliente.index')
                ->with('error', 'Error al remover las asignaciones: '.$e->getMessage());
        }
    }
}
