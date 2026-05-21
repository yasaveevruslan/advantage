<?php
session_start();
global $connect;

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=auth');
    exit;
}

$stmt = $connect->prepare("SELECT id, full_name, phone, email, role FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header('Location: index.php?page=auth');
    exit;
}

if (($user['role'] ?? '') === 'admin') {
    header('Location: index.php?page=admin_lk');
    exit;
}
$statusFilter = $_GET['status'] ?? '';
$validStatuses = ['new', 'confirmed', 'preparing', 'delivering', 'completed', 'cancelled'];
if ($statusFilter && !in_array($statusFilter, $validStatuses)) {
    $statusFilter = '';
}

$sql = "SELECT o.*, (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.id) as items_count
        FROM orders o 
        WHERE o.user_id = ?";
$params = [$user['id']];

if ($statusFilter) {
    $sql .= " AND o.status = ?";
    $params[] = $statusFilter;
}

$sql .= " ORDER BY o.created_at DESC";

$stmt = $connect->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll();

$orderItems = [];
foreach ($orders as $order) {
    $itemsStmt = $connect->prepare("
        SELECT oi.*, 
               COALESCE(d.name, sd.name) as item_name,
               COALESCE(d.image, sd.image) as item_image,
               COALESCE(d.kcal, sd.kcal) as kcal,
               COALESCE(d.protein, d.protein) as protein,
               COALESCE(d.fat, d.fat) as fat,
               COALESCE(d.carbs, d.carbs) as carbs
        FROM order_items oi
        LEFT JOIN dishes d ON oi.item_type = 'dish' AND oi.item_id = d.id
        LEFT JOIN set_dishes sd ON oi.item_type = 'set' AND oi.item_id = sd.id
        WHERE oi.order_id = ?
    ");
    $itemsStmt->execute([$order['id']]);
    $orderItems[$order['id']] = $itemsStmt->fetchAll();
}

$statusLabels = [
    'new' => 'Новый',
    'confirmed' => 'Подтверждён',
    'preparing' => 'Готовится',
    'delivering' => 'Передан курьеру',
    'completed' => 'Доставлен',
    'canceled' => 'Отменён'
];
$statusClasses = [
    'new' => 'zel',
    'confirmed' => 'sin',
    'preparing' => 'ora',
    'delivering' => 'ora',
    'completed' => 'hz',
    'canceled' => 'kiz'
];
?>

<div class="lich container">
    <?php include('includes/l_V.php') ?>

    <div class="lk container">
        <?php include('includes/lk_filter.php') ?>
    </div>
</div>

<div class="ocen container">
    <p>Оцените наше качество и получите <br>
        промокод на 10% к следующему заказу</p>
    <a href="?page=otz">Оставить отзыв</a>
</div>

<div class="ist_zak container">
    <p id="ist">История заказов</p>

    <div class="navigat">
        <a href="?page=lk" class="<?= !$statusFilter ? 'active' : '' ?>" id="vse">Все заказы</a>
        <?php foreach ($statusLabels as $status => $label): ?>
        <a href="?page=lk&status=<?= $status ?>" class="<?= $statusFilter === $status ? 'active' : '' ?>">
            <?= $label ?>
        </a>
        <?php endforeach; ?>
    </div>

    <?php if (empty($orders)): ?>
    <p class="null2">
        <?= $statusFilter ? 'Нет заказов с таким статусом' : 'У вас еще не было заказов, совершите ваш первый заказ' ?>
    </p>
    <?php else: ?>
    <?php foreach ($orders as $order): 
            $items = $orderItems[$order['id']] ?? [];
            $statusClass = $statusClasses[$order['status']] ?? '';
        ?>
    <div class="zak">
        <div class="nom_z">
            <div class="nom1">
                <p>Заказ #<?= $order['id'] ?></p>
                <p><?= date('d.m.Y \в H:i', strtotime($order['order_date'])) ?></p>
            </div>
            <p id="<?= $statusClass ?>"><?= $statusLabels[$order['status']] ?? $order['status'] ?></p>
        </div>

        <?php foreach ($items as $item): 
                $imgPath = ($item['item_type'] === 'set') ? 'na/' : 'bl/';
                $imgSrc = $item['item_image'] ? $imgPath . $item['item_image'] : 'placeholder.png';
            ?>
        <div class="gips">
            <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($item['item_name']) ?>">
            <div class="gips_txt">
                <p><?= htmlspecialchars($item['item_name']) ?></p>
                <div class="kal">
                    <div class="k">
                        <p id="or"><?= (int)$item['kcal'] ?></p>
                        <p id="s">ккал</p>
                    </div>
                    <div class="k">
                        <p id="si"><?= (int)$item['protein'] ?></p>
                        <p id="s">белков</p>
                    </div>
                    <div class="k">
                        <p id="kr"><?= (int)$item['fat'] ?></p>
                        <p id="s">жиров</p>
                    </div>
                    <div class="k">
                        <p id="ze"><?= (int)$item['carbs'] ?></p>
                        <p id="s">углеводов</p>
                    </div>
                </div>
                <h5><?= number_format($item['price_at_time'] * $item['quantity'], 0, '.', ' ') ?> ₽</h5>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="inf_zak">
            <div class="iz1">
                <div class="sp_o">
                    <h5>Способ оплаты</h5>
                    <p><?= $order['payment_method'] === 'card' ? 'Картой' : ($order['payment_method'] === 'cash' ? 'Наличными' : 'Через СБП') ?>
                    </p>
                </div>
                <div class="sp_o">
                    <h5>Доставка по адресу</h5>
                    <p><?= htmlspecialchars($order['delivery_address']) ?></p>
                </div>
                <div class="sp_o">
                    <h5>Дата и время доставки</h5>
                    <p><?= date('d.m.Y', strtotime($order['order_date'])) ?> в
                        <?= htmlspecialchars($order['delivery_time']) ?></p>
                </div>
            </div>
            <h6><?= number_format($order['final_amount'], 0, '.', ' ') ?> ₽</h6>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>