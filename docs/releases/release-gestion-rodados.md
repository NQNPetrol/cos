# Release Notes - Sistema de Gestión de Rodados

**Versión:** 1.0.0  
**Fecha de Release:** Diciembre 2025  
**Tipo:** Nueva Funcionalidad

---

## Resumen Ejecutivo

Se ha implementado un sistema completo de gestión de rodados (vehículos) que permite a los administradores registrar, gestionar y monitorear todos los aspectos relacionados con los vehículos de la empresa. El sistema incluye gestión de servicios, mantenimientos, cambios de equipos, registro de kilometraje y control de pagos asociados.

---

## Características Principales

### 1. Gestión de Vehículos
- Registro completo de información de vehículos (marca, modelo, tipo, año, patente)
- Clasificación entre vehículos propios y alquilados
- Asociación con clientes y proveedores
- Visualización de kilometraje actual en el listado principal
- Filtros avanzados por marca, tipo, cliente y propiedad

### 2. Gestión de Servicios Unificada
- **Turnos de Service**: Programación y seguimiento de servicios regulares
- **Turnos Mecánicos**: Registro de reparaciones y mantenimientos fuera de service
- **Cambios de Equipos**: Control de cambios de cubiertas, antenas Starlink, cámaras móviles y DVR
- **Turnos al Taller**: Gestión de turnos adicionales para reparaciones específicas
- Calendario unificado con todos los servicios
- Sistema de alertas para vencimientos de pago

### 3. Control de Pagos
- **Pago de Patente**: Registro mensual de pagos de patente para vehículos propios
- **Pago de Alquiler**: Gestión de pagos mensuales de alquiler (incluye service cuando aplica)
- **Pago a Proveedores**: Control de otros pagos relacionados
- Verificación automática del tipo de vehículo para mostrar opciones apropiadas
- Almacenamiento de comprobantes de pago

### 4. Registro de Kilometraje
- Historial completo de registros de kilometraje por vehículo
- Validación automática para asegurar que el kilometraje sea mayor al anterior
- Visualización del kilometraje actual en el listado de vehículos
- Registro de observaciones por cada entrada

---

## Estructura de Base de Datos

### Tablas Implementadas

#### 1. `proveedores`
Almacena información de proveedores de vehículos (para alquileres).

**Campos:**
- `id` (PK)
- `nombre` (string, required)
- `contacto` (string, nullable)
- `telefono` (string, nullable)
- `email` (string, nullable)
- `created_at`, `updated_at`

#### 2. `talleres`
Almacena información de talleres mecánicos.

**Campos:**
- `id` (PK)
- `nombre` (string, required)
- `contacto` (string, nullable)
- `telefono` (string, nullable)
- `email` (string, nullable)
- `direccion` (string, nullable)
- `created_at`, `updated_at`

#### 3. `rodados`
Tabla principal de vehículos.

**Campos:**
- `id` (PK)
- `marca` (string, required)
- `tipo_vehiculo` (string, required)
- `modelo` (string, required)
- `año` (integer, required)
- `proveedor_id` (FK, nullable) → `proveedores.id`
- `cliente_id` (FK, required) → `clientes.id`
- `es_propio` (boolean, default: true)
- `patente` (string, nullable)
- `created_at`, `updated_at`

**Índices:**
- Foreign key a `proveedores` (on delete: set null)
- Foreign key a `clientes` (on delete: cascade)

#### 4. `turnos_rodados`
Tabla unificada para todos los tipos de turnos y servicios.

**Campos:**
- `id` (PK)
- `rodado_id` (FK, required) → `rodados.id`
- `taller_id` (FK, required) → `talleres.id`
- `tipo` (enum: 'turno_service', 'turno_mecanico', 'cambio_equipo', 'turno_taller')
- `fecha_hora` (datetime, required)
- `encargado_dejar` (string, nullable)
- `encargado_retirar` (string, nullable)
- `tipo_reparacion` (string, nullable) - Solo para turno_mecanico
- `descripcion` (text, nullable) - Solo para turno_mecanico
- `cubre_servicio` (boolean, default: false) - Solo para turno_mecanico
- `tipo_equipo` (string, nullable) - Solo para cambio_equipo
- `tipo_cubierta` (string, nullable) - Solo para cambio_equipo tipo cubiertas
- `pago_mano_obra` (decimal 10,2, nullable) - Solo para cambio_equipo
- `factura_path` (string, nullable)
- `comprobante_pago_path` (string, nullable)
- `fecha_factura` (date, nullable)
- `dias_vencimiento` (integer, nullable)
- `fecha_vencimiento_pago` (date, nullable) - Calculado automáticamente
- `estado` (enum: 'pendiente', 'completado', default: 'pendiente')
- `created_at`, `updated_at`

