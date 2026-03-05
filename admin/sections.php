<?php require_once __DIR__ . '/common.php';
if ($_SERVER['REQUEST_METHOD']==='POST'){ $pdo->prepare('INSERT INTO sections(name) VALUES(?)')->execute([trim($_POST['name'])]); }
if (isset($_GET['delete'])) { $pdo->prepare('DELETE FROM sections WHERE id=?')->execute([(int)$_GET['delete']]); }
$rows=$pdo->query('SELECT * FROM sections ORDER BY id DESC')->fetchAll();
include __DIR__ . '/../templates/header.php'; include __DIR__ . '/../templates/sidebar.php'; ?>
<h4>Sections</h4><form method="post" class="row g-2 mb-3"><div class="col-md-6"><input name="name" class="form-control" required></div><div class="col-md-2"><button class="btn btn-primary">Add</button></div></form>
<table class="table"><tr><th>ID</th><th>Name</th><th></th></tr><?php foreach($rows as $r):?><tr><td><?=$r['id']?></td><td><?=e($r['name'])?></td><td><a href="?delete=<?=$r['id']?>" class="btn btn-sm btn-danger">Delete</a></td></tr><?php endforeach;?></table>
<?php include __DIR__ . '/../templates/footer.php'; ?>
