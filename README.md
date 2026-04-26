# 🎟️ EventBook
### A Full-Stack Event Booking System built with Laravel
> A real-world web application demonstrating relational database design, CRUD operations, cookie & session management, and role-based authentication — all within the Laravel MVC architecture.

[📹 Demo Video](https://drive.google.com/drive/folders/1KNWQmZVdx592WBAzi_KjZZn39IWhXuPZ) · 



## About the Project

**EventBook** is a full-stack web-based event booking system built with Laravel. It supports both regular users and administrators, enabling event browsing, ticket booking, and complete admin management of bookings, users, and events.

This project was developed to demonstrate real-world web development concepts in an academic context, including relational database design, CRUD operations, cookie & session management, and authentication & authorization.



## Features
### 1. User Side
- Browse and search events
- View event details (date, time, venue)
- Book tickets
- View booking history
- QR ticket generation

### 2. Admin Side
- Dashboard with statistics
- Manage events (CRUD)
- Manage categories
- Manage users & admins
- Manage bookings
- Filter, search, and sort bookings
- Cancel/Delete bookings

### Database Table
| # | Table | Description |
|---|-------|-------------|
| 1 | `users` | Registered user accounts |
| 2 | `admins` | Administrator accounts |
| 3 | `events` | Event listings |
| 4 | `categories` | Event categories |
| 5 | `bookings` | Ticket booking records |
| 6 | `tickets` | Generated QR tickets |
| 7 | `payments` | Payment transaction records |

### Relationships (Eloquent ORM)
```
User        → hasMany    → Bookings
Booking     → belongsTo  → User
Booking     → belongsTo  → Event
Event       → belongsTo  → Category
```
This relational structure ensures data consistency and enables efficient querying across the system.

### CRUD Operations
Full CRUD is implemented across the admin panel:
| Operation | Example |
|-----------|---------|
| **Create** | Add new events, users, bookings |
| **Read** | View listing pages and detailed records |
| **Update** | Edit event details, user information |
| **Delete** | Remove bookings or records |

### Authentication & Authorization
| Feature | Implementation |
|---------|---------------|
| Login system | Laravel session-based authentication |
| Route protection | Middleware (`auth`, `role`) |
| Access control | Admin-only routes and views |
| Password security | Bcrypt hashing via Laravel |

### Cookies
Cookies are used **only for non-sensitive admin preferences** to persist filter state across page visits:
| Cookie Key | Purpose |
|------------|---------|
| `q` | Remember the last search keyword |
| `status` | Remember the selected booking status filter |
| `per_page` | Remember the preferred pagination size |
| `sort` | Remember the selected sort order |

> Cookies store UI preferences only — no authentication or personal data is stored in cookies.

### Sessions
Sessions are used for secure, server-side state management:

| Usage | Description |
|-------|-------------|
| Authentication | Tracks login state across requests |
| CSRF Protection | Laravel's built-in token validation |
| Flash Messages | Displays one-time success/error alerts |



## 🔄 Booking Flow

```
1. User browses events
        ↓
2. User selects an event & books a ticket
        ↓
3. Booking record saved to database
        ↓
4. Payment is completed
        ↓
5. QR ticket generated automatically
        ↓
6. Admin monitors and manages all bookings
```

---


## Installation
**1. Clone the repository**
```bash
git clone https://github.com/christan7678/advanced-was.git
cd advanced-was
```

**2. Install dependencies**
```bash
composer install
npm install && npm run build
```

**3. Set up environment**
```bash
cp .env.example .env
php artisan key:generate
```

**4. Configure your database** in `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=event_booking_db
DB_USERNAME=root
DB_PASSWORD=
```

**5. Link storage & serve**
```bash
php artisan storage:link
php artisan serve
```



## Demo
[Click here to view the demo video and screenshots]
(https://drive.google.com/drive/folders/1KNWQmZVdx592WBAzi_KjZZn39IWhXuPZ?usp=sharing)


## Learning Outcomes
This project demonstrates practical application of:
- **Laravel MVC Architecture** — clean separation of Models, Views, and Controllers
- **Relational Database Design** — normalized schema with Eloquent relationships
- **CRUD with Eloquent ORM** — efficient database operations without raw SQL
- **Authentication & Authorization** — session-based login with middleware-protected routes
- **Cookies & Sessions** — real-world usage distinguishing non-sensitive preferences (cookies) from secure state (sessions)
- **Admin Dashboard UI** — role-based interface with filtering, sorting, and pagination



## Notes
- `vendor/` folder is excluded from the repository (run `composer install` to restore)

