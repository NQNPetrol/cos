# CHECKLIST QA — Módulos del proyecto COS

> Referencia para el AGENTE_QA. Cada ítem debe verificarse en el navegador.
> Rutas y permisos completos en `BLUEPRINT.md`.

---

## Cómo usar este checklist

- Marcar ✅ si pasa, ❌ si hay bug, ⚠️ si hay advertencia menor.
- Verificar **siempre con dos usuarios**: uno con el permiso correcto y otro sin él.
- Si un módulo no fue modificado en el scope actual, puede marcarse como `⏭ no en scope`.

---

## 🌐 Acceso y autenticación

| Check | URI | Descripción |
|-------|-----|-------------|
| [ ] | `/login` | Formulario carga, credenciales correctas redirigen al dashboard |
| [ ] | `/login` | Credenciales incorrectas muestran error sin romper |
| [ ] | `/logout` | Cierra sesión y redirige al landing/login |
| [ ] | `/no-access` | Visible para usuarios sin rol asignado |
| [ ] | `/` | Redirige a landing si no está autenticado, a dashboard si sí |

---

## 🏠 Dashboards

| Check | URI | Layout | Descripción |
|-------|-----|--------|-------------|
| [ ] | `/dashboard` | admin | Carga sin errores, gráficos visibles |
| [ ] | `/main-dashboard` | admin | KPIs y gráficos de eventos por cliente/categoría |
| [ ] | `/client/dashboard` | cliente | Dashboard cliente carga y muestra datos del cliente asignado |
| [ ] | `/operaciones/dashboard` | admin | Dashboard operacional carga y muestra mapa/eventos |
| [ ] | `/client/operaciones/dashboard` | cliente | Igual pero en layout cliente |
| [ ] | `/client/dashboard-patrullas` | cliente | Gráficos de recorridos y tendencia |

---

## 👤 Usuarios y roles

| Check | URI | Descripción |
|-------|-----|-------------|
| [ ] | `/usuarios` | Lista de usuarios visible solo con `administrar.usuarios` |
| [ ] | `/usuarios` | Sin permiso → redirige o 403 |
| [ ] | `/roles` | Vista de roles accesible con `administrar.roles` |
| [ ] | `/admin/sistema` | Solo accesible con `role:admin` |
| [ ] | `/usuarios/asignar-clientes` | Vincular usuario a cliente funciona |

---

## 👥 Clientes y Empresas Asociadas

| Check | URI | Descripción |
|-------|-----|-------------|
| [ ] | `/clientes/create` | Formulario de nuevo cliente con `crear.cliente` |
| [ ] | `/clientes/{id}/edit` | Edición de cliente funciona |
| [ ] | `/empresas-asociadas` | Lista visible con `crear.empresas` |
| [ ] | `/client/empresas-asociadas` | Livewire `Client\EmpresasAsociadas\Index` carga en layout cliente |

---

## 📅 Eventos

| Check | URI | Layout | Descripción |
|-------|-----|--------|-------------|
| [ ] | `/eventos` | admin | Lista de eventos con `ver.eventos` |
| [ ] | `/eventos/nuevo` | admin | Formulario de creación |
| [ ] | `/eventos/{id}/edit` | admin | Edición de evento existente |
| [ ] | `/eventos/{id}/reporte` | admin | Preview del reporte PDF |
| [ ] | `/client/eventos` | cliente | Lista de eventos propios del cliente |
| [ ] | `/client/eventos/nuevo` | cliente | Formulario cliente (sin campos admin) |
| [ ] | `/client/eventos/{id}/edit` | cliente | Solo puede editar sus propios eventos |
| [ ] | `/client/eventos/{id}/reporte` | cliente | Preview reporte cliente |

---

## 🎟️ Tickets

| Check | URI | Layout | Descripción |
|-------|-----|--------|-------------|
| [ ] | `/tickets/nuevo` | admin | Componente Livewire `ManageTickets` carga |
| [ ] | `/client/tickets/nuevo` | cliente | Componente `ManageTicketsClient` carga |
| [ ] | `/tickets/nuevo` | cliente s/permiso | Sin `ver.tickets` → bloqueado |
| [ ] | — | — | Crear ticket desde formulario cliente funciona, sin alert() nativo |
| [ ] | — | — | Confirmación inline visible tras envío |

---

## 🚗 Rodados

