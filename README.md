# Laravel MFA API + Customer Management
A production-ready API with Multi-Factor Authentication (MFA) and customer CRUD operations.

## 🚀 Quick Start

### 1. Download the zip or Clone & Install
cd cutomer_namagement
composer install
npm install
cp .env.example .env
php artisan key:generate

### 2. Configure Environment
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_mfa
DB_USERNAME=root
DB_PASSWORD=

# Mail (for MFA tokens)
MAIL_MAILER=MAIL_SERVER
MAIL_HOST=MAIL_HOST
MAIL_PORT=2525
MAIL_USERNAME=your_user
MAIL_PASSWORD=your_pass
MAIL_FROM_ADDRESS="no-reply@yourdomain.com"

# Sanctum (API auth)
SANCTUM_STATEFUL_DOMAINS=localhost:8000
# Database Setup
php artisan migrate:fresh --seed

# Run the project
php artisan serve --port=8000 &
Start frontend
npm install && npm run dev

# Process queues (for emails)
php artisan queue:work

# API Documentation
User import json-collection.json file in postman

# Sample Protected Request
curl -X GET \
  http://localhost:8000/api/v1/customers \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -H "Accept: application/json"

# Run Test Suite
# Unit + Feature tests
php artisan test

# With coverage report (requires Xdebug)
XDEBUG_MODE=coverage php artisan test --coverage-html storage/coverage








   
