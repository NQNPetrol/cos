# Desarrollo local (Windows) y riesgo de copias duplicadas del proyecto

Este documento es la **fuente de verdad** para evitar que el código que editás no sea el que ejecuta PHP, y para que agentes de IA (Cursor) sepan en qué árbol trabajar.

## Ruta canónica del proyecto

- **Producción de desarrollo recomendada:** `C:\wamp64\www\cos`
- Ahí vive `artisan`, `app/`, `vendor/`, `public/`, etc.

Todo lo que sigue asume que **esa** es la carpeta que usás para editar y para correr la app, salvo que explícitamente estés trabajando en otro clon/worktree a propósito.

## Cursor: worktrees bajo `.cursor/worktrees/`

Cursor puede crear **worktrees** en rutas como:

`C:\Users\<usuario>\.cursor\worktrees\cos\<id>\`

Eso es **otra copia física** del repositorio (otro árbol de archivos). Puede estar en la misma rama Git (`develop`) que `C:\wamp64\www\cos`, pero:

- Los archivos **no están sincronizados automáticamente** entre carpetas.
- Si `php artisan serve` se levantó desde una carpeta y vos editás la otra, verás comportamientos “imposibles”: métodos que “no existen”, logs que no aparecen, cambios que “no aplican”.

**Regla:** tratá cada carpeta como un proyecto distinto hasta que hagas `git pull` / merge / copia consciente en ambos.

## `php artisan serve` y `base_path()`

Laravel resuelve la raíz del proyecto desde `public/index.php` (directorio padre de `public/`). Eso es lo que devuelve `base_path()`.

**Asegurate de:**

1. Hacer `cd` a la **misma** carpeta canónica antes de `php artisan serve`.
2. No dejar corriendo un `serve` viejo levantado desde otra ruta (sigue atendiendo el puerto 8000).
3. Si dudás, con el servidor corriendo:  
   `php artisan tinker` → `base_path()`  
   Tiene que ser `C:\wamp64\www\cos` (o la ruta que elegiste como única verdad).

## WAMP vs `artisan serve`

- **WAMP (Apache):** usa el `php.ini` de Apache (ruta típica bajo `wamp64\bin\apache\...\php.ini`).
- **`php artisan serve`:** usa el **PHP CLI** indicado en el PATH (ej. `C:\wamp64\bin\php\php8.3.28\php.ini`).

Los límites `upload_max_filesize` / `post_max_size` son **independientes**. Si subís archivos grandes, hay que revisar **ambos** si usás ambos entornos.

## PHP 8.3 y OPcache con el servidor embebido

En Windows/WAMP puede haber **varias** instalaciones de PHP (8.2, 8.3, etc.). El que usa `php artisan serve` es el que responde a `php -v` en la terminal donde corrés el comando.

Con OPcache habilitado para el SAPI que usa el servidor embebido, en algunos entornos se observó código de controladores **viejo** en memoria mientras `routes/web.php` sí se recargaba. Mitigaciones:

- Desarrollo local: `opcache.enable=0` en el `php.ini` del PHP que usa `artisan serve`, **y** reiniciar el proceso `serve` tras cambiar `php.ini`.
- O invalidar OPcache / reiniciar `serve` tras cambios grandes en controladores.

## Composer y `vendor/`

Ejecutá `composer install` y `composer dump-autoload` en la **misma** raíz desde la que servís la app, para no mezclar autoload entre copias.

## Casos reales que ya ocurrieron en este repo

1. **`TurnoRodadoController::adjuntarDocumentacion`**  
   En una copia del proyecto **faltaba** el bloque que guarda `comprobante_pago_path` (solo informe/factura). En otra copia estaba completo. Síntoma: factura e informe OK, comprobante siempre `NULL`.

2. **`PagoServiciosRodadoController::adjuntarComprobanteBatch`**  
   La ruta existía pero en una copia el método no estaba mergeado → error 500 “undefined method”.

3. **Diagnóstico útil:** un script temporal que haga `ReflectionClass` sobre el controlador y muestre `getFileName()`. Si la ruta apunta a `.cursor\worktrees\...` y vos editás `wamp64\www\cos`, ahí está el desalineamiento.

## Checklist rápido (para vos)

- [ ] Cursor: workspace abierto en `C:\wamp64\www\cos` (no en un worktree), salvo que sepas que estás en otra copia a propósito.
- [ ] Terminal: `cd C:\wamp64\www\cos` antes de `php artisan serve`.
- [ ] Un solo proceso en el puerto que uses (8000 u otro).
- [ ] Tras dudas: `base_path()` en tinker = carpeta donde editás.

## Referencias internas

- Nginx/producción: `docs/nginx-config.md`
- Este archivo: mantener actualizado si cambia la ruta canónica o el flujo de desarrollo.