**Índices:**
- Foreign key a `rodados` (on delete: cascade)
- Foreign key a `talleres` (on delete: cascade)

#### 5. `cambio_equipo_rodado`
Registro específico de cambios de equipos (cubiertas, antenas, cámaras, DVR).

**Campos:**
- `id` (PK)
- `rodado_id` (FK, required) → `rodados.id`
- `taller_id` (FK, required) → `talleres.id`
- `tipo` (enum: 'cubiertas', 'antena_starlink', 'camara_mobil', 'dvr', default: 'cubiertas')
- `fecha_hora_estimada` (datetime, required)
- `tipo_cubierta` (string, nullable) - Solo para tipo cubiertas
- `pago_mano_obra` (decimal 10,2, required)
- `factura_path` (string, nullable)
- `comprobante_pago_path` (string, nullable)
- `kilometraje_en_cambio` (integer, required)
- `created_at`, `updated_at`

**Índices:**
- Foreign key a `rodados` (on delete: cascade)
- Foreign key a `talleres` (on delete: cascade)

#### 6. `registros_kilometraje`
Historial de registros de kilometraje por vehículo.

**Campos:**
- `id` (PK)
- `rodado_id` (FK, required) → `rodados.id`
- `kilometraje` (integer, required)
- `fecha_registro` (date, required)
- `observaciones` (text, nullable)
- `created_at`, `updated_at`

**Índices:**
- Foreign key a `rodados` (on delete: cascade)

#### 7. `pago_servicios_rodados`
Tabla unificada para todos los tipos de pagos relacionados con rodados.

**Campos:**
- `id` (PK)
- `rodado_id` (FK, required) → `rodados.id`
- `proveedor_id` (FK, nullable) → `proveedores.id`
- `tipo` (enum: 'pago_patente', 'pago_alquiler', 'pago_proveedor', default: 'pago_patente')
- `mes` (integer, required, 1-12)
- `año` (integer, required)
- `monto` (decimal 10,2, required)
- `monto_service` (decimal 10,2, nullable) - Solo para pago_alquiler
- `factura_path` (string, nullable)
- `comprobante_pago_path` (string, nullable)
- `fecha_pago` (date, required)
- `created_at`, `updated_at`

**Índices:**
- Foreign key a `rodados` (on delete: cascade)
- Foreign key a `proveedores` (on delete: set null)

---

## Modelos Eloquent

### 1. `App\Models\Proveedor`

**Tabla:** `proveedores`

**Relaciones:**
- `hasMany` → `Rodado` (rodados)
- `hasMany` → `PagoServiciosRodado` (pagosServicios)

**Fillable:**
- nombre, contacto, telefono, email

### 2. `App\Models\Taller`

**Tabla:** `talleres`

**Relaciones:**
- `hasMany` → `TurnoRodado` (turnosRodados)
- `hasMany` → `CambioEquipoRodado` (cambiosEquipos)

**Fillable:**
- nombre, contacto, telefono, email, direccion

### 3. `App\Models\Rodado`

**Tabla:** `rodados`

**Relaciones:**
- `belongsTo` → `Cliente` (cliente)
- `belongsTo` → `Proveedor` (proveedor, nullable)
- `hasMany` → `TurnoRodado` (turnosRodados)
- `hasMany` → `CambioEquipoRodado` (cambiosEquipos)
- `hasMany` → `RegistroKilometraje` (registrosKilometraje)
- `hasOne` → `RegistroKilometraje` (kilometrajeActual) - Más reciente
- `hasMany` → `PagoServiciosRodado` (pagosServicios)

**Fillable:**
- marca, tipo_vehiculo, modelo, año, proveedor_id, cliente_id, es_propio, patente

**Casts:**
- es_propio → boolean
- año → integer

### 4. `App\Models\TurnoRodado`

**Tabla:** `turnos_rodados`

**Relaciones:**
- `belongsTo` → `Rodado` (rodado)
- `belongsTo` → `Taller` (taller)

