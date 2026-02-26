# Cola de prompts — agent-bootstrap/prompts

Estructura que usa el **AGENTE_WORKFLOW** (y el agente PM al guardar prompts):

```
agent-bootstrap/prompts/
  pendientes/     ← prompts listos para tomar (el agente elige de aquí)
  en_proceso/     ← prompt que UN agente está ejecutando AHORA (movido al reclamar)
  completados/    ← prompts finalizados (historial; se mueve aquí al cerrar la tarea)
  bloqueados/     ← prompts con dependencia no cumplida (no se ejecutan hasta desbloquear)
```

**Regla de oro (workflow):** un archivo en `en_proceso/` + rama `feature/vX.Y.Z-*` en origin = otro agente lo tiene. No lo toques.

---

## Uso de cada carpeta

| Carpeta | Uso |
|--------|-----|
| **pendientes/** | Prompts listos para ejecutar. El agente hace `ls pendientes/`, verifica dependencias y que no esté en `en_proceso` ni en `completados`, y toma el de menor versión. |
| **en_proceso/** | El agente mueve aquí el prompt al reclamarlo (PASO 2: `mv pendientes/ARCHIVO en_proceso/ARCHIVO`). Ahí permanece hasta terminar la tarea; al cerrar, mueve el archivo a `completados/`. |
| **completados/** | Prompts ya ejecutados (commit + tag hechos). Historial. Al completar una versión, revisar si algún prompt en `bloqueados/` depende de ella para moverlo a `pendientes/`. |
| **bloqueados/** | Prompts cuya cabecera tiene `DEPENDENCIAS: vX.Y.Z` y esa versión aún no está en `completados/`. No se listan en `pendientes/`; cuando vX.Y.Z pase a completados, mover este archivo a `pendientes/`. |

---

## Regla de desbloqueo

- Un prompt en **bloqueados** indica `DEPENDENCIAS: vX.Y.Z`.
- Cuando el prompt **vX.Y.Z** se mueve a **completados/** (tarea ejecutada y cerrada), se desbloquea cualquier prompt que dependa de esa versión.
- Acción: mover ese prompt de `bloqueados/` a `pendientes/` para que el agente pueda tomarlo en el siguiente ciclo.

---

## Resumen de flujo

1. **Nuevo prompt con dependencia no cumplida** → guardar en `bloqueados/`.
2. **Prompt sin dependencias (o dependencia ya completada)** → guardar en `pendientes/`.
3. **Agente toma un prompt** → lo mueve de `pendientes/` a `en_proceso/`, crea rama y trabaja.
4. **Al completar la tarea** → mover el prompt de `en_proceso/` a `completados/`; revisar `bloqueados/` y mover a `pendientes/` los que pasen a tener dependencia satisfecha.
