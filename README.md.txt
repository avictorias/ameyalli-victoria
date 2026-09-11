#Tienda E-Commerce con Laravel, Livewire y Reportes Dinámicos

Sistema de comercio electrónico desarrollado con **Laravel**, **Livewire**, **Alpine.js** y **Chart.js**, que incluye gestión de productos, carrito de compras, pasarela de pagos simulada y un módulo de reportes con gráfica.

---

## Características Principales

* **Autenticación y Registro:** Los usuarios pueden registrarse libremente en el sistema y acceder a sus cuentas.
* **Catálogo y Carrito:** Gestión de productos con control de stock y precios en USD / MXN.
* **Proceso de Checkout y Pagos:** 
  * Simulación de pagos con tarjeta de crédito/débito. 
  * *Nota de prueba:* Para realizar compras exitosas en la simulación, la tarjeta válida debe ingresarse con **puros 9** (ej. `9999 9999 9999 9999`).
  * Generación automática de folios de pedidos.
* **Módulo de Reportes Protegido:** 
  * Restringido exclusivamente a usuarios autenticados.
  * Filtros de búsqueda por rango de fechas en tiempo real.
  * Cálculo automático de ganancias totales en el periodo seleccionado.
  * Top 3 de productos más vendidos.
  * **Gráfica interactiva de ganancias diarias** impulsada por Chart.js y Alpine.js.

---

## Tecnologías Utilizadas

* **PHP / Laravel** (Backend y lógica de negocio)
* **Livewire v3** (Componentes reactivos en tiempo real)
* **Alpine.js** (Interactividad y control de gráficos)
* **Chart.js** (Visualización de datos y reportes)
* **Tailwind CSS** (Diseño de interfaces)
* **MySQL** (Base de datos relacional)

---
## Requisitos del Sistema
Asegúrate de tener instalado en tu entorno local:
* **PHP** >= 8.2
* **Composer**
* **Node.js & NPM**
* **MySQL / MariaDB** (o un entorno como XAMPP / Laragon)

---

## Si usas XAMPP hay que agregar el dominio virtual
1. Abrir C:\xampp\apache\conf\extra\httpd-vhosts.conf
2. Agregar al final del archivo un bloque como este (reemplaza tu-proyecto por el nombre real de tu carpeta dentro de htdocs)
	<VirtualHost *:80>
    ServerName tu-tienda.test
    DocumentRoot "C:/xampp/htdocs/ameyalli-victoria/public"
    <Directory "C:/xampp/htdocs/ameyalli-victoria/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
3. Mapear el dominio local en el archivo hosts de Windows
	Abrir como administrador el archivo C:\Windows\System32\drivers\etc\hosts
	Agregar la linea o descomentar : 127.0.0.1   tienda.test

##Instalación y Configuración Local

Sigue estos pasos para clonar y echar a andar el proyecto en tu máquina:

1. **Clona el repositorio:**
   
   	git clone https://github.com/avictorias/ameyalli-victoria.git
2. Instalar dependencias PHP
	composer install
3. Instalar las dependencias de Node
	npm install && npm run dev
4. Configurar tu archivo de entorno:
	Copia el archivo .env.example a .env y configura los datos de tu base de datos:
	APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:OUOJaWh1JxvhQ2ZC+2qwsNqZYn4SaxSWcqD45T3ef/I=
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

# PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tienda_evaluacion
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync

EXCHANGE_RATE_API_KEY=8fe070f06b-6ec0cafe79-tl6g2t

CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

5. Generar llave de aplicación 
	php artisan key:generate
6. Ejecutar las migraciones
	php artisan migrate
7. Iniciar el servidor local
	php artisan serve

	