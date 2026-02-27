# DESIGN SYSTEM — COS (Centro de Operaciones de Seguridad)

> Referencia de componentes, estilos y patrones visuales disponibles en el proyecto.
> Fuente de verdad del AGENTE_DESIGNER al proponer o revisar interfaces.

---

## 🎨 Stack visual

| Tecnología | Rol | Notas |
|-----------|-----|-------|
| **Tailwind CSS 4** | Utilidades de estilos | Clases de spaciado, tipografía, colores, grid |
| **Flux** | Componentes UI de alto nivel | Botones, inputs, modales, dropdowns (Livewire-native) |
| **Alpine.js** | Comportamiento JS liviano | Toggles, tooltips, dropdowns, animaciones simples |
| **Livewire 3** | Componentes reactivos | Toda la interactividad compleja, formularios, tablas |
| **Chart.js** | Gráficos | Barras, líneas, tortas, mapas de calor |
| **Leaflet** | Mapas interactivos | Ubicaciones, recorridos, calor |

---

## 🏗️ Layouts disponibles

### Layout Admin (`resources/views/layouts/app.blade.php`)
- **Sidebar** izquierdo con navegación por módulos
- **Header** top con nombre del usuario, notificaciones y acciones
- **Área de contenido** central con breadcrumbs
- **Uso:** todas las rutas sin prefijo `/client/`

### Layout Cliente (`resources/views/layouts/client.blade.php`)
- **Header** superior simplificado
- **Menú** reducido (solo módulos del cliente)
- **Área de contenido** más limpia, menos opciones
- **Uso:** todas las rutas con prefijo `/client/`

---

## 🧱 Componentes Flux disponibles

> Flux es el sistema de componentes UI nativo de Livewire. Usar siempre estos antes de crear elementos custom.

### Botones

```blade
<flux:button>Acción primaria</flux:button>
<flux:button variant="primary">Primario</flux:button>
<flux:button variant="danger">Eliminar</flux:button>
<flux:button variant="ghost">Acción secundaria</flux:button>
<flux:button size="sm">Pequeño</flux:button>
<flux:button icon="plus">Con ícono</flux:button>
```

### Inputs y formularios

```blade
<flux:input label="Nombre" wire:model="nombre" placeholder="Ingresá el nombre" />
<flux:input type="email" label="Email" wire:model="email" />
<flux:textarea label="Descripción" wire:model="descripcion" rows="4" />
<flux:select label="Estado" wire:model="estado">
    <option value="activo">Activo</option>
    <option value="inactivo">Inactivo</option>
</flux:select>
<flux:checkbox label="Activo" wire:model="activo" />
<flux:radio label="Opción A" wire:model="opcion" value="a" />
```

### Modales

```blade
<flux:modal name="confirmar-eliminar">
    <flux:modal.header>Confirmar eliminación</flux:modal.header>
    <flux:modal.body>¿Estás seguro?</flux:modal.body>
    <flux:modal.footer>
        <flux:button x-on:click="$flux.modal.close('confirmar-eliminar')">Cancelar</flux:button>
        <flux:button variant="danger" wire:click="eliminar">Eliminar</flux:button>
    </flux:modal.footer>
</flux:modal>

{{-- Trigger --}}
<flux:button x-on:click="$flux.modal.show('confirmar-eliminar')">Eliminar</flux:button>
```

### Tablas

```blade
<flux:table>
    <flux:columns>
        <flux:column>Nombre</flux:column>
        <flux:column>Estado</flux:column>
        <flux:column>Acciones</flux:column>
    </flux:columns>
    <flux:rows>
        @foreach ($items as $item)
        <flux:row>
            <flux:cell>{{ $item->nombre }}</flux:cell>
            <flux:cell><flux:badge>{{ $item->estado }}</flux:badge></flux:cell>
            <flux:cell>
                <flux:button size="sm" icon="pencil" wire:click="editar({{ $item->id }})">Editar</flux:button>
            </flux:cell>
        </flux:row>
        @endforeach
    </flux:rows>
</flux:table>
```

### Badges y estados

```blade
<flux:badge color="green">Activo</flux:badge>
<flux:badge color="red">Inactivo</flux:badge>
<flux:badge color="yellow">Pendiente</flux:badge>
<flux:badge color="blue">En proceso</flux:badge>
<flux:badge color="gray">N/A</flux:badge>
```

### Alerts / Feedback inline

```blade
{{-- Éxito --}}
<flux:callout variant="success" icon="check-circle">
    Guardado correctamente.
</flux:callout>

{{-- Error --}}
<flux:callout variant="danger" icon="exclamation-triangle">
    Hubo un error. Revisá los datos.
</flux:callout>

{{-- Advertencia --}}
<flux:callout variant="warning" icon="information-circle">
    Atención: este cambio es irreversible.
</flux:callout>

{{-- Errores de validación Livewire --}}
@error('campo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
```

### Dropdowns

```blade
<flux:dropdown>
    <flux:button icon-trailing="chevron-down">Opciones</flux:button>
    <flux:menu>
        <flux:menu.item icon="pencil" wire:click="editar">Editar</flux:menu.item>
        <flux:menu.item icon="trash" variant="danger" wire:click="eliminar">Eliminar</flux:menu.item>
    </flux:menu>
</flux:dropdown>
```

---

## 📐 Patrones de layout frecuentes

### Página con tabla + filtros (patrón más común)

