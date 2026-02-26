# Reglas para todos los agentes

> **Aplican a cualquier agente que ejecute código y/o haga commits en este repositorio.**

---

## Tests y QA obligatorios antes de commit

**NUNCA** hacer commit (ni push) sin haber cumplido **ambos** puntos:

### 1. Tests automáticos

- Ejecutar **todos** los tests necesarios del proyecto (suite unitaria, de integración, e2e, según corresponda).
- Ejemplo Laravel/PHP: `php artisan test` o `./vendor/bin/pest`.
- Si algún test falla → **corregir antes de commitear**. No commitear con tests rotos.

### 2. QA manual en el navegador

- **Abrir el proyecto en el navegador** (entorno local o el que corresponda).
- **Probar todas las vistas, botones y funcionalidades** afectadas por los cambios (y las relacionadas si aplica).
- Actuar como **QA**: tocar todo lo relevante para detectar errores (pantallas en blanco, botones que no responden, formularios que no envían, rutas rotas, etc.).
- Si aparece algún error o comportamiento incorrecto → **corregir antes de commitear**.

Solo después de que **tests pasen** y **la verificación manual en navegador sea correcta**, se puede proceder al commit y al Pull Request.
