# Laravel-Authentication-with-OTP-Verification
This repository demonstrates a user authentication system in Laravel with OTP (One-Time Password) verification for added security. The AuthController handles user registration, login, OTP generation, email verification, and user dashboard access.

**Instructions:**
1. Clone the repository.
2. Install dependencies with composer install.
3. Set up Google and Facebook OAuth credentials in the .env file as:
GOOGLE_CLIENT_ID=your-google-client-id, GOOGLE_CLIENT_SECRET=your-google-client-secret, FACEBOOK_CLIENT_ID=your-facebook-client-id, FACEBOOK_CLIENT_SECRET=your-facebook-client-secret
   
5. Run the migrations to set up the user table (php artisan migrate).
6. Try logging in with your Google or Facebook account.
