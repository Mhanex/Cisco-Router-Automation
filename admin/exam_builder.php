<?php require_once __DIR__ . '/common.php';
$examId=(int)($_GET['exam_id']??0);
$exam=$pdo->prepare('SELECT * FROM exams WHERE id=?');$exam->execute([$examId]);$exam=$exam->fetch(); if(!$exam) exit('Exam not found');
if($_SERVER['REQUEST_METHOD']==='POST'){
 if($_POST['mode']==='manual'){ foreach($_POST['question_ids']??[] as $qid){$pdo->prepare('INSERT IGNORE INTO exam_questions(exam_id,question_id) VALUES(?,?)')->execute([$examId,(int)$qid]);}}
 if($_POST['mode']==='auto'){ $q=$pdo->prepare('SELECT id FROM questions WHERE class_id=? AND subject_id=? LIMIT ?'); $q->bindValue(1,$exam['class_id'],PDO::PARAM_INT);$q->bindValue(2,$exam['subject_id'],PDO::PARAM_INT);$q->bindValue(3,(int)$_POST['count'],PDO::PARAM_INT);$q->execute(); foreach($q->fetchAll() as $row){$pdo->prepare('INSERT IGNORE INTO exam_questions(exam_id,question_id) VALUES(?,?)')->execute([$examId,$row['id']]);}}
}
$questions=$pdo->prepare('SELECT * FROM questions WHERE class_id=? AND subject_id=?');$questions->execute([$exam['class_id'],$exam['subject_id']]);$questions=$questions->fetchAll();
$added=$pdo->prepare('SELECT COUNT(*) FROM exam_questions WHERE exam_id=?');$added->execute([$examId]);$added=$added->fetchColumn();
include __DIR__ . '/../templates/header.php'; include __DIR__ . '/../templates/sidebar.php'; ?>
<h4>Exam Builder - <?=e($exam['title'])?> (Added: <?=$added?>)</h4>
<form method="post" class="mb-3"><input type="hidden" name="mode" value="auto"><div class="input-group w-25"><input type="number" name="count" class="form-control" value="<?=$exam['total_questions']?>"><button class="btn btn-outline-primary">Auto-pick</button></div></form>
<form method="post"><input type="hidden" name="mode" value="manual"><div class="table-responsive"><table class="table table-sm"><tr><th></th><th>Question</th></tr><?php foreach($questions as $q):?><tr><td><input type="checkbox" name="question_ids[]" value="<?=$q['id']?>"></td><td><?=e($q['question_text'])?></td></tr><?php endforeach;?></table></div><button class="btn btn-primary">Add Selected</button></form>
<?php include __DIR__ . '/../templates/footer.php'; ?>
