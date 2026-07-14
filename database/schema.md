# Database Schema - Student Management System

## Overview

The Student Management System uses MySQL as its database. This document outlines all tables, relationships, and key design decisions.

## Tables

### 1. users

User accounts for authentication (Admin, Teachers, Guardians)

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| id | BIGINT | PK, AUTO_INCREMENT | Primary key |
| name | VARCHAR(255) | NOT NULL | Full name |
| email | VARCHAR(255) | UNIQUE, NOT NULL | Email address |
| email_verified_at | TIMESTAMP | NULLABLE | Email verification |
| password | VARCHAR(255) | NOT NULL | Hashed password (Bcrypt) |
| role | ENUM('admin','teacher','guardian') | NOT NULL | User role |
| phone | VARCHAR(20) | NULLABLE | Contact number |
| is_active | BOOLEAN | DEFAULT 1 | Account status |
| remember_token | VARCHAR(100) | NULLABLE | Remember me token |
| created_at | TIMESTAMP | NULLABLE | Creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Last update timestamp |

**Indexes**: email (UNIQUE), role

---

### 2. students

Core student information and records

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| id | BIGINT | PK, AUTO_INCREMENT | |
| student_id | VARCHAR(20) | UNIQUE, NOT NULL | Format: STD001, STD002, etc |
| surname | VARCHAR(100) | NOT NULL | Last name |
| other_names | VARCHAR(100) | NOT NULL | First and middle names |
| gender | ENUM('Male','Female','Other') | NOT NULL | |
| date_of_birth | DATE | NOT NULL | Calculated for age |
| photo_path | VARCHAR(255) | NULLABLE | Path to student photo |
| entry_year | YEAR | NOT NULL | Year student enrolled |
| class_id | BIGINT | FK (classes) | Current class assignment |
| status | ENUM('Active','Graduated','Dropped Out') | DEFAULT 'Active' | Student status |
| guardian_id | BIGINT | FK (guardians), NULLABLE | Parent/Guardian |
| community_worker_id | BIGINT | FK (community_workers), NULLABLE | Community worker |
| zone | VARCHAR(100) | NULLABLE | Nansana zone |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

**Indexes**: student_id (UNIQUE), class_id, guardian_id, status
**Soft Deletes**: Consider adding soft_delete_at for data recovery

---

### 3. classes

Class definitions for each academic year and level

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| id | BIGINT | PK, AUTO_INCREMENT | |
| class_name | VARCHAR(10) | NOT NULL | P.1, P.2, P.3, P.4, P.5, P.6, P.7 |
| academic_year | YEAR | NOT NULL | 2024, 2025, 2026 |
| teacher_id | BIGINT | FK (teachers), NULLABLE | Primary class teacher |
| total_students | INT | DEFAULT 0 | Count of active students |
| is_active | BOOLEAN | DEFAULT 1 | |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

**Unique Index**: (class_name, academic_year)
**Indexes**: teacher_id, is_active

---

### 4. guardians

Parent/Guardian information

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| id | BIGINT | PK, AUTO_INCREMENT | |
| name | VARCHAR(150) | NOT NULL | Guardian name |
| phone | VARCHAR(20) | UNIQUE, NOT NULL | Contact number |
| email | VARCHAR(100) | NULLABLE | Email address |
| relationship | VARCHAR(50) | NOT NULL | Father, Mother, Uncle, Aunt, etc |
| occupation | VARCHAR(100) | NULLABLE | Guardian's job |
| address | TEXT | NULLABLE | Residential address |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

**Indexes**: phone (UNIQUE), name

---

### 5. community_workers

Community worker information for student follow-up

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| id | BIGINT | PK, AUTO_INCREMENT | |
| name | VARCHAR(150) | NOT NULL | |
| phone | VARCHAR(20) | UNIQUE, NOT NULL | |
| email | VARCHAR(100) | NULLABLE | |
| zone | VARCHAR(100) | NOT NULL | Nansana East, Nansana West, etc |
| address | TEXT | NULLABLE | |
| is_active | BOOLEAN | DEFAULT 1 | |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

