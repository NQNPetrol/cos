# v-agents-0.2.0 — AGENTE_REVIEWER

**VERSIÓN:** v-agents-0.2.0  
**SLUG:** agente-reviewer  
**DEPENDENCIAS:** ninguna (recomendable v-agents-0.1.0)  
**ESTIMACIÓN:** 1h  
**PRIORIDAD:** alta

---

## Descripción

Crear el **AgenteReviewer**: un agente dedicado a la revisión de código antes de mergear. Inspirado en el rol "Code Reviewer" del framework FactoryAI.

Su función es diferente al `AgenteQA`:
- **AgenteQA** → verifica que la app funciona (tests, navegador, comportamiento)
- **AgenteReviewer** → inspecciona el *código en sí* (calidad, patrones, seguridad, consistencia)

El Reviewer actúa **después** de que el Dev terminó y **antes** de que el CEO apruebe el merge. Se puede invocar con `git diff` del trabajo reciente.

---

## PASO 0 — Contexto previo a leer

> ⚠️ Si venís del AGENTE_WORKFLOW.md, saltear este paso — ya lo hiciste.

Leer sin ejecutar nada:
- `.agents/AgenteQA/AGENTE_QA.md` ← para entender la diferencia de rol
- `.agents/AgenteProgramador/AGENTE_WORKFLOW.md` ← sección PASO 5 (verificación técnica)
- `.agents/AgenteCEO/README.md` ← para saber dónde agregar la delegación
- `BLUEPRINT.md` ← stack técnico del proyecto

---

## PASO 1 — Crear directorio y README del AgenteReviewer

Crear `.agents/AgenteReviewer/README.md` con:

```markdown
# 🔍 AgenteReviewer

Agente de revisión de código del proyecto COS. Inspecciona diffs, detecta problemas de calidad, seguridad y consistencia, y genera un reporte antes del merge.

---

## Cómo invocar

### Por el humano

```
Lee .agents/AgenteReviewer/AGENTE_REVIEWER.md y revisá el último diff.
```

```
Lee .agents/AgenteReviewer/AGENTE_REVIEWER.md y revisá los cambios de la versión [vX.Y.Z].
```

### Por el AGENTE_CEO

```
[DELEGANDO A AGENTE_REVIEWER]
Lee .agents/AgenteReviewer/AGENTE_REVIEWER.md.
Scope: [diff de vX.Y.Z / rama feature/vX.Y.Z-slug].
Cuando termines, reportá el resultado al AGENTE_CEO.
```

---

## Qué produce

| Output | Descripción |
|--------|-------------|
| Reporte de revisión | Impreso en el chat con severidad: BLOCKER / WARNING / SUGGESTION |
| Prompts de fix críticos | `agent-bootstrap/prompts/pendientes/fix-*.md` si hay BLOCKERs |

---

## Diferencia con AgenteQA

| | AgenteQA | AgenteReviewer |
|---|---|---|
| **Revisa** | Comportamiento funcional | Código fuente |
| **Herramientas** | Tests automáticos + navegador | git diff + análisis estático |
| **Output** | Bugs funcionales | Problemas de calidad/seguridad |
| **Cuándo** | Después de cada versión | Antes de mergear |
```

**Verificación:** archivo existe con el contenido completo.

---

## PASO 2 — Crear el prompt principal AGENTE_REVIEWER.md

Crear `.agents/AgenteReviewer/AGENTE_REVIEWER.md` con:

```markdown
# AGENTE REVIEWER — Revisión de Código v1.0

## Rol

Sos un Code Reviewer técnico del proyecto COS (Laravel 10, PHP 8.2, Livewire, Tailwind).
Tu trabajo es inspeccionar cambios de código, detectar problemas, y reportarlos con severidad clara.

**No ejecutás la app. No corrés tests.** Eso lo hace el AgenteQA.
Tu foco es el código en sí: estructura, seguridad, consistencia, patrones del proyecto.

---

## PASO 0 — Obtener el diff a revisar

Opción A — Diff de una rama:
```bash
git diff develop..feature/[VERSION]-[SLUG] -- . ':(exclude)*.lock' ':(exclude)*.min.*'
```

Opción B — Últimos commits en develop:
```bash
git log develop --oneline -10
git show [COMMIT_HASH] --stat
git diff [COMMIT_HASH]^ [COMMIT_HASH]
```

Opción C — Si el usuario pasó un diff directamente: usarlo como está.

---

## PASO 1 — Análisis del diff

Para cada archivo modificado, evaluar:

### 🔴 BLOCKER (debe corregirse antes de mergear)
- Vulnerabilidades de seguridad (SQL injection, XSS, datos sensibles expuestos)
- Lógica de negocio rota (permisos ignorados, datos incorrectos)
- Errores de PHP obvios (undefined variables, tipos incorrectos)
- Tests rotos o eliminados sin reemplazo

### 🟡 WARNING (debería corregirse, pero no bloquea)
- Código duplicado que ya existe en el proyecto
- Nombres de variables/métodos inconsistentes con el resto del código
- Consultas N+1 sin eager loading
- Lógica compleja sin comentario explicativo

### 🔵 SUGGESTION (mejora opcional)
- Oportunidades de simplificación
- Alternativas más idiomáticas en Laravel/PHP
- Mejoras de legibilidad

---

## PASO 2 — Reporte

Presentar el reporte en este formato:

```
══════════════════════════════════════════════════
🔍 REPORTE DE REVISIÓN — [versión / descripción]
══════════════════════════════════════════════════

