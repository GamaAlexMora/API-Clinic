# API Clinic

REST-style backend project for managing clinical records. The project was built as an academic exercise to practice API design, database access, authentication, and protected routes.

## Tech stack

- PHP
- MySQL / PDO
- JSON Web Tokens (JWT)
- Composer
- Apache / `.htaccess`

## What the project implements

- User login with password verification.
- JWT generation and validation for protected routes.
- Patient and disease controllers.
- Patient data model with create, read, update, delete, search, and duplicate-email checks.
- Prepared SQL statements through PDO.
- JSON responses for API requests.

## Main structure

```
config/       Database configuration
controllers/  Authentication, patients, and diseases
middleware/   JWT validation
models/       Data access and CRUD logic
index.php     API entry point and routing
```

## Environment variables

Copy the values from `.env.example` into environment variables configured for your local PHP/Apache environment.

Required values:

```
DB_HOST
DB_NAME
DB_USER
DB_PASSWORD
JWT_SECRET
```

Never commit real credentials or JWT secrets to the repository.

## Authentication flow

1. A client sends credentials to the login endpoint.
2. The API verifies the stored password hash.
3. A signed JWT is returned when authentication succeeds.
4. Protected routes validate the bearer token before processing the request.

## Notes

This is an academic project intended to demonstrate backend and API fundamentals. Before production use, it would require additional validation, centralized error handling, rate limiting, HTTPS-only deployment, automated tests, and more granular authorization.
