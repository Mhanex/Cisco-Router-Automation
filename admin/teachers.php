<?php require_once __DIR__ . '/common.php';
$subjects=$pdo->query('SELECT * FROM subjects')->fetchAll(); $classes=$pdo->query('SELECT * FROM classes')->fetchAll();
if($_SERVER['REQUEST_METHOD']==='POST'){
 $pdo->beginTransaction();
 $pdo->prepare('INSERT INTO users(username,full_name,password_hash,role,status) VALUES(?,?,?,?,1)')->execute([trim($_POST['username']),trim($_POST['full_name']),password_hash($_POST['password'],PASSWORD_DEFAULT),'teacher']);
 $uid=$pdo->lastInsertId();
 $pdo->prepare('INSERT INTO teachers(user_id,phone,email) VALUES(?,?,?)')->execute([$uid,trim($_POST['phone']),trim($_POST['email'])]);
 $tid=$pdo->lastInsertId();
 if(!empty($_POST['subject_id'])) $pdo->prepare('INSERT INTO teacher_subjects(teacher_id,subject_id,class_id) VALUES(?,?,?)')->execute([$tid,(int)$_POST['subject_id'],(int)$_POST['class_id']]);
 $pdo->commit();
}
$rows=$pdo->query('SELECT t.*,u.full_name,u.username FROM teachers t JOIN users u ON u.id=t.user_id ORDER BY t.id DESC')->fetchAll();
include __DIR__ . '/../templates/header.php'; include __DIR__ . '/../templates/sidebar.php'; ?>
<h4>Teachers</h4>
<form method="post" class="row g-2 mb-3"><div class="col"><input name="full_name" class="form-control" placeholder="Full Name" required></div><div class="col"><input name="username" class="form-control" placeholder="Username" required></div><div class="col"><input name="password" class="form-control" placeholder="Password" required></div><div class="col"><input name="phone" class="form-control" placeholder="Phone"></div><div class="col"><input name="email" class="form-control" placeholder="Email"></div><div class="col"><select class="form-select" name="subject_id"><option value="">Subject</option><?php foreach($subjects as $s):?><option value="<?=$s['id']?>"><?=e($s['name'])?></option><?php endforeach;?></select></div><div class="col"><select class="form-select" name="class_id"><?php foreach($classes as $c):?><option value="<?=$c['id']?>"><?=e($c['name'])?></option><?php endforeach;?></select></div><div class="col"><button class="btn btn-primary">Add</button></div></form>
<table class="table"><tr><th>Name</th><th>Username</th><th>Phone</th><th>Email</th></tr><?php foreach($rows as $r):?><tr><td><?=e($r['full_name'])?></td><td><?=e($r['username'])?></td><td><?=e($r['phone'])?></td><td><?=e($r['email'])?></td></tr><?php endforeach;?></table>
<?php include __DIR__ . '/../templates/footer.php'; ?>
