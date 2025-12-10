📄 Overview

Abudiyab Task is a Laravel‑based backend application for managing orders and payments.
It supports creating orders and processing payments via payment gateway (e.g. Stripe).
The project uses two main database tables:

orders — stores orders (id, customer_id ,amount, currency, customer_email, status)

payments — stores payment transactions (id, order_id, payment_gateway, transaction_id, amount, status, metadata)

The code provides a Payment Service following a PaymentGatewayInterface, so you can add or swap payment gateways (Stripe, PayPal, etc.) without major changes.



🚀 Setup — Local Development
   1. Clone the repository:
      git clone https://github.com/halaibrahim867/Abudiyab_Task.git
      cd Abudiyab_Task
   2. Install dependencies:
      composer install
   3. Copy the example environment file and set your environment variables:
      cp .env.example .env
      # Update .env with your database and payment gateway credentials
   4. Generate an application key:
      php artisan key:generate
   5. Run database migrations:
      php artisan migrate
   6. Start the development server:
      php artisan serve
   7. Access the application at http://localhost:8000


🔧 Payment Setup (Testing Mode)

- For Stripe:
  1. Sign up for a Stripe account and get your API keys.
  2. Update your .env file with the Stripe keys:
     STRIPE_KEY=your_stripe_key
     STRIPE_SECRET=your_stripe_secret
  3. Use Stripe's test card numbers for testing payments (e.g., 4242 4242 4242 4242).
