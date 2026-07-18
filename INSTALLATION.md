# ⚙️ Installation Guide — AttendTrack Pro

Follow these simple steps to install, configure, and launch the **AttendTrack Pro** Attendance Management System locally on your machine.

---

## 📋 Prerequisites

Make sure you have **XAMPP** installed. If not, download and install it from the official site:
*   [Download XAMPP for Windows](https://www.apachefriends.org/index.html)

XAMPP comes bundled with **Apache HTTP Server**, **MySQL Database**, and a **PHP interpreter** — which are all required for this project.

---

## 🛠️ Installation Steps

### Step 1: Copy Project files
Download or clone this project and place the directory inside the htdocs root folder of XAMPP:
```
C:\xampp\htdocs\shubhangi\
```
Ensure all directories (`config/`, `admin/`, `api/`, `assets/`, `includes/`) are positioned directly within this folder.

### Step 2: Open XAMPP Control Panel
1. Locate and launch the **XAMPP Control Panel** from your Windows Start Menu.
2. Click the **Start** button next to **Apache** server.
3. Click the **Start** button next to **MySQL** database.
4. Verify both display green background markers indicating active running status.

### Step 3: Launch in Web Browser
Open your preferred browser (Google Chrome, Microsoft Edge, Mozilla Firefox) and navigate to the project address:
```
http://localhost/shubhangi/
```

### Step 4: Automatic Database Setup
AttendTrack Pro features **Auto-Initialization logic**:
*   On your very first page load, the application will detect if the database or tables are missing.
*   It will automatically create the database `attendance_db` and all mandatory tables and default seed records.
*   **No manual SQL import via phpMyAdmin is necessary!**

---

## 🔑 Logging In

Once the page loads, click **Login** in the top navbar and log in using either account:

### 1. System Administrator
*   **Username:** `admin`
*   **Password:** `admin123`

### 2. Demo Instructor / Teacher
*   **Username:** `teacher`
*   **Password:** `teacher123`

---

## 🔍 Troubleshooting

1.  **Database Connection Refused:**
    *   Ensure the MySQL Service is actively started inside the XAMPP Control Panel.
    *   Verify the MySQL server port matches default port `3306`.
2.  **Displaying CSS/JS Load Errors:**
    *   Confirm the project folder matches casing precisely (`shubhangi`).
    *   Verify URL rewritten settings if using customized Apache hosts.
