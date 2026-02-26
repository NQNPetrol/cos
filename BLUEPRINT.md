# Blueprint — Centro de Operaciones de Seguridad (CyH Sur)

> Documento de arquitectura técnica. Describe el stack, la estructura del sistema y los módulos del COS.

---

## 🎯 Visión técnica

COS es una aplicación web fullstack que centraliza la administración y las operaciones de CyH Sur SA: gestión de clientes y empresas asociadas, flota de rodados, patrullas, eventos, contratos, seguimientos, integración con drones (Flytbase) y cámaras (HikCentral), tickets, ingresos y nómina.

La arquitectura prioriza una única aplicación web servida en un servidor: dos perfiles de uso (admin y cliente) mediante rutas y permisos, sin separación física de frontends. El backend es monolítico Laravel; la interactividad se resuelve con Livewire para evitar duplicar lógica en una SPA.

---

## 🏗️ Stack tecnológico

| Capa | Tecnología | Notas |
|------|-----------|--------|
| Backend | PHP 8.2 + Laravel 12 | API web, lógica de negocio, colas |
| Auth / equipos | Laravel Jetstream 5.3 + Sanctum | Autenticación, equipos, API tokens |
| UI reactiva | Livewire 3, Volt, Flux | Componentes sin escribir JS dedicado |
| Base de datos | MySQL | Persistencia principal |
| Frontend build | Vite 6 | Bundling de CSS/JS |
| Estilos | Tailwind CSS 4, Alpine.js | UI y comportamiento en el cliente |
| Gráficos / mapas | Chart.js, Leaflet (+ heat) | Dashboards y mapas |
| PDF | Barryvdh DomPDF | Reportes y documentos |
| Permisos / auditoría | Spatie Permission, Spatie Activity Log | Roles y registro de actividad |
| Integraciones | AWS SDK, Guzzle | S3, servicios externos |
| Testing | Pest | Tests automatizados |
| Estilo de código | Laravel Pint | Formato consistente |
| CI/CD | GitHub Actions | Workflow en el repo |

---

## 📁 Estructura del proyecto

```
cos/
│
├── app/
│   ├── Http/Controllers/     # Controladores web (admin y client)
│   ├── Livewire/             # Componentes Livewire por módulo
│   │   ├── Admin/            # Roles, permisos
│   │   ├── Client/           # Perfil, empresas, usuarios cliente, activity log
│   │   ├── Contratos/        # CRUD contratos
│   │   ├── FlotasVehiculares/
│   │   ├── Patrullas/        # Listados admin y cliente
│   │   ├── Rodados/          # Calendario rodados
│   │   ├── Seguimientos/
│   │   ├── Tickets/
│   │   └── ...
│   ├── Models/               # Eloquent (Cliente, Rodado, Evento, Patrulla, etc.)
│   ├── Services/             # HikCentral, Gallery, KmzParser
│   ├── Mail/                 # Notificaciones por email
│   ├── Providers/
│   ├── Observers/            # PermissionObserver
│   └── Listeners/
│
├── config/                   # Configuración Laravel
├── database/                 # Migraciones, seeders
├── resources/
│   ├── views/                # Blade (layouts admin, client, livewire)
│   ├── css/                  # app.css, modern-ui.css
│   └── js/                   # app.js, dashboards, landing
├── routes/
│   └── web.php               # Rutas públicas, client/*, admin (auth)
├── docs/                     # Documentación y kit de agentes
└── tests/
```

---

## 🔄 Flujo de datos principal

- **Web (navegador):** el usuario accede a la app vía HTTP; las rutas distinguen prefijo `client/` (layout cliente) del resto (layout admin).
- **Laravel:** middleware `auth` + `verified`; permisos con Spatie (`can:ver.eventos`, etc.). Controladores y Livewire cargan modelos y devuelven vistas o respuestas Livewire.
- **Persistencia:** MySQL vía Eloquent; archivos/medios en disco o S3 según configuración.
- **Integraciones:** llamadas salientes a HikCentral (eventos, imágenes), Flytbase (drones, misiones), AWS (S3, webhooks entrantes en `S3WebhookController`).

```
Usuario → Navegador → Laravel (routes → middleware → Controller/Livewire)
                            → Models (MySQL)
                            → Services (HikCentral, Flytbase, S3)
                            → Vista Blade / respuesta Livewire → HTML/JS
```

---

## 🧩 Módulos principales

### Administración