**Fillable:**
- rodado_id, taller_id, tipo, fecha_hora, encargado_dejar, encargado_retirar, tipo_reparacion, descripcion, cubre_servicio, tipo_equipo, tipo_cubierta, pago_mano_obra, factura_path, comprobante_pago_path, fecha_factura, dias_vencimiento, fecha_vencimiento_pago, estado

**Casts:**
- fecha_hora → datetime
- fecha_factura → date
- fecha_vencimiento_pago → date
- cubre_servicio → boolean
- pago_mano_obra → decimal:2
- dias_vencimiento → integer

**Constantes:**
- `TIPO_TURNO_SERVICE = 'turno_service'`
- `TIPO_TURNO_MECANICO = 'turno_mecanico'`
- `TIPO_CAMBIO_EQUIPO = 'cambio_equipo'`
- `TIPO_TURNO_TALLER = 'turno_taller'`
- `ESTADO_PENDIENTE = 'pendiente'`
- `ESTADO_COMPLETADO = 'completado'`

### 5. `App\Models\CambioEquipoRodado`

**Tabla:** `cambio_equipo_rodado`

**Relaciones:**
- `belongsTo` → `Rodado` (rodado)
- `belongsTo` → `Taller` (taller)

**Fillable:**
- rodado_id, taller_id, tipo, fecha_hora_estimada, tipo_cubierta, pago_mano_obra, factura_path, comprobante_pago_path, kilometraje_en_cambio

**Casts:**
- fecha_hora_estimada → datetime
- pago_mano_obra → decimal:2
- kilometraje_en_cambio → integer

**Constantes:**
- `TIPO_CUBIERTAS = 'cubiertas'`
- `TIPO_ANTENA_STARLINK = 'antena_starlink'`
- `TIPO_CAMARA_MOBIL = 'camara_mobil'`
- `TIPO_DVR = 'dvr'`

### 6. `App\Models\RegistroKilometraje`

**Tabla:** `registros_kilometraje`

**Relaciones:**
- `belongsTo` → `Rodado` (rodado)

**Fillable:**
- rodado_id, kilometraje, fecha_registro, observaciones

**Casts:**
- kilometraje → integer
- fecha_registro → date

### 7. `App\Models\PagoServiciosRodado`

**Tabla:** `pago_servicios_rodados`

**Relaciones:**
- `belongsTo` → `Rodado` (rodado)
- `belongsTo` → `Proveedor` (proveedor, nullable)

**Fillable:**
- rodado_id, proveedor_id, tipo, mes, año, monto, monto_service, factura_path, comprobante_pago_path, fecha_pago

**Casts:**
- mes → integer
- año → integer
- monto → decimal:2
- monto_service → decimal:2
- fecha_pago → date

**Constantes:**
- `TIPO_PAGO_PATENTE = 'pago_patente'`
- `TIPO_PAGO_ALQUILER = 'pago_alquiler'`
- `TIPO_PAGO_PROVEEDOR = 'pago_proveedor'`

---

## Controladores

### 1. `App\Http\Controllers\RodadoController`

**Responsabilidades:**
- Vista principal con pestañas
- CRUD de rodados
- Carga de datos para todas las pestañas
- Eliminación de archivos asociados al eliminar rodados

**Métodos:**
- `index()` - Muestra la vista principal con todas las pestañas
- `store(Request $request)` - Crea un nuevo rodado
- `update(Request $request, Rodado $rodado)` - Actualiza un rodado existente
- `destroy(Rodado $rodado)` - Elimina un rodado y sus archivos asociados

### 2. `App\Http\Controllers\TurnoRodadoController`

**Responsabilidades:**
- Gestión de turnos (service, mecánicos, cambios de equipos, turnos al taller)
- Manejo de archivos (facturas y comprobantes)
- Cálculo automático de vencimientos

**Métodos:**
- `store(Request $request)` - Crea un nuevo turno
- `update(Request $request, TurnoRodado $turno)` - Actualiza un turno
- `destroy(TurnoRodado $turno)` - Elimina un turno y sus archivos

**Validaciones:**
- Archivos: PDF o JPEG, máximo 10MB
- Campos condicionales según tipo de turno
- Cálculo de fecha_vencimiento_pago = fecha_factura + dias_vencimiento

### 3. `App\Http\Controllers\CambioEquipoRodadoController`

**Responsabilidades:**
- Gestión específica de cambios de equipos
- Manejo de archivos asociados