**Indexes**: phone (UNIQUE), zone, is_active

---

### 6. teachers

Teacher/Staff information linked to user accounts

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| id | BIGINT | PK, AUTO_INCREMENT | |
| user_id | BIGINT | FK (users), UNIQUE | Links to user account |
| name | VARCHAR(150) | NOT NULL | |
| phone | VARCHAR(20) | UNIQUE, NOT NULL | |
| email | VARCHAR(100) | UNIQUE, NOT NULL | |
| qualification | VARCHAR(100) | NULLABLE | Teaching qualification |
| assigned_class_id | BIGINT | FK (classes), NULLABLE | Primary class |
| subjects | JSON | NULLABLE | ["Mathematics", "English", ...] |
| is_active | BOOLEAN | DEFAULT 1 | |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

**Indexes**: user_id (UNIQUE), assigned_class_id, is_active

---

### 7. marks

Student academic performance and marks

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| id | BIGINT | PK, AUTO_INCREMENT | |
| student_id | BIGINT | FK (students) | |
| subject | VARCHAR(50) | NOT NULL | Mathematics, English, Science, etc |
| academic_year | YEAR | NOT NULL | |
| term | TINYINT | NOT NULL | 1, 2, or 3 |
| test_1_score | INT | NULLABLE | 0-100 |
| test_2_score | INT | NULLABLE | 0-100 |
| assignment_score | INT | NULLABLE | 0-100 |
| exam_score | INT | NULLABLE | 0-100 |
| total_score | INT | GENERATED | Calculated: (test1 + test2 + assignment + exam) / 4 |
| grade | CHAR(1) | GENERATED | A, B, C, D, E, F (based on total_score) |
| teacher_id | BIGINT | FK (teachers) | Teacher who entered marks |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

**Unique Index**: (student_id, subject, academic_year, term)
**Indexes**: student_id, academic_year, term, grade

**Grade Scale**:
- A: 90-100
- B: 80-89
- C: 70-79
- D: 60-69
- E: 50-59
- F: 0-49

---

### 8. scholarships

Scholarship tracking for students

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| id | BIGINT | PK, AUTO_INCREMENT | |
| student_id | BIGINT | FK (students) | |
| has_scholarship | BOOLEAN | DEFAULT 0 | |
| scholarship_type | VARCHAR(50) | NULLABLE | Secondary School, University, Other |
| sponsor_name | VARCHAR(150) | NULLABLE | Organization/Person sponsoring |
| sponsor_contact | VARCHAR(100) | NULLABLE | Email or phone |
| amount | DECIMAL(10,2) | NULLABLE | Scholarship amount |
| currency | VARCHAR(3) | DEFAULT 'UGX' | UGX, USD, EUR, etc |
| start_year | YEAR | NULLABLE | Year scholarship starts |
| end_year | YEAR | NULLABLE | Year scholarship ends |
| status | ENUM('Active','Completed','Pending','Cancelled') | DEFAULT 'Active' | |
| certificate_path | VARCHAR(255) | NULLABLE | Path to certificate |
| notes | TEXT | NULLABLE | Additional information |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

**Indexes**: student_id, status, start_year

---

### 9. attendance

Student attendance records

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| id | BIGINT | PK, AUTO_INCREMENT | |
| student_id | BIGINT | FK (students) | |
| class_id | BIGINT | FK (classes) | |
| attendance_date | DATE | NOT NULL | |
| status | ENUM('Present','Absent','Excused','Late') | NOT NULL | |
| notes | TEXT | NULLABLE | Reason for absence, etc |
| recorded_by | BIGINT | FK (teachers) | Teacher who recorded |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

**Unique Index**: (student_id, class_id, attendance_date)
**Indexes**: student_id, attendance_date, status

---

### 10. class_promotions

