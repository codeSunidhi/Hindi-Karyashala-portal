-- ==========================================
-- HINDI KARYASHALA MANAGEMENT SYSTEM
-- FINAL DATABASE
-- ==========================================

DROP DATABASE IF EXISTS karyashala;

CREATE DATABASE karyashala;

USE karyashala;

-- ==========================================
-- EMPLOYEE TABLE
-- ==========================================

CREATE TABLE employee (

    ic_no INT PRIMARY KEY,

    name VARCHAR(100) NOT NULL,

    phone VARCHAR(15),

    designation VARCHAR(100),

    email VARCHAR(120) UNIQUE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);

-- ==========================================
-- ROLES TABLE
-- ONLY LOGIN USERS
-- ==========================================

CREATE TABLE roles (

    ic_no INT PRIMARY KEY,

    password VARCHAR(255) NOT NULL,

    role ENUM('Admin','Karyashala Admin') NOT NULL,

    FOREIGN KEY (ic_no)
    REFERENCES employee(ic_no)
    ON DELETE CASCADE

);

-- ==========================================
-- WORKSHOPS TABLE
-- ==========================================

CREATE TABLE workshops (

    id INT AUTO_INCREMENT PRIMARY KEY,

    ic_no INT NOT NULL,

    workshop_year YEAR NOT NULL,

    workshop_name VARCHAR(150) DEFAULT 'Hindi Workshop',

    attended_date DATE NULL,

    attendance_status ENUM(

        'Pending',

        'Attended',

        'Absent'

    ) DEFAULT 'Pending',

    remarks TEXT,

    updated_by INT NULL,

    updated_at TIMESTAMP
    DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (ic_no)
    REFERENCES employee(ic_no)
    ON DELETE CASCADE,

    FOREIGN KEY (updated_by)
    REFERENCES employee(ic_no)
    ON DELETE SET NULL

);

-- ==========================================
-- REPORTS TABLE
-- ==========================================

CREATE TABLE reports (

    id INT AUTO_INCREMENT PRIMARY KEY,

    report_name VARCHAR(200),

    report_year YEAR,

    employee_ic INT NOT NULL,

    generated_by INT,

    generated_date TIMESTAMP
    DEFAULT CURRENT_TIMESTAMP,

    status ENUM(
        'Pending',
        'Verified'
    ) DEFAULT 'Pending',

    verified_by INT NULL,

    verified_date DATETIME NULL,

    json_path VARCHAR(255),

    FOREIGN KEY (employee_ic)
    REFERENCES employee(ic_no),

    FOREIGN KEY (generated_by)
    REFERENCES employee(ic_no),

    FOREIGN KEY (verified_by)
    REFERENCES employee(ic_no)

);

-- ==========================================
-- ACTIVITY LOG
-- ==========================================

CREATE TABLE activity_log (

    id INT AUTO_INCREMENT PRIMARY KEY,

    activity TEXT,

    activity_by INT,

    activity_date TIMESTAMP
    DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (activity_by)
    REFERENCES employee(ic_no)

);

-- ==========================================
-- EMPLOYEE DATA
-- ==========================================

INSERT INTO employee
(ic_no,name,phone,designation,email)

VALUES

(1001,'Rajesh Sharma','9876543210','Administrator','admin@gmail.com'),

(1002,'Hindi Cell','9876543211','Hindi Officer','hindicell@gmail.com'),

(1003,'Amit Kumar','9876500001','Assistant','amit@gmail.com'),

(1004,'Neha Sharma','9876500002','Officer','neha@gmail.com'),

(1005,'Rahul Singh','9876500003','Clerk','rahul@gmail.com'),

(1006,'Pooja Gupta','9876500004','Assistant','pooja@gmail.com'),

(1007,'Rohan Mehta','9876500005','Manager','rohan@gmail.com'),

(1008,'Anjali Verma','9876500006','Officer','anjali@gmail.com'),

(1009,'Suresh Kumar','9876500007','Clerk','suresh@gmail.com'),

(1010,'Deepak Singh','9876500008','Assistant','deepak@gmail.com');

-- ==========================================
-- LOGIN USERS
-- ==========================================

INSERT INTO roles
(ic_no,password,role)

VALUES

(1001,'admin123','Admin'),

(1002,'karya123','Karyashala Admin');

-- ==========================================
-- WORKSHOP DATA
-- ==========================================

INSERT INTO workshops
(ic_no,workshop_year,workshop_name,attended_date,attendance_status,remarks,updated_by)

VALUES

(1003,2025,'Hindi Workshop','2025-04-10','Attended','Excellent participation',1002),

(1004,2025,'Hindi Workshop','2025-04-12','Attended','Good communication',1002),

(1005,2025,'Hindi Workshop',NULL,'Pending','',NULL),

(1006,2025,'Hindi Workshop','2025-04-18','Attended','Very active',1002),

(1007,2025,'Hindi Workshop',NULL,'Absent','Absent from workshop',1002),

(1008,2025,'Hindi Workshop','2025-04-22','Attended','Good performance',1002),

(1009,2026,'Hindi Workshop',NULL,'Pending','',NULL),

(1010,2026,'Hindi Workshop',NULL,'Pending','',NULL);

-- ==========================================
-- SAMPLE REPORTS
-- ==========================================

INSERT INTO reports
(
    report_name,
    report_year,
    employee_ic,
    generated_by,
    generated_date,
    status,
    verified_by,
    verified_date,
    json_path
)

VALUES

(
    'Report_1003_2025',
    2025,
    1003,
    1002,
    NOW(),
    'Pending',
    NULL,
    NULL,
    'reports/report_1003_2025.json'
),

(
    'Report_1004_2025',
    2025,
    1004,
    1002,
    NOW(),
    'Verified',
    1001,
    NOW(),
    'reports/report_1004_2025.json'
);
-- ==========================================
-- SAMPLE ACTIVITY
-- ==========================================

INSERT INTO activity_log
(
    activity,
    activity_by
)

VALUES

(
    'Hindi Cell updated Amit Kumar workshop attendance',
    1002
),

(
    'Hindi Cell generated report for Amit Kumar',
    1002
),

(
    'Admin verified report of Neha Sharma',
    1001
),

(
    'Admin logged into the system',
    1001
);