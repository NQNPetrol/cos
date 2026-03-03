# 🧪 AgenteQA

Agente de control de calidad del proyecto COS. Ejecuta tests, verifica el navegador y genera prompts de fix para el agente dev.

---

## Cómo invocar

### Por el humano

```
Lee .agents/AgenteQA/AGENTE_QA.md y ejecutá el ciclo de QA.
```

```
Lee .agents/AgenteQA/AGENTE_QA.md y hacé QA del módulo tickets.
```

```
Lee .agents/AgenteQA/AGENTE_QA.md y hacé QA de la versión v0.5.0.
```

### Por el AGENTE_CEO

El CEO puede delegar al QA con:

```
[DELEGANDO A AGENTE_QA]
Lee .agents/AgenteQA/AGENTE_QA.md.
Scope: [versión recién completada / módulo / completo].
Cuando termines, reportá el resultado de vuelta al AGENTE_CEO.
```

---

## Qué produce

| Output | Ubicación |
|--------|-----------|
| Reporte de QA | Impreso en el chat / guardado si se pide |
| Prompts de bugs críticos y altos | `agent-bootstrap/prompts/pendientes/fix-*.md` |

---

## Archivos de referencia

| Archivo | Propósito |
|---------|-----------|
| `AGENTE_QA.md` | Prompt principal — leer para arrancar |
| `CHECKLIST_QA.md` | Checklist de verificación por módulo |
| `BLUEPRINT.md` (raíz) | Fuente de verdad de rutas, permisos y módulos |