Track student class promotions annually

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| id | BIGINT | PK, AUTO_INCREMENT | |
| student_id | BIGINT | FK (students) | |
| from_class_id | BIGINT | FK (classes) | Previous class |
| to_class_id | BIGINT | FK (classes) | New class |
| academic_year | YEAR | NOT NULL | Promotion year |
| promotion_date | TIMESTAMP | NOT NULL | When promotion occurred |
| promoted_by | BIGINT | FK (users) | Admin who promoted |
| status | ENUM('Promoted','Held Back','Graduated') | NOT NULL | |
| notes | TEXT | NULLABLE | Promotion notes |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

**Indexes**: student_id, academic_year, status

---

### 11. graduates

Graduation records

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| id | BIGINT | PK, AUTO_INCREMENT | |
| student_id | BIGINT | FK (students), UNIQUE | |
| graduation_year | YEAR | NOT NULL | |
| graduation_date | DATE | NOT NULL | |
| final_class | VARCHAR(10) | NOT NULL | P.7 |
| achievement_level | ENUM('Excellent','Good','Average','Below Average') | NULLABLE | |
| scholarship_received | BOOLEAN | DEFAULT 0 | |
| notes | TEXT | NULLABLE | |
| diploma_path | VARCHAR(255) | NULLABLE | Path to diploma/certificate |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

**Indexes**: graduation_year, student_id

---

### 12. report_cards

Generated report card records

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| id | BIGINT | PK, AUTO_INCREMENT | |
| student_id | BIGINT | FK (students) | |
| academic_year | YEAR | NOT NULL | |
| term | TINYINT | NOT NULL | 1, 2, or 3 |
| class_id | BIGINT | FK (classes) | |
| teacher_comment | TEXT | NULLABLE | Qualitative feedback |
| teacher_signature_path | VARCHAR(255) | NULLABLE | |
| principal_comment | TEXT | NULLABLE | |
| principal_signature_path | VARCHAR(255) | NULLABLE | |
| generated_at | TIMESTAMP | NOT NULL | When report generated |
| pdf_path | VARCHAR(255) | NULLABLE | Path to PDF file |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

**Unique Index**: (student_id, academic_year, term)
**Indexes**: academic_year, term, class_id

---

## Entity Relationships

```
users (1) ←→ (1) teachers
        ↓
    (1) classes (1) ←→ (n) students
                         ↓
                    (n) marks
                    (n) scholarships
                    (n) attendance
                    (n) class_promotions
                    (1) graduates
                    (n) report_cards
                         ↓
                    (1) guardians
                    (1) community_workers
```

## Key Design Decisions

1. **Student ID Generation**: Custom format (STD001, STD002) instead of just database ID for readability

2. **Marks Calculation**: `total_score` and `grade` are GENERATED columns for consistency

3. **Photo Storage**: Photos stored in `storage/app/public/students/` with path in database

4. **JSON Subjects**: Teacher subjects stored as JSON array for flexibility

5. **Soft Deletes**: Consider implementing for data recovery (add `deleted_at` timestamp)

6. **Audit Trail**: All tables have `created_at` and `updated_at` for tracking changes

7. **Status Fields**: Use ENUM for fixed values (status, role, grade) for consistency

8. **Relationships**: Foreign keys with CASCADE DELETE on non-critical data

## Constraints & Indexes

### Primary Keys
All tables use `BIGINT AUTO_INCREMENT` as primary key

### Foreign Keys
- CASCADE DELETE for relationships
- RESTRICT on critical relationships (students)

### Unique Constraints
- `student_id` - Must be unique across system
- `email` in users - One email per account
- `phone` in guardians - One phone per guardian
- `(student_id, subject, academic_year, term)` in marks - One mark per subject per term
- `(student_id, academic_year, term)` in report_cards - One report per term

## Sample Data

The system comes with seeders to populate:
- 1 Admin user (admin@sms.local)
- 2 Teacher users (teacher1@sms.local, teacher2@sms.local)
- 50 Sample students across P.1-P.7
- Sample marks for multiple terms
- Sample scholarships
- Sample attendance records

## Backup & Recovery

```bash
# Backup
mysqldump -u root -p sms_db > backup.sql

# Restore
mysql -u root -p sms_db < backup.sql
```

---

**Last Updated**: July 14, 2026
