/project-root
├── src/
│   ├── Auth/
│   │   ├── Controllers/
│   │   │   └── AuthController.php
│   │   ├── Models/
│   │   │   └── User.php
│   │   ├── Services/
│   │   │   └── AuthService.php
│   │   └── routes.php
│
│   ├── Products/
│   │   ├── Controllers/
│   │   │   └── ProductController.php
│   │   ├── Models/
│   │   │   └── Product.php
│   │   ├── Services/
│   │   │   └── ProductService.php
│   │   └── routes.php
│
│   ├── Shared/
│   │   ├── Helpers/
│   │   │   └── ResponseHelper.php
│   │   ├── Middleware/
│   │   │   └── AuthMiddleware.php
│   │   └── Config/
│   │       └── database.php
│
├── public/
│   └── index.php
├── composer.json
├── routes.php (aggregates all feature routes)
