# BalearTrek · Backend (API)

Backend de **BalearTrek**, la plataforma de excursiones (treks) por las Islas
Baleares. Expone la **API REST**, gestiona la **capa de datos** (modelos,
migraciones, seeders y triggers) e incluye un **panel web de administración**
construido con Blade.

> Este repositorio forma parte del proyecto BalearTrek. Para levantar todo el
> entorno (backend + frontend + base de datos) con Docker, usa el repositorio
> de despliegue y su script `start-demo.sh`. Las instrucciones de abajo son para
> trabajar con el backend de forma aislada.

## Tecnologías

- Laravel 12 y PHP 8.2+
- Laravel Sanctum (autenticación por token / SPA)
- MySQL 8 (o SQLite para pruebas rápidas)
- Blade + Vite + Tailwind CSS (panel de administración)

## Funcionalidades

- API REST de excursiones, encuentros, lugares destacables, usuarios y
  comentarios.
- Registro, login y logout de usuarios con Sanctum.
- Inscripción y cancelación de usuarios en encuentros.
- Roles (`admin`, `guia`, `visitant`) con middleware de autorización.
- Panel de administración para gestionar excursiones, encuentros, lugares,
  municipios, usuarios y comentarios.
- Seeders con datos demo cargados desde JSON.

## Requisitos

- PHP 8.2+ con las extensiones `pdo_mysql`, `mbstring`, `exif`, `pcntl`,
  `bcmath` y `gd`
- Composer 2
- MySQL 8 (o SQLite)

## Instalación (standalone)

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

> [!WARNING]
> `migrate:fresh --seed` elimina todas las tablas y recarga los datos demo.
> Úsalo solo en desarrollo.

## Estructura de datos

Las migraciones definen, entre otras, las tablas: `users`, `roles`, `treks`,
`meetings`, `comments`, `images`, `places`, `place_types`, `place_trek`,
`municipalities`, `islands`, `zones` y el pivote `meeting_user`.

Los seeders leen los datos demo desde `database/seeders/data/*.json`. El orden
lo define `DatabaseSeeder`:

1. `RoleSeeder`
2. `IslandSeeder`
3. `ZoneSeeder`
4. `MunicipalitySeeder`
5. `UserSeeder` (admin + guías desde JSON)
6. `TrekSeeder`
7. `PlaceSeeder`
8. Factory de usuarios (100 visitantes)

## API REST

Todas las rutas cuelgan del prefijo `/api`.

| Método | Ruta | Descripción |
| --- | --- | --- |
| `POST` | `/api/register` | Registro de usuario |
| `POST` | `/api/login` | Inicio de sesión (devuelve token) |
| `POST` | `/api/logout` | Cierre de sesión *(auth)* |
| `GET` | `/api/treks` | Listado de excursiones |
| `GET` | `/api/treks/{trek}` | Detalle de una excursión |
| `POST` | `/api/treks` | Crear excursión *(admin)* |
| `GET` | `/api/user` | Datos del usuario autenticado *(auth)* |
| `PUT` | `/api/user` | Actualizar perfil *(auth)* |
| `DELETE` | `/api/user` | Desactivar cuenta *(auth)* |
| `PUT` | `/api/user/password` | Cambiar contraseña *(auth)* |
| `POST` | `/api/meetings/{meeting}/subscribe` | Inscribirse a un encuentro *(auth)* |
| `DELETE` | `/api/meetings/{meeting}/subscribe` | Cancelar inscripción *(auth)* |
| `GET` | `/api/users` | Listado de usuarios *(admin)* |
| `GET` | `/api/users/{user}` | Detalle de usuario *(admin)* |
| `PUT` | `/api/users/{user}` | Actualizar usuario *(admin)* |
| `DELETE` | `/api/users/{user}` | Eliminar usuario *(admin)* |

Consulta el listado completo y actualizado con:

```bash
php artisan route:list --path=api
```

## Usuarios demo

| Rol | Email | Contraseña |
| --- | --- | --- |
| Administrador | `admin@baleartrek.com` | `12345678` |
| Guía | `marbonas@baleartrek.com` | `12345678` |
| Visitante | email `@example.*` sembrado | `password` |

El administrador accede al panel web en `/login`. Hay más guías (con contraseñas
propias) y 100 visitantes generados automáticamente.

## Licencia

Distribuido bajo la licencia MIT. Consulta [LICENSE](LICENSE).
