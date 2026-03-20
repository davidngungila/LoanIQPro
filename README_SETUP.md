# Loan Management System - Setup Guide

A comprehensive loan management system with role-based access control, two-factor authentication, and detailed sidebar navigation for various user roles.

## 🚀 Features Implemented

### ✅ Authentication & Access Control
- **Secure Login**: Email/Phone + Password authentication
- **Two-Factor Authentication (2FA)**: OTP verification system
- **Role-Based Access Control (RBAC)**: 9 distinct user roles with hierarchical permissions
- **Session Management**: Secure session handling with activity tracking
- **Activity Logging**: Comprehensive audit trail for all user actions

### ✅ User Roles & Permissions
1. **Super Admin** - Full system control
2. **Admin / Branch Manager** - Branch-level management
3. **Loan Officer** - Loan processing and customer management
4. **Accountant / Finance Officer** - Financial management
5. **Collector / Recovery Officer** - Loan recovery
6. **Auditor / Compliance Officer** - Audit and compliance
7. **Customer (Client / Borrower)** - Customer portal
8. **Guarantor** - Limited access for loan guarantors
9. **IT Support** - Technical support and system maintenance

### ✅ Frontend Features
- **Responsive Design**: Mobile-first approach with Tailwind CSS
- **Role-Based Sidebar**: Dynamic navigation based on user permissions
- **Modern UI**: Clean, professional interface with yellow theme
- **Interactive Components**: Dropdowns, notifications, tooltips
- **No External Dependencies**: All CSS/JS built internally using npm

## 📋 Prerequisites

- PHP 8.2+
- Composer
- Node.js & npm
- MySQL/MariaDB or PostgreSQL
- Laravel 11+

## 🛠️ Installation Steps

### 1. Clone and Install Dependencies
```bash
cd I:\SERVERONE\loanmanagement\loans

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 2. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=loan_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3. Database Setup
```bash
# Run migrations
php artisan migrate

# Seed the database with roles and demo users
php artisan db:seed
```

### 4. Build Frontend Assets
```bash
# Compile CSS and JavaScript
npm run build

# Or for development
npm run dev
```

### 5. Start the Application
```bash
# Start Laravel development server
php artisan serve
```

## 🔐 Demo Accounts

The system comes with pre-configured demo accounts for each role:

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@demo.com | password |
| Branch Manager | manager@demo.com | password |
| Loan Officer | loanofficer@demo.com | password |
| Accountant | accountant@demo.com | password |
| Collector | collector@demo.com | password |
| Auditor | auditor@demo.com | password |
| Customer | customer@demo.com | password |
| IT Support | itsupport@demo.com | password |

## 📁 Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── AuthenticatedSessionController.php
│   │   │   │   └── TwoFactorController.php
│   │   │   └── DashboardController.php
│   │   └── Middleware/
│   │       ├── RoleMiddleware.php
│   │       └── PermissionMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Role.php
│       ├── Permission.php
│       ├── ActivityLog.php
│       ├── Branch.php
│       └── TwoFactorToken.php
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── RoleSeeder.php
│       └── DatabaseSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php
│   │   │   ├── sidebar.blade.php
│   │   │   └── navigation.blade.php
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   └── 2fa-challenge.blade.php
│   │   ├── dashboard/
│   │   │   └── super-admin.blade.php
│   │   └── components/
│   │       └── lucide-icon.blade.php
│   ├── css/app.css
│   └── js/app.js
└── routes/web.php
```

## 🎨 Frontend Technologies

- **Tailwind CSS 4.0**: Utility-first CSS framework
- **Vite**: Fast build tool and development server
- **Lucide Icons**: Beautiful icon set (implemented as Blade components)
- **Vanilla JavaScript**: No heavy frontend frameworks required

## 🔧 Configuration

### Two-Factor Authentication
- 2FA is enabled for users with `two_factor_secret` set
- Demo codes are shown in the 2FA challenge view for testing
- In production, integrate with SMS/Email services

### Role Permissions
- Roles are hierarchical with levels (1-100)
- Higher level roles inherit lower level permissions
- Custom permissions can be added via the `permissions` table

### Activity Logging
- All user actions are automatically logged
- Includes IP address, user agent, and timestamp
- Viewable in the Super Admin dashboard

## 🚀 Deployment

### Production Build
```bash
# Optimize for production
composer install --optimize-autoloader --no-dev
npm run build

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Security Considerations
- Set `APP_ENV=production` in production
- Configure proper database permissions
- Set up HTTPS with SSL certificates
- Configure firewall rules
- Regular security updates

## 📝 Next Steps

### Features to Implement:
1. **Loan Management Module**: Complete loan application, approval, and disbursement workflows
2. **Customer Management**: Full customer profiles, KYC verification, and document management
3. **Financial Reports**: Advanced reporting with charts and analytics
4. **Payment Integration**: Integration with payment gateways
5. **Notification System**: Email/SMS notifications for important events
6. **File Management**: Document upload and storage system
7. **API Endpoints**: RESTful API for mobile app integration

### Enhancements:
1. **Dark Mode**: Toggle between light and dark themes
2. **Multi-language**: Internationalization support
3. **Advanced Search**: Global search across all modules
4. **Dashboard Widgets**: Customizable dashboard components
5. **Real-time Updates**: WebSocket integration for live updates

## 🐛 Troubleshooting

### Common Issues:

1. **Migration Errors**
   ```bash
   # Fresh start
   php artisan migrate:fresh --seed
   ```

2. **Asset Build Issues**
   ```bash
   # Clear and rebuild
   npm run build
   # Or
   rm -rf node_modules && npm install && npm run build
   ```

3. **Permission Issues**
   ```bash
   # Set proper permissions
   chmod -R 775 storage bootstrap/cache
   ```

4. **2FA Not Working**
   - Ensure `two_factor_secret` is set in the users table
   - Check session configuration
   - Verify email/SMS settings for production

## 📞 Support

For technical support or questions:
1. Check the activity logs for error details
2. Review Laravel logs in `storage/logs/laravel.log`
3. Verify all environment variables are correctly set
4. Ensure all migrations have been run successfully

---

**Note**: This is a comprehensive foundation for a loan management system. The authentication, authorization, and UI framework are fully functional and ready for business logic implementation.
