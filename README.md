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

## API Usage

### Upload a file

```bash
curl -X POST http://localhost:8000/api/file-uploads \
  -F "file=@/path/to/document.pdf" \
  -F "type=visa_request_form"
```

Accepted values for `type`: `visa_request_form`, `photo`, `passport`.  
Accepted file types: PDF, JPG, PNG. Max size: 4MB.

### List uploaded files

```bash
curl http://localhost:8000/api/file-uploads
```

Returns files grouped by type.

### Delete a file

```bash
curl -X DELETE http://localhost:8000/api/file-uploads/{id}
```

Removes the file from storage and the database.

## Tests

```bash
cd api && php artisan test
```