| Check | URI | Descripción |
|-------|-----|-------------|
| [ ] | `/rodados` | Lista de vehículos principal |
| [ ] | `/rodados/calendario` | Calendario de turnos carga y muestra eventos |
| [ ] | `/rodados/admin-dashboard` | KPIs y gráficos de flota |
| [ ] | `/rodados/proveedores-talleres` | Lista de proveedores y talleres |
| [ ] | `/rodados/pagos-servicios` | Vista de pagos carga |
| [ ] | `/rodados/cobranzas` | Lista de cobranzas |

---

## 🚔 Patrullas y Recorridos

| Check | URI | Layout | Descripción |
|-------|-----|--------|-------------|
| [ ] | `/patrullas` | admin | Lista de patrullas con permisos |
| [ ] | `/patrullas/location` | admin | Mapa de ubicación carga |
| [ ] | `/client/patrullas` | cliente | Lista patrullas del cliente |
| [ ] | `/client/patrullas/mapa` | cliente | Mapa carga con marcadores |
| [ ] | `/client/recorridos` | cliente | Lista de recorridos definidos |
| [ ] | `/client/recorridos/historial` | cliente | Historial de recorridos |

---

## 📡 Seguimientos

| Check | URI | Layout | Descripción |
|-------|-----|--------|-------------|
| [ ] | `/seguimientos` | admin | Listado admin |
| [ ] | `/client/seguimientos` | cliente | Listado cliente |
| [ ] | `/seguimientos/nuevo` | admin | Formulario de nuevo seguimiento |

---

## 📋 Contratos

| Check | URI | Descripción |
|-------|-----|-------------|
| [ ] | `/contratos` | Lista de contratos |
| [ ] | `/contratos/create` | Formulario nuevo contrato |
| [ ] | `/contratos/{id}/edit-livewire` | Editor Livewire de contrato |

---

## 🎥 HikCentral / ANPR

| Check | URI | Descripción |
|-------|-----|-------------|
| [ ] | `/cameras` | Lista de cámaras carga (puede tener error de API si HikCentral no está disponible — documentar) |
| [ ] | `/cameras/stream/{code}` | Stream de cámara (depende de disponibilidad HikCentral) |
| [ ] | `/anpr/records` | Lista de registros ANPR |

---

## 🚁 Drones / Flytbase

| Check | URI | Descripción |
|-------|-----|-------------|
| [ ] | `/drones-flytbase` | Lista de drones |
| [ ] | `/docks-flytbase` | Lista de docks |
| [ ] | `/misiones-flytbase` | Lista de misiones |
| [ ] | `/flight-logs` | Tabla de flight logs (admin) |
| [ ] | `/client/flight-logs` | Tabla de flight logs (cliente) |
| [ ] | `/client/misiones` | Alertas/misiones cliente |
| [ ] | `/gallery` | Galería de fotos/videos (admin) |
| [ ] | `/client/gallery` | Galería (cliente) |

---

## 🔔 Notificaciones

| Check | URI | Descripción |
|-------|-----|-------------|
| [ ] | `/admin/notificaciones` | Panel admin de notificaciones |
| [ ] | — | Campana/contador de notificaciones visible en header |
| [ ] | — | Marcar como leída funciona sin recargar página |
| [ ] | — | Descartar notificación funciona |

---

## ⚙️ Configuración y Perfil

| Check | URI | Layout | Descripción |
|-------|-----|--------|-------------|
| [ ] | `/settings/user-profile` | admin | Formulario de perfil carga |
| [ ] | `/settings/password` | admin | Cambio de contraseña funciona |
| [ ] | `/client/settings/user-profile` | cliente | Perfil cliente carga |
| [ ] | `/activity-log` | admin | Log de actividad carga |
| [ ] | `/client/activity-log` | cliente | Log de actividad cliente |

---

## 🌐 Landing

| Check | URI | Descripción |
|-------|-----|-------------|
| [ ] | `/landing` | Página pública carga sin login |
| [ ] | `/landing/contact` | Formulario de contacto envía y muestra confirmación |

---

## 🔑 Verificaciones transversales (en todos los módulos)

- [ ] No hay errores en la **consola del navegador** (JS errors, 404 de assets)
- [ ] No hay **N+1 queries** visibles (tiempos de carga razonables)
- [ ] Los **toasts / flash messages** aparecen y desaparecen correctamente
- [ ] Los **modales** abren, cierran y no dejan el scroll bloqueado
- [ ] Las **tablas con paginación** cambian de página correctamente
- [ ] Los **filtros** de búsqueda/filtrado funcionan y se pueden limpiar
- [ ] Los **formularios** muestran errores de validación inline (no alert())
- [ ] Los **permisos** están correctamente configurados: sin permiso → 403 o redirect
