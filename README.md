# Data Scraping Project

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/your-username/data-scraping/actions"><img src="https://github.com/your-username/data-scraping/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About the Project

This is a simple **Laravel-based web scraping project** that collects data from the **NTRCA Teletalk Results** website and stores the scraped data into a local MySQL database. The project is mainly composed of a single function that scrapes and saves the data.

### Key Features:

- Scrapes data for a range of roll numbers.
- Posts requests to **[Teletalk Results](http://ntrca.teletalk.com.bd/result/index.php)** using the roll number and exam parameters.
- Saves successfully scraped data (containing "CONGRATULATIONS") in a database table called scraped_data.
- Designed for easy execution through a controller function.

## Installation

Follow these steps to set up the project on your local machine.

### Prerequisites

- PHP >= 8.2
- Laravel >= 12.0
- Composer
- MySQL (or any other database supported by Laravel)

### Step 1: Clone the repository

Clone the repository and navigate into the project directory using the following command:

    git clone https://github.com/ramananda1110/Data-Scraping-Project.git
    cd data-scraping

### Step 2: Install dependencies
Run the following command to install the required dependencies via Composer:

    composer install

### Step 3: Configure environment variables
Create a .env file by copying the .env.example file:
    
    cp .env.example .env

### Step 4: Set up the database
Update your .env file with the database configuration. For example:
    
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password

### Step 5: Run database migrations
Run the following command to migrate the database:
    
    php artisan migrate

### Step 6: Run the application
Finally, serve the application:
    
    php artisan serve

Visit http://127.0.0.1:8000/scrape-data in your browser to see the application running.

### Conclusion
This project efficiently scrapes data from an external website. The scraped data is then stored in a MySQL database for easy access and management. The system automates the process, ensuring smooth and reliable data collection.
