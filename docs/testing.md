# Tests — COS

## Cómo ejecutar

```bash
./vendor/bin/pest
# o
php artisan test
```

## Stack de testing

- **Pest** sobre PHPUnit
- **Base de datos:** SQLite en memoria (`:memory:`) — ver `phpunit.xml`
- **RefreshDatabase** en tests Feature para limpiar datos entre tests

## Cobertura (v0.1.0)

- **Autenticación:** pantalla de login, login con credenciales válidas/inválidas, logout (`tests/Feature/Auth/AuthenticationTest.php`)
- **Autorización:** rutas protegidas con Spatie Permission; usuario con permiso accede (200), usuario sin permiso recibe 403. Casos: listado eventos admin (`ver.eventos`), listado eventos cliente (`ver.eventos-cliente`) — `tests/Feature/Auth/AuthorizationEventosTest.php`

## CI

El workflow `.github/workflows/tests.yml` ejecuta migraciones y luego `./vendor/bin/pest`. El resultado del job depende del resultado de los tests (no hay `continue-on-error`).

## Base de datos en tests: MySQL recomendado

La suite de tests está configurada para usar **MySQL** en CI (GitHub Actions con servicio MySQL). Las migraciones del proyecto usan sintaxis MySQL (p. ej. `ENUM`, `MODIFY`), por lo que para ejecutar los tests en local se recomienda usar también MySQL:

1. Crear la base de datos en MySQL, por ejemplo:
   - **Consola:** `mysql -u root -e "CREATE DATABASE IF NOT EXISTS cos_test;"`
   - **WAMP:** abrir phpMyAdmin y crear una base de datos llamada `cos_test`.
2. Copiar `.env.testing.example` a `.env.testing` y ajustar `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` si hace falta (por defecto: `cos_test`, `root`, sin contraseña).
3. Ejecutar `./vendor/bin/pest` o `php artisan test` (Laravel cargará `.env.testing` cuando `APP_ENV=testing`).

Si no existe `.env.testing`, se usan las variables de `.env`; si ahí tienes SQLite, las migraciones pueden fallar.

## Cobertura (v0.2.0)

- **Factories:** Evento, Rodado, Ticket, Cliente, Categoria, Personal (`database/factories/`).
- **Unit:** Evento (atributos, relaciones), Rodado (marca/tipo, display_name), Ticket (estado, categoría) — `tests/Unit/`.
- **Feature Eventos (admin):** ver listado (ver.eventos), formulario creación (crear.eventos), store con datos válidos, editar/update (editar.eventos), sin permiso 403 — `tests/Feature/Eventos/EventosAdminTest.php`.
- **Feature Rodados:** acceso listado, creación con datos válidos — `tests/Feature/Rodados/RodadosAdminTest.php`.
- **Feature Tickets (admin):** acceso vista tickets (ver.tickets), sin permiso 403 — `tests/Feature/Tickets/TicketsAdminTest.php`.

Módulos cubiertos en esta versión: Eventos, Rodados, Tickets.
