<?php require_once __DIR__ . '/common.php'; $id=(int)($_GET['attempt_id']??0);
$a=$pdo->prepare('SELECT ea.*,e.title FROM exam_attempts ea JOIN exams e ON e.id=ea.exam_id WHERE ea.id=? AND ea.student_id=?');$a->execute([$id,$student['id']]);$a=$a->fetch(); if(!$a) exit('Not found');
include __DIR__ . '/../templates/header.php'; include __DIR__ . '/../templates/sidebar.php'; ?>
<h4>Exam Result</h4><div class="card"><div class="card-body"><h5><?=e($a['title'])?></h5><p>Score: <?=$a['score']?> / <?=$a['total']?> (<?= $a['total']?round($a['score']/$a['total']*100,2):0 ?>%)</p><a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a></div></div>
<?php include __DIR__ . '/../templates/footer.php'; ?>
