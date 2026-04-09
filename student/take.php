<?php require_once __DIR__ . '/common.php'; $attemptId=(int)($_GET['attempt_id']??0);
$attempt=$pdo->prepare('SELECT ea.*,e.title,e.duration_minutes,e.allow_review FROM exam_attempts ea JOIN exams e ON e.id=ea.exam_id WHERE ea.id=? AND ea.student_id=?');$attempt->execute([$attemptId,$student['id']]);$attempt=$attempt->fetch(); if(!$attempt) exit('Attempt not found');
if($attempt['token'] !== ($_SESSION['attempt_token'] ?? '')) exit('Session token mismatch. Open single tab only.');
$qs=$pdo->prepare('SELECT a.id answer_id,a.selected_option,a.is_flagged,q.* FROM exam_answers a JOIN questions q ON q.id=a.question_id WHERE a.attempt_id=?');$qs->execute([$attemptId]);$qs=$qs->fetchAll();
$endTs=strtotime($attempt['start_time']) + ((int)$attempt['duration_minutes']*60);
include __DIR__ . '/../templates/header.php'; include __DIR__ . '/../templates/sidebar.php'; ?>
<div class="exam-layout row">
<div class="col-lg-8">
  <?php foreach($qs as $idx=>$q): ?>
  <div class="card mb-3 question-item" data-index="<?=$idx?>" style="display:<?=$idx===0?'block':'none'?>">
    <div class="card-body">
      <h6>Q<?=($idx+1)?>. <?=e($q['question_text'])?></h6>
      <?php foreach(['A','B','C','D','E'] as $op): $txt=$q['option_'.strtolower($op)]; if($txt==='') continue; ?>
      <div class="form-check"><input class="form-check-input" type="radio" name="q<?=$q['question_id']?>" value="<?=$op?>" <?= $q['selected_option']===$op?'checked':'' ?> onchange="saveAnswer(<?=$attemptId?>,<?=$q['question_id']?>,this.value)"><label class="form-check-label"><?=$op?>. <?=e($txt)?></label></div>
      <?php endforeach; ?>
      <div class="form-check mt-2"><input type="checkbox" class="form-check-input" onchange="flagQuestion(<?=$attemptId?>,<?=$q['question_id']?>,this.checked)" <?= $q['is_flagged']?'checked':'' ?>><label class="form-check-label">Flag for review</label></div>
    </div>
  </div>
  <?php endforeach; ?>
  <div class="d-flex gap-2"><button class="btn btn-secondary" onclick="navigateQ(-1)">Previous</button><button class="btn btn-secondary" onclick="navigateQ(1)">Next</button><a class="btn btn-danger" href="submit.php?attempt_id=<?=$attemptId?>">Submit Exam</a></div>
</div>
<div class="col-lg-4">
  <div class="card mb-3"><div class="card-body"><h5>Time Left: <span id="timer"></span></h5></div></div>
  <div class="card"><div class="card-body"><h6>Palette</h6><div class="question-palette"><?php foreach($qs as $idx=>$q):?><button id="p<?=$idx?>" class="btn btn-outline-secondary" onclick="jumpTo(<?=$idx?>)" type="button"><?=$idx+1?></button><?php endforeach;?></div></div></div>
</div>
</div>
<script src="<?=basePath('assets/js/exam.js')?>"></script>
<script>
let current = 0; const total = <?=count($qs)?>;
function showQ(){document.querySelectorAll('.question-item').forEach(el=>el.style.display='none');document.querySelector(`.question-item[data-index="${current}"]`).style.display='block';document.querySelectorAll('.question-palette button').forEach(b=>b.classList.remove('palette-current'));document.getElementById('p'+current).classList.add('palette-current');}
function navigateQ(step){current=Math.max(0,Math.min(total-1,current+step));showQ();}
function jumpTo(i){current=i;showQ();}
async function saveAnswer(attemptId, questionId, selected){const res=await postJSON('save_answer.php',{attempt_id:attemptId,question_id:questionId,selected_option:selected}); if(res.ok){document.getElementById('p'+current).classList.add('palette-answered');}}
async function flagQuestion(attemptId,questionId,flag){await postJSON('save_answer.php',{attempt_id:attemptId,question_id:questionId,is_flagged:flag?1:0}); document.getElementById('p'+current).classList.toggle('palette-flagged', flag);}
initExamTimer(Math.max(0, <?= $endTs-time() ?>), ()=>location.href='submit.php?attempt_id=<?=$attemptId?>'); showQ();
</script>
<?php include __DIR__ . '/../templates/footer.php'; ?>
