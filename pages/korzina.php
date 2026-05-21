<?php
session_start();
global $connect;

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=auth');
    exit;
}
$userId = (int)$_SESSION['user_id'];

// Обработка промокода (единственное, что осталось в этой форме)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_promo'])) {
    $code = strtoupper(trim($_POST['promo_code'] ?? ''));
    $_SESSION['promo_applied'] = $code;
    $_SESSION['promo_success'] = "Промокод $code применён!";
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

// Удаление товара (если форма удаления отправлена)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $cartId = (int)($_POST['cart_id'] ?? 0);
    $connect->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?")->execute([$cartId, $userId]);
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

$sql = "SELECT c.id as cart_id, c.item_type, c.item_id, c.quantity, c.added_at,
               COALESCE(sd.name, d.name) as name,
               COALESCE(sd.price, d.price) as price,
               COALESCE(sd.image, d.image) as image,
               COALESCE(sd.is_available, d.is_available) as is_available,
               COALESCE(sd.kcal, d.kcal) as kcal,
               COALESCE(sd.protein, d.protein) as protein,
               COALESCE(sd.fat, d.fat) as fat,
               COALESCE(sd.carbs, d.carbs) as carbs
        FROM cart c
        LEFT JOIN set_dishes sd ON c.item_type = 'set' AND c.item_id = sd.id
        LEFT JOIN dishes d ON c.item_type = 'dish' AND c.item_id = d.id
        WHERE c.user_id = ?
        ORDER BY c.added_at DESC";

$stmt = $connect->prepare($sql);
$stmt->execute([$userId]);
$rows = $stmt->fetchAll();

