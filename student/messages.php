<?php require_once __DIR__ . '/../config/auth.php'; requireAuth(); require_once __DIR__ . '/../config/db.php'; require_once __DIR__ . '/../config/helpers.php';
$pdo->prepare('UPDATE messages SET is_read=1 WHERE receiver_user_id=?')->execute([$_SESSION['user']['id']]);
$rows=$pdo->prepare('SELECT m.*,u.full_name sender FROM messages m JOIN users u ON u.id=m.sender_user_id WHERE receiver_user_id=? ORDER BY id DESC');$rows->execute([$_SESSION['user']['id']]);$rows=$rows->fetchAll();
include __DIR__ . '/../templates/header.php'; include __DIR__ . '/../templates/sidebar.php'; ?>
<h4>Inbox</h4><table class="table"><tr><th>From</th><th>Subject</th><th>Message</th><th>Date</th></tr><?php foreach($rows as $r):?><tr><td><?=e($r['sender'])?></td><td><?=e($r['subject'])?></td><td><?=e($r['body'])?></td><td><?=$r['created_at']?></td></tr><?php endforeach;?></table>
<?php include __DIR__ . '/../templates/footer.php'; ?>