**Métodos:**
- `store(Request $request)` - Registra un cambio de equipo
- `update(Request $request, CambioEquipoRodado $cambio)` - Actualiza un cambio
- `destroy(CambioEquipoRodado $cambio)` - Elimina un cambio y sus archivos

### 4. `App\Http\Controllers\RegistroKilometrajeController`

**Responsabilidades:**
- Registro de kilometraje
- Validación de que el kilometraje sea mayor al anterior

**Métodos:**
- `store(Request $request)` - Registra nuevo kilometraje
- `destroy(RegistroKilometraje $registro)` - Elimina un registro

**Validaciones:**
- Kilometraje debe ser mayor al último registro del vehículo

### 5. `App\Http\Controllers\PagoServiciosRodadoController`

**Responsabilidades:**
- Gestión de pagos (patente, alquiler, proveedor)
- Validación según tipo de vehículo
- Manejo de archivos de comprobantes

**Métodos:**
- `store(Request $request)` - Registra un nuevo pago
- `update(Request $request, PagoServiciosRodado $pago)` - Actualiza un pago
- `destroy(PagoServiciosRodado $pago)` - Elimina un pago y sus archivos

**Validaciones:**
- Proveedor requerido para pago_alquiler
- monto_service solo para pago_alquiler

### 6. `App\Http\Controllers\ProveedorController` (API Resource)

**Métodos:** index, store, show, update, destroy

### 7. `App\Http\Controllers\TallerController` (API Resource)

**Métodos:** index, store, show, update, destroy

---

## Rutas

Todas las rutas están bajo el prefijo `/admin/rodados` y requieren autenticación y rol de administrador.

```php
Route::prefix('rodados')->name('rodados.')->group(function () {
    // Vista principal
    Route::get('/', [RodadoController::class, 'index'])->name('index');
    
    // CRUD Rodados
    Route::post('/', [RodadoController::class, 'store'])->name('store');
    Route::put('/{rodado}', [RodadoController::class, 'update'])->name('update');
    Route::delete('/{rodado}', [RodadoController::class, 'destroy'])->name('destroy');
    
    // Turnos Rodados
    Route::post('/turnos', [TurnoRodadoController::class, 'store'])->name('turnos.store');
    Route::put('/turnos/{turno}', [TurnoRodadoController::class, 'update'])->name('turnos.update');
    Route::delete('/turnos/{turno}', [TurnoRodadoController::class, 'destroy'])->name('turnos.destroy');
    
    // Cambios de Equipos
    Route::post('/cambios-equipos', [CambioEquipoRodadoController::class, 'store'])->name('cambios-equipos.store');
    Route::put('/cambios-equipos/{cambio}', [CambioEquipoRodadoController::class, 'update'])->name('cambios-equipos.update');
    Route::delete('/cambios-equipos/{cambio}', [CambioEquipoRodadoController::class, 'destroy'])->name('cambios-equipos.destroy');
    
    // Kilometraje
    Route::post('/kilometraje', [RegistroKilometrajeController::class, 'store'])->name('kilometraje.store');
    Route::delete('/kilometraje/{registro}', [RegistroKilometrajeController::class, 'destroy'])->name('kilometraje.destroy');
    
    // Pagos Servicios
    Route::post('/pagos', [PagoServiciosRodadoController::class, 'store'])->name('pagos.store');
    Route::put('/pagos/{pago}', [PagoServiciosRodadoController::class, 'update'])->name('pagos.update');
    Route::delete('/pagos/{pago}', [PagoServiciosRodadoController::class, 'destroy'])->name('pagos.destroy');
    
    // Proveedores y Talleres (API)
    Route::apiResource('proveedores', ProveedorController::class);
    Route::apiResource('talleres', TallerController::class);
});
```

---

## Vistas y Componentes

### Estructura de Vistas

```
resources/views/rodados/
├── index.blade.php                    # Vista principal con pestañas
├── partials/
│   ├── vehiculos-tab.blade.php        # Pestaña de vehículos
│   ├── servicios-tab.blade.php        # Pestaña de servicios unificada
│   └── pagos-tab.blade.php            # Pestaña de pagos
└── modals/
    ├── vehiculo-modal.blade.php       # Modal crear/editar vehículo
    ├── turno-modal.blade.php          # Modal crear/editar turno
    ├── cambio-equipo-modal.blade.php  # Modal crear/editar cambio de equipo
    ├── pago-modal.blade.php           # Modal crear/editar pago
    └── kilometraje-modal.blade.php    # Modal registrar kilometraje
```

