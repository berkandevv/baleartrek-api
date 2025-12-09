# 📌 BalearTrek API – Parte A  
**Migraciones · Seeders · Factories · Modelos · Triggers · Carga de datos**

Este proyecto corresponde a la **primera parte** del desarrollo de la API para *BalearTrek*, una plataforma dedicada a la gestión de **excursiones, encuentros, participantes, lugares remarcables y comentarios**.

En esta fase se construye toda la **capa de datos**, incluyendo:

- Modelo relacional  
- Migraciones  
- Seeders  
- Factories  
- Modelos Eloquent  
- Automatización mediante triggers  
- Carga masiva desde JSON  

> Esta parte NO incluye aún rutas, controladores, requests, resources ni middlewares. Esto se desarrollará en la Parte B.

---

## 📌 1. Configuración necesaria (JSON externos)

La ruta donde se encuentran los archivos JSON se define en `.env`:

```
JSON_PATH=/ruta/a/baleartrek/
```

**Debe terminar en `/` o `\`**, según el sistema operativo.

---

## 📌 2. Cómo usan los seeders la variable JSON_PATH

```php
$jsonData = File::get(env('JSON_PATH') . 'treks.json');
$data = json_decode($jsonData, true);
```

---

## 📌 3. Contenido implementado en la Parte A

### 🧱 Migraciones
- users, roles  
- treks  
- meetings  
- comments  
- images  
- place_types, places, place_trek  
- municipalities, islands, zones  
- meeting_user (pivot)

---

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

---

### 🧩 Factories
- UserFactory  
- ImageFactory  

---

### 🗂️ Modelos y relaciones
Relaciones 1:N y N:N definidas según el modelo ER del proyecto.

---

## 📌 4. Triggers de la base de datos

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

## 📌 6. Instalación y ejecución del proyecto

```bash
composer install
cp .env.example .env
php artisan migrate:fresh --seed
```

---

## 📂 7. Estructura del proyecto

```
database/
│── migrations/
│── seeders/
│── factories/
app/
│── Models/
```

---

## 📌 8. Estado actual del proyecto

| Fase | Estado |
|------|--------|
| Parte A – Base de datos | ✔️ Completada |
| Parte B – API | ⏳ Pendiente |
| Parte C – Integración con frontend | ⏳ Pendiente |

---

## 📖 9. Resumen técnico final

✔ Migraciones completas  
✔ Seeders basados en JSON  
✔ Factories masivas  
✔ Triggers automáticos  
✔ Carga reproducible  
✔ Configuración flexible  

---
