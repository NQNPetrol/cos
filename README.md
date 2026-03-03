# Centro de Operaciones de Seguridad (CyH Sur) — COS

> Sistema web para el control de la administración de operaciones y facturación de CyH Sur SA. Opera, administra y factura la flota de vehículos alquilada a empresas que trabajan en Vaca Muerta.

[![Estado](https://img.shields.io/badge/estado-en%20producción-green)]()
[![Licencia](https://img.shields.io/badge/licencia-sin%20licencia-lightgrey)]()

---

## ¿Para quién es?

- **CyH Sur SA**: empresa cliente con una persona en administración y varias en operaciones.
- Uso interno: gestión de flota, patrullas, eventos, contratos, seguimientos, drones (Flytbase), cámaras (HikCentral), tickets, ingresos y nómina.

---

## 🚀 Instalación

```bash
git clone <repo-url>
cd cos
composer install
cp .env.example .env
php artisan key:generate
# Configurar .env (DB: MySQL, HikCentral, Flytbase, AWS, etc.)
php artisan migrate
npm install
npm run build
php artisan serve
```

### Requisitos previos

- PHP 8.2+
- Composer
- Node.js y npm
- MySQL
- Extensiones PHP: pdo_mysql, mbstring, xml, ctype, json, openssl, fileinfo

---

## 📖 Uso básico

- **Desarrollo:** `composer run dev` (servidor + cola + Vite en paralelo) o `php artisan serve` y `npm run dev`.
- **Producción:** desplegar en servidor web (Apache/Nginx + PHP-FPM), configurar cola (`php artisan queue:work`) y assets (`npm run build`).

---

## 🏗️ Arquitectura

Aplicación web fullstack: **Laravel 12** + **Livewire 3** (Volt, Flux) en el backend; **Vite**, **Tailwind**, **Alpine.js** y **Vue** en el frontend. Base de datos **MySQL**.

**Módulos principales:**

- **Administración:** clientes, empresas asociadas, rodados (flota), filmación (cámaras), tickets, ingresos, nómina.
- **Operaciones:** eventos, personal, contratos, seguimientos, drones (Flytbase), HikCentral, patrullas.

```
cos/
├── app/           # Modelos, controladores, Livewire, servicios
├── config/        # Configuración Laravel
├── database/      # Migraciones y seeders
├── resources/     # Vistas Blade, CSS, JS
├── routes/        # web.php (admin + client layout)
└── docs/          # Documentación y kit de agentes
```

Ver [BLUEPRINT.md](./BLUEPRINT.md) para documentación técnica completa.

---

## 🛣️ Roadmap

Ver [ROADMAP.md](./ROADMAP.md) para el plan de versiones y funcionalidades.

---

## 🧪 Tests

- **Ejecutar en local:** `./vendor/bin/pest` o `php artisan test`
- **Framework:** Pest (PHPUnit bajo el capó). En CI se usa MySQL; en local se recomienda MySQL (copiar `.env.testing.example` a `.env.testing` y configurar una base `cos_test`).
- **Cobertura (v0.1.0 + v0.2.0):** autenticación y autorización (Spatie); tests unitarios y feature para **Eventos**, **Rodados** y **Tickets** (listados, creación, edición, permisos). Factories para Evento, Rodado, Ticket y modelos relacionados.

Más detalle en [docs/testing.md](./docs/testing.md).

---

## 🤝 Equipo y contribución

- Equipo de dos personas: desarrollo (programación y estado) e ingeniería (clientes y requerimientos).
- El ingeniero define los prompts de las próximas versiones con Claude y los sube al repo.
- Flujo de trabajo: ramas por feature, integración vía `develop` → `main`.

---

## 📄 Licencia

Sin licencia explícita por ahora.
