🚀 API REST - Sistema de Gestión de Ferretería

API REST desarrollada con Laravel para gestionar productos, compras, ventas y usuarios en un sistema de ferretería.

🛠 Tecnologías

PHP

Laravel

MySQL

Laravel Sanctum (Autenticación)

REST API

📂 Estructura del proyecto
app/
 ├── Http
 │   ├── Controllers
 │   ├── Middleware
 │   └── Requests
 │
 ├── Models
 │
routes/
 └── api.php
🌿 Estrategia de ramas (Git)

El proyecto utiliza una estructura simple:

main → versión estable
dev  → desarrollo

Flujo de trabajo:

Desarrollar funcionalidades en dev

Probar cambios

Fusionar en main cuando esté listo

⚙️ Instalación
1️⃣ Clonar repositorio
git clone https://github.com/tuusuario/ferreteria-api.git
cd ferreteria-api
2️⃣ Instalar dependencias
composer install
3️⃣ Configurar entorno
cp .env.example .env

Configurar la base de datos en .env

DB_DATABASE=ferreteria
DB_USERNAME=root
DB_PASSWORD=
4️⃣ Generar clave de aplicación
php artisan key:generate
5️⃣ Ejecutar migraciones
php artisan migrate
6️⃣ Ejecutar servidor
php artisan serve

La API estará disponible en:

http://localhost:8000
🔐 Autenticación

La API utiliza Laravel Sanctum para autenticación basada en tokens.

Ejemplo de login:

POST /api/login

Respuesta:

{
 "token": "1|asdasdasdasd"
}
📡 Endpoints principales
Productos
GET /api/productos
POST /api/productos
PUT /api/productos/{id}
DELETE /api/productos/{id}
Ventas
POST /api/ventas
GET /api/ventas
Compras
POST /api/compras
GET /api/compras
📊 Estado del proyecto

🚧 En desarrollo

👨‍💻 Autor

Aaron