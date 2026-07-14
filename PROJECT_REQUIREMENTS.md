# Student Management System - Project Requirements

## Project Overview

Develop a comprehensive Student Management System (SMS) for an organization (NGO/School) in Nansana, Uganda, supporting children's education through record management, tracking, and reporting.

## Client: Nansana Organization (Likely NGO/School)

## 1. Student Registration Module

### 1.1 Student Database

**Personal Information to Collect:**
- Student ID (auto-generated: STD001, STD002, etc.)
- Surname
- Other Names
- Gender (Male, Female, Other)
- Date of Birth
- Student Photo (upload)
- Year of Entry (2024, 2025, etc.)
- Class Assignment (P.1 - P.7)
- Status (Active, Graduated, Dropped Out)

**Guardian Information:**
- Guardian Name
- Guardian Phone Number
- Guardian Relationship (Father, Mother, Uncle, Aunt, etc.)
- Guardian Occupation (optional)
- Guardian Address (optional)

**Community Information:**
- Community Worker Name (assigned)
- Community Worker Contact
- Zone where child stays (e.g., Nansana East, Nansana West)

### 1.2 Data Entry

- Manual entry form
- Real-time validation
- Photo upload and storage
- Auto-generated Student ID
- Field validation (email, phone format)

## 2. Excel Import/Export Feature

### 2.1 Import Students from Excel

**Expected Excel Format:**
| Student Name | Gender | DOB | Class | Guardian Name | Guardian Phone | Zone |
|--------------|--------|-----|-------|---------------|----------------|------|
| John Peter | Male | 12/03/2015 | P.4 | Mary John | 0700123456 | Nansana East |

**Features:**
- Validate data before import
- Check for duplicate entries
- Handle missing/invalid data
- Show import summary
- Rollback on errors
- Bulk create student records

### 2.2 Export Students

- Export student list as Excel
- Custom filters (class, zone, status)
- Include photos (optional - link in Excel)
- Export marks for specific term/year
- Export scholarships

### 2.3 Batch Operations

- Update multiple students at once
- Delete incorrect records
- Change class in bulk
- Update status (Active → Graduated)

## 3. Automatic Class Promotion

### 3.1 Age Calculation

- Automatically calculate age from Date of Birth
- Update age annually
- Display current age on student profile

### 3.2 Class Promotion System

**Automatic Promotion Logic:**
```
Entry Year → Class Mapping
2024 → P.1
2025 → P.2
2026 → P.3
...
2030 → P.7 → Graduated
```

**Annual Promotion (e.g., End of 2024 Academic Year):**
- P.1 2024 → P.2 2025
- P.2 2024 → P.3 2025
- P.3 2024 → P.4 2025
- P.4 2024 → P.5 2025
- P.5 2024 → P.6 2025
- P.6 2024 → P.7 2025
- P.7 2024 → Graduated 2025

**Features:**
- Promotion triggered at academic year boundary
- Manual override for students held back
- Dropout option (no promotion)
- Audit trail of promotions
- Batch promotion for all students
- Option to hold back students

## 4. Graduation Management

### 4.1 Graduation List

**Display:**
- Students who completed P.7
- Graduation year
- Achievement level (Excellent, Good, Average, Below Average)
- Scholarship status (Yes/No)
- Scholarship details (Sponsor, Type, Amount)

### 4.2 Graduation Operations

- Generate graduation list as PDF
- Export to Excel
- Print certificates
- Search graduates by year
- Filter by achievement level
- Filter by scholarship status
- Graduation records history

### 4.3 Graduation Data

- Final grades
- Teacher comments
- Achievement summary
- Scholarship information
- Next education level

## 5. Scholarship Tracking

### 5.1 Scholarship Information per Student

**Data Fields:**
- Scholarship Status (Has/No scholarship)
- Scholarship Type:
  - Secondary School
  - University
  - Other (specify)
- Sponsor Name
- Sponsor Contact Information
- Scholarship Amount
- Currency (UGX, USD, EUR, etc.)
- Start Year
- End Year
- Certificate/Proof Path
- Status (Active, Completed, Pending, Cancelled)

### 5.2 Scholarship Features

- View all scholarships
- Filter by sponsor
- Filter by scholarship type
- Filter by status
- Export scholarship list
- Send scholarship notifications
- Track scholarship timeline
- Update scholarship status
- Upload scholarship certificates

### 5.3 Scholarship Reports

- Students with scholarships
- Scholarship by type
- Scholarship by sponsor
- Scholarship expiry alerts
- Success stories (students with scholarships)

## 6. Class Management

### 6.1 Class Organization

**Display Structure:**
```
Classes 2026
├── P.1 (25 students)
│   ├── John Peter
│   ├── Sarah Mary
│   └── ...
├── P.2 (28 students)
├── P.3 (30 students)
├── P.4 (29 students)
├── P.5 (26 students)
├── P.6 (24 students)
└── P.7 (22 students)
```

### 6.2 Class View

- List students in class
- Student details (ID, name, photo, DOB, guardian)
- Class teacher
- Total strength
- Attendance statistics
- Performance summary

### 6.3 Class Operations

- Create/Edit classes
- Assign class teacher
- View class timetable
- Manage student roster
- View class reports
- Bulk operations

## 7. Teacher Login & Account System

### 7.1 User Roles

**Administrator:**
- Full system access
- User management
- Data import/export
- System configuration
- Reporting
- Class promotion
- Graduation management

**Teacher:**
- Login with credentials
- View assigned class
- Enter student marks
- Update student performance
- View student profiles
- Add student comments
- Generate report cards
- View attendance
- Update class roster

