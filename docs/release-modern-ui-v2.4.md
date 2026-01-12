# Modern UI v2.4 - Release Notes

**Release Date:** Diciembre 2024  
**Version:** 2.4  
**Status:** Stable

---

## Executive Summary

Modern UI v2.4 introduces a comprehensive user profile management system, activity logging capabilities, system configuration views, and multiple UI improvements. This release focuses on user account management, system monitoring through activity logs, and enhanced visual consistency across the application with support for both admin and client layouts.

---

## New Features and Improvements

### 1. Sistema de Gestión de Perfil de Usuario

**Problem Solved:** Falta de una vista personalizada para que los usuarios gestionen su información personal, seguridad y preferencias de cuenta.

**Solution:**
- Vista completa de perfil de usuario con tema oscuro
- Actualización de nombre y correo electrónico
- Subida y gestión de foto de perfil
- Cambio de contraseña con validación
- Gestión de sesiones del navegador (ver y cerrar sesiones en otros dispositivos)
- Eliminación de cuenta con confirmación por contraseña
- Soporte para layouts admin (`layouts.app`) y cliente (`layouts.cliente`)

**Files Created/Modified:**
- `app/Livewire/Settings/UserProfile.php` - Componente Livewire principal
- `app/Livewire/Client/Settings/UserProfile.php` - Versión para layout cliente
- `resources/views/livewire/settings/user-profile.blade.php` - Vista admin
- `resources/views/livewire/client/settings/user-profile.blade.php` - Vista cliente
- `resources/views/livewire/settings/_settings-styles.blade.php` - Estilos compartidos

**Technical Details:**
```php
// Gestión de sesiones del navegador
public function loadSessions(): void
{
    if (config('session.driver') === 'database') {
        $this->sessions = DB::connection(config('session.connection'))
            ->table(config('session.table', 'sessions'))
            ->where('user_id', Auth::id())
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                return (object) [
                    'agent' => $this->createAgent($session),
                    'ip_address' => $session->ip_address,
                    'is_current_device' => $session->id === Session::getId(),
                    'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                ];
            })->toArray();
    }
}
```

---

### 2. Sistema de Activity Log

**Problem Solved:** Necesidad de monitorear la actividad del sistema, detectar comportamientos sospechosos y registrar eventos de autenticación.

**Solution:**
- Integración del paquete `spatie/laravel-activitylog`
- Registro automático de eventos de autenticación:
  - Login exitoso
  - Logout
  - Intentos de login fallidos
  - Bloqueos por exceso de intentos
- Vista de Activity Log con filtros avanzados
- Filtrado por usuario, tipo de log, rango de fechas
- Búsqueda por descripción
- Soporte para layouts admin y cliente
- Vista cliente muestra solo logs del usuario actual

**Files Created/Modified:**
- `app/Livewire/ActivityLog/Index.php` - Componente Livewire admin
- `app/Livewire/Client/ActivityLog/Index.php` - Componente Livewire cliente
- `resources/views/livewire/activity-log/index.blade.php` - Vista admin
- `resources/views/livewire/client/activity-log/index.blade.php` - Vista cliente
- `app/Listeners/LogAuthenticationActivity.php` - Listener para eventos de auth
- `app/Providers/AppServiceProvider.php` - Registro de listeners
- `database/migrations/2024_12_18_000001_create_activity_log_table.php` - Migración

**Technical Details:**
```php
// Listener para eventos de autenticación
class LogAuthenticationActivity
{
    public function handleLogin(Login $event): void
    {
        activity('login')
            ->causedBy($event->user)
            ->withProperties([
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('Usuario inició sesión');
    }

    public function handleFailedLogin(Failed $event): void
    {
        activity('login_failed')
            ->withProperties([
                'email' => $event->credentials['email'] ?? 'unknown',
                'ip' => request()->ip(),
            ])
            ->log('Intento de login fallido');
    }

    public function handleLockout(Lockout $event): void
    {
        activity('lockout')
            ->withProperties([
                'ip' => request()->ip(),
                'email' => $event->request->input('email'),
            ])
            ->log('Cuenta bloqueada por exceso de intentos');
    }
}
```

---

### 3. Vista de Configuración del Sistema

**Problem Solved:** Falta de una vista centralizada para configuración del sistema y acceso a soporte técnico.

**Solution:**
- Vista de configuración del sistema con información técnica
- Botón de soporte con mailto a `cos@cyhsur.com`
- Información del sistema: versión, entorno, PHP, Laravel
- Enlaces rápidos a otras secciones
- Soporte para layouts admin y cliente

**Files Created/Modified:**
- `app/Livewire/Settings/SystemSettings.php` - Componente Livewire admin
- `app/Livewire/Client/Settings/SystemSettings.php` - Componente Livewire cliente
- `resources/views/livewire/settings/system-settings.blade.php` - Vista admin
- `resources/views/livewire/client/settings/system-settings.blade.php` - Vista cliente