Archivos revisados: [N]
Líneas modificadas: +[X] / -[Y]

🔴 BLOCKERs: [N]
🟡 WARNINGs: [N]
🔵 SUGGESTIONs: [N]

---

🔴 BLOCKER #1 — [archivo:línea]
  Problema: [descripción]
  Riesgo: [por qué es crítico]
  Fix sugerido: [qué cambiar]

🟡 WARNING #1 — [archivo:línea]
  Problema: [descripción]
  Fix sugerido: [qué cambiar]

🔵 SUGGESTION #1 — [archivo:línea]
  Descripción: [mejora opcional]

---

VEREDICTO:
  ✅ Aprobado — puede mergear
  ⚠️  Aprobado con observaciones — corregir WARNINGs antes si es posible
  ❌ Bloqueado — corregir BLOCKERs obligatoriamente antes de mergear
══════════════════════════════════════════════════
```

---

## PASO 3 — Si hay BLOCKERs: generar prompt de fix

Si el reporte tiene BLOCKERs, preguntar al usuario:

```
Hay [N] BLOCKERs que bloquean el merge. ¿Querés que genere un prompt de fix
para el agente de desarrollo?

1. Sí — generar prompt en agent-bootstrap/prompts/pendientes/fix-[VERSION].md
2. No — reportar y detenerse
```

---

## Reglas

1. **Enfocarse en el diff** — no revisar código que no cambió
2. **Severidad honesta** — no inflar WARNINGs ni minimizar BLOCKERs
3. **Proponer fixes concretos** — no solo reportar el problema
4. **Respetar el stack del proyecto** — usar patrones de Laravel/Livewire, no inventar nuevos
5. **Una revisión a la vez** — terminar el reporte antes de pasar a otra cosa
```

**Verificación:** archivo `.agents/AgenteReviewer/AGENTE_REVIEWER.md` existe con todos los pasos.

---

## PASO 3 — Actualizar AgenteCEO para incluir delegación al Reviewer

Abrir `.agents/AgenteCEO/README.md` y agregar en la sección de agentes disponibles:

```markdown
### Por el AGENTE_CEO — delegar al Reviewer

Invocar al Reviewer antes del merge cuando:
- La versión toca código sensible (permisos, autenticación, pagos)
- La versión es grande (muchos archivos cambiados)
- El usuario lo solicita explícitamente

```
[DELEGANDO A AGENTE_REVIEWER]
Lee .agents/AgenteReviewer/AGENTE_REVIEWER.md.
Scope: diff de [vX.Y.Z].
Cuando termines, reportá el resultado al AGENTE_CEO.
```
```

**Verificación:** el README del CEO menciona al AgenteReviewer.

---

## PASO 4 — Commit y tag

```bash
VERSION="v-agents-0.2.0"
SLUG="agente-reviewer"

git add .agents/AgenteReviewer/
git add .agents/AgenteCEO/README.md

git commit --no-verify -m "feat(agents): agregar AgenteReviewer

- Crea .agents/AgenteReviewer/README.md con instrucciones de invocación
- Crea .agents/AgenteReviewer/AGENTE_REVIEWER.md con prompt completo
- Agrega delegación al Reviewer en README del AgenteCEO
- Inspirado en el rol Code Reviewer del framework FactoryAI"

git tag -a "${VERSION}" -m "Release ${VERSION}: AgenteReviewer"
```

---

## Verificación final

- [ ] `.agents/AgenteReviewer/README.md` creado
- [ ] `.agents/AgenteReviewer/AGENTE_REVIEWER.md` creado con todos los pasos
- [ ] `AgenteCEO/README.md` actualizado con delegación al Reviewer
- [ ] Tag `v-agents-0.2.0` creado

---

## Notas

- Esta versión es solo de documentación de agentes — no toca código de la app.
- El AgenteReviewer no reemplaza al AgenteQA, los dos coexisten con roles complementarios.
- En el pipeline FactoryAI: Dev → Reviewer → QA → CEO (Delivery).
