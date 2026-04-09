<?php require_once __DIR__ . '/common.php';
$requiredHeaders = ['subject_name','class_name','group_name','difficulty_name','question_text','option_a','option_b','option_c','option_d','option_e','correct_option','explanation'];
$preview=[]; $errors=[]; $done='';
if(isset($_POST['import']) && isset($_SESSION['csv_rows'])){
    $rows=$_SESSION['csv_rows']; $inserted=0; $skipped=0;
    foreach($rows as $i=>$r){
      [$subject,$class,$group,$difficulty,$q,$a,$b,$c,$d,$e,$correct,$exp] = array_map('trim',$r);
      $subjectId=$pdo->prepare('SELECT id FROM subjects WHERE name=?'); $subjectId->execute([$subject]); $subjectId=$subjectId->fetchColumn();
      $classId=$pdo->prepare('SELECT id FROM classes WHERE name=?'); $classId->execute([$class]); $classId=$classId->fetchColumn();
      if(!$subjectId || !$classId){$skipped++; continue;}
      $gid=$pdo->prepare('SELECT id FROM question_groups WHERE name=? AND subject_id=? AND class_id=?'); $gid->execute([$group,$subjectId,$classId]); $gid=$gid->fetchColumn();
      if(!$gid){$pdo->prepare('INSERT INTO question_groups(name,subject_id,class_id) VALUES(?,?,?)')->execute([$group,$subjectId,$classId]); $gid=$pdo->lastInsertId();}
      $did=$pdo->prepare('SELECT id FROM difficulty_levels WHERE name=?'); $did->execute([$difficulty]); $did=$did->fetchColumn();
      if(!$did){$pdo->prepare('INSERT INTO difficulty_levels(name) VALUES(?)')->execute([$difficulty]); $did=$pdo->lastInsertId();}
      $dup=$pdo->prepare('SELECT id FROM questions WHERE question_text=? AND subject_id=? AND class_id=?'); $dup->execute([$q,$subjectId,$classId]);
      if($dup->fetchColumn()){ $skipped++; continue; }
      $pdo->prepare('INSERT INTO questions(subject_id,class_id,group_id,difficulty_id,question_text,option_a,option_b,option_c,option_d,option_e,correct_option,explanation,created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)')
          ->execute([$subjectId,$classId,$gid,$did,$q,$a,$b,$c,$d,$e,$correct,$exp,$_SESSION['user']['id']]);
      $inserted++;
    }
    $done="Imported {$inserted}, Skipped {$skipped}";
}
if(isset($_FILES['csv']) && is_uploaded_file($_FILES['csv']['tmp_name'])){
    $f=fopen($_FILES['csv']['tmp_name'],'r'); $header=fgetcsv($f);
    if($header!==$requiredHeaders){ $errors[]='Invalid header order.'; } else {
      $rows=[]; $line=1;
      while(($data=fgetcsv($f))!==false){$line++; if(count($data)<12){$errors[]="Line {$line}: incomplete row"; continue;} if(!in_array(strtoupper(trim($data[10])),['A','B','C','D','E'])){$errors[]="Line {$line}: invalid correct option";} $rows[]=$data;}
      $_SESSION['csv_rows']=$rows; $preview=array_slice($rows,0,10);
    }
    fclose($f);
}
include __DIR__ . '/../templates/header.php'; include __DIR__ . '/../templates/sidebar.php'; ?>
<h4>Import Questions (CSV)</h4>
<a href="<?= basePath('admin/sample_questions.csv') ?>" class="btn btn-outline-secondary btn-sm">Download CSV Template</a>
<?php if($done):?><div class="alert alert-success mt-2"><?=e($done)?></div><?php endif; ?>
<?php foreach($errors as $er):?><div class="alert alert-danger mt-2"><?=e($er)?></div><?php endforeach; ?>
<form method="post" enctype="multipart/form-data" class="my-3"><input type="file" name="csv" accept=".csv" required><button class="btn btn-primary">Preview</button></form>
<?php if($preview): ?><div class="table-responsive"><table class="table table-sm"><tr><?php foreach($requiredHeaders as $h):?><th><?=e($h)?></th><?php endforeach;?></tr><?php foreach($preview as $r):?><tr><?php foreach($r as $v):?><td><?=e($v)?></td><?php endforeach;?></tr><?php endforeach;?></table></div><form method="post"><button name="import" value="1" class="btn btn-success">Import Valid Rows</button></form><?php endif; ?>
<?php include __DIR__ . '/../templates/footer.php'; ?>
