<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Gym Management System

A comprehensive gym management system built with Laravel, featuring membership management, class scheduling, equipment tracking, and integrated payment processing with Doovera Gateway.

## Features

### 🏃‍♂️ **Member Management**
- User registration and profile management
- Role-based access (Admin, Trainer, Customer)
- Progress tracking and fitness goals
- Membership plans and renewals

### 💳 **Payment Integration**
- Doovera Payment Gateway integration
- Multiple payment methods (VA, Credit Card, E-Wallet)
- Real-time payment status updates
- Transaction history and reporting

### 📅 **Class & Booking System**
- Gym class scheduling and management
- Equipment booking and availability tracking
- Trainer assignment and scheduling
- Automated booking confirmations

### 📊 **Admin Dashboard**
- Comprehensive analytics and reporting
- User and membership management
- Equipment and facility management
- Financial reporting and transaction tracking

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Payment System

This gym management system includes a comprehensive payment integration with **Doovera Payment Gateway**. The payment system supports various payment methods including Virtual Account, Credit Card, E-Wallet, and more.

### Payment Features

- **Multiple Payment Methods**: Virtual Account (BCA, BNI, BRI, Mandiri), Credit Card, E-Wallet (OVO, DANA, GoPay), and others
- **Real-time Payment Status**: Automatic payment status updates via webhooks
- **Secure Transactions**: All payments are processed securely through Doovera's encrypted API
- **Payment History**: Complete transaction tracking and history management
- **Auto-renewal**: Automatic membership renewal with payment reminders
- **Payment Simulation**: Built-in testing tools for development environment

### Payment Configuration

The payment system requires the following environment variables:

```env
# Doovera Payment Gateway Configuration
DOOVERA_CLIENT_ID=your_client_id
DOOVERA_SECRET_KEY=your_secret_key
DOOVERA_API_URL=https://api.doovera.com
DOOVERA_WEBHOOK_SECRET=your_webhook_secret
```

### Payment Flow

1. **Order Creation**: Customer selects membership plan and creates payment order
2. **Payment Method Selection**: Customer chooses preferred payment method
3. **Payment Processing**: Secure payment processing through Doovera Gateway
4. **Real-time Updates**: Automatic payment status updates via webhooks
5. **Membership Activation**: Automatic membership activation upon successful payment

### Payment API Endpoints

- `POST /payment/create-order` - Create new payment order
- `POST /payment/verify/{orderId}` - Verify payment status
- `POST /payment/webhook` - Handle payment webhooks
- `GET /payment/history` - Get payment transaction history
- `POST /payment/simulate` - Simulate payment for testing (development only)

### Payment Models

- **Transaction**: Stores all payment transactions and status
- **Membership**: Links payments to user memberships
- **User**: Extended with payment-related methods

For detailed payment integration guide, please refer to the Doovera API documentation.

## Installation & Setup

### Requirements

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL Database
- Doovera Payment Gateway Account

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-repo/gym-manager.git
   cd gym-manager
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Payment Gateway Configuration**
   Add your Doovera credentials to `.env`:
   ```env
   DOOVERA_CLIENT_ID=your_client_id
   DOOVERA_SECRET_KEY=your_secret_key
   DOOVERA_API_URL=https://api.doovera.com
   DOOVERA_WEBHOOK_SECRET=your_webhook_secret
   ```

6. **Build Assets**
   ```bash
   npm run build
   ```

7. **Start the Application**
   ```bash
   php artisan serve
   ```

### Default Users

The system comes with pre-configured users for testing:

- **Admin**: admin@gym.com / password123
- **Trainer**: trainer@gym.com / password123  
- **Customer**: customer@gym.com / password123

## Payment Testing

### Development Environment

The system includes payment simulation tools for development:

1. **Simulate Payment Success**
   ```bash
   POST /payment/simulate
   Content-Type: application/json
   {
     "order_id": "your_order_id",
     "status": "success"
   }
   ```

2. **Webhook Testing**
   Use the built-in webhook simulator or tools like ngrok to test webhook endpoints locally.

### Production Environment

1. **Webhook URL Configuration**
   Configure your webhook URL in Doovera dashboard:
   ```
   https://your-domain.com/payment/webhook
   ```

2. **SSL Certificate**
   Ensure your production server has a valid SSL certificate for secure payment processing.

## Troubleshooting

### Common Payment Issues

1. **Payment Failed**
   - Check Doovera API credentials
   - Verify webhook URL configuration
   - Check server logs for detailed error messages

2. **Webhook Not Received**
   - Ensure webhook URL is accessible
   - Check firewall and server configuration
   - Verify webhook secret key

3. **Database Errors**
   - Run migrations: `php artisan migrate`
   - Check database connection in `.env`
   - Verify user permissions

### Support

For payment gateway issues, contact:
- **Doovera Support**: support@doovera.com
- **Technical Documentation**: [Doovera API Docs](https://docs.doovera.com)

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
