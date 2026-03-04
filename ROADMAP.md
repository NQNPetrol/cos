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
- [x] **v0.1.0 — Base de tests + seguridad** *(completado)*  
  Configuración Pest/CI (MySQL en CI), tests de autenticación y autorización (permisos admin/cliente). Prompt en `agent-bootstrap/prompts/completados/v0.1.0-base-tests-seguridad.md`. Tag `v0.1.0`.
- [x] **v0.3.0 — Corrección y mejora carga KML en Recorridos** *(completado)*  
  Prompt en `agent-bootstrap/prompts/completados/v0.3.0-recorridos-kml-fix.md`. Tag `v0.3.0`. Parseo KML robusto (namespace, LineString/Point), mapa preview y detalle, velocidad máxima, mensajes de error y validación .kml/.kmz.
- [x] **v0.4.0 — Visibilidad de tickets para todos los roles cliente** *(completado)*  
  Que cliente, clientadmin y clientsupervisor vean los tickets del/los cliente(s) asignados. Prompt: `agent-bootstrap/prompts/pendientes/v0.4.0-tickets-visibilidad-roles-cliente.md`. Sin dependencias.
- [x] **v0.5.0 — UX creación de tickets** *(completado)*  
  Menos clicks, formulario intuitivo y feedback claro al crear ticket (vista cliente). Prompt: `agent-bootstrap/prompts/pendientes/v0.5.0-tickets-ux-creacion.md`. Recomendable v0.4.0.
- [x] **v0.2.0 — Tests funcionales de módulos críticos** *(completado)*  
  Prompt: `agent-bootstrap/prompts/pendientes/v0.2.0-tests-funcionales-modulos.md`. Unit + feature para Eventos, Rodados y Tickets; factories; CI; documentación. Depende de v0.1.0 (ya completado).
- [x] **v0.6.0 — Mesa de ayuda / chat asistente** *(completado)*  
  Mesa de ayuda con chat tipo bot que guía al usuario: crear ticket, crear evento, documentación patrullas, ver recorridos, dashboard. Respuestas predefinidas por temas y detección por palabras clave.  
  Prompt: `agent-bootstrap/prompts/completados/v0.6.0-mesa-ayuda-chat-asistente.md`. Tag `v0.6.0`.

---

##  Planeado

> Cola de prompts listos. Ver `agent-bootstrap/prompts/pendientes/`.

- [ ] **v0.6.1 — Envío de email desde notificaciones admin** *(pendiente)*  
  Checkbox "Enviar por email" al crear notificación + botón "Enviar email" en listado admin. Destinatarios según tipo: global→todos, user→seleccionado, client→usuarios del cliente. Email simple (asunto=título, cuerpo=mensaje).  
  Prompt: `agent-bootstrap/prompts/pendientes/v0.6.1-notificaciones-envio-email.md`.

- [ ] **v0.7.0 — Rediseño vistas de autenticación + reCAPTCHA v2** *(pendiente)*  
  Unificar y modernizar login, register, forgot-password y reset-password con el sistema dark de la app. Logo CyH Sur SA correcto (`cyh-white.png`). reCAPTCHA v2 en login. Responsive mobile-first.  
  Prompt: `agent-bootstrap/prompts/pendientes/v0.7.0-auth-redesign-recaptcha.md`.  
  Diseño: `agent-bootstrap/prompts/pendientes/DISEÑO_v0.7.0-auth-recaptcha.md`.

Flujo de prompts: ver `agent-bootstrap/prompts/README.md` (pendientes / en_proceso / completados / bloqueados).

### 🤖 Sistema de Agentes

- [x] **v-agents-0.1.0 — Activar AgenteQA y AgenteDesigner** *(completado)*  
  Verificada integridad de archivos existentes, ambos agentes registrados formalmente en el sistema. CEO actualizado con delegación explícita a ambos.  
  Prompt: `agent-bootstrap/prompts/completados/v-agents-0.1.0-activar-agenteqa-y-designer.md`. Tag `v-agents-0.1.0`.

- [x] **v-agents-0.2.0 — AGENTE_REVIEWER** *(completado)*  
  Agente de revisión de código antes del merge. Inspecciona diffs, detecta BLOCKERs/WARNINGs/SUGGESTIONs, y genera prompts de fix si hay bloqueantes. Complementa al AgenteQA con revisión estática de código.  
  Prompt: `agent-bootstrap/prompts/completados/v-agents-0.2.0-agente-reviewer.md`.

- [x] **v-agents-0.3.0 — Handoffs estructurados** *(completado)*  
  Traspaso de contexto entre agentes con archivos de handoff en `agent-bootstrap/handoffs/`. El PM deja un handoff para el Dev; el Reviewer lo deja para el CEO. Mejora la continuidad en sesiones largas o multi-agente.  
  Prompt: `agent-bootstrap/prompts/completados/v-agents-0.3.0-handoffs-estructurados.md`.

---

## 🔮 Backlog / Ideas

> Ideas priorizadas sin prompt generado aún. Al definir cada una con el PM, se creará el prompt y se moverá a `pendientes/`.

- [ ] **v0.8.0 — Dashboard de Operaciones: migración a Leaflet** *(prioridad 1 — sin prompt)*  
  Reemplazar Google Maps por Leaflet.js en el dashboard de operaciones. Respetar marcadores con colores e iconos distintivos, mejorar el comportamiento de agrupamiento (clustering) al quitar zoom, mantener los filtros estilo pill y la interacción mapa ↔ panel de eventos lateral. Modernizar y optimizar sin sobrecargar la vista.

- [ ] **v0.9.0 — Auditoría y corrección responsive de vistas cliente** *(prioridad 2 — sin prompt)*  
  Verificar que todas las vistas del proyecto (principalmente vistas cliente) sean responsive. Adaptar las que no lo sean. Incluir tests con AgenteQA para asegurar la calidad del resultado en múltiples tamaños de pantalla.

---

## 📌 Notas

- No hay esquema de versionado fijo (SemVer/CalVer) definido.
- Flujo de desarrollo: ramas por feature → integración en `develop` → `main`.
- Referencia de estado y scope: ramas locales/remotas y este ROADMAP.
