<?php require_once __DIR__ . '/common.php';
$id=(int)($_GET['id']??0);
$attempt=$pdo->prepare('SELECT ea.*,e.title exam_title,e.duration_minutes,s.reg_no,u.full_name FROM exam_attempts ea JOIN exams e ON e.id=ea.exam_id JOIN students s ON s.id=ea.student_id JOIN users u ON u.id=s.user_id WHERE ea.id=?');$attempt->execute([$id]);$attempt=$attempt->fetch(); if(!$attempt) exit('Not found');
$answers=$pdo->prepare('SELECT q.question_text,q.correct_option,q.explanation,a.selected_option,a.is_correct FROM exam_answers a JOIN questions q ON q.id=a.question_id WHERE a.attempt_id=?');$answers->execute([$id]);$answers=$answers->fetchAll();
include __DIR__ . '/../templates/header.php'; include __DIR__ . '/../templates/sidebar.php'; ?>
<h4>Result Details</h4>
<p><strong>Student:</strong> <?=e($attempt['full_name'])?> (<?=e($attempt['reg_no'])?>) | <strong>Exam:</strong> <?=e($attempt['exam_title'])?></p>
<p><strong>Score:</strong> <?=$attempt['score']?> / <?=$attempt['total']?> (<?= $attempt['total']?round($attempt['score']/$attempt['total']*100,2):0 ?>%)</p>
<table class="table table-sm"><tr><th>Question</th><th>Chosen</th><th>Correct</th><th>Status</th><th>Explanation</th></tr><?php foreach($answers as $a):?><tr><td><?=e($a['question_text'])?></td><td><?=e((string)$a['selected_option'])?></td><td><?=e($a['correct_option'])?></td><td><?= $a['is_correct']?'Correct':'Wrong' ?></td><td><?=e((string)$a['explanation'])?></td></tr><?php endforeach;?></table>
<?php include __DIR__ . '/../templates/footer.php'; ?>