### Diseño y Estilo

**Paleta de Colores:**
- Fondo principal: `bg-gray-900`
- Contenedores: `bg-gray-800` con `border-gray-700`
- Botones primarios: `bg-blue-600 hover:bg-blue-700`
- Texto: `text-gray-100` (títulos), `text-gray-300` (contenido), `text-gray-400` (secundario)
- Alertas: Verde (completado), Amarillo (pendiente/venciendo), Rojo (vencido)

**Componentes Reutilizables:**
- Modales con diseño consistente
- Tablas con filtros integrados
- Badges para estados y tipos
- Inputs de archivo con preview
- Datepickers para fechas

---

## Funcionalidades Detalladas

### Pestaña "Vehículos"

**Características:**
- Listado completo de vehículos con información resumida
- **Kilometraje actual**: Muestra el registro más reciente de kilometraje para cada vehículo
- Filtros por:
  - Marca
  - Tipo de vehículo
  - Cliente
  - Propiedad (Propio/Alquilado)
- Indicadores visuales:
  - Badge verde para vehículos propios
  - Badge azul para vehículos alquilados (con nombre del proveedor)
- Acciones:
  - Crear nuevo vehículo
  - Editar vehículo existente
  - Eliminar vehículo (con confirmación)
  - Registrar kilometraje (botón dedicado)

**Validaciones:**
- Año entre 1900 y año actual + 1
- Proveedor requerido solo si es_propio = false
- Patente opcional

### Pestaña "Servicios"

**Características:**
- Vista unificada que muestra:
  - Turnos de service
  - Turnos mecánicos (mantenimientos y reparaciones)
  - Cambios de equipos (cubiertas, antenas, cámaras, DVR)
  - Turnos al taller adicionales
- Filtros por:
  - Vehículo
  - Tipo de servicio
  - Taller
  - Estado (pendiente/completado)
- Botones de acción:
  - "Turno Service" - Crea un turno de service regular
  - "Turno Taller" - Crea un turno mecánico para reparaciones
  - "Cambio Equipo" - Registra un cambio de equipo
- Columnas de información:
  - Tipo de servicio (con badge de color)
  - Vehículo
  - Fecha y hora
  - Taller
  - Estado
  - Vencimiento de pago (con alertas visuales)
  - Acciones (editar/eliminar)

**Lógica Especial:**
- **Turnos Mecánicos**: Campo "Cubre Servicio" determina si la empresa o el cliente paga
  - Si cubre_servicio = true → Badge verde "Cubre Empresa"
  - Si cubre_servicio = false → Badge rojo "Cubre Cliente"
- **Alertas de Vencimiento**:
  - Rojo: Pago vencido
  - Amarillo: Vence en 7 días o menos
  - Sin alerta: Más de 7 días restantes

**Campos por Tipo de Turno:**

1. **Turno Service:**
   - Fecha/hora, taller, encargados (dejar/retirar)
   - Factura y comprobante opcionales
   - Fecha factura y días de vencimiento

2. **Turno Mecánico:**
   - Todos los campos de service +
   - Tipo de reparación
   - Descripción
   - Checkbox "Cubre Servicio"

3. **Cambio de Equipo:**
   - Tipo de equipo (cubiertas, antena starlink, cámara móvil, DVR)
   - Tipo de cubierta (solo si es cubiertas)
   - Pago mano de obra
   - Kilometraje en el cambio
   - Factura y comprobante opcionales

4. **Turno al Taller:**
   - Similar a turno service pero para reparaciones adicionales

### Pestaña "Pagos"

**Características:**
- Listado unificado de todos los tipos de pagos
- Filtros por:
  - Vehículo
  - Tipo de pago (patente, alquiler, proveedor)
  - Año
  - Proveedor
- **Verificación Automática**: El sistema detecta si el vehículo es propio o alquilado y muestra opciones apropiadas
- Columnas:
  - Tipo de pago (con badge de color)
  - Vehículo
  - Mes/Año
  - Monto
  - Fecha de pago
  - Comprobante (enlace si existe)
  - Acciones

**Lógica de Tipos de Pago:**

1. **Pago Patente:**
   - Solo para vehículos propios
   - Campos: mes, año, monto, fecha_pago
   - Comprobante opcional

2. **Pago Alquiler:**
   - Solo para vehículos alquilados
   - Requiere proveedor
   - Campos: mes, año, monto (alquiler), monto_service (opcional, incluido en alquiler)
   - Comprobante opcional