---

### 4. Correcciones de UI en Top Bar y Menú de Usuario

**Problem Solved:** Problemas visuales en el botón de perfil y menú desplegable de usuario.

**Solution:**
- Eliminado highlight gris al hacer hover en el botón de perfil
- Corregido contenedor de avatar para ser un círculo perfecto
- Reordenados iconos en el menú desplegable (iconos a la izquierda del texto)
- Eliminado botón "Ayuda y soporte" del menú
- Actualizado menú para soportar rutas diferentes según layout (admin/cliente)

**Files Modified:**
- `resources/css/modern-ui.css` - Estilos del avatar y botón de usuario
- `resources/views/components/modern/user-menu.blade.php` - Menú de usuario
- `resources/views/components/modern/top-nav.blade.php` - Barra superior

**Technical Details:**
```css
/* Quitar hover gris del botón de usuario */
#userMenuBtn:hover {
    background-color: transparent;
}

/* Avatar como círculo perfecto */
.modern-top-nav-avatar {
    width: var(--fb-button-size);
    height: var(--fb-button-size);
    min-width: var(--fb-button-size);
    min-height: var(--fb-button-size);
    max-width: var(--fb-button-size);
    max-height: var(--fb-button-size);
    border-radius: 50%;
    overflow: hidden;
}
```

---

### 5. Mejoras de Estilos en Activity Log

**Problem Solved:** Las vistas de Activity Log usaban colores con tinte azulado (gray de Tailwind) que no eran visualmente neutros.

**Solution:**
- Cambio de paleta de colores de `gray` a `zinc` (gris neutro)
- Contenedores en gris claro con texto oscuro
- Badges de tipo siempre en azul (eliminados verde, rojo, amarillo)
- Mejor contraste y legibilidad
- Soporte para modo claro y oscuro

**Color Mapping:**
| Light Mode | Dark Mode |
|------------|-----------|
| `bg-zinc-50` | `dark:bg-zinc-800` |
| `bg-zinc-100` | `dark:bg-zinc-700` |
| `bg-zinc-200` | `dark:bg-zinc-600` |
| `text-zinc-800` | `dark:text-zinc-200` |
| `border-zinc-300` | `dark:border-zinc-600` |

---

### 6. Dashboard Operacional en Sidebar de Clientes

**Problem Solved:** Los clientes no tenían acceso directo al Dashboard Operacional desde el menú lateral.

**Solution:**
- Agregado botón "Dashboard Operacional" en la sección "Home" del sidebar de clientes
- Icono de gráfico de barras
- Redirección a `/client/operaciones/dashboard`

**Files Modified:**
- `resources/views/components/modern/sidebar.blade.php` - Sidebar con nuevo enlace

---

### 7. Habilitación de Fotos de Perfil

**Problem Solved:** La funcionalidad de fotos de perfil de Jetstream no estaba habilitada.

**Solution:**
- Habilitado `Features::profilePhotos()` en configuración de Jetstream
- Creado directorio `storage/app/public/profile-photos`
- Ejecutado `php artisan storage:link` para enlace simbólico

**Files Modified:**
- `config/jetstream.php` - Descomentado profilePhotos()

---

## New Routes

### Admin Routes (layout principal)
```php
Route::get('settings/user-profile', UserProfile::class)->name('settings.user-profile');
Route::get('settings/system', SystemSettings::class)->name('settings.system');
Route::get('activity-log', Index::class)->name('activity-log.index');
```

### Client Routes (layout cliente)
```php
Route::get('/settings/user-profile', ClientUserProfile::class)->name('client.settings.user-profile');
Route::get('/settings/system', ClientSystemSettings::class)->name('client.settings.system');
Route::get('/activity-log', ClientActivityLog::class)->name('client.activity-log');
```

---

## Dependencies Added

### Composer Packages
```json
{
    "spatie/laravel-activitylog": "^4.10"
}
```

**Installation:**
```bash
composer require spatie/laravel-activitylog
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider"
php artisan migrate
```

---

## Database Migrations

### Activity Log Table
```php
Schema::create('activity_log', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->string('log_name')->nullable();
    $table->text('description');
    $table->nullableMorphs('subject', 'subject');
    $table->string('event')->nullable();
    $table->nullableMorphs('causer', 'causer');
    $table->json('properties')->nullable();
    $table->uuid('batch_uuid')->nullable();
    $table->timestamps();
    $table->index('log_name');
    $table->index(['causer_type', 'causer_id']);
});
```

---

## Files Created

### Livewire Components
- `app/Livewire/Settings/UserProfile.php`
- `app/Livewire/Settings/SystemSettings.php`
- `app/Livewire/Client/Settings/UserProfile.php`
- `app/Livewire/Client/Settings/SystemSettings.php`
- `app/Livewire/ActivityLog/Index.php`
- `app/Livewire/Client/ActivityLog/Index.php`

