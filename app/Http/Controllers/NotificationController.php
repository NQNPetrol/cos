<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        $page = $request->get('page', 1);
        $perPage = 10;

        // Obtenemos las notificaciones visibles para el usuario
        $notifications = $this->getUserVisibleNotifications($user)
            ->with(['users' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        // Formatear los datos para el frontend
        $formattedNotifications = $notifications->getCollection()->map(function ($notification) use ($user) {
            $pivot = $notification->users->first()?->pivot;
            
            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'type' => $notification->type,
                'priority' => $notification->priority,
                'created_at' => $notification->created_at->format('d/m/Y H:i'),
                'created_at_human' => $notification->created_at->diffForHumans(),
                'is_read' => $pivot ? $pivot->is_read : false,
                'is_dismissed' => $pivot ? $pivot->is_dismissed : false,
            ];
        });

        return response()->json([
            'data' => $formattedNotifications,
            'current_page' => $notifications->currentPage(),
            'last_page' => $notifications->lastPage(),
            'total' => $notifications->total(),
            'has_more' => $notifications->hasMorePages(),
        ]);
    }

    //contador notif sin leer

    public function unreadCount(): JsonResponse
    {
        $user = auth()->user();
        
        // Primero obtenemos todas las notificaciones visibles para el usuario
        $visibleNotificationIds = $this->getUserVisibleNotifications($user)->pluck('id');
        
        if ($visibleNotificationIds->isEmpty()) {
            return response()->json(['count' => 0]);
        }
        
        // Contamos las notificaciones visibles que NO están leídas NI descartadas
        $unreadCount = \DB::table('notification_user')
            ->where('user_id', $user->id)
            ->whereIn('notification_id', $visibleNotificationIds)
            ->where('is_read', false)
            ->where('is_dismissed', false)
            ->count();

        return response()->json(['count' => $unreadCount]);
    }

    // metodo para marar una notif como leida

    public function markAsRead(Notification $notification): JsonResponse
    {
        $user = auth()->user();
        
        if (!$notification->isVisibleForUser($user)) {
            return response()->json(['error' => 'Notificación no encontrada'], 404);
        }

        $notification->markAsReadForUser($user);

        return response()->json(['success' => true]);
    }

    // descartar notif
    public function dismiss(Notification $notification): JsonResponse
    {
        $user = auth()->user();
        
        if (!$notification->isVisibleForUser($user)) {
            return response()->json(['error' => 'Notificación no encontrada'], 404);
        }

        $notification->dismissForUser($user);

        return response()->json(['success' => true]);
    }

    //Marcar TODAS como leida

    public function markAllAsRead(): JsonResponse
    {
        try {
            $user = auth()->user();
            
            // Obtener notificaciones visibles
            $visibleNotifications = $this->getUserVisibleNotifications($user)->get();

            foreach ($visibleNotifications as $notification) {
                // Solo marcar como leída si no está descartada
                if (!$notification->isDismissedByUser($user)) {
                    $notification->markAsReadForUser($user);
                }
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    // vista p administrar notis
    public function admin(Request $request): View
    {
        
        $query = Notification::with(['user', 'cliente']);
    
        if ($request->has('filter_type') && $request->filter_type != '') {
            $query->where('type', $request->filter_type);
        }
        if ($request->has('filter_priority') && $request->filter_priority != '') {
            $query->where('priority', $request->filter_priority);
        }

        if ($request->has('filter_status') && $request->filter_status != '') {
            if ($request->filter_status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->filter_status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->has('filter_date_from') && $request->filter_date_from != '') {
            $query->whereDate('created_at', '>=', $request->filter_date_from);
        }
        
        if ($request->has('filter_date_to') && $request->filter_date_to != '') {
            $query->whereDate('created_at', '<=', $request->filter_date_to);
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(15);
        $users = User::all();
        $clientes = Cliente::all();

        return view('admin.notifications', compact('notifications', 'users', 'clientes'));
    }

    //crear notif nueva
    public function create(): View
    {   
        $users = User::all();
        $clients = Cliente::all();
        return view('admin.nueva-notif', compact('users', 'clients'));
    }

    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'message' => 'required|string',
                'type' => 'required|in:global,user,client',
                'user_id' => 'nullable|exists:users,id',
                'client_id' => 'nullable|exists:clientes,id',
                'priority' => 'required|in:BAJA,NORMAL,ALTA',
            ]);

            $notification = Notification::create($validated);

            return redirect()->route('notifications.admin')
                ->with('success', 'Notificación creada exitosamente');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear la notificación: ' . $e->getMessage())
                ->withInput();
        }
    }
    public function edit(Notification $notification)
    {
        $users = User::all();
        $clients = Cliente::all();
        
        return view('admin.edit-notif', compact('notification', 'users', 'clients'));
    }

     public function update(Request $request, Notification $notification)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'message' => 'required|string',
                'type' => 'required|in:global,user,client',
                'user_id' => 'nullable|exists:users,id',
                'client_id' => 'nullable|exists:clientes,id',
                'priority' => 'required|in:BAJA,NORMAL,ALTA',
                'is_active' => 'required|boolean'
            ]);

            $notification->update($validated);


            return redirect()->route('notifications.admin')
                ->with('success', 'Notificación actualizada exitosamente');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la notificación: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function editData(Notification $notification)
    {
        return response()->json([
            'notification' => $notification
        ]);
    }

    public function toggle(Request $request, Notification $notification)
    {
        try {
            $request->validate([
                'activate' => 'required|boolean'
            ]);

            $notification->update(['is_active' => $request->activate]);

            $message = $request->activate 
                ? 'Notificación activada exitosamente' 
                : 'Notificación desactivada exitosamente';

            return redirect()->route('notifications.admin')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la notificación: ' . $e->getMessage());
        }
    }

    public function destroy(Notification $notification)
    {
        try {

            $notification->delete();

            return redirect()->route('notifications.admin')
                    ->with('success', 'Notificación eliminada correctamente.');
            } catch (\Exception $e) {
                return redirect()->back()
                ->with('error', 'Error al eliminar la notificación: ' . $e->getMessage());
            }
    }

    //obtener notificaciones visibles p un usuario
    private function getUserVisibleNotifications(User $user)
    {
        $userClientIds = $user->clientes()->pluck('cliente_id')->toArray();

        return Notification::active()
            ->where(function ($query) use ($user, $userClientIds) {
                $query->global()
                      ->orWhere(function ($q) use ($user) {
                          $q->forUser($user->id);
                      })
                      ->orWhere(function ($q) use ($userClientIds) {
                          if (!empty($userClientIds)) {
                              $q->where('type', 'client')
                                ->whereIn('client_id', $userClientIds);
                          }
                      });
            });
    }
}
