<?php $role = $_SESSION['user']['role']; ?>
<aside class="col-lg-2 col-md-3 bg-light min-vh-100 p-3 border-end">
    <ul class="nav flex-column gap-1">
        <?php if (in_array($role, ['admin', 'teacher'])): ?>
        <li><a class="nav-link" href="<?= basePath('admin/core_features.php') ?>">Core Features</a></li>
        <li><a class="nav-link" href="<?= basePath('admin/dashboard.php') ?>">Dashboard</a></li>
        <li><a class="nav-link" href="<?= basePath('admin/results.php') ?>">Results</a></li>
        <?php endif; ?>
        <?php if ($role === 'student'): ?>
        <li><a class="nav-link" href="<?= basePath('student/dashboard.php') ?>">Student Dashboard</a></li>
        <?php endif; ?>
        <li><a class="nav-link" href="<?= basePath('student/messages.php') ?>">Messages</a></li>
    </ul>
</aside>
<main class="col-lg-10 col-md-9 p-4">
