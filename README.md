# Camposanto La Paz

Aplicación web de acceso (login) para el sistema administrativo del **Camposanto La Paz**, construida con Laravel, Livewire (Volt) y Tailwind CSS, con PostgreSQL como base de datos, totalmente dockerizada.

## Stack

- **Backend:** PHP 8.4, Laravel 13, Livewire 3 / Volt
- **Frontend:** Tailwind CSS, Vite
- **Base de datos:** PostgreSQL 16
- **Autenticación:** Laravel Breeze (stack Livewire) — login, registro, recuperación de contraseña, verificación de correo, límite de intentos (rate limiting)
- **Infraestructura:** Docker + Docker Compose (PHP-FPM, Nginx, PostgreSQL, Node para assets)
- **Pruebas (QA):** PHPUnit (pruebas de característica sobre autenticación y perfil) + Playwright (pruebas end-to-end del flujo de login en un navegador real)

## Requisitos

- Docker Desktop (con Docker Compose)

No es necesario tener PHP, Composer, Node ni PostgreSQL instalados localmente: todo corre dentro de contenedores.

## Puesta en marcha

1. Copia el archivo de entorno de ejemplo (ya incluido como `.env` para desarrollo local; si necesitas regenerarlo):

   ```bash
   cp .env.example .env
   ```

2. Construye las imágenes y levanta los contenedores:

   ```bash
   docker compose up -d --build
   ```

3. Compila los assets de Tailwind/Vite (una sola vez, o cada vez que cambien los estilos):

   ```bash
   docker compose --profile assets run --rm node
   ```

   Para desarrollo con recarga en caliente, en su lugar puedes ejecutar:

   ```bash
   docker compose run --rm --service-ports node sh -c "npm install && npm run dev -- --host 0.0.0.0"
   ```

4. Genera la clave de la aplicación (si el `.env` no trae ya `APP_KEY`) y ejecuta las migraciones contra PostgreSQL:

   ```bash
   docker compose exec app php artisan key:generate
   docker compose exec app php artisan migrate
   ```

5. (Opcional) Crea un usuario de prueba para iniciar sesión:

   ```bash
   docker compose exec app php artisan db:seed
   ```

   Esto crea el usuario `admin@camposantolapaz.test` con contraseña `password123`.

6. Abre la aplicación en [http://localhost:8080](http://localhost:8080).

## Servicios de Docker Compose

| Servicio | Descripción                              | Puerto host |
|----------|-------------------------------------------|-------------|
| `nginx`  | Servidor web, sirve la aplicación         | `8080`      |
| `app`    | PHP-FPM (Laravel)                         | interno `9000` |
| `db`     | PostgreSQL 16                             | `5432`      |
| `node`   | Compila los assets de Tailwind/Vite (perfil `assets`, no se inicia con `up`) | — |
| `e2e`    | Corre las pruebas end-to-end con Playwright (perfil `e2e`, no se inicia con `up`) | — |

## Pruebas de QA

La suite de pruebas cubre el flujo de login (pantalla de login, autenticación válida/ inválida, usuario inexistente, validación de campos requeridos y formato de correo, límite de intentos fallidos, cierre de sesión) además de registro, recuperación de contraseña y gestión de perfil.

Ejecutar toda la suite dentro del contenedor de la aplicación:

```bash
docker compose exec app php artisan test
```

Las pruebas usan SQLite en memoria (definido en `phpunit.xml`) para que corran rápido y de forma aislada, sin afectar la base de datos de PostgreSQL de desarrollo.

### Pruebas end-to-end (Playwright)

Simulan a un usuario real interactuando con la aplicación en un navegador (Chromium): cargar el login, ingresar credenciales inválidas y ver el error, dejar campos vacíos, iniciar sesión correctamente y llegar al panel, y cerrar sesión.

Requieren que la app esté levantada (`docker compose up -d`) y con un usuario de prueba sembrado (`docker compose exec app php artisan db:seed`). Luego:

```bash
docker compose --profile e2e run --rm e2e
```

Esto instala las dependencias de Node y corre Playwright contra `http://nginx` (dentro de la red de Docker), usando la imagen oficial `mcr.microsoft.com/playwright`, que ya trae los navegadores instalados. El reporte HTML queda en `playwright-report/` dentro del proyecto.

## Estructura relevante

```
docker/
  php/Dockerfile          # Imagen PHP-FPM de la aplicación
  nginx/default.conf      # Configuración de Nginx
  postgres/init.sql       # Inicialización de base de datos auxiliar
docker-compose.yml
app/Livewire/Forms/LoginForm.php        # Lógica y validación del login
resources/views/livewire/pages/auth/    # Vistas de autenticación (Volt)
tests/Feature/Auth/                     # Pruebas de QA de autenticación (PHPUnit)
e2e/                                     # Pruebas end-to-end (Playwright)
playwright.config.js
```

## Variables de entorno principales

Ver `.env.example`. Para producción, cambia `APP_ENV`, `APP_DEBUG=false`, `DB_PASSWORD` y genera una nueva `APP_KEY`.