3. **Pago Proveedor:**
   - Para otros pagos a proveedores
   - Requiere proveedor
   - Campos: mes, año, monto, fecha_pago
   - Comprobante opcional

---

## Manejo de Archivos

### Almacenamiento

**Ubicación:** `storage/app/public/rodados/{rodado_id}/`

**Estructura de Carpetas:**
```
storage/app/public/rodados/
├── {rodado_id}/
│   ├── facturas/
│   │   ├── factura_turno_123.pdf
│   │   └── factura_cambio_456.jpg
│   └── comprobantes/
│       ├── comprobante_pago_789.pdf
│       └── comprobante_alquiler_012.jpg
```

### Validaciones de Archivos

- **Formatos aceptados:** PDF, JPEG (jpg, jpeg)
- **Tamaño máximo:** 10MB por archivo
- **Campos:** Todos los campos de archivo son opcionales (nullable)

### Gestión de Archivos

- Los archivos se eliminan automáticamente cuando se elimina el registro asociado
- Al actualizar, si se sube un nuevo archivo, el anterior se elimina
- Los archivos se acceden mediante `asset('storage/' . $path)`

---

## Validaciones y Reglas de Negocio

### Validaciones de Datos

1. **Rodados:**
   - Marca, tipo, modelo: requeridos, máximo 255 caracteres
   - Año: requerido, entre 1900 y año actual + 1
   - Cliente: requerido, debe existir en tabla clientes
   - Proveedor: opcional, debe existir si se proporciona
   - Patente: opcional, máximo 20 caracteres

2. **Turnos:**
   - Vehículo y taller: requeridos
   - Tipo: requerido, debe ser uno de los valores permitidos
   - Fecha/hora: requerida, formato datetime
   - Archivos: opcionales, PDF o JPEG, máximo 10MB
   - Días de vencimiento: opcional, entero positivo

3. **Cambios de Equipos:**
   - Vehículo, taller, tipo: requeridos
   - Fecha/hora estimada: requerida
   - Pago mano de obra: requerido, decimal positivo
   - Kilometraje en cambio: requerido, entero positivo
   - Tipo cubierta: opcional, solo si tipo = 'cubiertas'

4. **Kilometraje:**
   - Vehículo: requerido
   - Kilometraje: requerido, entero positivo
   - **Validación especial:** El kilometraje debe ser mayor al último registro del vehículo
   - Fecha registro: requerida
   - Observaciones: opcional

5. **Pagos:**
   - Vehículo: requerido
   - Tipo: requerido, debe ser uno de los valores permitidos
   - Mes: requerido, entre 1 y 12
   - Año: requerido, entre 2000 y año actual + 1
   - Monto: requerido, decimal positivo
   - Fecha pago: requerida
   - **Validación especial:** Proveedor requerido si tipo = 'pago_alquiler'
   - Monto service: opcional, solo para pago_alquiler

### Reglas de Negocio

1. **Vehículos Propios vs Alquilados:**
   - Si `es_propio = true`: No requiere proveedor, puede tener pagos de patente
   - Si `es_propio = false`: Requiere proveedor, puede tener pagos de alquiler

2. **Cálculo de Vencimientos:**
   - `fecha_vencimiento_pago = fecha_factura + dias_vencimiento`
   - Solo se calcula si ambos campos están presentes

3. **Kilometraje:**
   - Debe ser siempre creciente (mayor al anterior)
   - Se muestra el más reciente en el listado de vehículos

4. **Pagos de Alquiler:**
   - Incluyen el monto del alquiler
   - Pueden incluir monto_service (service incluido en el alquiler)
   - Todo viene en una sola factura mensual

---

## Integración con el Sistema

### Menú de Navegación

**Ubicación:** `resources/views/layouts/app.blade.php`

**Item agregado:**
- Sección: Administración
- Nombre: "Gestión de Rodados"
- Icono: `bi-truck` (Bootstrap Icons)
- Ruta: `route('rodados.index')`
- Acceso: Solo administradores

### Permisos y Seguridad

- **Middleware:** `auth`, `role:admin`
- **Rutas:** Todas las rutas requieren autenticación y rol de administrador
- **Validación:** Sanitización de inputs en todos los controladores
- **Archivos:** Validación de tipos MIME y tamaño máximo

---

## Mejoras de Performance

