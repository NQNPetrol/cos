# Plan técnico: próximos cambios (Administrative, Dashboard, Vehículos, Alertas)

Este documento detalla la lógica, razonamiento y pasos técnicos para los cambios solicitados. **No incluye implementación**; sirve como guía para desarrolladores.

---

## 1. Layouts separados y rol Administrative

### 1.1 Objetivo
- Crear un layout **administrative** que replique el aspecto del layout actual (`layouts/app.blade.php`) pero con una top bar específica para la sección Administración.
- Restringir el acceso a todas las vistas de esa sección mediante el rol **administrative** y middleware.

### 1.2 Layout `administrative.blade.php`

**Ubicación sugerida:** `resources/views/layouts/administrative.blade.php`

**Estructura:**
- Misma base que `app.blade.php`: `modern-ui`, mismos Vite assets (`app.css`, `app.js`, `modern-navigation.js`, `modern-search.js`), `@livewireStyles`, `@stack('styles')`, `@livewireScripts`, `@stack('scripts')`.
- **Top bar distinta:** en lugar de `<x-modern.top-nav />` (que usa `isClient=false` y muestra Inicio, Administración, Operaciones, Sistema), usar un **nuevo componente** `<x-modern.top-nav-administrative />` con:
  - **Home:** enlace al dashboard del administrative (ruta que use este layout).
  - **Icono vehículo:** enlace a `route('rodados.index')` (Gestión de Vehículos).
  - **Icono calendario:** enlace a `route('rodados.calendario.index')`.
  - **Icono pesos/dólar:** abre submenú con: Pagos y Servicios, Cobranzas.
  - **Icono personas:** abre submenú con: Clientes (crear/administrar), Empresas Asociadas, Proveedores y Talleres, Personal.
- **Sidebar:** puede ser el mismo `<x-modern.sidebar :is-client="false" />` o un sidebar específico para administrative que solo muestre la rama "Administración" (Dashboard, Clientes, Personal, Rodados, Proveedores y Talleres, Pagos y Servicios, Cobranzas, Calendario, Alertas). **Razonamiento:** si el usuario solo tiene rol `administrative`, no debe ver Operaciones, Sistema, etc. Por tanto, o se crea `<x-modern.sidebar-administrative />` que solo renderiza esa rama, o el sidebar actual recibe un prop `context="administrative"` y oculta el resto.

**Lógica del top bar administrative:**
- Submenús (pesos, personas) pueden implementarse con dropdowns en JS (por ejemplo en `modern-navigation.js` o un nuevo `modern-navigation-administrative.js`): al hacer clic en el icono se muestra un panel con enlaces.
- Rutas a usar (todas bajo prefijo actual): `rodados.index`, `rodados.calendario.index`, `rodados.pagos-servicios.index`, `rodados.cobranzas.index`, `crear.cliente`, `empresas-asociadas.index`, `rodados.proveedores-talleres.index`, `personal.index`.

### 1.3 Componente Blade para el layout

**Opción A – Layout que elige top/sidebar por rol:**  
Crear `AdministrativeLayout.php` (View Component) que renderice `layouts.administrative` y pase datos necesarios al top nav y sidebar.

**Opción B – Layout estático:**  
`layouts/administrative.blade.php` que incluye directamente el top nav y sidebar administrative (componentes nuevos).

**Recomendación:** Opción B para no mezclar lógica de rol en el layout; el middleware ya restringe el acceso.

### 1.4 Rol `administrative` y permisos

