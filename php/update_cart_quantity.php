<?php
session_start();
require_once __DIR__ . '/connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=auth');
    exit;
}

$userId = $_SESSION['user_id'];
$itemType = $_REQUEST['item_type'] ?? '';
$itemId   = intval($_REQUEST['item_id'] ?? 0);
$action   = $_REQUEST['action'] ?? '';

if ($itemId <= 0 || !in_array($itemType, ['dish', 'set']) || !in_array($action, ['increase', 'decrease'])) {
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
}

try {
    if ($action === 'decrease') {
        $stmt = $connect->prepare("
            UPDATE cart SET quantity = quantity - 1 
            WHERE user_id = ? AND item_type = ? AND item_id = ? AND quantity > 1
        ");
        $stmt->execute([$userId, $itemType, $itemId]);
        
        if ($stmt->rowCount() === 0) {
            $connect->prepare("DELETE FROM cart WHERE user_id = ? AND item_type = ? AND item_id = ?")
                    ->execute([$userId, $itemType, $itemId]);
        }
    } else {
        $connect->prepare("
            UPDATE cart SET quantity = quantity + 1 
            WHERE user_id = ? AND item_type = ? AND item_id = ?
        ")->execute([$userId, $itemType, $itemId]);
    }
} catch (PDOException $e) {
    $_SESSION['cart_error'] = 'Ошибка обновления количества';
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php?page=korzina'));
exit;
?>