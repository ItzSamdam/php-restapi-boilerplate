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