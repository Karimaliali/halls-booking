# Halls Booking System API

## 📋 Project Description

**Halls Booking System** is a comprehensive REST API for managing event hall bookings and reservations. It allows hall owners to manage their properties and clients to discover and book available halls. The system includes features for checking availability, blocking dates, confirming bookings, and managing hall information.

This is a modern Laravel-based API built with best practices for authentication, authorization, and real-time availability tracking.

**Note:** Frontend has been separated into a dedicated project at `../halls-frontend/` for better architecture.

---

## ✨ Features

### For Clients (Customers)

- 🔍 **Browse Available Halls** - View all available event halls with details
- 📅 **Check Availability** - Check hall availability for specific dates
- 🎫 **Book Halls** - Create new booking requests
- 📊 **View My Bookings** - Track booking history and status
- 🔐 **User Account** - Register and manage profile

### For Hall Owners

- 🏢 **Manage Halls** - Create, update, and delete hall information
- 📅 **Block Dates** - Mark dates when halls are unavailable
- ✅ **Confirm Bookings** - Accept or manage booking requests
- 👥 **View Bookings** - Track all bookings for their halls
- 📊 **Booking Management** - Confirm payment and manage reservations

### System Features

- 🔐 **JWT Authentication** - Secure API using Laravel Sanctum tokens
- 🛡️ **Role-Based Access Control** - Separate permissions for Admin, Owner, and Customer
- 📖 **Interactive API Documentation** - Swagger/OpenAPI UI for all endpoints
- ✔️ **Input Validation** - Comprehensive request validation
- 📱 **RESTful Design** - Standard REST conventions

---

## 🔧 Tech Stack

| Component             | Technology          |
| --------------------- | ------------------- |
| **Backend Framework** | Laravel 11          |
| **Authentication**    | Laravel Sanctum     |
| **Database**          | MySQL 8.0+          |
| **API Documentation** | Swagger/OpenAPI 3.0 |
| **Server**            | PHP 8.2+            |
| **Package Manager**   | Composer            |

**Key Dependencies:**

- `laravel/sanctum` - API authentication
- `darkaonline/l5-swagger` - Swagger documentation
- `laravel/framework` - Core framework

---

## 📥 Installation Steps

### Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- XAMPP or similar local server environment

### Step 1: Clone and Setup

```bash
# Navigate to your local project directory
cd c:\xampp\htdocs\halls-booking

# Install PHP dependencies
composer install
```

### Step 2: Environment Configuration

```bash
# Copy the example environment file
cp .env.example .env

# Or manually create .env with these settings:
APP_NAME=HallsBooking
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=halls_booking
DB_USERNAME=root
DB_PASSWORD=

# JWT Token Secret (generate new key)
php artisan key:generate
```

### Step 3: Database Setup

```bash
# Create database in MySQL
# mysql> CREATE DATABASE halls_booking;

# Run migrations and seeders
php artisan migrate --seed

# Or run migrations only (without default seeders)
php artisan migrate
```

### Step 4: Start Development Server

```bash
# Start the Laravel development server
php artisan serve --host=127.0.0.1 --port=8000

# Server will be available at: http://127.0.0.1:8000
```

### Step 5: Generate Swagger Documentation

```bash
php artisan l5-swagger:generate
```

---

## 📚 API Documentation

### Swagger UI

Access the interactive API documentation at:

```
http://127.0.0.1:8000/api/documentation
```

The Swagger UI provides:

- 📖 Complete endpoint documentation
- 🧪 Try-it-out functionality to test endpoints
- 📝 Request/response examples
- 🔍 Schema definitions

---

## 🔐 Authentication

All protected endpoints require an **Authorization** header with a Bearer token.

### Authentication Header Format

```
Authorization: Bearer YOUR_TOKEN_HERE
```

### Example Request

```bash
curl -X GET http://127.0.0.1:8000/api/halls \
  -H "Authorization: Bearer 10|I1fGYvJ6zGkxrwdh1u3qYBeR81A..."
```

### Getting a Token

1. Register a new user via `POST /api/register`
2. Login via `POST /api/login`
3. Use the returned `token` in the Authorization header

---

## 👥 Default Test Accounts

### For Testing

After running seeders, you can use these accounts:

#### Hall Owner Account

```
Email:    owner@example.com
Password: password123
Role:     owner
```

#### Customer Account

```
Email:    customer@example.com
Password: password123
Role:     customer
```

#### Admin Account

```
Email:    admin@example.com
Password: password123
Role:     admin
```

---

## 🚀 API Endpoints Overview

### Authentication Endpoints

| Method | Endpoint        | Description            |
| ------ | --------------- | ---------------------- |
| POST   | `/api/register` | Register new user      |
| POST   | `/api/login`    | Login user             |
| POST   | `/api/logout`   | Logout (requires auth) |

### Hall Endpoints

