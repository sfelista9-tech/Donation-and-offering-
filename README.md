# Smart Donation System

A comprehensive web-based system for managing donations, offerings, and members in a church or organization.

## Features

✅ **User Authentication** - Secure login system with role-based access
✅ **Dashboard** - Real-time statistics and overview
✅ **Members Management** - Add, view, and manage members
✅ **Offerings Tracking** - Record and track member offerings
✅ **Donations Management** - Track donations from members and non-members
✅ **Search & Filter** - Quick search functionality across tables
✅ **Responsive Design** - Works on desktop, tablet, and mobile
✅ **Currency Formatting** - Automatic TZS currency formatting
✅ **Data Validation** - Client and server-side validation

## System Requirements

- PHP 7.0 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Modern web browser (Chrome, Firefox, Safari, Edge)

## Installation Steps

### 1. Create Database

1. Open phpMyAdmin or MySQL command line
2. Run the SQL commands from `config/database_setup.sql`
3. This will create the database and all tables with sample data

```bash
mysql -u root -p < config/database_setup.sql
```

### 2. Configure Database Connection

Edit `config/db.php` and update the following if needed:

```php
$host = "localhost";     // Your database host
$user = "root";          // Your database username
$password = "";          // Your database password
$dbname = "smart_donation_system";  // Database name
```

### 3. Deploy Files

1. Copy all files to your web server directory (e.g., `/var/www/html/smart-donation-system/`)
2. Make sure the directory has proper permissions (755 for folders, 644 for files)

### 4. Access the System

Open your browser and navigate to:
```
http://localhost/smart-donation-system/
```

## Default Login Credentials

**Username:** admin
**Password:** admin123

## File Structure

```
smart-donation-system/
├── config/
│   ├── db.php                 # Database configuration
│   └── database_setup.sql     # Database schema and sample data
├── auth/
│   ├── login.php             # Login page
│   └── logout.php            # Logout functionality
├── admin/
│   ├── dashboard.php         # Dashboard with statistics
│   ├── members.php           # Members management
│   ├── offerings.php         # Offerings tracking
│   └── donations.php         # Donations tracking
├── includes/
│   ├── header.php            # Navigation header
│   └── footer.php            # Footer
├── css/
│   └── style.css             # Main stylesheet
├── js/
│   └── main.js               # JavaScript utilities
├── index.php                 # Entry point (redirects to login)
└── README.md                 # This file
```

## Key Features Explained

### Dashboard
- Displays total members, offerings, and donations
- Shows recent activities
- Real-time statistics updates

### Members Management
- Add new members with details
- View all members
- Search and filter members
- Delete member records
- Track member status (Active/Inactive)

### Offerings
- Record member offerings
- Track offering history
- Calculate total offerings
- Filter offerings by member

### Donations
- Record donations from members and non-members
- Track donation purpose
- Manage donation history
- Calculate total donations received

## Security Features

- Password hashing using bcrypt
- SQL injection prevention with mysqli_real_escape_string()
- Session-based authentication
- CSRF protection (add tokens in production)
- Input validation and sanitization
- Role-based access control

## Database Schema

### Users Table
- Stores admin and user credentials
- Role-based access control

### Members Table
- Member information and contact details
- Active/Inactive status tracking
- Joined date tracking

### Offerings Table
- Links offerings to members
- Amount and date tracking
- Description of offering

### Donations Table
- Links donations to members or anonymous donors
- Amount and date tracking
- Purpose of donation

## How to Use

### Adding a Member
1. Log in to the system
2. Go to Members menu
3. Fill in the member form
4. Click "Save Member"

### Recording an Offering
1. Go to Offerings menu
2. Select member from dropdown
3. Enter amount and description
4. Click "Save Offering"

### Recording a Donation
1. Go to Donations menu
2. Select member or leave empty for anonymous
3. Enter donor name, amount, and purpose
4. Click "Save Donation"

## Troubleshooting

### Database Connection Error
- Check if MySQL is running
- Verify database credentials in `config/db.php`
- Ensure database is created

### Login Issues
- Clear browser cookies and cache
- Check if users table has data
- Verify password using phpMyAdmin

### File Permissions
```bash
chmod -R 755 /var/www/html/smart-donation-system/
```

## Future Enhancements

- User registration system
- Email notifications
- Report generation (PDF/Excel)
- Advanced filtering and analytics
- Mobile app integration
- Payment gateway integration
- SMS notifications
- Multi-language support
- Dark mode theme

## Support

For issues or questions, please check the code comments or review the database schema.

## License

This project is free to use and modify for personal or organizational purposes.

---

**Version:** 1.0
**Last Updated:** 2026
**Developed for:** Smart Donation System Project
