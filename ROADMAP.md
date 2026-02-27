# Roadmap — Centro de Operaciones de Seguridad (CyH Sur)

> Estado actual: **versión base en producción** — en testing y ajuste de features existentes.

---

## ✅ Completado (versión base)

### Módulo Administración

- [x] Clientes (alta, edición, logo, permisos)
- [x] Empresas asociadas y vinculación a clientes
- [x] Rodados: CRUD, turnos (service/mecánicos), cambios de equipo, kilometraje, pagos de servicios
- [x] Rodados: proveedores, talleres, cobranzas, alertas admin, documentación de turnos
- [x] Rodados: calendario y dashboard (KPIs, ingresos/egresos, flota, turnos por vehículo)
- [x] Filmación: integración HikCentral (cámaras, liveview, vinculación a dispositivos)
- [x] Tickets (admin y cliente)
- [x] Usuarios, roles y permisos (Spatie); asignación usuario–cliente
- [x] Notificaciones (CRUD, leer/descartar)

### Módulo Operaciones

- [x] Eventos (CRUD admin y cliente, anulación, notas, reportes y PDF)
- [x] Personal (CRUD)
- [x] Contratos (CRUD, edición Livewire)
- [x] Seguimientos (listado, alta; vista cliente)
- [x] Patrullas: listado, creación, ubicación en mapa (Mobile Vehicle), dispositivos por patrulla
- [x] Patrullas: supervisores, recorridos (CRUD, historial, import KML), checklist, calendario cliente
- [x] Dashboard patrullas (recorridos, tendencia, indicadores)
- [x] Drones/Flytbase: drones, docks, misiones (CRUD, KMZ), flight logs, liveview, galería
- [x] Flytbase: planificación de misiones (cliente), peticiones de misiones, sitios, pilotos, alertas
- [x] HikCentral: ANPR (registros, import 24h, estadísticas, imagen de evento)

### Transversal

- [x] Landing pública y formulario de contacto
- [x] Dashboards: admin (eventos), cliente (eventos, PDF), operaciones (admin y cliente)
- [x] Perfil, configuración, activity log (admin y cliente)
- [x] Inventario de dispositivos
- [x] Objetivos y objetivos AIPEM
- [x] CI/CD (workflow en repo), Laravel Pint

---

## 🔄 En progreso / ajuste

> Trabajo actual reflejado en ramas (develop y features). Sin fecha fija de release.

- [ ] **Recorridos:** mejoras de aspecto en vistas, mapa, KPIs y gráficos *(rama feature/recorridos)*
- [ ] **Gestión de rodados:** migraciones, modelos, controladores y vistas adicionales *(rama feature/gestion)*
- [ ] **Dashboard:** ajustes de estilos *(rama update/dashboard)*
- [ ] **Flight logs:** mejora de interfaz previa al envío del drone *(rama update/flightlogs)*
- [ ] **Misiones Flytbase:** peticiones cliente para aceptar archivos .kmz *(rama update/misionesflyt)*
- [ ] **Turnos rodados:** corrección de errores en la sección *(rama update/ui)*
- [ ] **Rodados:** mejoras de layout administrativo *(rama upgrade/rodados)*

---

## 📋 Planeado

- [x] **v0.1.0 — Base de tests + seguridad** *(completado)*  
  Configuración Pest/CI (MySQL en CI), tests de autenticación y autorización (permisos admin/cliente). Prompt en `agent-bootstrap/prompts/completados/v0.1.0-base-tests-seguridad.md`. Tag `v0.1.0`.
- [ ] **v0.2.0 — Tests funcionales de módulos críticos** *(pendiente)*  
  Prompt: `agent-bootstrap/prompts/pendientes/v0.2.0-tests-funcionales-modulos.md`. Unit + feature para Eventos, Rodados y Tickets; factories; CI; documentación. Depende de v0.1.0 (ya completado).
- [x] **v0.3.0 — Corrección y mejora carga KML en Recorridos** *(completado)*  
  Prompt en `agent-bootstrap/prompts/completados/v0.3.0-recorridos-kml-fix.md`. Tag `v0.3.0`. Parseo KML robusto (namespace, LineString/Point), mapa preview y detalle, velocidad máxima, mensajes de error y validación .kml/.kmz.
- [ ] **v0.4.0 — Visibilidad de tickets para todos los roles cliente** *(pendiente)*  
  Que cliente, clientadmin y clientsupervisor vean los tickets del/los cliente(s) asignados. Prompt: `agent-bootstrap/prompts/pendientes/v0.4.0-tickets-visibilidad-roles-cliente.md`. Sin dependencias.
- [ ] **v0.5.0 — UX creación de tickets** *(pendiente)*  
  Menos clicks, formulario intuitivo y feedback claro al crear ticket (vista cliente). Prompt: `agent-bootstrap/prompts/pendientes/v0.5.0-tickets-ux-creacion.md`. Recomendable v0.4.0.
- [ ] **v0.6.0 — Mesa de ayuda / chat asistente** *(pendiente)*  
  Mesa de ayuda accesible desde cliente (y opcionalmente admin) con chat que guíe: crear ticket, crear evento, documentación patrullas. Respuestas estáticas o por palabras clave. Prompt: `agent-bootstrap/prompts/pendientes/v0.6.0-mesa-ayuda-chat-asistente.md`. Recomendable v0.4.0 y v0.5.0.

Flujo de prompts: ver `agent-bootstrap/prompts/README.md` (pendientes / en_proceso / completados / bloqueados).

---

## 🔮 Backlog / Ideas

- Próximas funcionalidades y prioridades según requerimientos del cliente (CyH Sur) y prompts en el repo.

---

## 📌 Notas

- No hay esquema de versionado fijo (SemVer/CalVer) definido.
- Flujo de desarrollo: ramas por feature → integración en `develop` → `main`.
- Referencia de estado y scope: ramas locales/remotas y este ROADMAP.