| Módulo | Responsabilidad | Dependencias |
|--------|-----------------|--------------|
| Clientes | CRUD clientes, empresas asociadas, logo | MySQL, permisos |
| Rodados | Flota, turnos, pagos, cobranzas, proveedores, talleres, calendario, dashboards | MySQL, DomPDF, archivos |
| Filmación | Cámaras HikCentral, liveview, vinculación a dispositivos | HikCentral API |
| Tickets | Creación y gestión de tickets (admin y cliente) | MySQL, Mail |
| Ingresos / nómina | Cobranzas y pagos en módulo rodados | MySQL |
| Usuarios y roles | CRUD usuarios, asignación cliente–usuario, roles/permisos | Spatie Permission, Jetstream |
| Notificaciones | CRUD, leer/descartar, contador | MySQL |

### Operaciones

| Módulo | Responsabilidad | Dependencias |
|--------|-----------------|--------------|
| Eventos | CRUD eventos, reportes PDF, anulación, notas | MySQL, DomPDF |
| Personal | CRUD personal | MySQL |
| Contratos | CRUD contratos (Controller + Livewire Edit) | MySQL |
| Seguimientos | Alta y listado; vista cliente | MySQL |
| Patrullas | Listados, creación, mapa (Mobile Vehicle), dispositivos, supervisores, recorridos (CRUD, KML), checklist, calendario | MySQL, mapas |
| Drones / Flytbase | Drones, docks, misiones (KMZ), flight logs, liveview, galería, peticiones, sitios, pilotos, alertas | Flytbase API, S3 |
| HikCentral | Cámaras, streams, ANPR (registros, import, imágenes) | HikCentral API |

### Transversal

| Módulo | Responsabilidad | Dependencias |
|--------|-----------------|--------------|
| Dashboards | Admin, cliente, operaciones, patrullas, rodados | Chart.js, APIs internas, Leaflet |
| Landing | Página pública y formulario de contacto | MySQL (ContactLead) |
| Inventario | Dispositivos | MySQL |
| Objetivos / AIPEM | Objetivos y vistas AIPEM | MySQL |

---

## 🔑 Decisiones de arquitectura (ADRs)

### ADR-001: Laravel + Livewire como stack principal

**Estado:** Aceptado

**Contexto:** Necesidad de una aplicación web con alta interactividad (listados, filtros, formularios, dashboards) mantenida por un equipo pequeño.

**Decisión:** Laravel 12 con Livewire 3 (y Volt/Flux) para evitar un frontend SPA separado y mantener lógica y permisos en el servidor.

**Consecuencias:** Desarrollo ágil, menos contexto front/back; posible límite en UX muy dinámica sin tocar Alpine/JS.

### ADR-002: Dos layouts (admin vs cliente) por rutas

**Estado:** Aceptado

**Contexto:** Dos perfiles de usuario (interno CyH Sur vs clientes que consumen servicios) con permisos y menús distintos.

**Decisión:** Prefijo de rutas `client/` para el layout cliente; resto bajo layout admin. Mismo código base, permisos con Spatie.

**Consecuencias:** Un solo deploy; permisos y middleware definen el acceso por ruta.

### ADR-003: Integraciones externas (HikCentral, Flytbase, AWS)

**Estado:** Aceptado

**Contexto:** Requerimientos de video vigilancia (HikCentral), operación de drones (Flytbase) y almacenamiento de medios (S3).

**Decisión:** Servicios dedicados en `app/Services`, controladores que los consumen; configuración vía `.env`.

**Consecuencias:** Funcionalidad operativa; dependencia de disponibilidad y APIs de terceros.

---

## ⚡ Principios de diseño

1. **Una app, dos perfiles:** Misma aplicación para admin y cliente; la seguridad se basa en autenticación y permisos por ruta.
2. **Livewire para interactividad:** Formularios, listados y acciones sin construir una API REST separada para el front.
3. **MySQL como única fuente de verdad:** Datos relacionales en MySQL; medios en filesystem o S3.
4. **Servicios para terceros:** Lógica de HikCentral, Flytbase y S3 encapsulada en clases de servicio.

---

## 🚫 Limitaciones conocidas

- **Sin licencia explícita:** El proyecto no declara licencia de uso o distribución.
- **Versionado no formal:** No hay SemVer/CalVer definido; el avance se sigue por ramas y ROADMAP.
- **Dependencia de integraciones:** HikCentral, Flytbase y AWS son críticos para varias funcionalidades; caídas o cambios de API impactan el sistema.
- **Un solo servidor web:** La aplicación está pensada para correr en un servidor; colas (queue) deben estar activas para jobs asíncronos si se usan.

---

## 📚 Referencias

- [Laravel](https://laravel.com/docs)
- [Livewire](https://livewire.laravel.com/)
- [Laravel Jetstream](https://jetstream.laravel.com/)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)
- [HikCentral](https://www.hikvision.com/) (producto y APIs según documentación del fabricante)
- [Flytbase](https://flytbase.com/) (APIs y documentación del proveedor)
- [Tailwind CSS](https://tailwindcss.com/docs)