```
┌─────────────────────────────────────────────────────┐
│ Breadcrumb: Inicio > Módulo                         │
│                                                     │
│  [Título del módulo]           [+ Nuevo botón]      │
│                                                     │
│  ┌──────────────────────────────────────────────┐   │
│  │ 🔍 Buscar...    [Filtro 1 ▼] [Filtro 2 ▼]   │   │
│  └──────────────────────────────────────────────┘   │
│                                                     │
│  ┌──────────────────────────────────────────────┐   │
│  │ Col A      │ Col B      │ Col C   │ Acciones │   │
│  │────────────┼────────────┼─────────┼──────────│   │
│  │ ...        │ ...        │ Badge   │ ✏️ 🗑️    │   │
│  │ ...        │ ...        │ Badge   │ ✏️ 🗑️    │   │
│  └──────────────────────────────────────────────┘   │
│                                                     │
│  [ < 1 2 3 > ]   Mostrando 1-10 de 45 resultados   │
└─────────────────────────────────────────────────────┘
```

### Página de detalle / edición

```
┌─────────────────────────────────────────────────────┐
│ Breadcrumb: Inicio > Módulo > Ítem #123             │
│                                                     │
│  [← Volver]              [Guardar] [Cancelar]       │
│                                                     │
│  ┌─────────────────────┐  ┌────────────────────┐   │
│  │  Sección principal  │  │  Sección lateral   │   │
│  │  Campo 1: ________  │  │  Metadata          │   │
│  │  Campo 2: ________  │  │  Estado: Badge     │   │
│  │  Campo 3: ________  │  │  Fecha: ___        │   │
│  └─────────────────────┘  └────────────────────┘   │
└─────────────────────────────────────────────────────┘
```

### Dashboard con KPIs

```
┌─────────────────────────────────────────────────────┐
│  [KPI 1]    [KPI 2]    [KPI 3]    [KPI 4]          │
│  Número     Número     Número     Número            │
│  ↑ trend    ↓ trend    —          ↑ trend          │
│                                                     │
│  ┌────────────────────┐  ┌────────────────────┐    │
│  │ Gráfico 1 (Chart)  │  │ Gráfico 2 (Chart)  │    │
│  │                    │  │                    │    │
│  └────────────────────┘  └────────────────────┘    │
│                                                     │
│  ┌────────────────────────────────────────────┐    │
│  │ Tabla resumen / últimos registros           │    │
│  └────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────┘
```

---

## 🎨 Paleta de colores y tokens

> Tailwind 4 usa variables CSS. Los colores del proyecto respetan el sistema de Tailwind.

| Uso | Clase Tailwind | Descripción |
|-----|---------------|-------------|
| Acción primaria | `bg-blue-600`, `hover:bg-blue-700` | CTA principal |
| Peligro/eliminar | `bg-red-600`, `text-red-500` | Acciones destructivas |
| Éxito | `text-green-600`, `bg-green-50` | Confirmaciones |
| Advertencia | `text-yellow-600`, `bg-yellow-50` | Alertas |
| Neutral/secundario | `bg-gray-100`, `text-gray-700` | Acciones secundarias |
| Texto principal | `text-gray-900` | Títulos |
| Texto secundario | `text-gray-500` | Labels, metadata |
| Bordes | `border-gray-200` | Separadores, cards |
| Fondo de página | `bg-gray-50` o `bg-white` | Fondo del área de contenido |

---

## ✏️ Tipografía

| Elemento | Clases | Uso |
|----------|--------|-----|
| Título de página | `text-2xl font-semibold text-gray-900` | H1 de cada pantalla |
| Subtítulo / sección | `text-lg font-medium text-gray-700` | H2 de sección |
| Label de campo | `text-sm font-medium text-gray-700` | Labels de form |
| Texto body | `text-sm text-gray-600` | Contenido general |
| Texto pequeño / meta | `text-xs text-gray-500` | Fechas, badges, helper text |

---

## 📏 Espaciado estándar

| Elemento | Clase |
|----------|-------|
| Padding de página | `p-6` o `px-6 py-4` |
| Gap entre secciones | `space-y-6` |
| Gap entre campos de form | `space-y-4` |
| Gap entre botones | `gap-2` o `gap-3` |
| Padding de cards | `p-4` o `p-6` |

---

## 🚦 Estados de UI estándar

Toda pantalla debe contemplar estos estados:

| Estado | Implementación sugerida |
|--------|------------------------|
| **Cargando** | `wire:loading` con spinner o `animate-pulse` skeleton |
| **Sin datos (empty state)** | Ilustración o ícono + mensaje + CTA opcional |
| **Error de carga** | `flux:callout variant="danger"` + botón reintentar |
| **Éxito de acción** | `flux:callout variant="success"` inline (no `alert()`) |
| **Confirmación destructiva** | Modal de confirmación antes de eliminar |
| **Formulario inválido** | Errores inline bajo cada campo (`@error`) |

---

## 🔒 Permisos y condicionales de UI

Siempre ocultar / deshabilitar elementos según el permiso del usuario:

```blade
@can('crear.eventos')
    <flux:button>Nuevo evento</flux:button>
@endcan

@cannot('eliminar.contratos')
    {{-- no mostrar el botón de eliminar --}}
@endcannot
```

**Regla:** Si un usuario no tiene permiso para una acción, el botón no debe aparecer en la UI.
No alcanza con que la ruta esté protegida; la UI también debe estar limpia.

---

## 📱 Responsive

El sistema es principalmente para **desktop** (app de gestión interna). Aun así:

- Las tablas deben ser horizontalmente scrolleables en pantallas pequeñas: `overflow-x-auto`
- Los formularios deben apilarse en mobile: `grid grid-cols-1 md:grid-cols-2`
- Los modales son mobile-friendly por defecto con Flux
