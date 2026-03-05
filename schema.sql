CREATE DATABASE IF NOT EXISTS cbt_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cbt_app;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) UNIQUE NOT NULL,
  full_name VARCHAR(150) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','teacher','student','parent') NOT NULL,
  status TINYINT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE classes (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) UNIQUE NOT NULL);
CREATE TABLE sections (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) UNIQUE NOT NULL);
CREATE TABLE subjects (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, class_id INT NOT NULL, FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE);
CREATE TABLE parents (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, phone VARCHAR(50), email VARCHAR(120), FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE);
CREATE TABLE students (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, reg_no VARCHAR(100) UNIQUE, class_id INT, section_id INT, parent_id INT NULL, FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE, FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE SET NULL, FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE SET NULL, FOREIGN KEY (parent_id) REFERENCES parents(id) ON DELETE SET NULL);
CREATE TABLE teachers (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, phone VARCHAR(50), email VARCHAR(120), FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE);
CREATE TABLE teacher_subjects (id INT AUTO_INCREMENT PRIMARY KEY, teacher_id INT NOT NULL, subject_id INT NOT NULL, class_id INT NOT NULL, UNIQUE KEY uniq_teacher_subject_class(teacher_id,subject_id,class_id), FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE, FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE, FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE);
CREATE TABLE difficulty_levels (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(50) UNIQUE NOT NULL);
CREATE TABLE question_groups (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(120) NOT NULL, subject_id INT NOT NULL, class_id INT NOT NULL, UNIQUE KEY uq_group(name,subject_id,class_id), FOREIGN KEY(subject_id) REFERENCES subjects(id) ON DELETE CASCADE, FOREIGN KEY(class_id) REFERENCES classes(id) ON DELETE CASCADE);
CREATE TABLE questions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  subject_id INT NOT NULL,
  class_id INT NOT NULL,
  group_id INT NOT NULL,
  difficulty_id INT NOT NULL,
  question_text TEXT NOT NULL,
  option_a TEXT NOT NULL,
  option_b TEXT NOT NULL,
  option_c TEXT NOT NULL,
  option_d TEXT NOT NULL,
  option_e TEXT NULL,
  correct_option ENUM('A','B','C','D','E') NOT NULL,
  explanation TEXT,
  created_by INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY(subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
  FOREIGN KEY(class_id) REFERENCES classes(id) ON DELETE CASCADE,
  FOREIGN KEY(group_id) REFERENCES question_groups(id) ON DELETE CASCADE,
  FOREIGN KEY(difficulty_id) REFERENCES difficulty_levels(id) ON DELETE CASCADE,
  FOREIGN KEY(created_by) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE exams (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(180) NOT NULL,
  class_id INT NOT NULL,
  subject_id INT NOT NULL,
  duration_minutes INT NOT NULL,
  total_questions INT NOT NULL,
  start_time DATETIME NULL,
  end_time DATETIME NULL,
  instructions TEXT,
  shuffle_questions TINYINT DEFAULT 0,
  shuffle_options TINYINT DEFAULT 0,
  allow_review TINYINT DEFAULT 1,
  status ENUM('draft','published','closed') DEFAULT 'draft',
  created_by INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY(class_id) REFERENCES classes(id) ON DELETE CASCADE,
  FOREIGN KEY(subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
  FOREIGN KEY(created_by) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE exam_questions (id INT AUTO_INCREMENT PRIMARY KEY, exam_id INT NOT NULL, question_id INT NOT NULL, UNIQUE KEY uq_exam_question(exam_id,question_id), FOREIGN KEY(exam_id) REFERENCES exams(id) ON DELETE CASCADE, FOREIGN KEY(question_id) REFERENCES questions(id) ON DELETE CASCADE);
CREATE TABLE exam_attempts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  exam_id INT NOT NULL,
  student_id INT NOT NULL,
  start_time DATETIME NOT NULL,
  submit_time DATETIME NULL,
  score INT DEFAULT 0,
  total INT DEFAULT 0,
  status ENUM('in_progress','submitted') DEFAULT 'in_progress',
  token VARCHAR(64) UNIQUE,
  FOREIGN KEY(exam_id) REFERENCES exams(id) ON DELETE CASCADE,
  FOREIGN KEY(student_id) REFERENCES students(id) ON DELETE CASCADE
);
CREATE TABLE exam_answers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  attempt_id INT NOT NULL,
  question_id INT NOT NULL,
  selected_option ENUM('A','B','C','D','E') NULL,
  is_flagged TINYINT DEFAULT 0,
  is_correct TINYINT NULL,
  answered_at DATETIME NULL,
  UNIQUE KEY uq_attempt_question(attempt_id,question_id),
  FOREIGN KEY(attempt_id) REFERENCES exam_attempts(id) ON DELETE CASCADE,
  FOREIGN KEY(question_id) REFERENCES questions(id) ON DELETE CASCADE
);
CREATE TABLE messages (id INT AUTO_INCREMENT PRIMARY KEY, sender_user_id INT NOT NULL, receiver_user_id INT NOT NULL, subject VARCHAR(180), body TEXT, is_read TINYINT DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(sender_user_id) REFERENCES users(id) ON DELETE CASCADE, FOREIGN KEY(receiver_user_id) REFERENCES users(id) ON DELETE CASCADE);
CREATE TABLE notices (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(180) NOT NULL, body TEXT NOT NULL, audience ENUM('all','teachers','students','parents') DEFAULT 'all', created_by INT NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, expires_at DATE NULL, FOREIGN KEY(created_by) REFERENCES users(id) ON DELETE CASCADE);
CREATE TABLE events (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(180) NOT NULL, description TEXT, event_date DATE NOT NULL, created_by INT NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(created_by) REFERENCES users(id) ON DELETE CASCADE);

INSERT INTO users(username,full_name,password_hash,role,status) VALUES
('admin','System Administrator','$2y$12$DRdSUsIwO7HV0Ibbkt/YwOKO6F9WaW6r.f.fhSqdUD76W2ce0m/tq','admin',1);
INSERT INTO classes(name) VALUES ('SS1');
INSERT INTO sections(name) VALUES ('A');
INSERT INTO subjects(name,class_id) VALUES ('Mathematics',1);
INSERT INTO difficulty_levels(name) VALUES ('Easy'),('Medium'),('Hard');
INSERT INTO question_groups(name,subject_id,class_id) VALUES ('Algebra',1,1);
INSERT INTO questions(subject_id,class_id,group_id,difficulty_id,question_text,option_a,option_b,option_c,option_d,option_e,correct_option,explanation,created_by) VALUES
(1,1,1,1,'2 + 5 = ?','5','6','7','8',NULL,'C','Simple addition',1),
(1,1,1,1,'3x = 12, x = ?','2','3','4','5',NULL,'C','Divide by 3',1),
(1,1,1,2,'10 - 4 = ?','5','6','7','8',NULL,'B','Simple subtraction',1),
(1,1,1,2,'Square root of 81?','7','8','9','10',NULL,'C','9*9=81',1),
(1,1,1,3,'If y=2 and x=3, xy=?','5','6','7','8',NULL,'B','2*3=6',1);
