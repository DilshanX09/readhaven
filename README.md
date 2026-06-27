<div align="center">

<pre>
  _____                _ _    _                     
 |  __ \              | | |  | |                    
 | |__) |___  __ _  __| | |__| | __ ___   _____ _ __ 
 |  _  // _ \/ _` |/ _` |  __  |/ _` \ \ / / _ \ '_ \
 | | \ \  __/ (_| | (_| | |  | | (_| |\ V /  __/ | | |
 |_|  \_\___|\__,_|\__,_|_|  |_|\__,_| \_/ \___|_| |_|
</pre>

The complete full-functional PHP-based e-commerce web architecture for modern bookstores.

<br />

[![PHP](https://img.shields.io/badge/PHP-v8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-v8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com/)
[![Apache](https://img.shields.io/badge/Apache-v2.4-D22128?style=for-the-badge&logo=apache&logoColor=white)](https://httpd.apache.org/)
[![Docker](https://img.shields.io/badge/Docker-Integrated-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://docker.com/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)

</div>

---

## Project Overview

**ReadHaven** is a comprehensive web application project focusing on structured backend management, clean separation of concerns, and full e-commerce functionality. Developed as a 1st-year project, it showcases a robust architecture designed for modern bookstores, offering a seamless shopping experience for users and powerful management tools for administrators.

## Core Features & Tech Stack

### Features
*   **User Authentication**: Secure login, registration, and session management.
*   **Dynamic Product Catalog**: Browse, search, and filter a vast collection of books.
*   **Shopping Cart**: Intuitive cart management with real-time updates.
*   **Secure Checkout**: Streamlined checkout process for finalizing purchases.
*   **Automated Mail Notifications**: Order confirmations and account alerts powered by PHPMailer.
*   **Admin Control Panel**: Comprehensive dashboard for managing products, users, and orders.

### Tech Stack
*   **Backend**: PHP
*   **Database**: MySQL
*   **Frontend**: HTML, JavaScript, Tailwind CSS
*   **Deployment/Environment**: Docker, Apache

## Local Infrastructure Setup (Standard)

To run ReadHaven locally using a standard setup (XAMPP/WAMP):

1.  **Clone the repository**:
    ```bash
    git clone <repository_url>
    cd readhaven
    ```
2.  **Move to Web Root**: Place the project folder inside your web server's document root (e.g., `htdocs` for XAMPP, `www` for WAMP).
3.  **Database Setup**:
    *   Open your database management tool (e.g., phpMyAdmin).
    *   Create a new database named `readhaven`.
    *   Import the provided `readhaven.sql` file into this new database.
4.  **Start Server**: Ensure your Apache and MySQL services are running.
5.  **Access the App**: Navigate to `http://localhost/readhaven` in your web browser.

## Mail Configuration Guide (Crucial Step)

> [!IMPORTANT]
> To enable automated mail notifications (like order confirmations or password resets), you **MUST** configure the mailer settings.

1.  Locate the mail configuration file in the project directory.
2.  Open the file and find the SMTP settings.
3.  Replace the template placeholders with your actual credentials:
    *   Replace `<YOUR_EMAIL_HERE>` with your Gmail address.
    *   Replace `<YOUR_APP_PASSWORD_HERE>` with your generated Google App Password. (Do NOT use your regular email password; generate an App Password in your Google Account security settings).

## Dockerized Environment Setup

ReadHaven includes a complete, production-ready Docker configuration for seamless deployment.

### `docker-compose.yml`

Create a `docker-compose.yml` file in the root directory (if not already present) with the following configuration:

```yaml
version: '3.8'

services:
  web:
    image: php:8.2-apache
    container_name: readhaven-web
    ports:
      - "8080:80"
    volumes:
      - .:/var/www/html
    depends_on:
      - db
    networks:
      - readhaven-network

  db:
    image: mysql:8.0
    container_name: readhaven-db
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: readhaven
      MYSQL_USER: readhaven_user
      MYSQL_PASSWORD: secretpassword
    ports:
      - "3306:3306"
    volumes:
      - db_data:/var/lib/mysql
      # Optionally mount the SQL dump to auto-import on first start:
      # - ./readhaven.sql:/docker-entrypoint-initdb.d/readhaven.sql
    networks:
      - readhaven-network

networks:
  readhaven-network:
    driver: bridge

volumes:
  db_data:
```

### Running with Docker

1. Ensure you have [Docker](https://docs.docker.com/get-docker/) and [Docker Compose](https://docs.docker.com/compose/install/) installed.
2. Open a terminal in the project root directory.
3. Run the following command to build and start the containers in the background:
   ```bash
   docker-compose up -d
   ```
4. Access the application at `http://localhost:8080`.