- **Rol:** `administrative` (Spatie `Role::create(['name' => 'administrative'])`).
- **Permisos a asignar:** todos los que hoy usan las rutas de la sección Administración (rodados, cobranzas, pagos, clientes, personal, proveedores, talleres, alertas-admin, calendario, etc.). Lista de permisos ya existentes en `PermissionsSeeder`: crear/editar cliente, ver/crear/editar personal, ver contratos, crear empresas, etc. Más los que se usen en las rutas bajo `rodados.*` (normalmente esas rutas están bajo middleware genérico `auth`; habría que agruparlas y protegerlas con `role:administrative` o un middleware `role:administrative|admin`).
- **Seeder:** en `PermissionsSeeder` (o en `RolesSeeder`), después de crear los roles existentes, añadir:
  - `Role::firstOrCreate(['name' => 'administrative'])`.
  - Definir un array `$administrativePermissions` con los nombres de permisos que correspondan a: clientes, empresas asociadas, personal, rodados (índice, store, update, destroy, turnos, cambios-equipos, kilometraje, pagos, calendario, proveedores-talleres, pagos-servicios, cobranzas, alertas-admin, servicios-usuario).
  - `$administrativeRole->syncPermissions($administrativePermissions)`.
- **Middleware en rutas:** agrupar todas las rutas que deben verse solo con layout administrative en un grupo, por ejemplo:
  - `Route::middleware(['auth', 'role:administrative|admin'])->prefix('rodados')->...` (y el resto de rutas de clientes, personal, etc. que correspondan a “Administración”).
  - **Razonamiento:** `admin` puede seguir entrando a todo; `administrative` solo a esa sección. Quién ve qué layout se resuelve en el DashboardController por rol.

### 1.5 Redirección en `DashboardController`

**Archivo:** `app/Http/Controllers/DashboardController.php`

**Lógica actual:** si el usuario tiene rol cliente/clientadmin/clientsupervisor → `client.dashboard`; si no → `main.dashboard`.

**Cambio:** después de comprobar roles de cliente, añadir:

```php
if ($user->hasRole('administrative')) {
    return redirect()->route('administrative.dashboard'); // o la ruta que sea el home del layout administrative
}
```

**Nueva ruta:** definir una ruta nombrada para el “dashboard administrative” (por ejemplo `administrative.dashboard` o reutilizar `rodados.admin-dashboard`). Si se reutiliza `rodados.admin-dashboard`, esa vista debe usar el layout administrative cuando el usuario sea `administrative` (por ejemplo con `@extends` o un componente que elija layout según rol). **Recomendación:** crear una ruta dedicada `GET /administrative/dashboard` que devuelva la vista del dashboard administrative con layout `administrative`, y que `DashboardController` redirija a esa ruta cuando el rol sea `administrative`.

### 1.6 Actualizar layout en todas las vistas de la sección

**Vistas que deben usar layout administrative (cuando el usuario tiene solo rol administrative):**

- Opción 1: **Vistas siempre con layout administrative.** Todas las vistas bajo rodados, cobranzas, pagos, clientes (crear/editar), empresas asociadas, personal, proveedores-talleres, alertas-admin, calendario usan `layouts.administrative` (o `<x-administrative-layout>`). Entonces tanto `admin` como `administrative` ven el mismo layout al entrar a esas rutas.
- Opción 2: **Elección dinámica por rol.** Cada controlador o un middleware determina si el usuario es `administrative` y pasa a la vista una variable `useAdministrativeLayout = true`; la vista hace `@extends($useAdministrativeLayout ? 'layouts.administrative' : 'layouts.app')`. Más complejo y propenso a errores.

**Recomendación:** Opción 1. Todas las vistas de “Administración” usan el layout administrative. Las rutas de esa sección están protegidas por `role:administrative|admin`. Así, admin y administrative ven la misma estructura al navegar por rodados, cobranzas, etc.

**Lista de vistas a actualizar (reemplazar `<x-app-layout>` por `<x-administrative-layout>` o `@extends('layouts.administrative')`):**

- `resources/views/rodados/index.blade.php`
- `resources/views/rodados/dashboard.blade.php` (dashboard rodados; ver punto 2)
- `resources/views/rodados/pagos.blade.php`
- `resources/views/rodados/cobranzas.blade.php`
- `resources/views/rodados/proveedores-talleres.blade.php`
- `resources/views/rodados/alertas-admin.blade.php`
- `resources/views/rodados/calendario.blade.php` (o la vista que use el calendario de rodados)
- Vistas de clientes: `clientes/nuevo.blade.php`, `clientes/edit.blade.php`; y las que usen las rutas `crear.cliente`, `clientes.edit`, `empresas-asociadas.index` (clienteEmpresaAsociada, clientes/nueva-empresa-asociada, etc.)
- Vistas de personal: `personal/index.blade.php`, `personal/create.blade.php`, `personal/edit.blade.php`

