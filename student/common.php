<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';
requireRole(['student']);
$studentStmt = $pdo->prepare('SELECT * FROM students WHERE user_id=?');
$studentStmt->execute([$_SESSION['user']['id']]);
$student = $studentStmt->fetch();
if (!$student) exit('Student record missing.');
