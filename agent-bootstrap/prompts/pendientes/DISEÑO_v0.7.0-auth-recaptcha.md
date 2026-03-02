# DISEÑO v0.7.0 — Vistas de Autenticación + reCAPTCHA v2

> Documento generado por AgenteDesigner.  
> Para uso del AgenteProgramador al implementar `v0.7.0`.  
> Stack: Laravel 12 + Blade + Tailwind 4 + Alpine.js

---

## Contexto de diseño

```
Modo: mejora de pantallas existentes (4 vistas)
Feature: Vistas de autenticación (login, register, forgot-password, reset-password)
Usuarios afectados: todos (es la puerta de entrada al sistema)
Módulo: Auth (x-guest-layout)
Componentes similares existentes: login.blade.php tiene un rediseño dark parcial
Restricciones: no usar componentes Flux (están dentro de x-guest-layout, fuera del app layout)
```

---

## Análisis del estado actual

| Vista | Estado actual | Problema |
|-------|--------------|---------|
| `login.blade.php` | Rediseño dark parcial, zinc-800/900 | Logo roto (`logo-cos.svg` no existe), sin reCAPTCHA |
| `register.blade.php` | Diseño Jetstream default (blanco) | Inconsistente con el resto |
| `forgot-password.blade.php` | Diseño Jetstream default (blanco) | Inconsistente con el resto |
| `reset-password.blade.php` | Diseño Jetstream default (blanco) | Inconsistente con el resto |

**Qué funciona bien en el login actual:** estructura dark, inputs con zinc-700, link de olvidé contraseña. Mantener esa dirección.

---

## Sistema de diseño — Vistas Auth

Estas vistas viven fuera del layout principal (no tienen sidebar ni header). Usar HTML/Tailwind puro, sin componentes Flux (que requieren contexto Livewire del layout admin/cliente).

### Paleta para vistas auth (dark)

| Token | Valor Tailwind | Uso |
|-------|---------------|-----|
| Fondo de página | `bg-zinc-950` | Fondo full-screen |
| Fondo de card | `bg-zinc-900` | Contenedor del formulario |
| Borde | `border border-zinc-700/50` | Card y inputs |
| Input fondo | `bg-zinc-800` | Campos de texto |
| Input texto | `text-zinc-100` | Texto ingresado |
| Input placeholder | `placeholder-zinc-500` | Placeholder |
| Label | `text-zinc-300 text-sm font-medium` | Labels de campo |
| Botón primario | `bg-indigo-600 hover:bg-indigo-500` | CTA principal |
| Botón texto | `text-white font-semibold` | Texto del botón |
| Link | `text-indigo-400 hover:text-indigo-300` | Links secundarios |
| Error | `text-red-400 text-sm` | Mensajes de error |
| Éxito / status | `text-emerald-400 text-sm` | Mensajes de estado OK |
| Texto secundario | `text-zinc-400 text-sm` | Texto de apoyo |

### Logo

- **Fondo dark (zinc-950/zinc-900):** usar `cyh-white.png`
- **Tamaño:** `h-10` (40px de alto), centrado horizontalmente
- **Margen inferior:** `mb-8`

### Layout general — todas las vistas auth

```
┌─────────────────────────── bg-zinc-950 ──────────────────────────┐
│                                                                    │
│              (centrado vertical y horizontal)                      │
│                                                                    │
│    ┌────────────────── bg-zinc-900 card ────────────────────┐     │
│    │          border border-zinc-700/50 rounded-2xl          │     │
│    │          w-full max-w-md p-8 shadow-2xl                 │     │
│    │                                                          │     │
│    │   [logo cyh-white.png — centrado — h-10 — mb-8]         │     │
│    │                                                          │     │
│    │   [Título de la pantalla — text-xl font-bold mb-6]      │     │
│    │                                                          │     │
│    │   [Contenido específico de cada vista]                   │     │
│    │                                                          │     │
│    └──────────────────────────────────────────────────────────┘    │
│                                                                    │
└────────────────────────────────────────────────────────────────────┘
```

