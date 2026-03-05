<?php
require_once __DIR__ . '/helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

const SESSION_TIMEOUT_SECONDS = 1800;

function enforceSessionTimeout(): void {
    if (!isset($_SESSION['user'])) {
        return;
    }

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT_SECONDS) {
        session_unset();
        session_destroy();
        header('Location: ' . basePath('auth/login.php?timeout=1'));
        exit;
    }

    $_SESSION['last_activity'] = time();
}

function requireAuth(): void {
    enforceSessionTimeout();
    if (!isset($_SESSION['user'])) {
        header('Location: ' . basePath('auth/login.php'));
        exit;
    }
}

function requireRole(array $roles): void {
    requireAuth();
    if (!in_array($_SESSION['user']['role'], $roles, true)) {
        http_response_code(403);
        exit('Access denied.');
    }
}

function isAdminOrTeacher(): bool {
    return isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['admin', 'teacher'], true);
}
