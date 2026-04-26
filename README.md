## About EventBook (Event Booking System)

EventBook is a full-stack web-based event booking system built with Laravel.
It supports both users and administrators, allowing event browsing, ticket booking, and full admin management of bookings, users, and events.

The system demonstrates real-world web development concepts, including:

- Relational Database Design
- CRUD Operations
- Cookies & Sessions
- Authentication & Authorization


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


## Security Features 
- Laravel Authentication (session-based)
- Role-based Authorization (admin, super_admin)
- CSRF Protection
- Session Regeneration (prevents session fixation)
- Password hashing


## System Architecture
MVC Pattern
1. Model → Database logic  
2. View → UI (Blade)
3. Controller → Business logic  


## Project Structure
app/Http/Controllers/Admin/
resources/views/admin/
public/css/admin/
public/js/
routes/web.php


## Database Table
1. users
2. admins
3. events
4. categories
5. bookings
6. tickets
7. payments

## Booking Flow
User selects event
      ↓
User books ticket
      ↓
Booking saved in database
      ↓
Admin manages booking
      ↓
Ticket generated (QR)


## Installation
### 1. Clone Repository
### 2. Install dependencies
### 3. Setup environment
### 4. Configure database
### 5. Run server


## Demo Guide
- **Link: ([https://vehikl.com/](https://drive.google.com/drive/folders/1KNWQmZVdx592WBAzi_KjZZn39IWhXuPZ?usp=sharing))**


## Learning Outcomes
This project demonstrates:

- Laravel MVC architecture
- Secure authentication & authorization
- CRUD operations with Eloquent ORM
- Real-world usage of cookies & sessions
- Admin dashboard UI design


## Notes
- vendor/ folder excluded (as required)
- Cookies used only for non-sensitive data
- Sessions handle secure authentication
