<?php
require_once __DIR__ . '/../db.php';

$isAuthenticated = !empty($_SESSION['user_id']);
$isAdministrator = ($_SESSION['user_role'] ?? '') === 'admin';

if (!$isAuthenticated || !$isAdministrator) {
    unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['user_role']);
    header('Location: admingiris.php');
    exit;
}