Implementar con:
```html
<div class="min-h-screen flex items-center justify-center bg-zinc-950 px-4">
    <div class="w-full max-w-md">
        <div class="bg-zinc-900 border border-zinc-700/50 rounded-2xl p-8 shadow-2xl">
            <!-- contenido -->
        </div>
    </div>
</div>
```

---

## Vista 1 — Login (`login.blade.php`)

### Propósito
Pantalla de inicio de sesión. Acción principal: ingresar credenciales y autenticarse. Única vista que tiene reCAPTCHA v2.

### Secciones

#### Logo
- `<img src="{{ asset('cyh-white.png') }}" alt="CyH Sur SA" class="h-10 mx-auto mb-8">`

#### Título
- Texto: `"Bienvenido"` (simple, no "Bienvenido al Centro de Operaciones de Seguridad" — es largo)
- Clases: `text-xl font-bold text-zinc-100 text-center mb-6`

#### Mensajes de estado (session flash)
- `@if (session('status'))` → `<div class="mb-4 text-emerald-400 text-sm text-center">{{ session('status') }}</div>`

#### Errores de validación
- `@error('email')` y `@error('password')` inline bajo cada campo (no `x-validation-errors`)

#### Campos del formulario

```
[Email]         ← label: "Email" | type: email | autofocus
[Contraseña]    ← label: "Contraseña" | type: password
```

Patrón de campo:
```html
<div class="space-y-1">
    <label class="text-zinc-300 text-sm font-medium" for="email">Email</label>
    <input id="email" type="email" name="email"
           class="w-full px-4 py-2.5 rounded-lg bg-zinc-800 text-zinc-100 
                  placeholder-zinc-500 border border-zinc-700 
                  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                  transition" ...>
    @error('email') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
</div>
```

#### Fila: Recordarme + ¿Olvidaste tu contraseña?

```
[☐ Recordarme]                    [¿Olvidaste tu contraseña? →]
```

- Layout: `flex items-center justify-between text-sm`
- Checkbox: estilizado con Tailwind, `accent-indigo-500`
- Link: `text-indigo-400 hover:text-indigo-300 transition`

#### reCAPTCHA v2

Posición: **entre los campos y el botón de submit.**

```html
<div class="flex justify-center">
    {!! NoCaptcha()->display() !!}
    {{-- o el helper del paquete instalado --}}
</div>
@error('g-recaptcha-response')
    <p class="text-red-400 text-xs text-center mt-1">{{ $message }}</p>
@enderror
```

> Nota para el programador: el widget de reCAPTCHA tiene su propio estilo. Envolver en un div centrado. No intentar re-estilizar el widget en sí.

#### Botón submit

```html
<button type="submit"
        class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-500 
               text-white font-semibold rounded-lg transition
               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-zinc-900">
    Ingresar
</button>
```

### Flujo de acciones
1. Usuario llega a `/login`
2. Ingresa email y contraseña
3. Completa el reCAPTCHA v2
4. Hace submit → validación backend (email, password, recaptcha)
5. Si OK → redirect al dashboard; si error → vuelve al form con errores inline

### Estados de la vista

| Estado | Qué mostrar |
|--------|-------------|
| Default | Form limpio |
| Error de credenciales | `@error('email')` con mensaje rojo bajo el campo |
| reCAPTCHA no completado | Error bajo el widget |
| Flash status (post forgot-password) | Mensaje verde centrado arriba del form |

---

## Vista 2 — Register (`register.blade.php`)

### Propósito
Registro de nuevos usuarios. Acción principal: completar datos y crear cuenta.

> En muchos sistemas corporativos el registro está deshabilitado o solo lo hace el admin. Verificar si esta ruta es pública o no. El diseño la trata como formulario estándar.

### Campos

