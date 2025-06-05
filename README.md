# Sistema de Gestión de Pedidos y Pagos 📦🌍

Este es un sistema desarrollado en Laravel para la gestión de pedidos y registro de pagos de clientes. Permite a los usuarios autenticados crear, ver y eliminar sus propios pedidos y pagos asociados.

<!-- (Opcional) Aquí podrías poner un screenshot:
![Screenshot de la App](ruta/a/tu/screenshot.png)
-->

## Características Principales 📍

*   Autenticación de usuarios (Registro e Inicio de Sesión).
*   Creación, visualización y eliminación de Pedidos por usuario.
*   Creación, visualización y eliminación de Pagos asociados a pedidos, por usuario.
*   Interfaz de usuario responsiva.

## Tecnologías Utilizadas 👨‍💻

*   **Framework Backend:** Laravel 10
*   **Lenguaje:** PHP 8.1+
*   **Base de Datos:** MySQL (localmente) / PostgreSQL (en despliegue - *ajusta según tu elección final*)
*   **Frontend:** Blade con Bootstrap (o la tecnología que uses)
*   **Gestor de Dependencias PHP:** Composer
*   **Gestor de Paquetes Frontend:** NPM (si aplica)

## Prerrequisitos🤔

Asegúrate de tener instalados los siguientes componentes antes de empezar:

*   PHP >= 8.1
*   Composer 2.x
*   Node.js y NPM (si vas a compilar assets de frontend)
*   Servidor de Base de Datos (MySQL 5.7+ / MariaDB 10.3+ o PostgreSQL)
*   Git

## Instalación y Configuración Local 🔋

Sigue estos pasos para configurar el proyecto en tu entorno local:

1.  **Clona el repositorio:**
    ```bash
    git clone [https://github.com/Keurydl/sistema_pedidos.git](https://github.com/Keurydl/sistema_pedidos.git)
    cd sistema_pedidos
    ```

2.  **Instala las dependencias de PHP:**
    ```bash
    composer install
    ```

3.  **Instala las dependencias de Node.js (si es necesario):**
    ```bash
    npm install
    ```

4.  **Crea tu archivo de entorno:**
    Copia el archivo de ejemplo `.env.example` a `.env`:
    ```bash
    cp .env.example .env
    ```

5.  **Genera la clave de la aplicación:**
    ```bash
    php artisan key:generate
    ```

6.  **Configura tu base de datos en el archivo `.env`:**
    Abre el archivo `.env` y actualiza las siguientes variables con los detalles de tu base de datos local:
    ```ini
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=sistema_pedidos # O el nombre de tu BD local
    DB_USERNAME=root          # O tu usuario de BD local
    DB_PASSWORD=              # O tu contraseña de BD local

    APP_URL=http://localhost:8000
    ```

7.  **Ejecuta las migraciones para crear las tablas en la base de datos:**
    ```bash
    php artisan migrate
    ```

8.  **(Opcional) Ejecuta los seeders para poblar la base de datos con datos de prueba:**
    ```bash
    php artisan db:seed
    ```

9.  **Compila los assets de frontend (si es necesario):**
    ```bash
    npm run dev
    ```
    (O `npm run build` para producción)

10. **Inicia el servidor de desarrollo de Laravel:**
    ```bash
    php artisan serve
    ```

11. **¡Listo!** Abre tu navegador y visita `http://localhost:8000`.

## Demostracion:

# Inicio:
![image](https://github.com/user-attachments/assets/b09190a3-4896-43ae-b451-11265c2f1020)

# Categorias:
![image](https://github.com/user-attachments/assets/61f39414-fe14-490d-853b-24fb13e245ac)

# Productos:
![image](https://github.com/user-attachments/assets/b6dac83a-4452-45d1-936e-909e5a8a749d)

# Pedidos y pagos:
![image](https://github.com/user-attachments/assets/daff4b76-e184-4524-b074-dfeafcbe4f16)

# Contacto:
![image](https://github.com/user-attachments/assets/4555fe67-c736-4233-8752-62ab9cf7b95b)

# Panel del admin:
![image](https://github.com/user-attachments/assets/139de4f1-0f78-458f-b75e-050673b3d7b3)

# Administracion de productos:
![image](https://github.com/user-attachments/assets/ddbb49ce-5f9e-484a-8815-a24d3e3db430)

# Administracion de categorias:
![image](https://github.com/user-attachments/assets/78794889-5ad3-419d-a97c-0af0d03e7f72)

# Administracion de usuarios:
![image](https://github.com/user-attachments/assets/a2e9ca2d-40b8-4182-b43d-64fa5c9185a9)

