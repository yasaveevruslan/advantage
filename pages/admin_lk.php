<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: index.php?page=auth');
    exit;
}

global $connect;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status']) && isset($_POST['order_id'])) {
    $orderId = intval($_POST['order_id']);
    $newStatus = $_POST['status'] ?? '';
    
    $allowed = ['new', 'confirmed', 'preparing', 'delivering', 'completed', 'canceled'];
    
    if (in_array($newStatus, $allowed)) {
        $stmt = $connect->prepare("UPDATE `orders` SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $orderId]);
        $_SESSION['success'] = 'Статус заказа обновлён';
        header('Location: index.php?page=admin_lk');
        exit;
    }
}

$statusFilter = $_GET['status'] ?? 'all';
$where = $statusFilter !== 'all' ? "WHERE o.status = ?" : "";
$params = $statusFilter !== 'all' ? [$statusFilter] : [];

$stmt = $connect->prepare("
    SELECT 
        o.id as order_id, o.status, o.order_date, o.delivery_address, 
        o.total_amount, o.discount_amount, o.final_amount,
        u.full_name as user_name, u.phone as user_phone,
        oi.id as item_id, oi.item_type, oi.quantity, oi.price_at_time,
        d.name as dish_name, d.image as dish_image,
        sd.name as set_name, sd.image as set_image
    FROM `orders` o
    JOIN users u ON o.user_id = u.id
    LEFT JOIN order_items oi ON o.id = oi.order_id
    LEFT JOIN dishes d ON oi.item_type = 'dish' AND oi.item_id = d.id
    LEFT JOIN set_dishes sd ON oi.item_type = 'set' AND oi.item_id = sd.id
    $where
    ORDER BY o.order_date DESC
");
$stmt->execute($params);
$rows = $stmt->fetchAll();

$orders = [];
foreach ($rows as $row) {
    $oid = $row['order_id'];
    
    if (!isset($orders[$oid])) {
        $orders[$oid] = [
            'id' => $oid,
            'status' => $row['status'],
            'order_date' => $row['order_date'],
            'delivery_address' => $row['delivery_address'],
            'total_amount' => $row['total_amount'],
            'discount_amount' => $row['discount_amount'],
            'final_amount' => $row['final_amount'],
            'user_name' => $row['user_name'],
            'user_phone' => $row['user_phone'],
            'items' => []
        ];
    }
    
    if ($row['item_id']) {
        $orders[$oid]['items'][] = [
            'name'       => $row['set_name'] ?? $row['dish_name'] ?? 'Товар удалён',
            'image'      => $row['set_image'] ?? $row['dish_image'],
            'item_type'  => $row['item_type'],
            'quantity'   => $row['quantity'],
            'price'      => $row['price_at_time']
        ];
    }
}

$statusConfig = [
    'new' => ['text' => 'Новый', 'color' => '#2196F3'],
    'confirmed' => ['text' => 'Подтверждён', 'color' => '#00BCD4'],
    'preparing' => ['text' => 'Готовится', 'color' => '#FF9800'],
    'delivering' => ['text' => 'Передан курьеру', 'color' => '#9C27B0'],
    'completed' => ['text' => 'Доставлен', 'color' => '#4CAF50'],
    'canceled' => ['text' => 'Отменён', 'color' => '#f44336'],
];
?>

<div class="lich container">
    <div class="l_v">
        <div class="l_v_panel">
            <h4>Админ-панель</h4>
            <p id="wel">Добро пожаловать, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Админ') ?>!</p>
        </div>
        <a href="php/logout.php">Выйти</a>
    </div>

    <div class="lk container">
        <div class="lk_filter">
            <a href="index.php?page=admin_lk"
                id="<?= (!isset($page) || $page === 'admin_lk') ? 'fil' : '' ?>">Заказы</a>
            <a href="index.php?page=admin_lk_otz"
                id="<?= (isset($page) && $page === 'admin_lk_otz') ? 'fil' : '' ?>">Отзывы</a>
            <a href="index.php?page=admin_lk_promocod"
                id="<?= (isset($page) && $page === 'admin_lk_promocod') ? 'fil' : '' ?>">Промокоды</a>
        </div>
    </div>
</div>

<div class="ist_zak container">
    <p id="ist">История заказов</p>

    <div class="navigat">
        <a href="index.php?page=admin_lk" class="<?= $statusFilter === 'all' ? 'active' : '' ?>">Все заказы</a>
        <?php foreach ($statusConfig as $key => $cfg): ?>
        <a href="index.php?page=admin_lk&status=<?= $key ?>" class="<?= $statusFilter === $key ? 'active' : '' ?>">
            <?= $cfg['text'] ?>
        </a>
        <?php endforeach; ?>
    </div>

    <?php if (empty($orders)): ?>
    <p class="null2" style="text-align:center;padding:30px;color:#666;">Заказов пока нет</p>
    <?php else: ?>

    <?php foreach ($orders as $order): 
            $status = $order['status'];
            $cfg = $statusConfig[$status] ?? $statusConfig['new'];
        ?>
    <div class="zak">
        <div class="nom_z">
            <div class="nom1">
                <p>Заказ #<?= str_pad($order['id'], 4, '0', STR_PAD_LEFT) ?></p>
                <p><?= date('d.m.Y \в H:i', strtotime($order['order_date'])) ?></p>
                <p style="font-size:13px;color:#666;">
                    Клиент: <?= htmlspecialchars($order['user_name']) ?>
                    (<?= htmlspecialchars($order['user_phone']) ?>)
                </p>
            </div>
            <p style="color:<?= $cfg['color'] ?>;font-weight:600;"><?= $cfg['text'] ?></p>
        </div>

        <?php foreach ($order['items'] as $item): 
            $imgFolder = ($item['item_type'] === 'set') ? 'na/' : 'bl/';
            $imgSrc = $item['image'] ? $imgFolder . $item['image'] : 'placeholder.png';
        ?>
        <div class="gips">
            <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
            <div class="gips_txt">
                <p><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></p>
                <h5><?= number_format($item['price'] * $item['quantity'], 0, '.', ' ') ?> ₽</h5>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="inf_zak">
            <div class="iz1">
                <div class="sp_o">
                    <h5>Адрес доставки</h5>
                    <p><?= htmlspecialchars($order['delivery_address'] ?? '—') ?></p>
                </div>
                <?php if ($order['discount_amount'] > 0): ?>
                <div class="sp_o">
                    <h5>Скидка</h5>
                    <p style="color:#4CAF50;">-<?= number_format($order['discount_amount'], 0, '.', ' ') ?> ₽</p>
                </div>
                <?php endif; ?>
            </div>
            <div style="text-align:right;">
                <?php if ($order['total_amount'] != $order['final_amount']): ?>
                <p style="text-decoration:line-through;color:#999;font-size:14px;">
                    <?= number_format($order['total_amount'], 0, '.', ' ') ?> ₽
                </p>
                <?php endif; ?>
                <h6><?= number_format($order['final_amount'], 0, '.', ' ') ?> ₽</h6>
            </div>
        </div>

        <?php if (!in_array($status, ['completed', 'canceled'])): ?>
        <button type="button" class="admin_edit_zakaz" data-order-id="<?= $order['id'] ?>"
            data-current-status="<?= $status ?>">
            Изменить статус заказа
        </button>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<div id="modalOverlay" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Изменить статус заказа</h3>
            <button class="modal-close" id="modalCloseBtn">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" id="statusForm">
                <input type="hidden" name="order_id" id="modalOrderId">
                <input type="hidden" name="update_status" value="1">

                <label for="statusSelect">Новый статус</label>
                <select name="status" id="statusSelect" required
                    style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;margin:10px 0;">
                    <option value="new">Новый</option>
                    <option value="confirmed">Подтверждён</option>
                    <option value="preparing">Готовится</option>
                    <option value="delivering">Передан курьеру</option>
                    <option value="completed">Доставлен</option>
                    <option value="canceled">Отменён</option>
                </select>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn modal-btn-cancel" id="modalCancelBtn">Отмена</button>
            <button type="submit" form="statusForm" class="modal-btn modal-btn-edit">Сохранить</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalOverlay');
    const closeBtn = document.getElementById('modalCloseBtn');
    const cancelBtn = document.getElementById('modalCancelBtn');
    const statusSelect = document.getElementById('statusSelect');
    const modalOrderId = document.getElementById('modalOrderId');

    document.querySelectorAll('.admin_edit_zakaz').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            const currentStatus = this.dataset.currentStatus;

            modalOrderId.value = orderId;
            statusSelect.value = currentStatus;
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    closeBtn?.addEventListener('click', closeModal);
    cancelBtn?.addEventListener('click', closeModal);
    modal?.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal?.classList.contains('active')) closeModal();
    });
});
</script>