| Method | Endpoint          | Description     | Auth Required |
| ------ | ----------------- | --------------- | ------------- |
| GET    | `/api/halls`      | Get all halls   | No            |
| POST   | `/api/halls`      | Create new hall | Yes (Owner)   |
| PUT    | `/api/halls/{id}` | Update hall     | Yes (Owner)   |
| DELETE | `/api/halls/{id}` | Delete hall     | Yes (Owner)   |

### Booking Endpoints

| Method | Endpoint                     | Description             | Auth Required     |
| ------ | ---------------------------- | ----------------------- | ----------------- |
| GET    | `/api/check-availability`    | Check hall availability | No                |
| POST   | `/api/bookings`              | Create booking          | Yes (Customer)    |
| POST   | `/api/bookings/{id}/confirm` | Confirm booking payment | Yes (Owner/Admin) |
| GET    | `/api/my-bookings`           | Get customer bookings   | Yes (Customer)    |
| GET    | `/api/owner/bookings`        | Get owner bookings      | Yes (Owner)       |

### Additional Endpoints

| Method | Endpoint                | Description            | Auth Required |
| ------ | ----------------------- | ---------------------- | ------------- |
| POST   | `/api/owner/block-date` | Block unavailable date | Yes (Owner)   |
| GET    | `/api/status`           | API status check       | No            |

---

## 📝 Example Requests

### Register New User

```bash
curl -X POST http://127.0.0.1:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "customer"
  }'
```

### Login

```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### Create Hall (Owner)

```bash
curl -X POST http://127.0.0.1:8000/api/halls \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "name": "Grand Ballroom",
    "price": 5000,
    "location": "Cairo - Maadi",
    "capacity": 300,
    "main_image": "image.jpg"
  }'
```

### Update Hall (Owner)

```bash
curl -X PUT http://127.0.0.1:8000/api/halls/1 \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "name": "Grand Ballroom - Updated",
    "price": 6000
  }'
```

### Delete Hall (Owner)

```bash
curl -X DELETE http://127.0.0.1:8000/api/halls/1 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Get All Halls

```bash
curl -X GET http://127.0.0.1:8000/api/halls
```

### Check Availability

```bash
curl -X GET "http://127.0.0.1:8000/api/check-availability?hall_id=1&start_date=2026-02-15&end_date=2026-02-20"
```

---

## 🔑 Key Features in Detail

### Role-Based Access Control

- **Admin**: Full system access
- **Owner**: Manage their own halls and bookings
- **Customer**: Browse halls and create bookings

### Authorization Rules

- Owners can only **update/delete** their own halls
- Customers can only view **their own bookings**
- Booking confirmation requires owner authentication

### Database Relations

- Users → Halls (One-to-Many)
- Users → Bookings (One-to-Many)
- Halls → Bookings (One-to-Many)

---

## 🧪 Testing

### Run Tests

```bash
php artisan test
```

### Test API Manually

Use the provided PowerShell test scripts:

```bash
# Test all endpoints
powershell -ExecutionPolicy Bypass .\test_api.ps1

# Test PUT/DELETE endpoints specifically
powershell -ExecutionPolicy Bypass .\test_put_delete.ps1
```

---

## 📁 Project Structure

```
halls-booking/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # API endpoints
│   │   └── Middleware/      # Role checking
│   ├── Models/              # Database models
│   └── Notifications/       # Event notifications
├── routes/
│   └── api.php              # API routes
├── database/
│   ├── migrations/          # Database schemas
│   └── seeders/             # Sample data
├── storage/
│   └── api-docs/           # Swagger documentation
├── tests/                   # Unit & feature tests
└── README.md               # This file
```

---

## 🐛 Troubleshooting

### Database Connection Error

- Verify MySQL is running
- Check `.env` database credentials
- Ensure database exists: `CREATE DATABASE halls_booking;`

### Swagger Documentation Not Loading

```bash
php artisan l5-swagger:generate
```

### Token Expired or Invalid

- Generate a new token via login endpoint
- Ensure token is properly included in Authorization header

### 405 Method Not Allowed Error

- Verify the HTTP method (GET, POST, PUT, DELETE)
- Check that authentication header is included for protected routes

---

## 📞 Support & Contact

For issues or questions:

1. Check the Swagger documentation at `/api/documentation`
2. Review the troubleshooting section above
3. Check the Laravel logs at `storage/logs/laravel.log`

---

## 📄 License

This project is built for educational purposes.

---

## ✅ Checklist

- [x] RESTful API endpoints
- [x] Role-based access control
- [x] JWT authentication (Sanctum)
- [x] Swagger/OpenAPI documentation
- [x] Database migrations
- [x] Input validation
- [x] Error handling
- [x] Update and Delete functionality for halls
- [x] Authorization checks (Only owner can modify their halls)

---

**Last Updated**: February 11, 2026
**API Version**: 1.0.0
