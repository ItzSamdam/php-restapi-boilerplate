# PHP API

A lightweight PHP API project with a modular architecture using
repositories, services, controllers, and middleware.

------------------------------------------------------------------------

## 📂 Project Structure

    php-api/
    ├── app/
    │   ├── Config/
    │   │   └── Database.php
    │   ├── Core/
    │   │   ├── Router.php
    │   │   ├── Request.php
    │   │   ├── Response.php
    │   │   └── Container.php
    │   ├── Repositories/
    │   │   ├── Contracts/
    │   │   │   ├── RepositoryInterface.php
    │   │   │   └── UserRepositoryInterface.php
    │   │   ├── BaseRepository.php
    │   │   ├── UserRepository.php
    │   │   └── TransactionRepository.php
    │   ├── Models/
    │   │   ├── User.php
    │   │   ├── Transaction.php
    │   │   └── Account.php
    │   ├── Services/
    │   │   ├── UserService.php
    │   │   ├── TransactionService.php
    │   │   └── AuthService.php
    │   ├── Controllers/
    │   │   ├── ApiController.php
    │   │   ├── UserController.php
    │   │   └── TransactionController.php
    │   └── Middleware/
    │       ├── AuthMiddleware.php
    │       └── CorsMiddleware.php
    ├── database/
    │   ├── migrations/
    │   │   ├── 001_create_users_table.php
    │   │   ├── 002_create_accounts_table.php
    │   │   └── 003_create_transactions_table.php
    │   └── seeds/
    │       └── DatabaseSeeder.php
    ├── public/
    │   └── index.php
    ├── .htaccess
    └── composer.json

------------------------------------------------------------------------

## 🚀 Features

-   **MVC-like structure** with repositories & services
-   **Dependency injection** via `Container.php`
-   **Database migrations & seeders**
-   **Authentication & CORS middleware**
-   **RESTful routing**

------------------------------------------------------------------------

## ⚙️ Installation

1.  Clone the repository:

    ``` bash
    git clone https://github.com/your-username/php-api.git
    cd php-api
    ```

2.  Install dependencies via Composer:

    ``` bash
    composer install
    ```

3.  Configure your database in:

    ``` php
    app/Config/Database.php
    ```

4.  Run migrations & seeders:

    ``` bash
    php database/migrations/001_create_users_table.php
    php database/migrations/002_create_accounts_table.php
    php database/migrations/003_create_transactions_table.php
    php database/seeds/DatabaseSeeder.php
    ```

------------------------------------------------------------------------

## ▶️ Running the Project

Start a PHP built-in server:

``` bash
php -S localhost:8000 -t public
```

Now visit <http://localhost:8000>

------------------------------------------------------------------------

## 📌 API Endpoints

  Method   Endpoint              Description
  -------- --------------------- ------------------------
  POST     `/api/register`       Register a new user
  POST     `/api/login`          Authenticate user
  GET      `/api/users`          List all users
  GET      `/api/transactions`   Get user transactions
  POST     `/api/transactions`   Create new transaction

------------------------------------------------------------------------

## 🛡️ Middleware

-   **AuthMiddleware** → Protects routes requiring authentication\
-   **CorsMiddleware** → Handles cross-origin requests

------------------------------------------------------------------------

## 🛠️ Tech Stack

-   **PHP 8+**
-   **Composer**
-   **MySQL / MariaDB**

------------------------------------------------------------------------

## 📄 License

This project is open-source under the [MIT License](LICENSE).
