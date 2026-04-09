<?php require_once __DIR__ . '/common.php';
$counts = [
 'students' => $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn(),
 'teachers' => $pdo->query('SELECT COUNT(*) FROM teachers')->fetchColumn(),
 'questions' => $pdo->query('SELECT COUNT(*) FROM questions')->fetchColumn(),
 'exams' => $pdo->query('SELECT COUNT(*) FROM exams')->fetchColumn(),
 'attempts' => $pdo->query('SELECT COUNT(*) FROM exam_attempts')->fetchColumn(),
];
$recent = $pdo->query("SELECT ea.*, u.full_name FROM exam_attempts ea JOIN students s ON s.id=ea.student_id JOIN users u ON u.id=s.user_id ORDER BY ea.id DESC LIMIT 10")->fetchAll();
include __DIR__ . '/../templates/header.php'; include __DIR__ . '/../templates/sidebar.php'; ?>
<h3>Dashboard</h3>
<div class="row g-3 mb-4"><?php foreach($counts as $k=>$v): ?><div class="col-md-2"><div class="card"><div class="card-body"><small><?= ucfirst($k) ?></small><h4><?= $v ?></h4></div></div></div><?php endforeach;?></div>
<div class="card"><div class="card-header">Recent Attempts</div><div class="table-responsive"><table class="table mb-0"><tr><th>Student</th><th>Score</th><th>Total</th><th>Status</th></tr><?php foreach($recent as $r): ?><tr><td><?= e($r['full_name']) ?></td><td><?= (int)$r['score'] ?></td><td><?= (int)$r['total'] ?></td><td><?= e($r['status']) ?></td></tr><?php endforeach; ?></table></div></div>
<?php include __DIR__ . '/../templates/footer.php'; ?>