Y cualquier otra vista que se sirva desde las rutas agrupadas bajo el middleware `administrative|admin`.

**Componente:** crear `resources/views/components/administrative-layout.blade.php` que extienda o incluya `layouts/administrative`, para poder usar `<x-administrative-layout>` igual que `<x-app-layout>`.

---

## 2. Dashboard Administrative

### 2.1 Alcance
- Vista: la que se muestre como “home” del layout administrative (p. ej. `rodados/dashboard` o una nueva `administrative/dashboard`).
- Actualizar su layout a `administrative`.
- Modernizar UI/UX al estilo de pagos, cobranzas, alertas (cards, bordes rounded-xl, colores zinc/amber/emerald, toasts).
- Gráficos con **Vue** (Chart.js o similar dentro de componentes Vue).

### 2.2 Gráficos requeridos (Vue)

**Stack sugerido:** Vue 3 (Inertia o SFCs en Vite) + Chart.js o ApexCharts. Si el proyecto aún no usa Vue, hay que configurar Vite para Vue y compilar con `npm run build` (como pide el usuario al final).

1. **Línea: Ingresos vs Egresos mensuales**
   - **Datos:** por mes (eje X): total cobrado (Cobranza, estado cobrado, `fecha_pago` en el mes) y total pagado (PagoServiciosRodado, estado pagado, `fecha_pago` en el mes). Afectado por el selector de mes/año del panel (mes actual por defecto). Si el selector es “mes del año”, mostrar solo ese mes como un punto o una barra; si se quiere “mensualmente” en el año, hacer 12 puntos (enero–diciembre) para el año seleccionado.
   - **Razonamiento:** el usuario pidió “comparando egresos vs ingresos mensualmente”; lo más útil es un gráfico de líneas con 12 meses en el eje X y dos series (ingresos, egresos). El “panel de gráficos superior” con mes del año afecta a todos excepto al anual; por tanto este gráfico puede ser “mensual del año seleccionado” (12 meses) o “solo el mes seleccionado” (una barra o valor). Aclarar con producto: si el selector es “Febrero 2026”, este gráfico podría mostrar solo Febrero (un valor) o todo 2026. Plan sugerido: **eje X = 12 meses del año del selector**, dos líneas (ingresos, egresos).
   - **API:** endpoint que reciba `anio` (y opcionalmente `mes` si se filtra por mes) y devuelva `{ meses: ['Enero', ...], ingresos: [...], egresos: [...] }`. Controller: sumar Cobranza (cobrado) y PagoServiciosRodado (pagado) por mes.

2. **Línea doble eje: Flota (recuento rodados) vs Ingresos en el año**
   - **Eje X:** meses del año (enero–diciembre).
   - **Eje Y izquierdo:** recuento de rodados al cierre de cada mes (o al día actual para el mes actual). Requiere histórico: si no hay tabla de historial de rodados, se puede usar “rodados existentes a fecha de hoy con `created_at <= fin del mes`” como aproximación.
   - **Eje Y derecho:** ingresos (cobranzas cobradas) del mes.
   - **Razonamiento:** “misma escala” es difícil porque unidades son “cantidad” vs “dinero”; por eso doble eje Y. Leyendas claras (“Vehículos” y “Ingresos ($)”). Año seleccionable (año anterior con datos).
   - **API:** mismo endpoint o uno específico que devuelva `meses`, `cantidad_rodados` (por mes), `ingresos` (por mes). Para cantidad de rodados por mes: `Rodado::whereYear('created_at', $anio)->whereMonth('created_at', '<=', $mes)->count()` por cada mes (o un snapshot si en el futuro hay historización).

