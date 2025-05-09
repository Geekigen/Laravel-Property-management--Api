# Property Management API

A Laravel 12-based RESTful API for property management, supporting properties, tenants, leases, and payment tracking across multiple user roles.

## Project Overview

This API enables complete CRUD operations with role-based access control, performance optimization through caching, and asynchronous task handling. Built with Laravel Sanctum for authentication and fully tested and documented.

## System Requirements

- PHP 8.3+
- Composer 2.x
- Node.js 18+
- MySQL 8.0+ / PostgreSQL 13+ / SQLite 3.8.8+
- Redis (optional for caching/queues)
- Docker (optional for containerization)

## Getting Started

```bash
# Clone the repository
git clone https://github.com/yourusername/your-repository.git
cd your-repository

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database configuration
# Edit .env with your database credentials

# Run migrations with seed data
php artisan migrate
php artisan db:seed
```

For Docker users:
```bash
docker-compose up -d
```

## Running the Application

**Development:**
```bash
php artisan serve
```

**Production:**
```bash
php artisan optimize
php artisan route:cache
php artisan view:cache
php artisan config:cache
```

## API Documentation

Complete documentation available at: https://documenter.getpostman.com/view/27137771/2sB2j989gk

Import the Postman collection to test all endpoints including authentication, properties, units, tenants, leases, and payments.

## Architecture

- **Repository Pattern:** Database logic abstraction for better testing and organization
- **RESTful Design:** Following REST principles with filtering, pagination, and sorting
- **Database Structure:** Relational design with proper migrations and relationships
- **Test-Driven:** 80%+ test coverage with mocking for external dependencies
- **Modular Design:** Follows Laravel conventions for separation of concerns

## Database Design

The schema includes:
- Users (with role-based access)
- Properties
- Units
- Tenants
- Leases
- Payments

Implemented with proper relationships, eager loading optimization, and indexing.

## Performance Optimizations

- **Caching:** Configurable cache system with automatic invalidation
- **Query Optimization:** Eager loading and indexing for database performance
- **Profiling:** Laravel Telescope integration for endpoint optimization

## Security Features

- Token-based authentication with Laravel Sanctum
- Role-based access control
- Form request validation
- Rate limiting
- Environment-based configuration
- XSS/CSRF protection

## Scalability Considerations

- Stateless API design for horizontal scaling
- Database optimization with indexing and read replicas
- Redis caching to reduce database load
- Queue system for asynchronous tasks
- Containerization for deployment consistency

## Testing

Comprehensive test suite including:
- Feature tests for authentication and controller actions
- Unit tests for repositories and services
- 80%+ code coverage

## Additional Features

- Docker configuration
- Complete Postman documentation
- Seeded sample data

## Troubleshooting

```bash
# Fix storage permissions
chmod -R 775 storage bootstrap/cache

# Resolve class autoloading issues
composer dump-autoload

# Clear application caches
php artisan optimize:clear
```

## Updates

```bash
git pull
composer install
php artisan migrate
php artisan optimize
```
