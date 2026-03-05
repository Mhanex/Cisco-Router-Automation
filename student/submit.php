<?php require_once __DIR__ . '/common.php'; $id=(int)($_GET['attempt_id']??0);
$attempt=$pdo->prepare("SELECT * FROM exam_attempts WHERE id=? AND student_id=? AND status='in_progress'");$attempt->execute([$id,$student['id']]);$attempt=$attempt->fetch(); if(!$attempt) { header('Location: dashboard.php'); exit;}
$answers=$pdo->prepare('SELECT a.id,a.selected_option,q.correct_option FROM exam_answers a JOIN questions q ON q.id=a.question_id WHERE a.attempt_id=?');$answers->execute([$id]);$answers=$answers->fetchAll();
$score=0; foreach($answers as $a){$ok=($a['selected_option']!==null && $a['selected_option']===$a['correct_option'])?1:0; if($ok)$score++; $pdo->prepare('UPDATE exam_answers SET is_correct=? WHERE id=?')->execute([$ok,$a['id']]);}
$total=count($answers);
$pdo->prepare("UPDATE exam_attempts SET submit_time=NOW(),score=?,total=?,status='submitted' WHERE id=?")->execute([$score,$total,$id]);
header('Location: result.php?attempt_id='.$id);
