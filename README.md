# CEB Meter Reading & Billing System - Setup Guide

## 1. Overview
The CEB Meter Reading & Billing System is a Laravel-based web application designed for the Ceylon Electricity Board.  
It allows meter readers to enter readings, customers to view their bills, and supports multilingual features.

---

## 2. Requirements
- PHP >= 8.1
- Composer
- MySQL >= 8.0
- Node.js & npm
- Laravel 12.x

---

## 3. Database Setup
1. Open **MySQL** in your terminal:
   ```bash
    mysql -u root -p
    Your_mysql_password
    CREATE DATABASE electricity_bill;
    EXIT;
## 4. Copy .env.example to .env
    ## cp .env.example .env
1. Open .env and update the database section:
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=ceb_billing_system
    DB_USERNAME=root
    DB_PASSWORD=Your_mysql_password
2. Install Dependencies
    composer install
    npm install
