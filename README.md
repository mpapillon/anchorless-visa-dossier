# Visa Dossier

## Requirements

- PHP 8.4+
- Composer
- Node.js 20+

## Installation

**API**

```bash
cd api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

**Frontend**

```bash
cd frontend
npm install
```

## Running the app

**API** - available at `http://localhost:8000/api`

```bash
cd api && php artisan serve
```

**Frontend** - available at `http://localhost:5173/`

```bash
cd frontend && npm run dev
```

## Tests

```bash
cd api && php artisan test
```
