# Configuración Nginx — COS

## `client_max_body_size`

Por defecto Nginx permite un máximo de 1 MB en el body del request. Laravel valida hasta 10 MB, pero Nginx corta antes devolviendo un **413 Request Entity Too Large**.

### Configurar en producción

Editar el archivo del sitio (ejemplo: `/etc/nginx/sites-available/cos.cyhsur.com`) y agregar dentro del bloque `server {}`:

```nginx
client_max_body_size 20M;
```

> Se usa 20M (no 10M) para dejar margen respecto al límite de Laravel y evitar que la validación del servidor web interfiera con la del framework.

### Aplicar cambios

```bash
sudo nginx -t && sudo systemctl reload nginx
```

- `nginx -t` valida la configuración sin aplicarla.
- `systemctl reload nginx` aplica los cambios sin cortar conexiones activas.

### Verificación

Tras recargar, subir un archivo de ~12 MB debería llegar a Laravel (que lo rechazará con un mensaje amigable) en vez de mostrar un 413 de Nginx.
