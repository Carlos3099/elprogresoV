README - Proyecto El Progreso
=============================

🚀 Requisitos previos
--------------------
Antes de levantar el proyecto, asegúrate de tener instalado:

- Docker Desktop (versión 20+)
- Docker Compose (versión 1.29+)
- Git
- Node.js 20 (solo si quieres correr Vite fuera de Docker)

📂 Estructura de carpetas
-------------------------
elprogreso/
│── docker-compose.yml
│── Dockerfile
│── src/                # Código fuente de Laravel
│── vendor/             # Dependencias PHP (se generan solas)

⚙️ Configuración inicial
------------------------
1. Clonar el repositorio
   git clone <url-del-repo>
   cd elprogreso

2. Crear archivo .env
   Entra a src/.env y ajusta la configuración de base de datos:

   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=elprogreso
   DB_USERNAME=elprogreso
   DB_PASSWORD=elprogreso

   Importante: DB_HOST debe ser "mysql" (nombre del servicio de Docker), no "localhost".

🐳 Levantar el proyecto con Docker
---------------------------------
1. Instalar dependencias de Laravel (fuera de Docker):
   docker run --rm -v ${PWD}/src:/app -w /app composer install
   docker run --rm -v ${PWD}/src:/app -w /app composer require barryvdh/laravel-dompdf

2. Construir y levantar contenedores:
   docker-compose up -d --build

   Esto levanta:
   - Laravel App → http://localhost:8020
   - phpMyAdmin  → http://localhost:8082
   - MySQL       → puerto 3320 en el host

3. Migraciones y seeders:
   docker-compose exec app php artisan migrate --seed

📦 Servicios en docker-compose
------------------------------
- app: Contenedor PHP con Laravel
- node: Contenedor Node.js para Vite (npm run dev)
- mysql: Base de datos MySQL 8.0
- phpmyadmin: Cliente web para administrar la BD

🔧 Comandos útiles
-----------------
- Ingresar al contenedor app:
  docker-compose exec app bash

- Correr Artisan:
  docker-compose exec app php artisan tinker

- Instalar un nuevo paquete (ejemplo DomPDF):
  docker run --rm -v ${PWD}/src:/app -w /app composer require barryvdh/laravel-dompdf

- Reconstruir todo (limpio):
  docker-compose down -v
  docker-compose up --build

🌐 Accesos rápidos
-----------------
- Laravel → http://localhost:8020
- phpMyAdmin → http://localhost:8082
  Usuario: elprogreso
  Password: elprogreso

✅ Notas importantes
-------------------
- No edites composer.json a mano, siempre usa composer require o composer remove.
- El volumen vendor/ está mapeado para que Composer en tu host y en el contenedor usen las mismas dependencias.
- Si Composer lanza advertencias de lock file, corre:
  docker run --rm -v ${PWD}/src:/app -w /app composer update
