# WorkBazar

WorkBazar is a comprehensive freelancing and enterprise talent marketplace designed to connect clients with top-tier freelance talent. It provides a secure, reliable, and user-friendly platform with integrated authentication, job posting, messaging, and payment processing capabilities.

**🌍 Live Demo:** [https://blue-tiger-674937.hostingersite.com](https://blue-tiger-674937.hostingersite.com)

## 🚀 Features

- **User Authentication:** Secure login, registration, and OTP verification workflows.
- **Role-based Dashboards:** Dedicated dashboards for Freelancers, Clients, and Administrators.
- **Job Management:** Clients can post, manage, and review job proposals. Freelancers can search, filter, and apply for jobs.
- **Real-time Chat:** Seamless communication between clients and freelancers.
- **Payment Integration:** Secure transactions and order creation powered by Razorpay.
- **Enterprise Portal:** Advanced features for enterprise clients to manage large-scale talent acquisition.
- **Modern UI/UX:** Fully responsive design with glassmorphism, animated gradients, and high-performance interactivity.

## 🛠️ Technology Stack

- **Frontend:** HTML5, Vanilla CSS3 (Custom Design System), JavaScript (ES6+).
- **Backend:** PHP 8+ (MVC-inspired Architecture).
- **Database:** MySQL.
- **Payment Gateway:** Razorpay API.
- **Email Delivery:** SMTP (PHPMailer / Native implementation).

## ⚙️ Installation & Setup

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/theaftabsk/workbazzer.git
   cd workbazzer
   ```

2. **Database Configuration:**
   - Import `database/schema.sql` into your MySQL server.
   - Open `includes/config.php` and configure your database credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'your_db_name_here');
     define('DB_USER', 'your_db_user_here');
     define('DB_PASS', 'your_db_password_here');
     ```

3. **Mail & OTP Configuration:**
   - In `includes/config.php`, provide your SMTP server details (e.g., Gmail App Password).

4. **Payment Gateway Configuration:**
   - Add your Razorpay Key ID and Secret in `includes/config.php`.

5. **Run the Project:**
   - Serve the project using a local PHP server (e.g., XAMPP, Laragon, or PHP built-in server):
     ```bash
     php -S localhost:8000
     ```

## 🛡️ Security
Ensure that your `includes/config.php` is NEVER committed with real, production-level credentials. Use environment variables or ensure `.gitignore` excludes sensitive configuration files in a production environment.

## 📄 License
This project is proprietary. All rights reserved.