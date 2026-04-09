<?php require_once __DIR__ . '/common.php';
$students=$pdo->query('SELECT s.id, u.full_name FROM students s JOIN users u ON u.id=s.user_id')->fetchAll();
if($_SERVER['REQUEST_METHOD']==='POST'){
 $pdo->beginTransaction();
 $pdo->prepare('INSERT INTO users(username,full_name,password_hash,role,status) VALUES(?,?,?,?,1)')->execute([trim($_POST['username']),trim($_POST['full_name']),password_hash($_POST['password'],PASSWORD_DEFAULT),'parent']);
 $uid=$pdo->lastInsertId();
 $pdo->prepare('INSERT INTO parents(user_id,phone,email) VALUES(?,?,?)')->execute([$uid,trim($_POST['phone']),trim($_POST['email'])]);
 $pid=$pdo->lastInsertId();
 if(!empty($_POST['student_id'])) $pdo->prepare('UPDATE students SET parent_id=? WHERE id=?')->execute([$pid,(int)$_POST['student_id']]);
 $pdo->commit();
}
$rows=$pdo->query('SELECT p.*,u.full_name,u.username FROM parents p JOIN users u ON u.id=p.user_id ORDER BY p.id DESC')->fetchAll();
include __DIR__ . '/../templates/header.php'; include __DIR__ . '/../templates/sidebar.php'; ?>
<h4>Parents</h4>
<form method="post" class="row g-2 mb-3"><div class="col"><input name="full_name" placeholder="Full Name" class="form-control" required></div><div class="col"><input name="username" placeholder="Username" class="form-control" required></div><div class="col"><input name="password" placeholder="Password" class="form-control" required></div><div class="col"><input name="phone" placeholder="Phone" class="form-control"></div><div class="col"><input name="email" placeholder="Email" class="form-control"></div><div class="col"><select name="student_id" class="form-select"><option value="">Link Student</option><?php foreach($students as $s):?><option value="<?=$s['id']?>"><?=e($s['full_name'])?></option><?php endforeach;?></select></div><div class="col"><button class="btn btn-primary">Add</button></div></form>
<table class="table"><tr><th>Name</th><th>Username</th><th>Phone</th><th>Email</th></tr><?php foreach($rows as $r):?><tr><td><?=e($r['full_name'])?></td><td><?=e($r['username'])?></td><td><?=e($r['phone'])?></td><td><?=e($r['email'])?></td></tr><?php endforeach;?></table>
<?php include __DIR__ . '/../templates/footer.php'; ?>
