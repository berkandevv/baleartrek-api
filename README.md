# 📌 BalearTrek API
**Base de datos · API REST · Seeders · Triggers · Autenticación**

BalearTrek es una plataforma dedicada a la gestión de **excursiones (treks), encuentros (meetings), participantes, lugares remarcables y comentarios**. Este repositorio agrupa tanto la **capa de datos** como la **API REST** del proyecto.

---

## 📌 1. Configuración necesaria (JSON de seeders)

Los seeders leen los JSON desde una ruta interna del proyecto:

```
database/seeders/data/
```

---

## 📌 2. Cómo cargan los seeders los JSON

```php
$jsonData = File::get(database_path('seeders/data/treks.json'));
$data = json_decode($jsonData, true);
```

---

## 📌 3. Instalación y ejecución del proyecto

```bash
composer install
cp .env.example .env
php artisan migrate:fresh --seed
php artisan serve
```

---

## 📌 4. Capa de datos (modelo, migraciones, seeders y triggers)

### 🧱 Migraciones
- users, roles  
- treks  
- meetings  
- comments  
- images  
- place_types, places, place_trek  
- municipalities, islands, zones  
- meeting_user (pivot)

### 🌱 Seeders
Orden ejecutado por `DatabaseSeeder`:

1. RoleSeeder  
2. IslandSeeder  
3. ZoneSeeder  
4. MunicipalitySeeder  
5. UserSeeder (admin + guías JSON)  
6. TrekSeeder  
7. PlaceSeeder  
8. UserFactory (100 visitants)  
9. MeetingUserSeeder  
10. ImageFactory (1000 imágenes)

### 🧩 Factories
- UserFactory  
- ImageFactory  

### 🗂️ Modelos y relaciones
Relaciones 1:N y N:N definidas según el modelo ER del proyecto.

### 🔹 Triggers sobre `comments`
Actualizan:
- meetings.totalScore  
- meetings.countScore  

### 🔹 Triggers sobre `meetings`
Actualizan:
- treks.totalScore  
- treks.countScore  

---

## 📌 5. Estructura esperada de los JSON

### users.json
```json
{
  "usuaris": {
    "usuari": [
      { "nom": "...", "llinatges": "...", "dni": "...", "telefon": "...", "email": "...", "password": "..." }
    ]
  }
}
```

### municipalities.json
Tres formatos posibles.

### treks.json
Incluye treks, meetings y comments.

### places.json
Incluye place_types, interesting_places y place_trek.

---

## 📌 6. API REST (lo implementado)

### ✅ Autenticación
- **Por credenciales**: register, login, logout (Sanctum).
- **Por API-KEY**: header `API-KEY` con el valor de `APP_KEY`.

### ✅ Middlewares
- `auth.or.api.key` (Sanctum o API-KEY).
- `check.role.admin` (solo administradores).

### ✅ Route model binding personalizado
- **Users**: `{user}` acepta **ID** o **email**.
- **Treks**: `{trek}` acepta **ID** o **regNumber**.

### ✅ Requests y Resources
- Requests: `UserUpdateRequest`, `UserDestroyRequest`, `TrekStoreRequest`, `LoginRequest`.
- Resources: `UserResource`, `UserSummaryResource`, `TrekResource`, `MeetingResource`, `CommentResource`,
  `MunicipalityResource`, `PlaceTypeResource`, `InterestingPlaceResource`.

---

## 📌 7. Endpoints principales

Base URL típica: `http://127.0.0.1:8000/api`

### Autenticación
- `POST /register`
- `POST /login`
- `POST /logout` (protegido)

### Usuarios
- `GET /users` (admin)
- `GET /users/{user}` (admin)
- `PUT /users/{user}` (admin)
- `DELETE /users/{user}` (admin o propio)
- `GET /user` (usuario autenticado)
- `PUT /user` (usuario autenticado)
- `DELETE /user` (usuario autenticado)

### Treks
- `GET /treks`
- `GET /treks/{trek}`
- `POST /treks` (admin)

---

## 📌 8. Mini documentación de uso

### 🔹 Autenticación con token Sanctum
1. `POST /login`
2. Usar `Authorization: Bearer <token>` en las rutas protegidas.

### 🔹 Autenticación con API-KEY
En cualquier ruta protegida, enviar:
```
API-KEY: <APP_KEY>
```

### 🔹 Filtros
- `GET /treks?illa=Mallorca` o `GET /treks?island_id=1`

### 🔹 Updates parciales de usuario
`PUT /user` acepta solo los campos que quieras modificar.

---

## 📂 9. Estructura del proyecto

```
database/
│── migrations/
│── seeders/
│   │── data/
│── factories/
app/
│── Models/
│── Http/Controllers/
│── Http/Requests/
│── Http/Resources/
│── Http/Middleware/
routes/
│── api.php
```

---

## 📌 10. Estado actual del proyecto

| Área | Estado |
|------|--------|
| Base de datos | ✔️ Completada |
| API REST | ✔️ Completada |
| Dashboard de la API | ✔️ Completada |
| Frontend (React + Vite) | ✔️ Completada |

---

## 📌 11. Cobertura funcional Back End (admin)

### 1. Gestión de usuarios
- Listado de usuarios en panel de administración.
- Búsqueda y filtrado por rol y estado.
- Alta y baja lógica de usuarios.
- Edición de datos básicos del usuario.
- Cambio de rol entre perfiles operativos del sistema.

### 2. Moderación de comentarios e imágenes
- Listado de comentarios con filtros por estado y excursión.
- Vista de detalle de comentario para moderación.
- Aprobación/actualización del estado del comentario como bloque único.
- Visualización de imágenes asociadas al comentario en detalle y edición.

### 3. Gestión de catálogos y operativa (CRUD)
- Lugares destacables: alta, listado, detalle y edición.
- Municipios: alta, listado, detalle y edición.
- Excursiones: alta, listado, detalle y edición.
- Encuentros de una excursión: alta, listado, detalle y edición.
- Asignación de guía principal en encuentros con validación de rol.
- Cálculo automático de rango de inscripción desde la fecha del encuentro.

### 4. Estado de encuentros, guías adicionales e inscripciones
- Visualización del estado de inscripción del encuentro.
- Filtro de encuentros por estado de inscripción.
- Asignación y retirada de guías adicionales.
- Visualización de listado de inscripciones/asistentes del encuentro.

---

## 📖 12. Resumen técnico final

✔ Migraciones completas  
✔ Seeders basados en JSON  
✔ Factories masivas  
✔ Triggers automáticos  
✔ Carga reproducible  
✔ API REST con Sanctum + API-KEY  
✔ Requests + Resources  
✔ Route model binding personalizado  
✔ Filtros y permisos por rol  

---