```
[Nombre]           ← text, autofocus
[Email]            ← email
[Contraseña]       ← password
[Confirmar contraseña] ← password
[Términos y privacidad] ← checkbox, solo si Jetstream::hasTermsAndPrivacyPolicyFeature()
```

### Botón submit
- Texto: `"Crear cuenta"`
- Mismo estilo que login (indigo-600, w-full)

### Link inferior
- `¿Ya tenés cuenta? Ingresá` → `route('login')` en `text-indigo-400`

---

## Vista 3 — Forgot Password (`forgot-password.blade.php`)

### Propósito
Solicitar recuperación de contraseña por email. Un solo campo.

### Título
- `"Recuperar contraseña"`

### Descripción
- `text-zinc-400 text-sm text-center mb-6`
- Texto: `"Ingresá tu email y te enviaremos un enlace para restablecer tu contraseña."`

### Campos
```
[Email]  ← email, autofocus
```

### Botón submit
- Texto: `"Enviar enlace"`

### Link inferior
- `← Volver al login` → `route('login')` en `text-indigo-400 text-sm`

### Estado de éxito
- Cuando Jetstream envía el email, devuelve session `status`
- Mostrar en `text-emerald-400` centrado arriba del formulario

---

## Vista 4 — Reset Password (`reset-password.blade.php`)

### Propósito
Ingresar nueva contraseña con el token del email.

### Título
- `"Nueva contraseña"`

### Campos
```
[Email]               ← precargado con $request->email, readonly visualmente
[Nueva contraseña]    ← password
[Confirmar contraseña] ← password
[input hidden token]  ← ya existe en el código
```

> El campo email en reset puede ser `readonly` + estilo apagado (`opacity-60 cursor-not-allowed`) ya que el valor viene del enlace del email.

### Botón submit
- Texto: `"Restablecer contraseña"`

---

## Consistencia entre vistas — checklist Designer

- [ ] Las 4 vistas usan el mismo layout base (zinc-950 + card zinc-900)
- [ ] El logo `cyh-white.png` aparece centrado en todas
- [ ] Todos los inputs tienen el mismo estilo (zinc-800, border zinc-700, ring indigo al foco)
- [ ] Los botones submit son idénticos en estilo (indigo-600, rounded-lg, w-full)
- [ ] Los links secundarios son consistentes (indigo-400)
- [ ] Los errores de validación aparecen inline bajo cada campo (text-red-400 text-xs)
- [ ] Los mensajes de sesión/estado aparecen en emerald-400
- [ ] Responsive: el card ocupa `w-full max-w-md` y se adapta sin scroll horizontal en mobile
- [ ] No se usan componentes `x-authentication-card` ni `x-authentication-card-logo` (diseño Jetstream viejo)

---

## Qué NO cambiar

- La lógica de las acciones del formulario (POST routes, CSRF, campos name)
- La funcionalidad de Jetstream para TFA (vistas separadas, no incluidas aquí)
- El `x-guest-layout` wrapper (se mantiene)

---

## Componentes a usar

| Componente | Origen | Nota |
|-----------|--------|------|
| HTML nativo | — | Inputs, labels, botones (sin Flux: estamos fuera del app layout) |
| Alpine.js | Disponible global | Si se necesita toggle de password visible/oculto |
| Tailwind 4 | Global | Todos los estilos |
| reCAPTCHA widget | Paquete a instalar | Solo en login |

---

## Nota sobre x-guest-layout

El componente `x-guest-layout` envuelve el contenido pero no aplica estilos al body. El fondo `bg-zinc-950` se aplica dentro del slot del componente, no en el layout en sí. Verificar que `guest.blade.php` no fuerce un fondo claro que override el de las vistas.

Si el layout guest tiene `class="bg-gray-100"` u similar en el body, actualizar `resources/views/layouts/guest.blade.php` para que el fondo sea neutro (o quitar la clase de fondo y delegarla a cada vista).
