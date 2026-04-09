<?php require_once __DIR__ . '/common.php'; include __DIR__ . '/../templates/header.php'; include __DIR__ . '/../templates/sidebar.php';
$features = [
['Dashboard','bi-speedometer2','admin/dashboard.php'],['Students List','bi-people','admin/students.php'],['Parents List','bi-people-fill','admin/parents.php'],['Teachers List','bi-person-badge','admin/teachers.php'],
['Import Question','bi-upload','admin/questions_import.php'],['Create Question','bi-plus-circle','admin/questions_create.php'],['Add Question Level & Group','bi-layers','admin/difficulty_levels.php'],['Add Class, Section & Subject','bi-diagram-3','admin/classes.php'],
['Take Exam','bi-journal-check','admin/exams.php'],['Set Exam Instructions','bi-card-text','admin/exam_instructions.php'],['Messaging System','bi-chat-left-text','admin/messages.php'],['Notice & Event Management','bi-megaphone','admin/notices.php']
];
?>
<h3 class="mb-4">Core Features</h3>
<div class="row g-3">
<?php foreach($features as [$name,$icon,$url]): ?>
<div class="col-lg-4 col-md-6">
  <a class="text-decoration-none" href="<?= basePath($url) ?>">
    <div class="card feature-card shadow-sm">
      <div class="card-body d-flex align-items-center gap-3 py-4">
        <i class="bi <?= $icon ?> feature-icon"></i>
        <h6 class="mb-0 text-dark"><?= e($name) ?></h6>
      </div>
    </div>
  </a>
</div>
<?php endforeach; ?>
</div>
<?php include __DIR__ . '/../templates/footer.php'; ?>
