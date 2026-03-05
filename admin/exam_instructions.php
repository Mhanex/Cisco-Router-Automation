<?php require_once __DIR__ . '/common.php'; $id=(int)($_GET['exam_id']??0);
if($_SERVER['REQUEST_METHOD']==='POST') $pdo->prepare('UPDATE exams SET instructions=? WHERE id=?')->execute([trim($_POST['instructions']),$id]);
$exam=$pdo->prepare('SELECT * FROM exams WHERE id=?');$exam->execute([$id]);$exam=$exam->fetch(); if(!$exam) exit('Exam not found');
include __DIR__ . '/../templates/header.php'; include __DIR__ . '/../templates/sidebar.php'; ?>
<h4>Set Exam Instructions - <?=e($exam['title'])?></h4>
<form method="post"><textarea name="instructions" rows="10" class="form-control"><?=e($exam['instructions']??'')?></textarea><button class="btn btn-primary mt-2">Save</button></form>
<?php include __DIR__ . '/../templates/footer.php'; ?>
