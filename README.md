# Fullstack Engineer Assessment Test

This repository contains the solution for the Fullstack Engineer Assessment Test, consisting of two tasks:

1. **Task 1: Online Store API** — Laravel REST API with flash sale, stock management, race condition handling, and functional testing.
2. **Task 2: Hidden Item** — PHP command-line program for a hidden item game located in the `hidden-item` folder.

---

## Requirements

* PHP 8.2 or higher
* Composer
* MySQL or MariaDB
* Git

---

## Task 1: Online Store API

### Install Dependencies

```bash
composer install
```

### Configure Environment

Copy the example environment file and generate the application key:

```bash
cp .env.example .env
php artisan key:generate
```

### Configure Database

Edit the `.env` file and set your database credentials:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fullstack_assessment
DB_USERNAME=root
DB_PASSWORD=
```

### Run Migrations

```bash
php artisan migrate
```

### Run Seeder

```bash
php artisan db:seed
```

### Start the Development Server

```bash
php artisan serve
```

The API will be available at:

```text
http://127.0.0.1:8000
```

---

## API Endpoints

Import Test fomo.postman_collection.json to postman

---

## Running Functional Tests

This project includes a functional test to verify race condition handling during flash sale.

Run the test with:

```bash
php artisan test --filter=FlashSaleTest
```

The test verifies that:

* Product stock never becomes negative.
* Flash sale quota is not exceeded.
* Only the allowed quantity receives flash sale pricing.
* Remaining purchases are charged at normal price when the quota is exhausted.

---

## Flash Sale Logic

* A flash sale has a **start date**, **end date**, **discount price**, and **quota**.
* The `sold_qty` field tracks how many items have received the flash sale price.
* When the quota is exhausted, customers can still purchase the product at the normal price if stock is available.
* Database transactions and `lockForUpdate()` are used to prevent race conditions.

---

## Task 2: Hidden Item

The Hidden Item game is located in the `hidden-item` folder.

### Navigate to the Folder

```bash
cd hidden-item
```

### Run the Program

```bash
php hidden-item-test.php
```

