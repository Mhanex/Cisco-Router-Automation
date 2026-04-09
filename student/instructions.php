<?php require_once __DIR__ . '/common.php'; $examId=(int)($_GET['exam_id']??0);
$exam=$pdo->prepare("SELECT * FROM exams WHERE id=? AND class_id=? AND status='published'");$exam->execute([$examId,$student['class_id']]);$exam=$exam->fetch(); if(!$exam) exit('Exam unavailable');
$now=date('Y-m-d H:i:s');
if(($exam['start_time'] && $now < $exam['start_time']) || ($exam['end_time'] && $now > $exam['end_time'])) exit('Outside exam window');
$existing=$pdo->prepare("SELECT * FROM exam_attempts WHERE exam_id=? AND student_id=? AND status='submitted'");$existing->execute([$examId,$student['id']]); if($existing->fetch()) exit('Exam already submitted');
if($_SERVER['REQUEST_METHOD']==='POST'){
 $token=bin2hex(random_bytes(16));
 $pdo->prepare("INSERT INTO exam_attempts(exam_id,student_id,start_time,score,total,status,token) VALUES(?,?,NOW(),0,0,'in_progress',?)")->execute([$examId,$student['id'],$token]);
 $attemptId=$pdo->lastInsertId();
 $qids=$pdo->prepare('SELECT question_id FROM exam_questions WHERE exam_id=?');$qids->execute([$examId]);
 foreach($qids->fetchAll() as $q){$pdo->prepare('INSERT INTO exam_answers(attempt_id,question_id) VALUES(?,?)')->execute([$attemptId,$q['question_id']]);}
 $_SESSION['attempt_token']=$token;
 header('Location: take.php?attempt_id='.$attemptId); exit;
}
include __DIR__ . '/../templates/header.php'; include __DIR__ . '/../templates/sidebar.php'; ?>
<h4><?=e($exam['title'])?> Instructions</h4><div class="card"><div class="card-body"><p><?=nl2br(e($exam['instructions'] ?: 'Read all questions carefully.'))?></p><ul><li>Duration: <?=$exam['duration_minutes']?> minutes</li><li>Questions: <?=$exam['total_questions']?></li></ul><form method="post"><button class="btn btn-success">Begin Exam</button></form></div></div>
<?php include __DIR__ . '/../templates/footer.php'; ?>