### Optimizaciones Implementadas

1. **Eager Loading:**
   - Rodados cargados con relaciones: cliente, proveedor, kilometrajeActual
   - Turnos cargados con: rodado, taller
   - Pagos cargados con: rodado, proveedor

2. **Consultas Optimizadas:**
   - Uso de `latest()` para ordenamiento eficiente
   - Índices en foreign keys
   - Consultas específicas por relación

3. **Caché:**
   - Se recomienda limpiar caché después de migraciones: `php artisan optimize:clear`

---

## Guía de Uso

### Para Administradores

#### Registrar un Nuevo Vehículo

1. Ir a **Administración > Gestión de Rodados**
2. Pestaña "Vehículos"
3. Click en "Nuevo Vehículo"
4. Completar formulario:
   - Marca, modelo, tipo, año
   - Seleccionar cliente
   - Indicar si es propio o alquilado
   - Si es alquilado, seleccionar proveedor
   - Patente (opcional)
5. Guardar

#### Registrar un Turno de Service

1. Pestaña "Servicios"
2. Click en "Turno Service"
3. Seleccionar vehículo y taller
4. Ingresar fecha y hora
5. Opcional: Encargados, factura, comprobante
6. Si hay factura: Ingresar fecha y días de vencimiento
7. Guardar

#### Registrar un Turno Mecánico

1. Pestaña "Servicios"
2. Click en "Turno Taller"
3. Completar información básica
4. Ingresar tipo de reparación y descripción
5. **Importante:** Marcar "Cubre Servicio" si la empresa paga
6. Si la empresa paga: Subir factura y comprobante
7. Guardar

#### Registrar Cambio de Equipo

1. Pestaña "Servicios"
2. Click en "Cambio Equipo"
3. Seleccionar vehículo, taller y tipo de equipo
4. Si es cubiertas: Especificar tipo de cubierta
5. Ingresar pago mano de obra y kilometraje
6. Opcional: Factura y comprobante
7. Guardar

#### Registrar Kilometraje

1. Pestaña "Vehículos"
2. Click en "Registrar Kilometraje"
3. Seleccionar vehículo
4. Ingresar kilometraje (debe ser mayor al anterior)
5. Fecha de registro
6. Opcional: Observaciones
7. Guardar

#### Registrar un Pago

1. Pestaña "Pagos"
2. Click en "Nuevo Pago"
3. Seleccionar vehículo (el sistema detecta si es propio o alquilado)
4. Seleccionar tipo de pago:
   - **Patente**: Solo para vehículos propios
   - **Alquiler**: Solo para vehículos alquilados (requiere proveedor)
   - **Proveedor**: Otros pagos
5. Completar mes, año, monto
6. Si es alquiler: Opcionalmente agregar monto_service
7. Fecha de pago
8. Opcional: Factura y comprobante
9. Guardar

---

## Consideraciones Técnicas

### Migraciones

**Orden de ejecución recomendado:**
1. `create_proveedores_table`
2. `create_talleres_table`
3. `create_rodados_table`
4. `create_turnos_rodados_table`
5. `create_cambio_equipo_rodado_table`
6. `create_registros_kilometraje_table`
7. `create_pago_servicios_rodados_table`

**Nota:** Las migraciones están diseñadas para ejecutarse en el orden correcto según las dependencias de foreign keys.

### Configuración de Storage

Asegurar que el enlace simbólico esté creado:
```bash
php artisan storage:link
```

Esto permite acceder a los archivos mediante `asset('storage/...')`.

### Pluralización de Modelos

Los modelos `Proveedor` y `Taller` tienen especificado explícitamente el nombre de la tabla debido a la pluralización incorrecta de Laravel:
- `Proveedor` → tabla `proveedores` (no `proveedors`)
- `Taller` → tabla `talleres` (no `tallers`)

---

## Próximas Mejoras Sugeridas

### Funcionalidades Futuras

1. **Reportes y Estadísticas:**
   - Gráficos de frecuencia de cambios de cubiertas por vehículo
   - Kilometraje promedio entre cambios de equipos
   - Historial de costos por vehículo
   - Análisis de gastos mensuales

2. **Notificaciones:**
   - Alertas automáticas para vencimientos de pago próximos
   - Recordatorios de turnos de service programados
   - Notificaciones de cambios de equipos frecuentes

3. **Exportación:**
   - Exportar listados a PDF/Excel
   - Reportes mensuales de gastos
   - Historial completo por vehículo

