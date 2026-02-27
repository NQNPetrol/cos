# v-agents-0.1.0 — Activar AgenteQA y AgenteDesigner

**VERSIÓN:** v-agents-0.1.0  
**SLUG:** activar-agenteqa-y-designer  
**DEPENDENCIAS:** ninguna  
**ESTIMACIÓN:** 30 min  
**PRIORIDAD:** alta

---

## Descripción

El `AgenteQA` y el `AgenteDesigner` ya están documentados en `.agents/` con sus READMEs y prompts principais, pero todavía no están formalmente registrados en `ROADMAP.md` como versiones completadas ni tienen su activación verificada en el sistema de agentes.

Esta versión los **activa formalmente**: revisa que todos los archivos estén en buen estado, que el `AGENTE_WORKFLOW.md` los mencione en las reglas de coordinación, y que aparezcan como disponibles en el flujo de delegación del CEO.

---

## PASO 0 — Contexto previo a leer

> ⚠️ Si venís del AGENTE_WORKFLOW.md, saltear este paso — ya lo hiciste.

Leer sin ejecutar nada:
- `.agents/AgenteQA/AGENTE_QA.md`
- `.agents/AgenteQA/README.md`
- `.agents/AgenteDesigner/AGENTE_DESIGNER.md`
- `.agents/AgenteDesigner/README.md`
- `.agents/AgenteCEO/README.md`

---

## PASO 1 — Verificar integridad de los archivos existentes

Confirmar que estos archivos existen y están completos:

- `.agents/AgenteQA/AGENTE_QA.md` ← prompt principal del QA
- `.agents/AgenteQA/CHECKLIST_QA.md` ← checklist de verificación
- `.agents/AgenteQA/README.md` ← cómo invocar
- `.agents/AgenteDesigner/AGENTE_DESIGNER.md` ← prompt principal del Designer
- `.agents/AgenteDesigner/DESIGN_SYSTEM.md` ← referencia de componentes
- `.agents/AgenteDesigner/README.md` ← cómo invocar

Si alguno falta o está vacío → notificar y detener.

**Verificación:** todos los archivos listados existen y tienen contenido.

---

## PASO 2 — Verificar que el AgenteCEO los mencione

Abrir `.agents/AgenteCEO/README.md` y confirmar que:
- Existe una sección o mención a la delegación al `AGENTE_QA`
- Existe una sección o mención a la delegación al `AGENTE_DESIGNER`

Si falta alguno, agregar en el README del CEO la sección de delegación siguiendo el patrón de los otros agentes (ver `.agents/AgenteQA/README.md` sección "Por el AGENTE_CEO").

**Verificación:** el README del CEO menciona explícitamente a AgenteQA y AgenteDesigner con instrucciones de delegación.

---

## PASO 3 — Commit y tag

```bash
VERSION="v-agents-0.1.0"
SLUG="activar-agenteqa-y-designer"

git add .agents/

git commit --no-verify -m "feat(agents): activar AgenteQA y AgenteDesigner

- Verificada integridad de archivos de AgenteQA y AgenteDesigner
- AgenteCEO actualizado para incluir delegación a ambos agentes"

git tag -a "${VERSION}" -m "Release ${VERSION}: AgenteQA y AgenteDesigner activos"
```

---

## Verificación final

- [ ] Todos los archivos de AgenteQA y AgenteDesigner existen y tienen contenido
- [ ] El README del AgenteCEO menciona la delegación a ambos agentes
- [ ] Tag `v-agents-0.1.0` creado

---

## Notas

- Esta versión es solo de documentación — no se toca código de la app.
- Si los archivos ya están perfectos y el CEO ya delega al QA y Designer, el paso 2 no requiere cambios y el commit puede ser mínimo.
