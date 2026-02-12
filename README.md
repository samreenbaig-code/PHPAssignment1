
## Description
This is a PHP and MySQL Task Manager application built using XAMPP.
It implements CRUD functionality similar to the Contact Manager covered in class.

## Features
- Add tasks
- View tasks
- Edit tasks
- Delete tasks

## Tech Used
- PHP
- MySQL (MariaDB)
- XAMPP
- phpMyAdmin

## How to Run
1. Start Apache and MySQL in XAMPP
2. Place project in htdocs
3. Open http://localhost/PHPAssignment1/
<<<<<<< HEAD
=======
## Assignment 2
This assignment continues Assignment 1 by ensuring full CRUD
functionality (Add, Update, Delete) using PHP and MySQL.
No image upload or placeholder logic is implemented, as discussed in class.
>>>>>>> 396ce5d (Assignment 2: add update delete and UI improvements)
## PHP Assignment 3 – Task Manager

Features:
- CRUD operations
- Categories table with foreign key
- Image upload
- Task details page
- Category dropdown (dynamic)
- MySQL database included (phpassignment1.sql)

How to run:
1. Import phpassignment1.sql into phpMyAdmin
2. Place project in htdocs
3. Open http://localhost/PHPAssignment1
  ## 🚀 Assignment 4 Updates
#🔐 Authentication System

User registration with password hashing (password_hash)

User login with password_verify

Session-based authentication

Logout functionality

Protected pages (redirect if not logged in)

## 🖼 Image Management

Automatically deletes old image when updating a task

Deletes image file when task is removed

Placeholder image is never deleted

## 🛡 Security Improvements

Prepared statements (MySQLi)

Input validation and sanitization

Required field validation

Duplicate email prevention

Protected against SQL injection

## 🔍 Additional Features

Search functionality (search by task title)

Foreign key relationship between tasks and categories

Clean UI improvements

## ⭐ OPTIONAL (For Extra Marks)

Add this too:

## 🗄 Database Structure
Users Table

id (INT, Primary Key)

name (VARCHAR)

email (UNIQUE)

password (Hashed)

created_at (Timestamp)

