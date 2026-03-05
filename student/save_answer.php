<?php require_once __DIR__ . '/common.php';
header('Content-Type: application/json');
$payload=json_decode(file_get_contents('php://input'),true);
$attemptId=(int)($payload['attempt_id']??0); $qid=(int)($payload['question_id']??0);
$attempt=$pdo->prepare("SELECT * FROM exam_attempts WHERE id=? AND student_id=? AND status='in_progress'");$attempt->execute([$attemptId,$student['id']]);$attempt=$attempt->fetch();
if(!$attempt || $attempt['token'] !== ($_SESSION['attempt_token'] ?? '')){echo json_encode(['ok'=>false]);exit;}
if(isset($payload['selected_option'])) $pdo->prepare('UPDATE exam_answers SET selected_option=?,answered_at=NOW() WHERE attempt_id=? AND question_id=?')->execute([$payload['selected_option'],$attemptId,$qid]);
if(isset($payload['is_flagged'])) $pdo->prepare('UPDATE exam_answers SET is_flagged=? WHERE attempt_id=? AND question_id=?')->execute([(int)$payload['is_flagged'],$attemptId,$qid]);
echo json_encode(['ok'=>true]);
