# JAMB-like CBT Web Application (PHP + MySQL)

A local CBT system for secondary schools using **PHP (no framework)**, **MySQL**, **Bootstrap 5**, JavaScript, and sessions.

## Features
- Multi-role auth: admin, teacher, student (+ parent records)
- Core features grid page (JAMB-like tiles)
- CRUD for students/parents/teachers/classes/sections/subjects
- Question creation and CSV import with preview and duplicate skipping
- Difficulty levels and question groups
- Exam creation, builder, instruction editor, publish/close
- Student exam taking interface with timer, palette, autosave AJAX, flagging
- Results listing and detail view
- Export endpoints for CSV, XLSX, PDF
- Messaging + notices + events

## Project Structure
Matches requested layout under `/config`, `/assets`, `/templates`, `/auth`, `/admin`, `/student`, plus `schema.sql`.

## XAMPP Setup (Step-by-step)
1. Copy this project folder into `xampp/htdocs/`.
2. Start **Apache** and **MySQL** from XAMPP control panel.
3. Open phpMyAdmin and create DB `cbt_app` (or import directly).
4. Import `schema.sql`.
5. In project root, install dependencies for exports:
   ```bash
   composer require phpoffice/phpspreadsheet
   composer require dompdf/dompdf
   ```
6. Access login page:
   `http://localhost/Cisco-Router-Automation/auth/login.php`

## Default Admin
- Username: `admin`
- Password: `Admin@12345`

## CSV Import Template
Download from: `admin/sample_questions.csv`

Required order:
`subject_name,class_name,group_name,difficulty_name,question_text,option_a,option_b,option_c,option_d,option_e,correct_option,explanation`

Rules:
- `option_e` may be empty
- `correct_option` must be A/B/C/D/E
- group/difficulty auto-created if missing
- subject/class must already exist (otherwise row skipped)
- duplicate questions skipped by `(question_text + subject_id + class_id)`

## Notes
- Update DB credentials in `config/db.php` if required.
- All DB calls use PDO prepared statements.
- Session timeout is configured in `config/auth.php`.
