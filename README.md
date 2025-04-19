# 🔒 Laravel MFA API + Customer Management

A production-ready API with Multi-Factor Authentication (MFA) and customer CRUD operations.

## 🚀 Quick Start

Clone & Install

```bash
git clone project_path
cd folder_name
composer install
npm install
cp .env.example .env
php artisan key:generate

# Configure Environment
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_mfa
DB_USERNAME=root
DB_PASSWORD=

# Mail (for MFA tokens)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_user
MAIL_PASSWORD=your_mailtrap_pass
MAIL_FROM_ADDRESS="no-reply@yourdomain.com"

# Sanctum (API auth)
SANCTUM_STATEFUL_DOMAINS=localhost:8000

Database Setup

# Start backend
php artisan serve --port=8000 &

# Start frontend
npm install
npm run dev &

# Process queues (for emails)
php artisan queue:work

# Unit + Feature tests
php artisan test

# With coverage report (requires Xdebug)
XDEBUG_MODE=coverage php artisan test --coverage-html storage/coverage


# Build and start containers
docker-compose up -d --build

# Run migrations
docker exec -it laravel-app php artisan migrate --seed

# Access container shell
docker exec -it laravel-app bash
