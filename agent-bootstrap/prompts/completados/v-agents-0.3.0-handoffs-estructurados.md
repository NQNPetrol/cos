# v-agents-0.3.0 — Handoffs estructurados entre agentes

**VERSIÓN:** v-agents-0.3.0  
**SLUG:** handoffs-estructurados  
**DEPENDENCIAS:** ninguna (recomendable v-agents-0.1.0 y v-agents-0.2.0)  
**ESTIMACIÓN:** 1h  
**PRIORIDAD:** media

---

## Descripción

Formalizar el traspaso de contexto entre agentes usando **archivos de handoff** en markdown. Inspirado en el concepto de "Agent Handoffs" del framework FactoryAI.

El problema actual: cuando un agente termina (por ejemplo el PM genera un prompt) y el Dev arranca, el Dev tiene que releer todo el prompt y el repo desde cero. No hay un resumen de "lo que el PM quiere que sepas antes de empezar".

Los handoffs resuelven esto: un archivo corto que el agente saliente deja para el entrante, con el contexto mínimo necesario para continuar sin repasar todo.

---

## PASO 0 — Contexto previo a leer

> ⚠️ Si venís del AGENTE_WORKFLOW.md, saltear este paso — ya lo hiciste.

Leer sin ejecutar nada:
- `.agents/AgentePM/AGENTE_PM.md` ← PASO 4 (dónde encaja el handoff de salida del PM)
- `.agents/AgenteProgramador/AGENTE_WORKFLOW.md` ← PASO 3 (dónde encaja el handoff de entrada del Dev)

---

## PASO 1 — Crear directorio y template de handoff

Crear `agent-bootstrap/handoffs/` si no existe.

Crear `agent-bootstrap/handoffs/HANDOFF.template.md`:

```markdown
# Handoff — [AGENTE_ORIGEN] → [AGENTE_DESTINO]

**Versión:** [vX.Y.Z]  
**Fecha:** [YYYY-MM-DD]  
**De:** [AgentePM / AgenteQA / AgenteReviewer / etc.]  
**Para:** [AgenteProgramador / AgenteCEO / etc.]

---

## Lo que hice

[1-3 bullets de qué hizo el agente saliente. Pasado, concreto.]

- Definí la versión vX.Y.Z con scope: [...]
- Generé el prompt en `agent-bootstrap/prompts/pendientes/[archivo]`
- Detecté estas dependencias: [...]

---

## Decisiones tomadas

[Decisiones que el agente entrante necesita respetar o conocer.]

- Se acordó con el usuario que [X] queda fuera del scope
- Se priorizó [Y] sobre [Z] porque [razón]

---

## Contexto clave

[Archivos, módulos, o patrones que el agente entrante debe leer primero.]

- `[archivo]` — [por qué es relevante]
- `[archivo]` — [por qué es relevante]

---

## Qué necesito que hagas

[Instrucción clara y directa para el agente entrante.]

Ejecutar la versión [vX.Y.Z] siguiendo el prompt en `agent-bootstrap/prompts/pendientes/[archivo]`.
Prestar especial atención a: [...]

---

## Notas y advertencias

- [edge case o riesgo que el agente Dev debe conocer]
- [dependencia técnica o comportamiento esperado]
```

**Verificación:** archivo template creado con todos los campos.

---

## PASO 2 — Agregar instrucción de handoff de salida al AGENTE_PM

Abrir `.agents/AgentePM/AGENTE_PM.md`.

En el **PASO 4** (Generar los archivos), agregar después del punto de guardar el prompt:

```markdown
### 4.1 — Generar handoff de salida (opcional pero recomendado)

Si la versión es compleja o requiere contexto especial para el Dev, generar un handoff:

Guardar en: `agent-bootstrap/handoffs/[VERSION]-pm-to-dev.md`

Usar la plantilla `agent-bootstrap/handoffs/HANDOFF.template.md`.
Completar con:
- Lo que se acordó en la sesión
- Decisiones de scope tomadas con el usuario
- Archivos clave para que el Dev lea primero
- Advertencias o edge cases detectados
```

**Verificación:** el AGENTE_PM.md menciona los handoffs en el PASO 4.

---

## PASO 3 — Agregar lectura de handoff al AGENTE_WORKFLOW

Abrir `.agents/AgenteProgramador/AGENTE_WORKFLOW.md`.

En el **PASO 3** (Leer el prompt y el contexto del repo), agregar al inicio:

```markdown
### 3.0 — Verificar si hay handoff del PM

```bash
ls agent-bootstrap/handoffs/ | grep "${VERSION}"
```

Si existe un handoff → leerlo antes del prompt. Es el contexto mínimo que el PM dejó.
Si no existe → continuar normalmente con el prompt.
```

**Verificación:** el AGENTE_WORKFLOW.md menciona la verificación de handoffs en PASO 3.

---

## PASO 4 — Agregar instrucción de handoff al AGENTE_REVIEWER

En `.agents/AgenteReviewer/AGENTE_REVIEWER.md` (creado en v-agents-0.2.0), al final del PASO 2 (Reporte), agregar:

```markdown
### Handoff al CEO tras revisión aprobada

Si el veredicto es ✅ o ⚠️, generar handoff opcional:

Guardar en: `agent-bootstrap/handoffs/[VERSION]-reviewer-to-ceo.md`

Incluir:
- Veredicto y resumen de hallazgos
- Si hay WARNINGs pendientes: listarlos para que el CEO los comunique al Dev
- Confirmación de que el merge puede proceder
```

**Verificación:** el AGENTE_REVIEWER.md menciona el handoff al CEO.

---

## PASO 5 — Commit y tag

```bash
VERSION="v-agents-0.3.0"
SLUG="handoffs-estructurados"

git add agent-bootstrap/handoffs/
git add .agents/AgentePM/AGENTE_PM.md
git add .agents/AgenteProgramador/AGENTE_WORKFLOW.md
git add .agents/AgenteReviewer/AGENTE_REVIEWER.md

git commit --no-verify -m "feat(agents): handoffs estructurados entre agentes

- Crea agent-bootstrap/handoffs/ con template HANDOFF.template.md
- AGENTE_PM actualizado: genera handoff de salida en PASO 4
- AGENTE_WORKFLOW actualizado: lee handoff del PM en PASO 3
- AGENTE_REVIEWER actualizado: genera handoff al CEO tras revisión"

git tag -a "${VERSION}" -m "Release ${VERSION}: Handoffs estructurados"
```

---

## Verificación final

- [ ] `agent-bootstrap/handoffs/HANDOFF.template.md` creado
- [ ] `AGENTE_PM.md` actualizado con paso de handoff de salida
- [ ] `AGENTE_WORKFLOW.md` actualizado con lectura de handoff
- [ ] `AGENTE_REVIEWER.md` actualizado con handoff al CEO
- [ ] Tag `v-agents-0.3.0` creado

---

## Notas

- Los handoffs son **opcionales** — el sistema funciona sin ellos.
- El valor es en sesiones largas o cuando hay múltiples agentes coordinándose.
- El template debe mantenerse corto: el objetivo es contexto mínimo, no documentación completa.
- Concepto original: "Agent Handoffs" del framework FactoryAI (FRAMWORK_TEST_README.md).
