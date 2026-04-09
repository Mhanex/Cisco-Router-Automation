<?php require_once __DIR__ . '/common.php';
if($_SERVER['REQUEST_METHOD']==='POST') $pdo->prepare('INSERT INTO notices(title,body,audience,created_by,expires_at) VALUES(?,?,?,?,?)')->execute([trim($_POST['title']),trim($_POST['body']),$_POST['audience'],$_SESSION['user']['id'],$_POST['expires_at']?:null]);
$rows=$pdo->query('SELECT * FROM notices ORDER BY id DESC')->fetchAll();
include __DIR__ . '/../templates/header.php'; include __DIR__ . '/../templates/sidebar.php'; ?>
<h4>Notice Management</h4>
<form method="post" class="row g-2 mb-3"><div class="col-md-3"><input name="title" class="form-control" placeholder="Title" required></div><div class="col-md-4"><input name="body" class="form-control" placeholder="Body" required></div><div class="col-md-2"><select name="audience" class="form-select"><option>all</option><option>teachers</option><option>students</option><option>parents</option></select></div><div class="col-md-2"><input type="date" name="expires_at" class="form-control"></div><div class="col-md-1"><button class="btn btn-primary">Add</button></div></form>
<table class="table"><tr><th>Title</th><th>Audience</th><th>Expires</th></tr><?php foreach($rows as $r):?><tr><td><?=e($r['title'])?></td><td><?=e($r['audience'])?></td><td><?=e((string)$r['expires_at'])?></td></tr><?php endforeach;?></table>
<p><a href="<?=basePath('admin/events.php')?>">Manage Events</a></p>
<?php include __DIR__ . '/../templates/footer.php'; ?>
