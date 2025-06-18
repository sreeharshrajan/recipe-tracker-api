# 🍽️ Symmersive Recipe Tracker API

A RESTful API-only Laravel application for managing cooking recipes. Developed as an interview project, this application supports recipe CRUD operations, advanced search by ingredients and cook time, authentication via Laravel Sanctum, and robust documentation with Swagger.

## 🚀 Features

- Full CRUD for recipes
- JSON responses with proper HTTP status codes
- SQLite file-based development database
- Authentication via Laravel Sanctum
- Input validation using Form Request classes
- Swagger documentation (OpenAPI) via L5-Swagger
- Filter recipes by difficulty
- Advanced search by ingredients and total cook time
- Custom Resource classes with calculated `total_time`
- Unit & feature tests using PHPUnit
- PSR-12 linting applied via Laravel Pint

---

## 🧠 Tech Stack

- **Laravel 12**
- **PHP 8.2+**
- **SQLite**
- **Laravel Sanctum**
- **L5-Swagger** for API docs
- **PHPUnit** for testing
- **Laravel Pint** for code linting

---

## 📦 Installation

```bash
git clone https://github.com/sreeharshrajan/recipe-tracker-api.git
cd recipe-tracker-api
composer install
cp .env.example .env
touch database/database.sqlite
php artisan key:generate
php artisan migrate --seed
```

### Make sure `.env` contains:

```
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

---

## 📖 API Documentation

Access Swagger UI at:

```
http://127.0.0.1:8000//api/documentation
```

---

## 🔐 Authentication

Laravel Sanctum is used for secure API authentication.

**Register**
```
POST /api/register
```

**Login**
```
POST /api/login
```

Use the token in headers:

```
Authorization: Bearer {your_token}
```

---

## 📚 API Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET    | /api/recipes | List all recipes | ✅ |
| POST   | /api/recipes | Create a new recipe | ✅ |
| GET    | /api/recipes/{id} | Show a specific recipe | ✅ |
| PUT    | /api/recipes/{id} | Update a recipe | ✅ |
| DELETE | /api/recipes/{id} | Delete a recipe | ✅ |
| GET    | /api/recipes/difficulty/{level} | Filter recipes by difficulty | ✅ |
| GET    | /api/recipes/search | Search by ingredients + time | ✅ |

### Search Example

```
GET /api/recipes/search?ingredients=potatoes,onion,cumin&min_time=20&max_time=30
```

---

## 🧪 Testing

Run all tests using:

```bash
php artisan test
```

Tests cover:
- Recipe CRUD
- Input validation
- 404 handling
- Auth & middleware
- Search feature

---

## 🌱 Seeder

Seeder uses the provided `recipe.json` file.

Each Recipe has:
- `name`: string
- `ingredients`: text (comma-separated)
- `prep_time`: integer
- `cook_time`: integer
- `difficulty`: enum (easy, medium, hard)
- `description`: string

---

## ✅ Validation Rules

Handled by:
- `StoreRecipeRequest`
- `UpdateRecipeRequest`

Validates required fields, type, enum constraints, and format.

---

## 🧩 Laravel API Resource

All API responses are wrapped using `RecipeResource` which also includes:

```php
'total_time' => $prep_time + $cook_time
```

---

## 🧹 Code Quality

```bash
./vendor/bin/pint
```

Applies PSR-12 coding standards using Laravel Pint.

---

## 📝 License

MIT License

---

## 👤 Author

Sreeharsh Rajan  
Full Stack Developer (Laravel)
[LinkedIn](https://linkedin.com/in/sreeharshk) | [GitHub](https://github.com/sreeharshrajan) | [Portfolio](https://sreeharsh.vercel.app/)

---
