<?php
session_start();
require_once __DIR__ . '/connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=auth');
    exit;
}

$userId = $_SESSION['user_id'];
$addressId = intval($_POST['address_id'] ?? 0);

if ($addressId > 0) {
    $stmt = $connect->prepare("SELECT id FROM addresses WHERE id = ? AND user_id = ?");
    $stmt->execute([$addressId, $userId]);
    
    if ($stmt->fetch()) {
        $connect->prepare("DELETE FROM addresses WHERE id = ?")->execute([$addressId]);
    }
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php?page=lk'));
exit;
?>