3. **Barras: Turnos mensuales por vehículo**
   - **Datos:** recuento de turnos (TurnoRodado) en el mes seleccionado, agrupado por `rodado_id`, ordenado descendente. Eje X: vehículo (patente o nombre); Eje Y: cantidad.
   - **API:** recibir `mes`, `anio`; devolver `[{ rodado_id, patente, total }, ...]` ordenado por `total` desc.

4. **Barras: Top 5 vehículos por kilometraje**
   - **Datos:** RegistroKilometraje: tomar el último registro por rodado (kilometraje actual) y ordenar por ese valor desc; top 5. Eje X: vehículo; Eje Y: km.
   - **API:** devolver `[{ rodado_id, patente, kilometraje }, ...]` (top 5). Afectado por el mes si se quiere “km al cierre del mes”; si no hay historial de km por fecha, usar el último registro disponible.

5. **Cartas: Próximos vencimientos de pagos y próximos turnos (7 días)**
   - Mantener lógica actual pero mejorar estilo: cards con bordes rounded-xl, iconos, mismo diseño que en cobranzas/alertas.
   - Datos: PagoServiciosRodado pendientes con `fecha_vencimiento` entre hoy y hoy+7; TurnoRodado con `fecha_turno` en ese rango (o estado pendiente).

6. **KPIs mensuales (solo estos 4)**
   - Recuento **services totales** del mes: por ejemplo turnos de tipo “service” o tabla que los distinga (TurnoRodado con tipo o relación con PagoServiciosRodado).
   - Recuento **turnos mecánicos totales** del mes: TurnoRodado en el mes.
   - Recuento **cambios de equipo** del mes: CambioEquipoRodado con `fecha_hora_estimada` en el mes.
   - Recuento **total rodados** (a fecha actual o al cierre del mes).

Todos los gráficos (salvo el anual) deben depender del **panel de filtros superior**: mes y año. El gráfico anual tiene selector de año adicional (años anteriores con datos).

### 2.3 Panel de filtros
- Selector **mes** y **año** (dropdowns o botones prev/next). Valor por defecto: mes y año actual.
- Afecta: KPIs, gráfico ingresos vs egresos, turnos por vehículo, top 5 km, cartas de vencimientos/turnos.
- No afecta (o lo hace solo el año): gráfico “flota vs ingresos anual”.
- Diseño: misma línea que en cobranzas (filtros en una barra, estilo moderno).

### 2.4 Implementación técnica Vue
- Crear componentes Vue (por ejemplo en `resources/js/Components/` o `resources/js/Pages/`) para cada gráfico.
- Cada uno recibe props (mes, anio, datos) o hace fetch a un endpoint con mes/anio.
- La vista del dashboard (Blade) incluye un div con `id="app"` y monta la app Vue, o usa Inertia si ya está en el proyecto. Si no hay Vue aún: instalar `vue`, `chart.js`, `vue-chartjs` (o equivalente), registrar componentes y compilar con Vite.
- Endpoints API en Laravel: por ejemplo `GET /administrative/dashboard/data/ingresos-egresos?mes=&anio=`, `GET .../flota-ingresos-anual?anio=`, etc., devolviendo JSON.

---

## 3. Vista Rodados (http://127.0.0.1:8000/rodados)

### 3.1 Renombrar a “Vehículos”
- **Título de la vista:** “Gestión de Rodados” → “Gestión de Vehículos”.
- **Sidebar / top nav:** texto “Rodados” → “Vehículos” (en `sidebar-administrative` o en el template `sidebar-administracion` que use el layout administrative).
- **Ruta:** puede seguir siendo `rodados.index` por compatibilidad; solo cambia la presentación.

### 3.2 Imágenes del vehículo (frente, costados, dorso)

**Modelo y BD:**
- La tabla `rodados` actual no tiene columnas de imágenes. Añadir migración:
  - `imagen_frente_path` (nullable string)
  - `imagen_costado_izq_path` (nullable string)
  - `imagen_costado_der_path` (nullable string)
  - `imagen_dorso_path` (nullable string)
  - O una tabla `rodado_imagenes` con `rodado_id`, `tipo` (enum: frente, costado_izq, costado_der, dorso), `path`, para permitir varias fotos por tipo si se desea.
