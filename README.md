# Student Management System (SMS)

A comprehensive web-based Student Management System designed for schools and educational organizations in Uganda.

## 🎯 Project Overview

This system helps manage student records, class promotions, scholarship tracking, and report card generation for educational institutions, particularly designed for Nansana NGO/School Support.

**Key Features:**
- ✅ Student Registration & Management
- ✅ Excel Import/Export
- ✅ Automatic Class Promotion
- ✅ Graduation Management
- ✅ Scholarship Tracking
- ✅ Marks Management
- ✅ Report Card Generation (PDF)
- ✅ Teacher & Admin Dashboards
- ✅ User Authentication System
- ✅ Attendance Tracking

## 🛠️ Technology Stack

- **Backend**: Laravel 11 (PHP)
- **Frontend**: Bootstrap 5 + HTML/CSS/JavaScript
- **Database**: MySQL 8.0+
- **PDF Generation**: Laravel Dompdf
- **Excel Handling**: Laravel Excel (Maatwebsite)
- **Server**: Apache/Nginx

## 📚 Main Modules

1. **Student Registration** - Personal info, guardian details, photo upload
2. **Class Management** - Students by class (P.1 - P.7)
3. **Marks Management** - Subject-wise marks entry (Test, Assignment, Exam)
4. **Automatic Promotion** - Annual class advancement
5. **Scholarship Tracking** - Sponsor and amount tracking
6. **Report Cards** - PDF generation with student photo
7. **Excel Operations** - Import/export student records
8. **User Management** - Admin and Teacher accounts

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js & npm

### Installation

```bash
# Clone the repository
git clone https://github.com/Moses123-png/student-management-system.git
cd student-management-system

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Build assets
npm run build

# Start server
php artisan serve
```

Access at: **http://localhost:8000**

### Default Credentials

- **Admin**: admin@sms.local / password123
- **Teacher**: teacher1@sms.local / password123

## 📖 Documentation

- [Setup Guide](SETUP.md) - Detailed installation instructions
- [Database Schema](database/schema.md) - Complete database design

## 📁 Project Structure

```
student-management-system/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   ├── Services/
│   └── Exports/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── schema.md
├── resources/
│   ├── views/
│   ├── css/
│   └── js/
├── routes/
├── storage/
├── public/
├── composer.json
├── package.json
├── .env.example
└── README.md
```

## 👥 User Roles

### Administrator
- Add/Edit/Delete students
- Manage teachers
- Excel import/export
- View all reports
- System configuration
- Class promotion
- Graduation management

### Teacher
- View assigned class
- Enter student marks
- View student profiles
- Generate report cards
- Update attendance

## 📊 Database Overview

**Main Tables:**
- `users` - Admin, teachers, guardians
- `students` - Student records with auto-generated IDs
- `classes` - Class definitions (P.1-P.7)
- `teachers` - Staff information
- `guardians` - Parent/guardian details
- `marks` - Academic performance
- `scholarships` - Scholarship records
- `attendance` - Attendance tracking
- `report_cards` - Generated reports
- `graduates` - Graduation records

See [database/schema.md](database/schema.md) for complete details.

## 🔐 Security

- Laravel Authentication
- Password hashing (Bcrypt)
- CSRF protection
- SQL injection prevention
- Role-based access control
- File upload validation

## 📝 Development Roadmap

### Phase 1: Core Setup ✅
- [x] Project initialization
- [x] Database schema
- [x] Project structure
- [ ] Laravel configuration

### Phase 2: Authentication & Users
- [ ] User registration
- [ ] Login/logout
- [ ] Role management

### Phase 3: Student Management
- [ ] Student CRUD
- [ ] Excel import/export
- [ ] Photo upload
- [ ] Class assignment

### Phase 4: Academic Features
- [ ] Marks management
- [ ] Class promotion
- [ ] Report card generation
- [ ] Scholarship tracking

### Phase 5: Admin Dashboard
- [ ] Analytics
- [ ] Reports
- [ ] System settings

## 🤝 Contributing

This is a project for an educational organization. For contributions or issues, please contact the development team.

## 📄 License

Proprietary - For authorized use only

---

**Version**: 1.0.0 (Development)
**Last Updated**: July 14, 2026
**Author**: Moses Nkalubo