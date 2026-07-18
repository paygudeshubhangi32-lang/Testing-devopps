# 📋 AttendTrack Pro — Attendance Management System

AttendTrack Pro is a modern, responsive, and secure web application designed to simplify and streamline attendance tracking for educational institutions.

Built using vanilla **PHP**, **MySQL (PDO)**, and **Bootstrap 5.3**, it features native dark mode support, fluid custom animations, comprehensive analytics, role-based access, and client/server-side validation checks.

---

## 🚀 Key Features

*   **Modern Frontend Design:** Styled with Bootstrap 5.3, Inter font, custom glassmorphism effects, and premium smooth animations.
*   **Theme Switcher:** Native Dark Mode toggle with persistent memory (`localStorage`).
*   **Interactive Dashboard:** View student, class, and subject statistics instantly. Integrated with **Chart.js** for trend lines and distribution doughnut charts.
*   **Smart Attendance marking:** Fetches student list dynamically via AJAX, allowing bulk status toggles (Mark All Present/Absent) and custom remarks.
*   **Secure API endpoints:** Sanitized input processing and transactional database operations to prevent data duplicate collisions.
*   **Report Analytics:** Generate date-range queries filterable by classes and export reports as clean standard **CSV** spreadsheets.
*   **Strict Security Safeguards:** Protected from SQL Injection (via PDO prepared statements), XSS injection attacks (`htmlspecialchars` escaping), CSRF form attacks, and Session Hijacking.
*   **Custom Error handling:** Handcrafted beautiful `404 Not Found` and `500 Server Error` redirect fallback templates.

---

## 🛠️ Technology Stack

*   **Backend:** PHP 8.x
*   **Database:** MySQL
*   **Styling:** Bootstrap 5.3 + custom CSS3 variables
*   **Scripting:** Vanilla JS (ES6) + jQuery (only for DataTables component integration)
*   **Visual Elements:** Bootstrap Icons, Chart.js

---

## 📂 Project Directory Structure

```
c:\xampp\htdocs\shubhangi\
├── index.php                    # Landing page
├── login.php                    # Login page
├── register.php                 # Registration page
├── logout.php                   # Logout handler
├── about.php                    # About page
├── contact.php                  # Contact page
├── faq.php                      # FAQ page
├── 404.php                      # Custom 404 page
├── 500.php                      # Custom 500 page
├── .htaccess                    # Apache URL rewrites & headers
│
├── config/
│   ├── database.php             # DB connection (PDO)
│   ├── app.php                  # App constants & config
│   └── init.php                 # Auto-create DB & tables if missing
│
├── admin/
│   ├── dashboard.php            # Admin dashboard
│   ├── students.php             # Student CRUD
│   ├── classes.php              # Class management
│   ├── subjects.php             # Subject management
│   ├── attendance.php           # Mark & view attendance
│   ├── reports.php              # Attendance reports & export
│   ├── users.php                # User management
│   ├── settings.php             # App settings
│   └── profile.php              # Profile management
│
├── api/
│   ├── students.php             # Student CRUD AJAX endpoint
│   ├── classes.php              # Class CRUD AJAX endpoint
│   ├── subjects.php             # Subject CRUD AJAX endpoint
│   ├── attendance.php           # Attendance mark/retrieve endpoint
│   ├── reports.php              # Report download CSV API
│   └── auth.php                 # Dynamic registration checks
│
├── includes/
│   ├── header.php               # HTML navbar, CSS variables
│   ├── footer.php               # Custom footer and scripts
│   ├── sidebar.php              # Sidebar navigation
│   ├── loader.php               # Page loader widget
│   ├── toast.php                # SnackBar templates
│   ├── auth_check.php           # Session check middleware
│   └── functions.php            # Security sanitizers and counters
│
└── database.sql                 # Database backup dump
```

---

## 🔑 Default Login Credentials

| Role | Username | Password |
|---|---|---|
| Admin | `admin` | `admin123` |
| Teacher | `teacher` | `teacher123` |

---

## 📄 License & Credits

Designed and implemented with 💖 by **Shubhangi** for modern classroom attendance needs.
For comprehensive guide, check out [INSTALLATION.md](file:///c:/xampp/htdocs/shubhangi/INSTALLATION.md) and [DOCUMENTATION.md](file:///c:/xampp/htdocs/shubhangi/DOCUMENTATION.md).
