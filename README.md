Sistema de Gestión de Talleres Automotrices

Aplicación web desarrollada en Laravel para administrar los procesos principales de un taller automotriz. El sistema permite gestionar usuarios, clientes, vehículos, servicios, órdenes de trabajo, mecánicos, repuestos, pagos y reportes.

El proyecto fue contenerizado con Docker, utilizando tres servicios principales:

Laravel + PHP-FPM 8.4

Nginx

MySQL 8

Repositorios

GitHub: https://github.com/ColdFer/PWII-GestionTalleres

Docker Hub: https://hub.docker.com/r/coldfer/pwii-gestiontalleres

Imagen Docker: coldfer/pwii-gestiontalleres:latest

Para descargar la imagen publicada:

docker pull coldfer/pwii-gestiontalleres:latest

También se encuentra disponible la versión:

docker pull coldfer/pwii-gestiontalleres:v1.0.0

Tecnologías utilizadas

Laravel

PHP 8.4

Blade

Bootstrap

JavaScript

Vite

MySQL 8

Nginx

Docker

Docker Compose

Git y GitHub

Módulos principales

Autenticación manual

Roles y permisos

Gestión de usuarios

Gestión de clientes

Gestión de vehículos

Marcas y modelos

Tipos de servicio

Servicios

Órdenes de trabajo

Asignación de mecánicos

Especialidades

Repuestos e inventario

Pagos

Reportes

Panel de administración

Portal del cliente

Arquitectura Docker

Navegador
    ↓
http://localhost:8080
    ↓
Nginx — puerto 80
    ↓
app:9000
    ↓
PHP-FPM + Laravel
    ↓
database:3306
    ↓
MySQL

Los servicios definidos en compose.yml son:

app: ejecuta Laravel con PHP-FPM.

web: ejecuta Nginx y publica el sistema en el puerto 8080.

database: ejecuta MySQL y almacena los datos en un volumen persistente.

Requisitos

Docker Desktop

Docker Compose

Git

Comprobación:

docker version
docker compose version
docker run --rm hello-world

Instalación con Docker

1. Clonar el repositorio

git clone https://github.com/ColdFer/PWII-GestionTalleres.git
cd PWII-GestionTalleres

2. Crear el archivo .env

En Windows:

Copy-Item .env.example .env

En Linux o macOS:

cp .env.example .env

3. Configurar la base de datos

APP_NAME="Sistema de Gestión de Talleres"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=database
DB_PORT=3306
DB_DATABASE=gestion_talleres
DB_USERNAME=taller_user
DB_PASSWORD=colocar_contrasena
DB_ROOT_PASSWORD=colocar_contrasena_root

Dentro de Docker debe utilizarse:

DB_HOST=database

4. Configurar el administrador inicial

ADMIN_NAME="Administrador del Taller"
ADMIN_EMAIL=admin@taller.com
ADMIN_PASSWORD=colocar_contrasena_segura

5. Construir la imagen

docker compose build

6. Levantar los servicios

docker compose up -d

7. Verificar los contenedores

docker compose ps

Deben aparecer:

taller_app
taller_web
taller_database

8. Generar la clave de Laravel

Ejecutar solamente cuando APP_KEY esté vacío:

docker compose exec app php artisan key:generate

9. Ejecutar migraciones

docker compose exec app php artisan migrate

10. Ejecutar seeders

docker compose exec app php artisan db:seed

11. Limpiar cachés

docker compose exec app php artisan optimize:clear

12. Abrir la aplicación

http://localhost:8080

Comandos útiles

docker compose ps
docker compose logs --tail=100
docker compose down
docker compose up -d
docker compose build app
docker compose exec app php artisan migrate:status



## Evidencias de Docker

### Docker Desktop funcionando

![Docker Desktop](./screenshots/docker/DockerDesktop.png)

### Contenedores activos

![Contenedores Docker](./screenshots/docker/DockerComposePS.png)

### Migraciones ejecutadas

![Migraciones en Docker](./screenshots/docker/MigracionesDocker.png)

### Pantalla de inicio de sesión

![Login de la aplicación](./screenshots/docker/LoginDocker.png)

### Panel de administración

![Dashboard de la aplicación](./screenshots/docker/DashboardDocker.png)

### Repositorio publicado en Docker Hub

![Repositorio Docker Hub](./screenshots/docker/DockerHubRepositorio.png)

### Etiquetas publicadas en Docker Hub

![Etiquetas Docker Hub](./screenshots/docker/DockerHubTags.png)

### Publicación de la imagen Docker

![Docker Push](./screenshots/docker/DockerPush.png)

Estructura Docker

PWII-GestionTalleres/
├── Dockerfile
├── compose.yml
├── .dockerignore
├── docker/
│   └── nginx/
│       └── default.conf
├── screenshots/
│   └── docker/
└── ...

Seguridad

El archivo .env no debe publicarse en GitHub ni incluirse en la imagen Docker. Las contraseñas reales y claves privadas deben mantenerse fuera del repositorio.

Autor

David Fernando Mayorga Barco

Proyecto desarrollado para la asignatura Programación Web II.