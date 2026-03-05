<?php require_once __DIR__ . '/common.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $pdo->prepare('INSERT INTO messages(sender_user_id,receiver_user_id,subject,body,is_read) VALUES(?,?,?,?,0)')->execute([$_SESSION['user']['id'],(int)$_POST['receiver_user_id'],trim($_POST['subject']),trim($_POST['body'])]);
}
$users=$pdo->query("SELECT id,full_name,role FROM users WHERE role IN ('student','parent')")->fetchAll();
$sent=$pdo->prepare('SELECT m.*,u.full_name receiver FROM messages m JOIN users u ON u.id=m.receiver_user_id WHERE sender_user_id=? ORDER BY m.id DESC');$sent->execute([$_SESSION['user']['id']]);$sent=$sent->fetchAll();
include __DIR__ . '/../templates/header.php'; include __DIR__ . '/../templates/sidebar.php'; ?>
<h4>Messaging</h4><form method="post" class="mb-3"><div class="row g-2"><div class="col-md-3"><select name="receiver_user_id" class="form-select"><?php foreach($users as $u):?><option value="<?=$u['id']?>"><?=e($u['full_name'].' ('.$u['role'].')')?></option><?php endforeach;?></select></div><div class="col-md-3"><input name="subject" class="form-control" placeholder="Subject" required></div><div class="col-md-4"><input name="body" class="form-control" placeholder="Message" required></div><div class="col-md-2"><button class="btn btn-primary">Send</button></div></div></form>
<table class="table"><tr><th>To</th><th>Subject</th><th>Body</th><th>Date</th></tr><?php foreach($sent as $m):?><tr><td><?=e($m['receiver'])?></td><td><?=e($m['subject'])?></td><td><?=e($m['body'])?></td><td><?=$m['created_at']?></td></tr><?php endforeach;?></table>
<?php include __DIR__ . '/../templates/footer.php'; ?>
