<?php require_once __DIR__ . '/common.php';
$classes=$pdo->query('SELECT * FROM classes')->fetchAll(); $sections=$pdo->query('SELECT * FROM sections')->fetchAll();
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $full=trim($_POST['full_name']); $reg=trim($_POST['reg_no']);
    $username='stu'.time().rand(100,999); $default='Student@123';
    $pdo->beginTransaction();
    $pdo->prepare('INSERT INTO users(username,full_name,password_hash,role,status) VALUES(?,?,?,?,1)')->execute([$username,$full,password_hash($default,PASSWORD_DEFAULT),'student']);
    $uid=$pdo->lastInsertId();
    $pdo->prepare('INSERT INTO students(user_id,reg_no,class_id,section_id,parent_id) VALUES(?,?,?,?,NULL)')->execute([$uid,$reg,(int)$_POST['class_id'],(int)$_POST['section_id']]);
    $pdo->commit();
    flash('ok',"Student created. Username: $username / Password: $default");
    header('Location: students.php');exit;
}
$rows=$pdo->query('SELECT s.*,u.full_name,u.username,c.name class_name,sec.name section_name FROM students s JOIN users u ON u.id=s.user_id LEFT JOIN classes c ON c.id=s.class_id LEFT JOIN sections sec ON sec.id=s.section_id ORDER BY s.id DESC')->fetchAll();
include __DIR__ . '/../templates/header.php'; include __DIR__ . '/../templates/sidebar.php'; ?>
<h4>Students List</h4><?php if($m=flash('ok')):?><div class="alert alert-success"><?=e($m)?></div><?php endif; ?>
<form method="post" class="row g-2 mb-3"><div class="col-md-3"><input name="full_name" class="form-control" placeholder="Full Name" required></div><div class="col-md-2"><input name="reg_no" class="form-control" placeholder="Reg No" required></div><div class="col-md-2"><select class="form-select" name="class_id"><?php foreach($classes as $c):?><option value="<?=$c['id']?>"><?=e($c['name'])?></option><?php endforeach;?></select></div><div class="col-md-2"><select class="form-select" name="section_id"><?php foreach($sections as $s):?><option value="<?=$s['id']?>"><?=e($s['name'])?></option><?php endforeach;?></select></div><div class="col-md-2"><button class="btn btn-primary">Add Student</button></div></form>
<table class="table table-sm"><tr><th>Name</th><th>Username</th><th>Reg No</th><th>Class</th><th>Section</th></tr><?php foreach($rows as $r):?><tr><td><?=e($r['full_name'])?></td><td><?=e($r['username'])?></td><td><?=e($r['reg_no'])?></td><td><?=e($r['class_name']??'')?></td><td><?=e($r['section_name']??'')?></td></tr><?php endforeach;?></table>
<?php include __DIR__ . '/../templates/footer.php'; ?>
