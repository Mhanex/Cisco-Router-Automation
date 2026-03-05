<?php require_once __DIR__ . '/common.php';
if($_SERVER['REQUEST_METHOD']==='POST') $pdo->prepare('INSERT INTO events(title,description,event_date,created_by) VALUES(?,?,?,?)')->execute([trim($_POST['title']),trim($_POST['description']),$_POST['event_date'],$_SESSION['user']['id']]);
$rows=$pdo->query('SELECT * FROM events ORDER BY event_date DESC')->fetchAll();
include __DIR__ . '/../templates/header.php'; include __DIR__ . '/../templates/sidebar.php'; ?>
<h4>Events</h4>
<form method="post" class="row g-2 mb-3"><div class="col-md-3"><input name="title" class="form-control" required></div><div class="col-md-4"><input name="description" class="form-control" required></div><div class="col-md-3"><input type="date" name="event_date" class="form-control" required></div><div class="col-md-2"><button class="btn btn-primary">Add Event</button></div></form>
<table class="table"><tr><th>Title</th><th>Description</th><th>Date</th></tr><?php foreach($rows as $r):?><tr><td><?=e($r['title'])?></td><td><?=e($r['description'])?></td><td><?=e($r['event_date'])?></td></tr><?php endforeach;?></table>
<?php include __DIR__ . '/../templates/footer.php'; ?>
