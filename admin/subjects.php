<?php require_once __DIR__ . '/common.php';
$classes=$pdo->query('SELECT * FROM classes')->fetchAll();
if ($_SERVER['REQUEST_METHOD']==='POST'){ $pdo->prepare('INSERT INTO subjects(name,class_id) VALUES(?,?)')->execute([trim($_POST['name']), (int)$_POST['class_id']]); }
if (isset($_GET['delete'])) { $pdo->prepare('DELETE FROM subjects WHERE id=?')->execute([(int)$_GET['delete']]); }
$rows=$pdo->query('SELECT s.*, c.name class_name FROM subjects s JOIN classes c ON c.id=s.class_id ORDER BY s.id DESC')->fetchAll();
include __DIR__ . '/../templates/header.php'; include __DIR__ . '/../templates/sidebar.php'; ?>
<h4>Subjects</h4><form method="post" class="row g-2 mb-3"><div class="col-md-4"><input name="name" class="form-control" required></div><div class="col-md-4"><select class="form-select" name="class_id"><?php foreach($classes as $c):?><option value="<?=$c['id']?>"><?=e($c['name'])?></option><?php endforeach;?></select></div><div class="col-md-2"><button class="btn btn-primary">Add</button></div></form>
<table class="table"><tr><th>Name</th><th>Class</th><th></th></tr><?php foreach($rows as $r):?><tr><td><?=e($r['name'])?></td><td><?=e($r['class_name'])?></td><td><a href="?delete=<?=$r['id']?>" class="btn btn-sm btn-danger">Delete</a></td></tr><?php endforeach;?></table>
<?php include __DIR__ . '/../templates/footer.php'; ?>
