<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';
requireRole(['admin', 'teacher']);
require_once __DIR__ . '/../config/helpers.php';
