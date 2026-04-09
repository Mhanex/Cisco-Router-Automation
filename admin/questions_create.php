<?php require_once __DIR__ . '/common.php';
$classes=$pdo->query('SELECT * FROM classes')->fetchAll();$subjects=$pdo->query('SELECT * FROM subjects')->fetchAll();$groups=$pdo->query('SELECT * FROM question_groups')->fetchAll();$levels=$pdo->query('SELECT * FROM difficulty_levels')->fetchAll();
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    foreach(['option_a','option_b','option_c','option_d'] as $f){ if(trim($_POST[$f])===''){ $msg='Options A-D are required'; break; }}
    if(!$msg){
        $pdo->prepare('INSERT INTO questions(subject_id,class_id,group_id,difficulty_id,question_text,option_a,option_b,option_c,option_d,option_e,correct_option,explanation,created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)')
            ->execute([(int)$_POST['subject_id'],(int)$_POST['class_id'],(int)$_POST['group_id'],(int)$_POST['difficulty_id'],trim($_POST['question_text']),trim($_POST['option_a']),trim($_POST['option_b']),trim($_POST['option_c']),trim($_POST['option_d']),trim($_POST['option_e']),$_POST['correct_option'],trim($_POST['explanation']),$_SESSION['user']['id']]);
        $msg='Question saved successfully';
    }
}
include __DIR__ . '/../templates/header.php'; include __DIR__ . '/../templates/sidebar.php'; ?>
<h4>Create Question</h4><?php if($msg):?><div class="alert alert-info"><?=e($msg)?></div><?php endif; ?>
<form method="post"><div class="row g-2"><div class="col-md-3"><select name="class_id" class="form-select"><?php foreach($classes as $c):?><option value="<?=$c['id']?>"><?=e($c['name'])?></option><?php endforeach;?></select></div><div class="col-md-3"><select name="subject_id" class="form-select"><?php foreach($subjects as $s):?><option value="<?=$s['id']?>"><?=e($s['name'])?></option><?php endforeach;?></select></div><div class="col-md-3"><select name="group_id" class="form-select"><?php foreach($groups as $g):?><option value="<?=$g['id']?>"><?=e($g['name'])?></option><?php endforeach;?></select></div><div class="col-md-3"><select name="difficulty_id" class="form-select"><?php foreach($levels as $l):?><option value="<?=$l['id']?>"><?=e($l['name'])?></option><?php endforeach;?></select></div></div>
<textarea name="question_text" class="form-control my-2" rows="3" placeholder="Question text" required></textarea>
<div class="row g-2"><?php foreach(['a','b','c','d','e'] as $o):?><div class="col-md-6"><input name="option_<?=$o?>" class="form-control" placeholder="Option <?=strtoupper($o)?>" <?= $o==='e'?'':'required' ?>></div><?php endforeach;?></div>
<div class="row g-2 my-2"><div class="col-md-3"><select name="correct_option" class="form-select"><?php foreach(['A','B','C','D','E'] as $o):?><option><?=$o?></option><?php endforeach;?></select></div><div class="col-md-9"><input name="explanation" class="form-control" placeholder="Explanation"></div></div>
<button class="btn btn-primary">Save Question</button></form>
<?php include __DIR__ . '/../templates/footer.php'; ?>
