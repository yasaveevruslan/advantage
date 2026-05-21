<?php
session_start();
require_once __DIR__ . '/connect.php';

$type = $_GET['type'] ?? $_POST['type'] ?? 'dish'; // 'dish' или 'set'
$itemId = intval($_GET['id'] ?? $_POST['id'] ?? 0);
$quantity = max(1, intval($_GET['qty'] ?? $_POST['qty'] ?? 1));
$referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';

if ($itemId <= 0 || !in_array($type, ['dish', 'set'])) {
    $_SESSION['cart_error'] = 'Неверный товар';
    header("Location: $referer");
    exit;
}

$userId = $_SESSION['user_id'] ?? null;
$sessionId = $userId ? null : session_id();

if ($type === 'set') {
    $stmt = $connect->prepare("
        SELECT id, name, price, image, is_available 
        FROM set_dishes 
        WHERE id = ?
    ");
} else {
    $stmt = $connect->prepare("
        SELECT id, name, price, image, is_available 
        FROM dishes 
        WHERE id = ?
    ");
}

$stmt->execute([$itemId]);
$item = $stmt->fetch();

if (!$item || $item['is_available'] == 0) {
    $_SESSION['cart_error'] = 'Товар недоступен';
    header("Location: $referer");
    exit;
}

try {
    $sql = "INSERT INTO cart (user_id, session_id, item_type, item_id, quantity) 
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity), added_at = CURRENT_TIMESTAMP";
    
    $stmt = $connect->prepare($sql);
    $stmt->execute([$userId, $sessionId, $type, $itemId, $quantity]);
    
    $_SESSION['cart_success'] = '«' . $item['name'] . '» добавлен в корзину';
} catch (PDOException $e) {
    $_SESSION['cart_error'] = 'Ошибка добавления в корзину';
    error_log('Cart error: ' . $e->getMessage());
}

header("Location: $referer");
exit;
?>