### Views
- `resources/views/livewire/settings/user-profile.blade.php`
- `resources/views/livewire/settings/system-settings.blade.php`
- `resources/views/livewire/settings/_settings-styles.blade.php`
- `resources/views/livewire/client/settings/user-profile.blade.php`
- `resources/views/livewire/client/settings/system-settings.blade.php`
- `resources/views/livewire/activity-log/index.blade.php`
- `resources/views/livewire/client/activity-log/index.blade.php`

### Listeners
- `app/Listeners/LogAuthenticationActivity.php`

### Migrations
- `database/migrations/2024_12_18_000001_create_activity_log_table.php`

### Directories
- `storage/app/public/profile-photos/`

---

## Files Modified

### Components
- `resources/views/components/modern/user-menu.blade.php`
- `resources/views/components/modern/top-nav.blade.php`
- `resources/views/components/modern/sidebar.blade.php`

### CSS
- `resources/css/modern-ui.css`

### Configuration
- `config/jetstream.php`

### Routes
- `routes/web.php`

### Providers
- `app/Providers/AppServiceProvider.php`

---

## Testing Checklist

### User Profile
- [x] Actualización de nombre funciona correctamente
- [x] Actualización de email funciona correctamente
- [x] Subida de foto de perfil funciona
- [x] Eliminación de foto de perfil funciona
- [x] Cambio de contraseña con validación
- [x] Visualización de sesiones activas
- [x] Cierre de otras sesiones funciona
- [x] Eliminación de cuenta con confirmación

### Activity Log
- [x] Registro de login exitoso
- [x] Registro de logout
- [x] Registro de intentos fallidos
- [x] Registro de bloqueos
- [x] Filtros por usuario funcionan
- [x] Filtros por tipo funcionan
- [x] Filtros por fecha funcionan
- [x] Búsqueda funciona
- [x] Paginación funciona
- [x] Vista cliente muestra solo logs propios

### System Settings
- [x] Información del sistema se muestra
- [x] Botón de soporte abre mailto
- [x] Enlaces rápidos funcionan

### UI Corrections
- [x] Sin highlight gris en botón de perfil
- [x] Avatar es círculo perfecto
- [x] Iconos alineados a la izquierda en menú
- [x] Colores zinc neutros aplicados

---

## Breaking Changes

**None.** Todos los cambios son compatibles con versiones anteriores. Las nuevas funcionalidades son aditivas.

---

## Migration Guide

### Para Desarrolladores

1. **Instalar dependencias:**
   ```bash
   composer install
   ```

2. **Ejecutar migraciones:**
   ```bash
   php artisan migrate
   ```

3. **Crear enlace simbólico de storage:**
   ```bash
   php artisan storage:link
   ```

4. **Limpiar cachés:**
   ```bash
   php artisan optimize:clear
   ```

---

## Performance Impact

- **Activity Log:** Impacto mínimo en autenticación (registro asíncrono)
- **User Profile:** Sin impacto significativo
- **System Settings:** Sin impacto significativo
- **UI Changes:** Mejora de rendimiento con CSS optimizado

---

## Known Issues

Ninguno conocido en esta versión.

---

## Future Enhancements

### Planned for v2.5
- Notificaciones por email de actividad sospechosa
- Exportación de activity logs a CSV
- Two-factor authentication UI
- Preferencias de notificaciones de usuario
- Dashboard de métricas de actividad
- Mejoras adicionales en gestión de sesiones

---

## Changelog

### v2.4 (Diciembre 2024)

**Added:**
- Sistema completo de gestión de perfil de usuario
- Vista de configuración del sistema con botón de soporte
- Sistema de Activity Log con spatie/laravel-activitylog
- Registro automático de eventos de autenticación
- Vistas de Activity Log para admin y cliente
- Filtros avanzados en Activity Log
- Soporte para fotos de perfil
- Botón Dashboard Operacional en sidebar de clientes
- Estilos compartidos para vistas de configuración

**Changed:**
- Menú de usuario actualizado con rutas dinámicas
- Paleta de colores de gray a zinc (gris neutro)
- Badges de tipo siempre azul en Activity Log
- Avatar con círculo perfecto

**Fixed:**
- Highlight gris en botón de perfil eliminado
- Contenedor de avatar ovalado corregido
- Iconos en menú ahora alineados correctamente
- Múltiples root elements en vistas Livewire

**Technical:**
- Integración de spatie/laravel-activitylog
- Nuevo Listener LogAuthenticationActivity
- Nuevos componentes Livewire para Settings
- Nuevas rutas para admin y cliente
- Migración para tabla activity_log

---

## Credits

Desarrollado por el equipo de desarrollo COS.

---

## Support

Para reportar bugs o solicitar features, contactar a cos@cyhsur.com

---

*Última actualización: Diciembre 2024*

