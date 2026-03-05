<?php
require_once __DIR__ . '/../config/auth.php';
requireAuth();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBT App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= basePath('assets/css/styles.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<nav class="navbar navbar-dark bg-primary navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">JAMB CBT</a>
        <div class="text-white small ms-auto">Logged in as <?= e($_SESSION['user']['full_name']) ?> (<?= e($_SESSION['user']['role']) ?>)</div>
        <a class="btn btn-light btn-sm ms-3" href="<?= basePath('auth/logout.php') ?>">Logout</a>
    </div>
</nav>
<div class="container-fluid">
<div class="row">
