# Order and Payment Management System 📦🌍

This is a system developed in Laravel for managing orders and recording customer payments. It allows authenticated users to create, view, and delete their own orders and associated payments.

<!-- (Optional) You could include a screenshot here:
![App Screenshot](path/to/your/screenshot.png)
-->

## Main Features 📍

* User authentication (Registration and Login).
* Creation, viewing, and deletion of orders per user.
* Creation, viewing, and deletion of payments associated with orders, per user.
* Responsive user interface.

## Technologies Used 👨‍💻

* **Backend Framework:** Laravel 10
* **Language:** PHP 8.1+
* **Database:** MySQL (locally) / PostgreSQL (on deployment - *adjust based on your final choice*)
* **Frontend:** Blade with Bootstrap (or whatever technology you use)
* **PHP Dependency Manager:** Composer
* **Fronend Package Manager:** NPM (if applicable)

## Prerequisites 🤔

Make sure you have the following components installed before you begin:

* PHP >= 8.1
* Composer 2.x
* Node.js and NPM (if you're building frontend assets)
* Database Server (MySQL 5.7+ / MariaDB 10.3+ or PostgreSQL)
* Git

## Local Installation and Configuration 🔋

Follow these steps to set up the project in your local environment:

1. Clone the repository:

```bash
git clone [https://github.com/Keurydl/sistema_pedidos.git](https://github.com/Keurydl/sistema_pedidos.git)
cd sistema_pedidos
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node.js dependencies (if necessary):
```bash
npm install
```

4. Create your environment file:
Copy the `.env.example` example file to `.env`:
```bash
cp .env.example .env
```

5. Generate the application key:
```bash
php artisan key:generate
```

6. **Configure your database in the `.env` file:**
Open the `.env` file and update the following variables with your local database details:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=order_system # Or your local database name
DB_USERNAME=root # Or your local database username
DB_PASSWORD= # Or your local database password

APP_URL=http://localhost:8000
```

7. **Run the migrations to create the tables in the database:**
```bash
php artisan migrate
```

8. **(Optional) Run the seeders to populate the database with test data:**
```bash
php artisan db:seed
```

9. **Compile the frontend assets (if necessary):**
```bash
npm run dev
```
(Or `npm run build` for production)

10. **Start the Laravel development server:**
```bash
php artisan serve
```

11. **Done!** Open your browser and visit `http://localhost:8000`.

## Demo:

# Start:
![image](https://github.com/user-attachments/assets/b09190a3-4896-43ae-b451-11265c2f1020)

# Categories:
![image](https://github.com/user-attachments/assets/61f39414-fe14-490d-853b-24fb13e245ac)

# Products:
![image](https://github.com/user-attachments/assets/b6dac83a-4452-45d1-936e-909e5a8a749d)

# Orders and payments:
![image](https://github.com/user-attachments/assets/daff4b76-e184-4524-b074-dfeafcbe4f16)

# Contact:
![image](https://github.com/user-attachments/assets/4555fe67-c736-4233-8752-62ab9cf7b95b)

# Admin panel:
![image](https://github.com/user-attachments/assets/139de4f1-0f78-458f-b75e-050673b3d7b3)

# Administration of products:
![image](https://github.com/user-attachments/assets/ddbb49ce-5f9e-484a-8815-a24d3e3db430)

# Category administration:
![image](https://github.com/user-attachments/assets/78794889-5ad3-419d-a97c-0af0d03e7f72)

# User administration:
![image](https://github.com/user-attachments/assets/a2e9ca2d-40b8-4182-b43d-64fa5c9185a9)