- **Recomendación:** columnas en `rodados` para 4 imágenes (una por ángulo), más simple.

**Lógica:**
- Subir: en `RodadoController@store` y `@update`, aceptar `imagen_frente`, `imagen_costado_izq`, etc. (file), guardar con `Storage::disk('public')->put(...)` en una carpeta tipo `rodados/{id}/` y guardar el path en el modelo. Si ya existía imagen, borrar la anterior.
- Eliminar: endpoint o parámetro en update que permita “eliminar” una imagen (poner el campo en null y borrar archivo).
- Modal nuevo/editar: inputs type file con preview opcional; botón “Eliminar” por cada imagen que exista.

### 3.3 Listado: patente arriba y más grande; click abre modal de detalle
- En la lista de vehículos (tab “Vehículos”), cambiar la celda/block de patente y modelo para que:
  - La **patente** esté arriba y con clase de texto más grande (ej. `text-lg font-bold`).
  - El modelo abajo y más pequeño.
- Hacer la patente (o toda la celda) clickeable: `onclick="abrirModalDetalle({{ $rodado->id }})"` (o data-id y un delegado).
- **Modal de detalle:** título “Datos del vehículo” (o “Vehículo”), mostrar: marca, modelo, tipo, año, patente, cliente, proveedor, y las 4 imágenes (si existen) con opción de descargar (enlace a `Storage::url(...)` o ruta de descarga). Sin edición; solo lectura y descarga de imágenes.

### 3.4 Cambios de equipo en listado de services/turnos y creación correcta

**Listado unificado “services y turnos”:**
- Hoy la vista rodados tiene un tab “Servicios” que probablemente lista turnos y/o pagos. Incluir también los **CambioEquipoRodado** en esa lista (o en un tab que agrupe “Servicios, turnos y cambios de equipo”) para que el usuario vea todo en un solo lugar.
- **Lógica de datos:** construir una colección unificada (o tres colecciones) de: TurnoRodado del rodado, PagoServiciosRodado del rodado, CambioEquipoRodado del rodado. Ordenar por fecha (por ejemplo `fecha_turno`, `fecha_vencimiento`/`fecha_pago`, `fecha_hora_estimada`) y mostrar en una misma tabla o cards con un “tipo” (Turno, Pago, Cambio de equipo).

**Creación en `cambio_equipo_rodado`:**
- En `CambioEquipoRodadoController@store` ya se hace `CambioEquipoRodado::create($validated)`. Verificar que el formulario del modal “Nuevo cambio de equipo” envíe todos los campos requeridos (`rodado_id`, `taller_id`, `tipo`, `fecha_hora_estimada`, etc.) y que no haya ningún `return` previo que impida el `create`. Revisar también que no exista validación que falle silenciosamente (p. ej. `dispositivo_id` cuando es “manual”). Si el botón “Guardar” envía por POST a `rodados.cambios-equipos.store`, el controller ya crea el registro; si no aparece en listado, es porque la vista del listado no está incluyendo los cambios de equipo. Añadir en el controlador que carga la vista de rodados (RodadoController@index) la carga de `cambiosEquipos` por rodado y pasar una colección unificada “todos los servicios” que incluya turnos, pagos y cambios de equipo ordenados por fecha.

---

## 4. Vista Alertas Admin (http://127.0.0.1:8000/rodados/alertas-admin)

### 4.1 “Días de anticipación” → “Km de anticipación” (tipo Control por KM)
- En el bloque `#field-km`, el label del campo que hoy dice “Dias de anticipacion” debe decir **“Km de anticipación”** (o “KM de anticipación”).
- El placeholder o texto de ayuda debe indicar que la unidad son km (ej.: “Avisar X km antes del próximo intervalo”). El nombre del input puede seguir siendo `dias_anticipacion` en el backend para no romper la API, pero en el backend para el tipo `km_vehiculo` ese valor se interpreta como **km** (y guardarse en `trigger_config` como `km_anticipacion` o reutilizar `dias_anticipacion` con semántica de km cuando el tipo es km). **Razonamiento:** en la UI la unidad debe ser clara (km); internamente se puede seguir llamando `dias_anticipacion` en la BD para no migrar, pero en el comando `alertas:procesar` para tipo km se usará ese número como km, no como días.
- **Archivo:** `resources/views/rodados/alertas-admin.blade.php`, línea ~392: cambiar el texto del `<label>` y el texto de ayuda debajo del input.

