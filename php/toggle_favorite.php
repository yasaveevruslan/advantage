<?php
session_start();
require_once __DIR__ . '/connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=auth');
    exit;
}

$userId = $_SESSION['user_id'];
$type = $_POST['type'] ?? '';
$itemId = intval($_POST['id'] ?? 0);
$referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';

if ($itemId <= 0 || !in_array($type, ['dish', 'set'])) {
    header('Location: ' . $referer);
    exit;
}

$table = ($type === 'set') ? 'set_dishes' : 'dishes';
$stmt = $connect->prepare("SELECT is_available FROM $table WHERE id = ?");
$stmt->execute([$itemId]);
$item = $stmt->fetch();

if (!$item || $item['is_available'] == 0) {
    header('Location: ' . $referer);
    exit;
}

try {
    $check = $connect->prepare("SELECT id FROM favorites WHERE user_id = ? AND item_type = ? AND item_id = ?");
    $check->execute([$userId, $type, $itemId]);
    $exists = $check->fetch();

    if ($exists) {
        $connect->prepare("DELETE FROM favorites WHERE id = ?")->execute([$exists['id']]);
    } else {
        $connect->prepare("INSERT INTO favorites (user_id, item_type, item_id) VALUES (?, ?, ?)")->execute([$userId, $type, $itemId]);
    }
} catch (PDOException $e) {
    error_log('error: ' . $e->getMessage());
}

header('Location: ' . $referer);
exit;
?>