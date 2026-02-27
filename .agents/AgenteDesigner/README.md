# 🎨 AgenteDesigner

Agente de diseño web del proyecto COS. Piensa la UI/UX antes de que el programador toque código, y recomienda mejoras de diseño en interfaces existentes.

---

## Cómo invocar

### Por el humano

```
Lee .agents/AgenteDesigner/AGENTE_DESIGNER.md y diseñá la UI de [descripción de pantalla o feature].
```

```
Lee .agents/AgenteDesigner/AGENTE_DESIGNER.md y revisá el diseño del módulo [nombre].
```

### Por el AGENTE_CEO

```
[DELEGANDO A AGENTE_DESIGNER]
Lee .agents/AgenteDesigner/AGENTE_DESIGNER.md.
Modo: [nueva pantalla / mejora existente].
Contexto: [descripción de lo que se va a construir o mejorar].
Cuando termines, reportá el documento de diseño al AGENTE_CEO.
```

### Por el AGENTE_PM

El PM puede invocar al Designer al definir versiones con componentes visuales nuevos:

```
[DELEGANDO A AGENTE_DESIGNER]
Lee .agents/AgenteDesigner/AGENTE_DESIGNER.md.
Modo: nueva pantalla.
Feature: [descripción de la versión que se va a implementar].
Cuando termines, el documento de diseño va a ser contexto adicional para el AGENTE_DEV.
```

---

## Qué produce

| Output | Descripción |
|--------|-------------|
| Documento de diseño (`DISEÑO_[VERSION].md`) | Wireframe, componentes, estados, decisiones de UX |
| Imágenes de mockup | Generadas con herramientas disponibles cuando la complejidad lo justifica |

El documento de diseño queda como contexto adicional dentro del prompt de la versión en `agent-bootstrap/prompts/pendientes/`.

---

## Archivos de referencia

| Archivo | Propósito |
|---------|-----------|
| `AGENTE_DESIGNER.md` | Prompt principal — leer para arrancar |
| `DESIGN_SYSTEM.md` | Referencia de componentes, clases Tailwind y Flux disponibles en el proyecto |
| `BLUEPRINT.md` (raíz) | Contexto técnico: módulos, rutas, stack |
