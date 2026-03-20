# Changelog — Centro de Operaciones de Seguridad (CyH Sur)

> Todos los cambios notables de este proyecto se documentan aquí.  
> Formato basado en [Keep a Changelog](https://keepachangelog.com/es/1.0.0/).

---

## [Unreleased]

### En progreso
- Ajustes en vistas y aspecto de recorridos (mapa, KPIs, gráficos).
- Gestión de rodados: migraciones, modelos, controladores y vistas adicionales.
- Ajustes de estilos en dashboard.
- Mejora de interfaz de flight logs (previa al envío del drone).
- Peticiones de misiones cliente: soporte para archivos .kmz.
- Mejoras de layout administrativo en rodados.

---

## [v0.6.2] — 2026-03-20

### Fixed
- **Upload rodados:** documentación de `client_max_body_size` para nginx (error 413).
- **PostTooLargeException:** manejo amigable con redirect y mensaje flash en lugar de pantalla 500.

### Changed
- **Estados de turnos:** migración de `enum('pendiente','completado')` a `string(30)` con estados: programado, asistido, cancelado, perdido.
- **Estado visual calculado:** accessor `estado_visual` que calcula dinámicamente próximo y asistido a confirmar según la fecha del turno.
- **Columna Estado en servicios:** badges color-coded con interacción para confirmar asistencia o marcar como perdido.

### Added
- Ruta y método `confirmarEstado` en `TurnoRodadoController` para confirmar/rechazar asistencia.
- Dropdown inline en badge "A confirmar" con opciones de confirmación.
- Documentación nginx en `docs/nginx-config.md`.

---

## [v0.3.0] — 2025-02-26

### Fixed
- **Recorridos KML:** parseo de archivos KML que reportaban 0 waypoints. Soporte para namespace KML 2.2 y XPath con `local-name()` para LineString/Point.
- Mapa de previsualización y mapa en detalle muestran la ruta cuando el KML tiene coordenadas válidas.

### Added
- Recorridos: soporte para archivos .kmz (ZIP con .kml interno).
- Validación de extensión .kml/.kmz y mensaje claro cuando no se pueden extraer waypoints.
- Velocidad máxima permitida persistida y mostrada en listado y detalle de recorridos.

### Changed
- `Recorrido::parseKmlFile()` refactorizado: `parseKmlContent()` para XML en string, `extractKmlFromKmz()` para KMZ, `extractWaypointsFromKml()` y `parseCoordinatePair()` para extracción robusta de coordenadas.

---

## Versión base (en producción) — 2025

> Estado: versión base desplegada, en testing y ajuste de features existentes.

### Added
- Workflow de CI/CD (GitHub Actions) y formato de código con Laravel Pint.
- Supervisores y recorridos: tablas, vistas, mapa, KPIs y gráfico de barras.
- Layout y vistas de rodados: pagos, talleres, proveedores, recordatorios mensuales (cron/schedule).
- Dashboard operacional (admin y cliente) y optimización de mapa con cámaras y clusters.
- Mejoras en dashboards de operaciones y eventos.
- Calendario de turnos rodados y galería (layout principal).
- Vistas de configuración y perfil optimizadas.
- Tema gris oscuro en vistas.
- PDF, vista de roles y patrullas (store).
- Landing comercial mejorada; clientes e imagen de patrullas.
- Redirección de dashboard clientes corregida.
- Configuración de Vite y estilos (modern-ui.css y dependencias).

### Changed
- Aspecto de últimas vistas de recorridos.
- Layout administrativo de rodados.
- Estilos generales aplicados en varias secciones.

### Fixed
- Bug de JS en dashboards de operaciones.
- Bug de Vite y estilos CSS.
- Errores en la sección de turnos rodados.

---

## Tipos de cambios

- **Added:** nuevas funcionalidades.
- **Changed:** cambios en funcionalidad existente.
- **Deprecated:** funcionalidades que se eliminarán pronto.
- **Removed:** funcionalidades eliminadas.
- **Fixed:** corrección de bugs.
- **Security:** corrección de vulnerabilidades.
