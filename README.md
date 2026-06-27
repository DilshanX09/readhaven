# ReadHaven

ReadHaven is a full-functional PHP E-commerce web project tailored for buying and selling books. This repository serves as a template for building robust and scalable e-commerce platforms using core PHP, MySQL, HTML, CSS, and JavaScript.

## Features
- **User Authentication:** Sign up, sign in, forgot password, and OTP-based verification.
- **Product Management:** Admin dashboard to add, update, and manage books.
- **Shopping Cart & Wishlist:** Fully functional cart and wishlist for users.
- **Order Management:** Secure checkout, invoice generation, and order history.
- **Responsive Design:** Mobile-friendly UI using Bootstrap and custom CSS.
- **Admin Panel:** Manage users, products, orders, and view sales analytics.

## Tech Stack
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap
- **Backend:** PHP 8
- **Database:** MySQL
- **Libraries/Tools:** PHPMailer for email delivery, Chart.js for analytics

## Setup Instructions

### Prerequisites
- [XAMPP](https://www.apachefriends.org/index.html) (or any other local server setup like WAMP/MAMP)
- PHP 8.0+
- MySQL

### Standard Setup (Apache & MySQL)
1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/ReadHaven.git
   ```
2. **Move to Server Directory:** 
   Move the cloned `ReadHaven` folder to your server's root directory (e.g., `C:\xampp\htdocs\`).
3. **Database Configuration:**
   - Open phpMyAdmin (usually `http://localhost/phpmyadmin`).
   - Create a new database named `ebookstore`.
   - Import the provided SQL dump (if available) into the `ebookstore` database.
   - Configure the database connection in `connection.php`:
     ```php
     Database::$connection = new mysqli("localhost", "root", "your_password", "ebookstore", "3306");
     ```

### Dockerized Setup
You can also run ReadHaven using Docker and Docker Compose. A basic `docker-compose.yml` is included.
1. Make sure you have Docker and Docker Compose installed.
2. In the project root, run:
   ```bash
   docker-compose up -d
   ```
3. Access the application at `http://localhost:8080`.
4. The MySQL database will be available on port `3306` with root password `rootpassword`.

---

## Mail Configuration Guide

ReadHaven uses PHPMailer to send OTP codes and contact forms. Before using the application, you **must** configure your own SMTP credentials.

1. Locate the following files in the `back-end` directory:
   - `admin-authenticate-process.php`
   - `contact-us-process.php`
   - `otp-code-resend-process.php`
   - `send-reset-password-code-process.php`
2. Open each file and look for the SMTP configuration block.
3. Replace the placeholder strings with your actual email and App Password:
   - `<YOUR_EMAIL_HERE>`: Replace with your Gmail address (e.g., `youremail@gmail.com`).
   - `<YOUR_APP_PASSWORD_HERE>`: Replace with your 16-character Google App Password.

> **Note:** Do not use your regular Gmail password. You must generate an **App Password** from your Google Account settings (requires 2-Step Verification to be enabled).

## License
This project is open-source and available under the [MIT License](LICENSE).