$cartItems = [];
foreach ($rows as $row) {
    if (!$row['name'] || $row['is_available'] == 0) continue;
    
    $item = [
        'cart_id' => $row['cart_id'],
        'type' => $row['item_type'],
        'id' => $row['item_id'],
        'name' => $row['name'],
        'price' => (float)$row['price'],
        'image' => $row['image'],
        'quantity' => (int)$row['quantity'],
        'kcal' => (int)($row['kcal'] ?? 0),
        'protein' => (int)($row['protein'] ?? 0),
        'fat' => (int)($row['fat'] ?? 0),
        'carbs' => (int)($row['carbs'] ?? 0),
        'added_at' => $row['added_at']
    ];
    
    if ($row['item_type'] === 'set') {
        $stmtDetails = $connect->prepare("
            SELECT d.name, d.kcal, d.protein, d.fat, d.carbs, sc.quantity,
                   d.kcal * sc.quantity as item_kcal,
                   d.protein * sc.quantity as item_protein,
                   d.fat * sc.quantity as item_fat,
                   d.carbs * sc.quantity as item_carbs
            FROM set_composition sc
            JOIN dishes d ON sc.dish_id = d.id
            WHERE sc.set_dish_id = ?
        ");
        $stmtDetails->execute([$row['item_id']]);
        $dishes = $stmtDetails->fetchAll();
        
        $item['details'] = [
            'dishes' => $dishes,
            'total_kcal' => array_sum(array_column($dishes, 'item_kcal')),
            'total_protein' => array_sum(array_column($dishes, 'item_protein')),
            'total_fat' => array_sum(array_column($dishes, 'item_fat')),
            'total_carbs' => array_sum(array_column($dishes, 'item_carbs'))
        ];
    }
    
    $cartItems[] = $item;
}

$cartTotal = 0.0;
$totalKcal = $totalProtein = $totalFat = $totalCarbs = 0;

foreach ($cartItems as $item) {
    $cartTotal += $item['price'] * $item['quantity'];
    
    if ($item['type'] === 'set' && !empty($item['details'])) {
        $totalKcal += $item['details']['total_kcal'] * $item['quantity'];
        $totalProtein += $item['details']['total_protein'] * $item['quantity'];
        $totalFat += $item['details']['total_fat'] * $item['quantity'];
        $totalCarbs += $item['details']['total_carbs'] * $item['quantity'];
    } else {
        $totalKcal += $item['kcal'] * $item['quantity'];
        $totalProtein += $item['protein'] * $item['quantity'];
        $totalFat += $item['fat'] * $item['quantity'];
        $totalCarbs += $item['carbs'] * $item['quantity'];
    }
}

$cartTotal = round($cartTotal, 2);
$discount = 0;
if (!empty($_SESSION['promo_applied'])) {
    $discount = round($cartTotal * 0.10, 2);
}
$finalTotal = round($cartTotal - $discount, 2);
?>

<p id="hleb" class="container">
    <a href="index.php">Главная</a> > Корзина
</p>

<div class="korzina container">
    <h3>Ваша корзина</h3>

    <?php if (empty($cartItems)): ?>
    <p style="text-align:center;padding:40px 0;color:#666;">
        Корзина пуста. <a href="index.php?page=catalog_blud" style="color:#94D201;">Перейти в каталог</a>
    </p>
    <?php else: ?>
    <!-- Форма только для промокода -->
    <form method="POST">
        <div class="korz">
            <div class="o5">
                <div class="sos_nab">
                    <?php foreach ($cartItems as $item): 
                            $imagePath = ($item['type'] === 'set') ? 'na/' : 'bl/';
                            $imageSrc = $item['image'] ? $imagePath . $item['image'] : 'placeholder.png';
                        ?>
                    <div class="gips">
                        <img src="<?= htmlspecialchars($imageSrc) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        <div class="gips_txt">
                            <p>
                                <?= htmlspecialchars($item['name']) ?>
                                <?php if ($item['type'] === 'set'): ?>
                                <small style="color:#888;font-size:12px;">(набор)</small>
                                <?php endif; ?>
                            </p>

                            <?php if ($item['type'] === 'set' && !empty($item['details']['dishes'])): ?>
                            <ul style="font-size:12px;color:#666;margin:5px 0 10px;padding-left:20px;">
                                <?php foreach ($item['details']['dishes'] as $dish): ?>
                                <li><?= htmlspecialchars($dish['name']) ?> ×<?= $dish['quantity'] ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>

                            <div class="kal">
                                <?php if ($item['type'] === 'set' && !empty($item['details'])): ?>
                                <div class="k">
                                    <p id="or"><?= (int)$item['details']['total_kcal'] ?></p>
                                    <p id="s">ккал</p>
                                </div>
                                <div class="k">
                                    <p id="si"><?= (int)$item['details']['total_protein'] ?></p>
                                    <p id="s">белков</p>
                                </div>
                                <div class="k">
                                    <p id="kr"><?= (int)$item['details']['total_fat'] ?></p>
                                    <p id="s">жиров</p>
                                </div>
                                <div class="k">
                                    <p id="ze"><?= (int)$item['details']['total_carbs'] ?></p>
                                    <p id="s">углеводов</p>
                                </div>
                                <?php else: ?>
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
                                <?php endif; ?>
                            </div>

                            <h5><?= number_format($item['price'] * $item['quantity'], 0, '.', ' ') ?> ₽</h5>

                            <div
                                style="display:flex;align-items:center;gap:8px;justify-content:space-between;margin-top:10px;">
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <a href="php/update_cart_quantity.php?item_id=<?= $item['id'] ?>&item_type=<?= $item['type'] ?>&action=decrease"
                                        style="background:#f5f5f5;border:1px solid #ddd;width:30px;height:30px;display:flex;align-items:center;justify-content:center;text-decoration:none;border-radius:4px;font-size:18px;color:#333;">−</a>
                                    <span
                                        style="min-width:30px;text-align:center;font-weight:600;"><?= $item['quantity'] ?></span>

                                    <a href="php/update_cart_quantity.php?item_id=<?= $item['id'] ?>&item_type=<?= $item['type'] ?>&action=increase"
                                        style="background:#94D201;color:#fff;text-decoration:none;width:30px;height:30px;display:flex;align-items:center;justify-content:center;border-radius:4px;font-size:18px;">+</a>

                                </div>

                                <form method="POST" style="margin-left:10px;">
                                    <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                    <button type="submit" name="remove_item" value="1"
                                        style="background:none;border:none;color:#f44336;cursor:pointer;font-size:18px;"
                                        title="Удалить">
                                        ⨉
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="kbzu">
                <div class="kb1">
                    <h4>Итого КБЖУ</h4>
                    <div class="kor_kal">
                        <p>Калории</p>
                        <h4><?= (int)$totalKcal ?></h4>
                    </div>
                    <div class="kor_kal2">
                        <div class="kor_b">
                            <h4 id="si"><?= (int)$totalProtein ?></h4>
                            <p>белков</p>
                        </div>
                        <div class="kor_b">
                            <h4 id="kr"><?= (int)$totalFat ?></h4>
                            <p>жиров</p>
                        </div>
                        <div class="kor_b">
                            <h4 id="ze"><?= (int)$totalCarbs ?></h4>
                            <p>углеводов</p>
                        </div>
                    </div>
                </div>

                <div class="kb1">
                    <div class="kor_prom">
                        <input type="text" name="promo_code" placeholder="Промокод"
                            value="<?= htmlspecialchars($_SESSION['promo_applied'] ?? '') ?>">
                        <button type="submit" name="apply_promo">Применить</button>
                    </div>

                    <?php if (!empty($_SESSION['promo_success'])): ?>
                    <p><?= $_SESSION['promo_success'] ?><?php unset($_SESSION['promo_success']); ?></p>
                    <?php endif; ?>

                    <div class="kor_sum">
                        <?php if ($discount > 0): ?>
                        <p style="text-decoration:line-through;color:#999;font-size:14px;">
                            <?= number_format($cartTotal, 0, '.', ' ') ?> ₽
                        </p>
                        <p style="color:#4CAF50;font-size:14px;">Скидка: -<?= number_format($discount, 0, '.', ' ') ?> ₽
                        </p>
                        <?php endif; ?>
                        <p>Сумма заказа</p>
                        <h4><?= number_format($finalTotal, 0, '.', ' ') ?> ₽</h4>
                    </div>

                    <a href="index.php?page=zakaz_dost">К оформлению заказа</a>
                </div>
            </div>
        </div>

        <div style="margin-top:20px;display:flex;gap:10px;justify-content:flex-end;">
            <a href="index.php?page=catalog_blud" style="color:#666;text-decoration:none;padding:10px 20px;">←
                Продолжить покупки</a>
        </div>
    </form>
    <?php endif; ?>
</div>