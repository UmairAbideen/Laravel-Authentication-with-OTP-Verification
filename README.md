## 🔐 Laravel 10 OTP Authentication with Email Verification
This project demonstrates how to build a simple authentication system in Laravel 10 with OTP (One-Time Password) verification sent via email. 

## 🧩 What This Project Contains
- User registration with email
- OTP (One-Time Password) verification for email validation
- Middleware to restrict access to only verified users
- Secure authentication and OTP verification routes
- Blade templates styled with Bootstrap 5

## ❓ Why Use OTP Authentication with Email Verification?
🛡️ Increased Security
✉️ Verifies User Authenticity
📱 Easy User Verification
💼 Use Case for Sensitive Applications

## 🛠️ Tech Stack

| Tool         | Purpose                                                           |
|--------------|-------------------------------------------------------------------|
| Laravel 10   | PHP framework for building the application                        |
| Blade        | View templating engine for rendering HTML views                   |
| Eloquent ORM | Database interaction using Laravel's ORM for data manipulation    |
| Bootstrap 5  | Frontend UI styling framework for responsive, mobile-first design |
| Mail         | Email handling for OTP verification and notifications             |

## 🚀 Setup Steps

1️⃣ Install Laravel
``` bash
composer create-project laravel/laravel otp-auth
```

2️⃣ Setup Authentication and OTP Verification

Manually implement routes, controllers, and Blade views for:
- Registration
- OTP Verification
- Login
- Logout
- Dashboard

3️⃣ Enable Email Verification
- Implement MustVerifyEmail in the User model.
- Use sendEmailVerificationNotification() after registration.
- Add verification routes:
	/email/verify
	/email/verify/{id}/{hash}
- Protect routes like the dashboard with the verified middleware.

4️⃣ Add OTP Functionality
- Generate a 6-digit OTP and send it to the user's email.
- Implement OTP verification route and validation.
- Handle OTP expiration (valid for 10 minutes).
- Add a resend OTP feature in case the user doesn't receive it.

5️⃣ Create Views

- Build Blade views using Bootstrap 5 for:
- Registration page
- OTP Verification page
- Login page
- Dashboard
- Flash messages for success/error notifications

## 🔐 Important Middleware

| Middleware  | Purpose                                                     |
|-------------|-------------------------------------------------------------|
| **auth**    | Restrict access to authenticated users only                 |
| **guest**   | Prevent authenticated users from accessing login/register pages |
| **verified**| Restrict access to routes for verified email users only     |
| **signed**  | Used for secure email verification links                    |

## 💡 Useful Artisan Commands

php artisan migrate                    # Run migrations to set up the database
php artisan serve                      # Start the local development server
php artisan route:list                 # View all defined routes in the app


## 📬 Notes on OTP Verification
The OTP is 6 digits generated randomly using PHP’s rand() function.
The OTP expires after 10 minutes.
The OTP is stored in the User model along with its expiration time.
The OTP is sent via email using Laravel's Mail system with a custom mailable OTPMail.
You can customize the email template in the resources/views/emails/otp.blade.php.

## ⚠️ Important Considerations
Avoid too many OTP requests: Consider adding a throttle function (e.g., limit OTP requests to 5 per minute).

## 📅 When to Use OTP Authentication with Email Verification?
1. User Registration
2. High-Security Applications
3. Preventing Fake Sign-Ups
4. Avoiding Password-Only Authentication
5. When You Need Temporary Access
6. Mobile or Multi-Device Authentication
