<?php
session_start();
require_once __DIR__ . '/connect.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['cart_error'] = 'Чтобы добавить товар в корзину, необходимо войти в аккаунт';
    header('Location: index.php?page=auth');
    exit;
}

$userId = $_SESSION['user_id'];
$type = $_GET['type'] ?? $_POST['type'] ?? 'dish';
$itemId = intval($_GET['id'] ?? $_POST['id'] ?? 0);
$quantity = max(1, intval($_GET['qty'] ?? $_POST['qty'] ?? 1));
$referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';

if ($itemId <= 0 || !in_array($type, ['dish', 'set'])) {
    header('Location: ' . $referer);
    exit;
}

$table = ($type === 'set') ? 'set_dishes' : 'dishes';
$stmt = $connect->prepare("SELECT id, name, is_available FROM $table WHERE id = ?");
$stmt->execute([$itemId]);
$item = $stmt->fetch();

if (!$item || $item['is_available'] == 0) {
    $_SESSION['cart_error'] = 'Товар недоступен';
    header('Location: ' . $referer);
    exit;
}

try {
    $sql = "INSERT INTO cart (user_id, item_type, item_id, quantity) 
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity), added_at = CURRENT_TIMESTAMP";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$userId, $type, $itemId, $quantity]);
    
    $_SESSION['cart_success'] = '«' . $item['name'] . '» добавлен в корзину';
} catch (PDOException $e) {
    $_SESSION['cart_error'] = 'Ошибка добавления в корзину';
}

header('Location: ' . $referer);
exit;
?>