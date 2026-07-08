DROP DATABASE IF EXISTS karyashala;
CREATE DATABASE karyashala;
USE karyashala;

-- ===========================
-- ADMIN TABLE
-- ===========================

CREATE TABLE admin (
    ic_no INT PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15),
    email VARCHAR(100) UNIQUE
);

INSERT INTO admin VALUES
(1001,'admin123','Rajesh Sharma','9876543210','rajesh@gmail.com'),
(1002,'admin123','Priya Verma','9876543211','priya@gmail.com');

-- ===========================
-- KARYASHALA ADMIN
-- ===========================

CREATE TABLE karyashala_admin (
    ic_no INT PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15),
    email VARCHAR(100) UNIQUE
);

INSERT INTO karyashala_admin VALUES
(2001,'karya123','Hindi Cell','9876501001','hindicell@gmail.com'),
(2002,'karya123','Workshop Cell','9876501002','workshop@gmail.com');

-- ===========================
-- EMPLOYEE
-- ===========================

CREATE TABLE employee (

    ic_no INT PRIMARY KEY,

    name VARCHAR(100) NOT NULL,

    designation VARCHAR(100),

    phone VARCHAR(15),

    email VARCHAR(100) UNIQUE

);

INSERT INTO employee VALUES

(1003,'Amit Kumar','Assistant','9876500001','amit@gmail.com'),
(1004,'Neha Sharma','Officer','9876500002','neha@gmail.com'),
(1005,'Rahul Singh','Clerk','9876500003','rahul@gmail.com'),
(1006,'Pooja Gupta','Assistant','9876500004','pooja@gmail.com'),
(1007,'Rohan Mehta','Manager','9876500005','rohan@gmail.com'),
(1008,'Anjali Verma','Officer','9876500006','anjali@gmail.com'),
(1009,'Suresh Kumar','Clerk','9876500007','suresh@gmail.com'),
(1010,'Deepak Singh','Assistant','9876500008','deepak@gmail.com');

-- ===========================
-- WORKSHOPS
-- ===========================

CREATE TABLE workshops (

    id INT AUTO_INCREMENT PRIMARY KEY,

    ic_no INT NOT NULL,

    title VARCHAR(100) DEFAULT 'Hindi Workshop',

    attended_date DATE,

    FOREIGN KEY(ic_no)
    REFERENCES employee(ic_no)
    ON DELETE CASCADE

);

INSERT INTO workshops(ic_no,title,attended_date) VALUES

(1003,'Hindi Workshop','2025-04-10'),
(1003,'Hindi Workshop','2026-04-15'),

(1004,'Hindi Workshop','2025-04-12'),

(1005,'Hindi Workshop','2026-04-16'),

(1006,'Hindi Workshop','2025-04-10'),
(1006,'Hindi Workshop','2026-04-15'),

(1007,'Hindi Workshop','2026-04-18'),

(1008,'Hindi Workshop','2025-04-09'),

(1009,'Hindi Workshop','2025-04-11'),
(1009,'Hindi Workshop','2026-04-16'),

(1010,'Hindi Workshop','2026-04-20');

-- ===========================
-- REPORTS
-- ===========================

CREATE TABLE reports (

    id INT AUTO_INCREMENT PRIMARY KEY,

    report_name VARCHAR(150),

    generated_by INT,

    generated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    approved ENUM('Yes','No') DEFAULT 'No',

    approved_by INT NULL

);