**Guardian (Optional):**
- Login to view child's profile
- View report cards
- View scholarship status
- View attendance (read-only)

### 7.2 Teacher Account Details

**Example Teacher:**
```
Username: teacher01
Password: *******
Full Name: Mrs. Jane Nakayima
Email: jane.nakayima@school.local
Phone: 0700234567
Qualification: Bachelor of Education
Assigned Class: P.5
Subjects:
  - Mathematics
  - English
  - Science
  - Social Studies
```

### 7.3 Authentication

- Email/password login
- Secure password hashing (Bcrypt)
- Session management
- Remember me option
- Password reset
- Account lockout after failed attempts
- Change password functionality

## 8. Marks Management System

### 8.1 Mark Entry

**Subjects:**
- Mathematics
- English
- Science
- Social Studies
- Religious Education
- Local Language

**Assessment Components:**
- Test 1 (0-100)
- Test 2 (0-100)
- Assignment (0-100)
- Examination (0-100)

### 8.2 Mark Calculation

```
Total Score = (Test1 + Test2 + Assignment + Exam) / 4

Grade Scale:
A: 90-100 (Excellent)
B: 80-89  (Good)
C: 70-79  (Very Good)
D: 60-69  (Satisfactory)
E: 50-59  (Pass)
F: 0-49   (Fail)
```

### 8.3 Mark Entry Process

- Enter marks by subject
- Enter marks by term (1, 2, 3)
- Enter marks by class
- Batch mark entry
- Mark validation (0-100 range)
- Duplicate check
- Edit existing marks
- Delete marks with audit
- View mark history

### 8.4 Mark Features

- View student marks by term
- View class average by subject
- Identify low performers
- Track improvement over time
- Export marks for analysis
- Print mark sheets
- Generate performance reports

## 9. Report Card Generation

### 9.1 Report Card Format

```
╔═══════════════════════════════════════╗
║        SCHOOL NAME / LOGO             ║
║      STUDENT REPORT CARD              ║
╠═══════════════════════════════════════╣
║                                       ║
║  [STUDENT PHOTO]                      ║
║                                       ║
║  Name: John Peter                     ║
║  Class: P.5                           ║
║  Year: 2026                           ║
║  Term: 2                              ║
║  Roll No: STD001                      ║
║                                       ║
╠═══════════════════════════════════════╣
║ SUBJECT       MARK    GRADE  COMMENT  ║
╠═══════════════════════════════════════╣
║ English        85      A     Good      ║
║ Mathematics    90      A     Excellent║
║ Science        78      B     Very Good║
║ Social Studies 75      B     Good     ║
║ R.E            82      A     Good     ║
║ Local Lang     88      A     Good     ║
╠═══════════════════════════════════════╣
║ Total Points:    518                  ║
║ Overall Grade:   A                    ║
║ Position:        1/28                 ║
╠═══════════════════════════════════════╣
║ Teacher Comment:                      ║
║ John is a hardworking student with    ║
║ excellent performance. He should      ║
║ maintain this standard.               ║
║                                       ║
║ Teacher: _______________   Date: _____║
║                                       ║
║ Principal: ____________    Date: _____║
╚═══════════════════════════════════════╝
```

### 9.2 Report Card Features

- Generate per student
- Generate for entire class
- Generate for specific term/year
- Include student photo
- Calculate position (ranking)
- Add teacher comments
- Add principal comments
- Teacher/Principal signatures
- Export as PDF
- Print directly
- Email to guardians
- Generate bulk reports
- Archive generated reports

### 9.3 Report Card Data

- Student details (ID, name, class, year, term)
- All subjects with marks and grades
- Student photo
- Class position/ranking
- Attendance percentage
- Teacher qualitative assessment
- Principal approval
- Report card date/term
- Previous term comparison

## 10. Ministry of Education Compliance

### 10.1 Reference Uganda MOE Format

- Follow Uganda MoE learner identification format
- Include school details (name, registration number)
- Use standard assessment components
- Follow grade reporting standards
- Maintain required information fields
- Enable data export in MOE format

### 10.2 Customization

- Adapt MOE format for organization
- Customize with organization logo
- Add organization-specific fields
- Maintain compatibility with MOE requirements
- Export data in MOE-compatible format

## 11. System Architecture

### Recommended: Web-Based (Local Network)

**Technology Stack:**
- Backend: Laravel 11 (PHP)
- Frontend: Bootstrap 5 + HTML/CSS/JavaScript
- Database: MySQL 8.0+
- Server: Apache/Nginx
- PDF: Laravel Dompdf
- Excel: Laravel Excel (Maatwebsite)

**Advantages:**
- Multiple users can access simultaneously
- Easy network deployment
- Easier to maintain and update
- Can later migrate to cloud
- Better for team collaboration
- Secure file handling

**Deployment:**
- Run on dedicated server in organization office
- Connect via WiFi/LAN network
- Teachers login from their devices
- Central database backup
- Minimal internet dependency

## 12. Success Criteria

- ✅ All modules fully functional
- ✅ Data integrity maintained
- ✅ Fast performance (< 2s response time)
- ✅ Secure user authentication
- ✅ Reliable backup system
- ✅ User-friendly interface
- ✅ Excel import/export working
- ✅ PDF reports generated correctly
- ✅ Class promotion automatic and accurate
- ✅ Multiple simultaneous users supported

## 13. Post-Development Support

- User training
- Data migration from existing systems
- System backup strategy
- Regular maintenance
- Bug fixes and enhancements
- Performance monitoring

---

**Project Status**: Requirements Gathering & Design Phase
**Date**: July 14, 2026
