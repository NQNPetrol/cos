# Flujo de trabajo con rama `develop` - Proyecto COS

## Estructura de ramas

- `main` → Producción (código estable)
- `develop` → Últimos cambios en desarrollo (testing / staging)
- `feature/*` → Ramas de desarrollo individuales por funcionalidad o corrección

## Pasos para colaborar con el flujo

### 1. Crear una nueva rama desde `develop`
```bash
git checkout develop
git pull origin develop --ff-only
git checkout -b feature/nueva-tarea
```

### 2. Trabajar localmente y hacer commits
```bash
git add .
git commit -m "Agrega funcionalidad X"
```

### 3. Rebasear con `develop` antes de subir
```bash
git fetch origin
git rebase origin/develop
```
> Si hay conflictos: resolverlos, luego `git add .` y `git rebase --continue`

### 4. Subir la rama rebaseada
```bash
git push origin feature/nueva-tarea --force
```

### 5. Hacer merge a `develop`
```bash
git checkout develop
git pull origin develop --ff-only
git merge feature/nueva-tarea --no-ff
git push origin develop
```

### 6. Para producción (merge a `main`)
```bash
git checkout main
git pull origin main --ff-only
git merge develop --no-ff
git push origin main

# Opcional: versionar
git tag v1.0.0
git push origin v1.0.0
```

### 7. Limpieza
```bash
git branch -d feature/nueva-tarea
git push origin --delete feature/nueva-tarea
```

## Reglas del equipo

- Nadie trabaja directo en `main`
- `develop` es el entorno de staging probado
- Cada colaborador trabaja en una rama `feature/*`
- Todo merge a `main` debe venir desde `develop` y estar aprobado