4. **Búsqueda Avanzada:**
   - Búsqueda global en todas las pestañas
   - Filtros combinados
   - Búsqueda por patente, proveedor, etc.

5. **Calendario Visual:**
   - Vista de calendario mensual con todos los turnos
   - Drag & drop para reprogramar turnos
   - Vista semanal/diaria

---

## Archivos Creados/Modificados

### Migraciones (7 archivos)
- `database/migrations/YYYY_MM_DD_HHMMSS_create_proveedores_table.php`
- `database/migrations/YYYY_MM_DD_HHMMSS_create_talleres_table.php`
- `database/migrations/YYYY_MM_DD_HHMMSS_create_rodados_table.php`
- `database/migrations/YYYY_MM_DD_HHMMSS_create_turnos_rodados_table.php`
- `database/migrations/YYYY_MM_DD_HHMMSS_create_cambio_equipo_rodado_table.php`
- `database/migrations/YYYY_MM_DD_HHMMSS_create_registros_kilometraje_table.php`
- `database/migrations/YYYY_MM_DD_HHMMSS_create_pago_servicios_rodados_table.php`

### Modelos (7 archivos)
- `app/Models/Proveedor.php`
- `app/Models/Taller.php`
- `app/Models/Rodado.php`
- `app/Models/TurnoRodado.php`
- `app/Models/CambioEquipoRodado.php`
- `app/Models/RegistroKilometraje.php`
- `app/Models/PagoServiciosRodado.php`

### Controladores (7 archivos)
- `app/Http/Controllers/RodadoController.php`
- `app/Http/Controllers/TurnoRodadoController.php`
- `app/Http/Controllers/CambioEquipoRodadoController.php`
- `app/Http/Controllers/RegistroKilometrajeController.php`
- `app/Http/Controllers/PagoServiciosRodadoController.php`
- `app/Http/Controllers/ProveedorController.php`
- `app/Http/Controllers/TallerController.php`

### Vistas (9 archivos)
- `resources/views/rodados/index.blade.php`
- `resources/views/rodados/partials/vehiculos-tab.blade.php`
- `resources/views/rodados/partials/servicios-tab.blade.php`
- `resources/views/rodados/partials/pagos-tab.blade.php`
- `resources/views/rodados/modals/vehiculo-modal.blade.php`
- `resources/views/rodados/modals/turno-modal.blade.php`
- `resources/views/rodados/modals/cambio-equipo-modal.blade.php`
- `resources/views/rodados/modals/pago-modal.blade.php`
- `resources/views/rodados/modals/kilometraje-modal.blade.php`

### Rutas
- `routes/web.php` (modificado - agregadas rutas de rodados)

### Layout
- `resources/views/layouts/app.blade.php` (modificado - agregado item de menú)

---

## Testing y Validación

### Checklist de Funcionalidades

- [x] CRUD completo de vehículos
- [x] Registro de kilometraje con validación
- [x] Gestión de turnos de service
- [x] Gestión de turnos mecánicos con lógica "Cubre Servicio"
- [x] Gestión de cambios de equipos
- [x] Gestión de pagos (patente, alquiler, proveedor)
- [x] Subida de archivos (facturas y comprobantes)
- [x] Cálculo automático de vencimientos
- [x] Filtros en todas las pestañas
- [x] Alertas visuales de vencimientos
- [x] Eliminación de archivos al eliminar registros
- [x] Validaciones de negocio (kilometraje, tipo de vehículo, etc.)

---

## Notas de Instalación

### Pasos para Desplegar

1. **Ejecutar migraciones:**
   ```bash
   php artisan migrate
   ```

2. **Crear enlace simbólico de storage:**
   ```bash
   php artisan storage:link
   ```

3. **Limpiar caché:**
   ```bash
   php artisan optimize:clear
   ```

4. **Verificar permisos:**
   - Asegurar que el usuario tenga rol de administrador
   - Verificar que las rutas estén correctamente configuradas

### Requisitos Previos

- Laravel 12.x
- PHP 8.3+
- Base de datos MySQL/MariaDB
- Tabla `clientes` existente (requerida para foreign key)

---

## Soporte y Contacto

Para reportar problemas o solicitar nuevas funcionalidades relacionadas con la gestión de rodados, contactar al equipo de desarrollo.

---

**Documento generado:** Diciembre 2025  
**Última actualización:** Diciembre 2025