### 4.2 Cobro a Cliente: servicio mensual y monto
- En el tipo “Cobro a Cliente” se debe **promptear al usuario con el servicio mensual** a recordar (fijos o variables, todos), y traer el **monto** del servicio para usar en alertas (dashboard, modal de notificación, correos).
- **Lógica:**
  - En el modal, en la sección `#field-cobro-cliente`, añadir un **selector de servicio** (similar al de “Recordatorio / Vencimiento”): dropdown con `ServicioUsuario` activos (todos, no solo mensuales si no hay flag; si hay flag “mensual”, filtrar por ese tipo). Cada opción debe mostrar nombre y monto (valor_unitario + moneda).
  - Al elegir un servicio, guardar en la alerta `servicio_usuario_id` (ya existe en `alertas_admin`) y opcionalmente el monto en `trigger_config` o derivarlo desde la relación al enviar notificaciones/correos.
  - Backend: en `AlertaAdminController@store` y `@update`, aceptar `servicio_usuario_id` para tipo `cobro_cliente` y guardarlo. En `crearNotificacion` / `enviarCorreo` (o en el comando que dispara las acciones), al armar el mensaje para alertas de tipo cobro_cliente, cargar `servicioUsuario` y mostrar nombre y monto en el cuerpo del correo y de la notificación.
- **Vista:** en `field-cobro-cliente` añadir un `<select name="servicio_usuario_id">` con opciones “Sin servicio” + lista de servicios (nombre + monto). Si el usuario elige un servicio, el título de la alerta puede auto-completarse con “Cobro: [nombre servicio] ([monto])” y ese dato usarse en dashboard, notificación y correo.

---

## 5. Cierre: `npm run build`

- Al finalizar los cambios front (Vue, Blade, CSS, JS), ejecutar **`npm run build`** para compilar assets con Vite y asegurar que no haya errores de compilación (Vue, Chart.js, etc.).

---

## Resumen de archivos a tocar (referencia)

| Área | Archivos principales |
|------|----------------------|
| Layout administrative | `resources/views/layouts/administrative.blade.php`, `resources/views/components/modern/top-nav-administrative.blade.php`, `resources/views/components/administrative-layout.blade.php`, opcional `sidebar-administrative.blade.php` |
| Rol y permisos | `database/seeders/PermissionsSeeder.php` o `RolesSeeder.php`, `app/Http/Controllers/DashboardController.php`, `routes/web.php` (middleware en grupo de rutas) |
| Vistas layout | Todas las listadas en 1.6: rodados/*, clientes/*, personal/*, etc. |
| Dashboard administrative | Vista dashboard (rodados/dashboard o nueva), controlador que exponga datos para gráficos, endpoints API, componentes Vue (Chart.js), panel de filtros mes/año |
| Rodados → Vehículos | `resources/views/rodados/index.blade.php`, `resources/views/components/modern/sidebar.blade.php` (texto “Vehículos”), migración `rodados` imágenes, `RodadoController`, modal detalle vehículo, listado unificado con CambioEquipoRodado |
| Cambio de equipo | `CambioEquipoRodadoController@store` (verificar create), vista listado “servicios” que incluya cambios de equipo |
| Alertas | `resources/views/rodados/alertas-admin.blade.php` (label km, campo servicio en cobro_cliente), `AlertaAdminController` y lógica de notificación/correo con servicio y monto |
| Build | `npm run build` |

Este plan debe leerse junto con el código actual (rutas, controladores, modelos) para aplicar los cambios sin omitir rutas o permisos ya existentes.
