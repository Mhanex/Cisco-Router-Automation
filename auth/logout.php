<?php
session_start();
session_unset();
session_destroy();
require_once __DIR__ . '/../config/helpers.php';
header('Location: ' . basePath('auth/login.php'));
exit;
