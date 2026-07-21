# Hindi Karyashala Portal

A web-based **Workshop Management System** developed using **PHP, MySQL, HTML, CSS, and JavaScript**. The application is designed to manage employee workshop information, attendance, report generation, and report verification through role-based access.

---

# Project Description

The Hindi Karyashala Portal is developed to simplify the management of workshop activities conducted for employees.

The system has two user roles:

- **Administrator**
- **Karyashala Administrator**

The Karyashala Administrator updates employee workshop details and generates workshop reports. These reports are then sent to the Administrator for verification. The Administrator can also perform every task available to the Karyashala Administrator along with employee management and report verification.

---

# Technologies Used

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript
- Font Awesome
- XAMPP

---

# Features

## Administrator

### Dashboard

- Total Employees
- Pending Reports
- Verified Reports
- Workshop Attendance
- Pending Attendance
- Absent Employees
- Attendance Pie Chart
- Workshop Statistics

### Employee Management

- Add Employee
- View Employees
- Update Employee Details

### Report Management

- View Generated Reports
- Verify Reports
- Verification History

---

## Karyashala Administrator

### Dashboard

Displays workshop statistics.

### Workshop Management

- View Employees
- Update Workshop Details
- Update Attendance
- Add Remarks
- Generate Workshop Report

Generated reports are automatically submitted to the Administrator for verification.

---

# Workflow

```
Administrator
      │
      ▼
Adds Employee
      │
      ▼
Karyashala Administrator
      │
      ▼
Updates Workshop Details
      │
      ▼
Generates Workshop Report
      │
      ▼
Administrator Reviews Report
      │
      ▼
Administrator Verifies Report
      │
      ▼
Verification History Updated
```

---

# Project Structure

```
Hindi-Karyashala/
│
├── index.php
├── login.php
├── logout.php
├── README.md
│
├── admin/
│   ├── add_employee.php
│   ├── dashboard.php
│   ├── history.php
│   ├── reports.php
│   ├── save_employee.php
│   ├── update.php
│   ├── update_process.php
│   ├── update_save.php
│   ├── verify.php
│   ├── view.php
│   └── view_report.php
│
├── config/
│   └── db.php
│
├── css/
│   ├── form.css
│   ├── layout.css
│   ├── login.css
│   ├── modal.css
│   └── table.css
│
├── employee/
│   ├── dashboard.php
│   ├── generate_report.php
│   ├── update.php
│   ├── update_workshop.php
│   └── view.php
│
├── images/
│   └── background.jpg
│
├── includes/
│   ├── auth.php
│   ├── employee_sidebar.php
│   ├── navbar.php
│   └── sidebar.php
│
├── js/
│   ├── dashboard.js
│   ├── modal.js
│   ├── search.js
│   └── validation.js
│
└── sql/
    └── karyashala.sql
```

---

# Database Tables

The project uses the following database tables:

## users

Stores login credentials.

Fields include:

- IC Number
- Username
- Password
- Role

Roles:

- Admin
- Karyashala Admin

---

## employees

Stores employee details.

Fields include:

- IC Number
- Name
- Phone
- Designation
- Email

---

## workshops

Stores workshop information.

Fields include:

- Employee IC
- Workshop Name
- Workshop Year
- Attendance Date
- Attendance Status
- Remarks
- Updated By

---

## reports

Stores generated workshop reports.

Fields include:

- Employee IC
- Workshop Details
- Report Status
- Generated Date
- Verified Date
- Verified By

---

## activity_log

Stores system activities such as:

- Employee Added
- Employee Updated
- Workshop Updated
- Report Generated
- Report Verified

---

# Installation

## Step 1

Install **XAMPP**.

---

## Step 2

Copy the project folder into:

```
C:\xampp\htdocs\Hindi-Karyashala
```

---

## Step 3

Start:

- Apache
- MySQL

---

## Step 4

Open **phpMyAdmin**.

Create a database named:

```
karyashala
```

Import:

```
sql/karyashala.sql
```

---

## Step 5

Update the database configuration in:

```
config/db.php
```

Example:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "karyashala";
```

---

## Step 6

Run the project:

```
http://localhost/Hindi-Karyashala/
```

---

# User Roles Summary

| Feature | Administrator | Karyashala Administrator |
|----------|--------------|--------------------------|
| Dashboard | ✅ | ✅ |
| Add Employee | ✅ | ❌ |
| View Employees | ✅ | ✅ |
| Update Employee | ✅ | ❌ |
| Update Workshop Details | ✅ | ✅ |
| Generate Report | ✅ | ✅ |
| Verify Reports | ✅ | ❌ |
| Verification History | ✅ | ❌ |

---

# Future Enhancements

- PDF Report Generation
- Export Reports to Excel
- Email Notification on Verification
- Advanced Search & Filters
- Password Reset
- Audit Logging
- Mobile Responsive Improvements
- Report Analytics Dashboard

---

# Developed By

**Hindi Karyashala Portal**

Workshop Management System

Developed as a PHP & MySQL academic project.

---

# License

This project is intended for educational and academic purposes.