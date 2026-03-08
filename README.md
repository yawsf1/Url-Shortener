# shortRL — URL Shortener

A modern, full-stack URL shortener built with **Laravel 12**, **Vue 3**, and **Inertia.js**.

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 12 (PHP) |
| Frontend | Vue 3 + Inertia.js |
| Styling | Custom CSS (Poppins & Unbounded fonts, Font Awesome icons) |
| Build Tool | Vite |
| Database | MySQL / SQLite |
| Cache | Redis |

---

## Features

### URL Shortening
- Shorten any long URL with a custom name
- Shortened URLs redirect to their original destination instantly
- Each URL card displays the name, original URL, and shortened URL side by side

### Authentication
- **Register** with name, email, and password
- **Login** with email and password
- **Remember Me** toggle — persists login session using Laravel's long-lived remember token
- **Logout** with session flash confirmation
- Password field eye icon toggle to show/hide password on both Login and Register pages

### Security
- Rate limiting on login and register routes (`throttle:5,1` — 5 attempts per minute)
- When the rate limit is hit, a friendly flash error message is shown automatically via Laravel's exception handler
- Passwords validated against the **Have I Been Pwned** database — rejects compromised passwords
- Session regeneration after login to prevent session fixation attacks
- CSRF protection on all forms via Inertia

### Flash Notifications
- Success and error flash messages displayed at the top of the page
- Auto-dismiss after 3 seconds
- Covers: login, logout, register, invalid credentials, rate limiting, and URL operations

### URL Management
- Authenticated users can delete any of their shortened URLs
- Guest users can remove URLs from their local list
- All URL cards show name, original URL, and shortened link with clickable anchors

### Redis Caching
- Shortened URL redirects are cached in **Redis** for **1 hour** (TTL: 3600s)
- On redirect, the app checks Redis first — if the key exists, it skips the database entirely
- On a cache miss, it queries the database and stores the result in Redis for subsequent requests
- Cache key format: `url:{shortCode}`
- Significantly reduces database load for frequently accessed URLs

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php       # Register, Login, Logout
│   │   └── UrlController.php        # Shorten, Redirect, Delete
│   └── Requests/
│       ├── LoginRequest.php         # Validation: email, password, remember
│       └── RegisterRequest.php      # Validation: name, email, password + uncompromised check
├── Models/
│   ├── User.php
│   └── Url.php
resources/
└── js/
    ├── Pages/
    │   ├── Home.vue                 # Main shortener page
    │   ├── Login.vue                # Login form
    │   └── Register.vue             # Register form
    └── Layouts/
        └── Layout.vue               # Shared navbar layout
routes/
└── web.php                          # All application routes
bootstrap/
└── app.php                          # Exception handler (rate limit flash)
database/
└── migrations/                      # users, urls, sessions tables
```

---

## Database Schema

### `users`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| name | string | |
| email | string | Unique |
| password | string | Hashed |
| avatar | string | Nullable |
| remember_token | string | For "Remember Me" |
| timestamps | | |

### `urls`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| name | string | Custom label |
| original_url | string | Full URL |
| short_url | string | Generated short code |
| user_id | foreignId | Nullable (guest URLs have null) |
| timestamps | | |

---

## Installation

```bash
# Clone the repo
git clone https://github.com/yawsf1/Url-Shortener.git
cd Url-Shortener

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Set up environment
cp .env.example .env
php artisan key:generate

# Configure your database and Redis in .env
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

# Then run migrations
php artisan migrate

# Start dev servers
php artisan serve
npm run dev
```

---

## Routes

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/` | Home page |
| POST | `/shorten` | Create a shortened URL |
| DELETE | `/shorten/{id}` | Delete a URL |
| GET | `/{shortCode}` | Redirect to original URL |
| GET | `/register` | Register page |
| POST | `/auth/register` | Handle registration |
| GET | `/login` | Login page |
| POST | `/auth/login` | Handle login |
| POST | `/auth/logout` | Handle logout |

> Login and Register routes are rate limited to **5 requests per minute** per IP.

---

## License

MIT
