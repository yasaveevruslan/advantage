<?php
session_start();
global $connect;

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=auth');
    exit;
}

$userId = $_SESSION['user_id'];
$errors = [];
$orderSuccess = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $deliveryAddress = trim($_POST['delivery_address'] ?? '');
    $customAddress = trim($_POST['custom_address'] ?? '');
    $deliveryTime = trim($_POST['delivery_time'] ?? '');
    $comment = trim($_POST['comment'] ?? '');
    $paymentMethod = $_POST['payment_method'] ?? '';
    $promoCode = strtoupper(trim($_POST['promo_code'] ?? ''));
    
    $finalAddress = ($deliveryAddress === 'other') ? $customAddress : $deliveryAddress;
    
    if ($finalAddress === '') $errors['address'] = 'Введите адрес доставки';
    if ($deliveryTime === '') $errors['time'] = 'Выберите время доставки';
    if (!in_array($paymentMethod, ['card', 'cash', 'sbp'])) $errors['payment'] = 'Выберите способ оплаты';
    
    $cartStmt = $connect->prepare("
        SELECT c.id as cart_id, c.item_type, c.item_id, c.quantity,
               COALESCE(sd.price, d.price) as price,
               COALESCE(sd.name, d.name) as name
        FROM cart c
        LEFT JOIN set_dishes sd ON c.item_type = 'set' AND c.item_id = sd.id
        LEFT JOIN dishes d ON c.item_type = 'dish' AND c.item_id = d.id
        WHERE c.user_id = ? AND (sd.is_available = 1 OR d.is_available = 1)
    ");
    $cartStmt->execute([$userId]);
    $cartItems = $cartStmt->fetchAll();
    
    if (empty($cartItems)) {
        $errors['general'] = 'Корзина пуста';
    }
    
    $totalAmount = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cartItems));
    $discountAmount = 0;
    
    $promoId = null;
    if ($promoCode !== '') {
        $promoStmt = $connect->prepare("
            SELECT id, discount_type, discount_value, min_order, valid_until, usage_limit, used_count
            FROM promocodes 
            WHERE code = ? AND is_active = 1
        ");
        $promoStmt->execute([$promoCode]);
        $promo = $promoStmt->fetch();
        
        if ($promo) {
            $now = date('Y-m-d');
            $validUntil = $promo['valid_until'] ? date('Y-m-d', strtotime($promo['valid_until'])) : null;
            
            if ($totalAmount >= $promo['min_order'] && 
                (!$validUntil || $now <= $validUntil) && 
                (!$promo['usage_limit'] || $promo['used_count'] < $promo['usage_limit'])) {
                
                $promoId = $promo['id'];
                $discountAmount = ($promo['discount_type'] === 'percentage') 
                    ? $totalAmount * ($promo['discount_value'] / 100) 
                    : min($promo['discount_value'], $totalAmount);
            } else {
                $errors['promo'] = 'Промокод недействителен для этого заказа';
            }
        } else {
            $errors['promo'] = 'Промокод не найден';
        }
    }
    
    $finalAmount = $totalAmount - $discountAmount;
    
    if (empty($errors)) {
        try {
            $connect->beginTransaction();
            
            $orderStmt = $connect->prepare("
                INSERT INTO orders 
                (user_id, delivery_address, delivery_time, payment_method, user_comment, total_amount, promocode_id, discount_amount, final_amount, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'new')
            ");
            $orderStmt->execute([$userId, $finalAddress, $deliveryTime, $paymentMethod, $comment, $totalAmount, $promoId, $discountAmount, $finalAmount]);
            $orderId = $connect->lastInsertId();
            
            $itemStmt = $connect->prepare("
                INSERT INTO order_items (order_id, item_type, item_id, quantity, price_at_time) 
                VALUES (?, ?, ?, ?, ?)
            ");
            foreach ($cartItems as $item) {
                $itemStmt->execute([
                    $orderId,
                    $item['item_type'],
                    $item['item_id'],
                    $item['quantity'],
                    $item['price']
                ]);
            }
            
            if ($promoId) {
                $connect->prepare("UPDATE promocodes SET used_count = used_count + 1 WHERE id = ?")
                        ->execute([$promoId]);
            }
            
            $connect->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$userId]);
            
            $connect->commit();
            $orderSuccess = [
                'id' => $orderId,
                'amount' => $finalAmount,
                'date' => date('d.m.Y H:i')
            ];
            
        } catch (PDOException $e) {
            $connect->rollBack();
            $errors['general'] = 'Ошибка оформления: ' . $e->getMessage();
        }
    }
}

if ($orderSuccess):
?>
<div class="container" style="padding:40px 0; text-align:center;">
    <div
        style="background:#fff; border-radius:12px; padding:30px; max-width:500px; margin:0 auto; box-shadow:0 5px 20px rgba(0,0,0,0.1);">
        <div style="font-size:48px; color:#4CAF50; margin-bottom:15px;">✓</div>
        <h3 style="margin:0 0 10px;">Заказ оформлен!</h3>
        <p style="color:#666; margin:0 0 20px;">Номер заказа: <strong>#<?= $orderSuccess['id'] ?></strong></p>
        <p style="margin:0 0 5px;">Сумма: <strong><?= number_format($orderSuccess['amount'], 0, '.', ' ') ?> ₽</strong>
        </p>
        <p style="color:#888; font-size:14px; margin:0 0 25px;">Дата: <?= $orderSuccess['date'] ?></p>
        <a href="index.php?page=lk"
            style="display:inline-block; background:#94D201; color:#fff; padding:12px 30px; border-radius:30px; text-decoration:none; font-weight:600;">
            В личный кабинет
        </a>
        <p style="margin:15px 0 0; font-size:13px; color:#888;">
            <a href="index.php?page=catalog_blud" style="color:#94D201; text-decoration:none;">Продолжить покупки →</a>
        </p>
    </div>
</div>
<?php
    exit;
endif;
$userStmt = $connect->prepare("SELECT full_name, phone, email FROM users WHERE id = ?");
$userStmt->execute([$userId]);
$user = $userStmt->fetch();

$addrStmt = $connect->prepare("SELECT id, city, street, house, apartment FROM addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC");
$addrStmt->execute([$userId]);
$savedAddresses = $addrStmt->fetchAll();

$cartStmt = $connect->prepare("
    SELECT c.id as cart_id, c.item_type, c.item_id, c.quantity,
           COALESCE(sd.name, d.name) as name,
           COALESCE(sd.price, d.price) as price,
           COALESCE(sd.image, d.image) as image,
           COALESCE(sd.kcal, d.kcal) as kcal,
           COALESCE(sd.protein, d.protein) as protein,
           COALESCE(sd.fat, d.fat) as fat,
           COALESCE(sd.carbs, d.carbs) as carbs
    FROM cart c
    LEFT JOIN set_dishes sd ON c.item_type = 'set' AND c.item_id = sd.id
    LEFT JOIN dishes d ON c.item_type = 'dish' AND c.item_id = d.id
    WHERE c.user_id = ?
    ORDER BY c.added_at DESC
");
$cartStmt->execute([$userId]);
$cartItems = $cartStmt->fetchAll();

$cartTotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cartItems));
$totalKcal = array_sum(array_map(fn($i) => ($i['kcal'] ?? 0) * $i['quantity'], $cartItems));
$totalProtein = array_sum(array_map(fn($i) => ($i['protein'] ?? 0) * $i['quantity'], $cartItems));
$totalFat = array_sum(array_map(fn($i) => ($i['fat'] ?? 0) * $i['quantity'], $cartItems));
$totalCarbs = array_sum(array_map(fn($i) => ($i['carbs'] ?? 0) * $i['quantity'], $cartItems));

$discount = 0;
if (!empty($_SESSION['promo_applied'])) {
    $promoStmt = $connect->prepare("SELECT discount_type, discount_value, min_order FROM promocodes WHERE code = ? AND is_active = 1");
    $promoStmt->execute([$_SESSION['promo_applied']]);
    $promo = $promoStmt->fetch();
    if ($promo && $cartTotal >= $promo['min_order']) {
        $discount = ($promo['discount_type'] === 'percentage') 
            ? $cartTotal * ($promo['discount_value'] / 100) 
            : min($promo['discount_value'], $cartTotal);
    }
}
$finalTotal = $cartTotal - $discount;
$deliveryFee = $finalTotal >= 2500 ? 0 : 190;
$grandTotal = $finalTotal + $deliveryFee;
?>

<p id="hleb" class="container">
    <a href="index.php">Главная</a> > <a href="index.php?page=korzina">Корзина</a> > Оформление заказа
</p>

<div class="korzina container">
    <h3>Заказ на доставку</h3>

    <?php if (!empty($errors['general'])): ?>
    <p style="background:#ffebee; color:#c62828; padding:12px; border-radius:6px; margin-bottom:20px;">
        <?= htmlspecialchars($errors['general']) ?>
    </p>
    <?php endif; ?>

    <form method="POST">
        <div class="korz">
            <div class="o7">
                <div class="aaa">
                    <label for="fullname">ФИО *</label>
                    <p><?= htmlspecialchars($user['full_name'] ?? 'Не указано') ?></p>

                    <label for="phone">Номер телефона *</label>
                    <input type="tel" id="phone" name="phone"
                        value="<?= htmlspecialchars($_POST['phone'] ?? $user['phone'] ?? '') ?>"
                        placeholder="+7 (___) ___-__-__" required
                        style="width:100%; padding:10px; border:1px solid #ddd; border-radius:6px; margin-bottom:15px;">
                </div>

                <div class="aaa">
                    <label for="delivery_address">Адрес доставки *</label>
                    <select name="delivery_address" id="delivery_address" required onchange="toggleCustomAddress()">
                        <option value="">— Выберите адрес —</option>
                        <?php foreach ($savedAddresses as $addr): 
                            $addrText = "{$addr['city']}, ул. {$addr['street']}, д. {$addr['house']}";
                            if ($addr['apartment']) $addrText .= ", кв. {$addr['apartment']}";
                        ?>
                        <option value="<?= htmlspecialchars($addrText) ?>"
                            <?= ($_POST['delivery_address'] ?? '') === $addrText ? 'selected' : '' ?>>
                            <?= htmlspecialchars($addrText) ?>
                        </option>
                        <?php endforeach; ?>
                        <option value="other" <?= ($_POST['delivery_address'] ?? '') === 'other' ? 'selected' : '' ?>>+
                            Другой адрес</option>
                    </select>

                    <input type="text" id="custom_address" name="custom_address" placeholder="Введите полный адрес"
                        value="<?= htmlspecialchars($_POST['custom_address'] ?? '') ?>"
                        style="width:100%; padding:10px; border:1px solid #ddd; border-radius:6px; margin-bottom:15px; display: <?= (($_POST['delivery_address'] ?? '') === 'other') ? 'block' : 'none' ?>;">

                    <label for="delivery_time">Время доставки *</label>
                    <select name="delivery_time" id="delivery_time">
                        <option value="">— Выберите время —</option>
                        <option value="10:00" <?= ($_POST['delivery_time'] ?? '') === '10:00' ? 'selected' : '' ?>>10:00
                        </option>
                        <option value="12:00" <?= ($_POST['delivery_time'] ?? '') === '12:00' ? 'selected' : '' ?>>12:00
                        </option>
                        <option value="14:00" <?= ($_POST['delivery_time'] ?? '') === '14:00' ? 'selected' : '' ?>>14:00
                        </option>
                        <option value="16:00" <?= ($_POST['delivery_time'] ?? '') === '16:00' ? 'selected' : '' ?>>16:00
                        </option>
                        <option value="18:00" <?= ($_POST['delivery_time'] ?? '') === '18:00' ? 'selected' : '' ?>>18:00
                        </option>
                    </select>

                    <label for="comment">Комментарий для курьера</label>
                    <textarea name="comment" id="comment"
                        placeholder="Например: домофон не работает"><?= htmlspecialchars($_POST['comment'] ?? '') ?></textarea>
                </div>

                <div class="aaa">
                    <h5>Способы оплаты</h5>
                    <div class="sp_opl">
                        <div class="sp1">
                            <input type="radio" id="opt1" name="payment_method" value="card"
                                <?= ($_POST['payment_method'] ?? '') === 'card' ? 'checked' : '' ?> required>
                            <label for="opt1"><img src="image/card.png" alt=""> Картой на сайте</label>
                        </div>
                        <div class="sp1">
                            <input type="radio" id="opt2" name="payment_method" value="cash"
                                <?= ($_POST['payment_method'] ?? '') === 'cash' ? 'checked' : '' ?>>
                            <label for="opt2"><img src="image/nal.png" alt=""> Наличными</label>
                        </div>
                        <div class="sp1">
                            <input type="radio" id="opt3" name="payment_method" value="sbp"
                                <?= ($_POST['payment_method'] ?? '') === 'sbp' ? 'checked' : '' ?>>
                            <label for="opt3"><img src="image/spb.png" alt=""> Через СБП</label>
                        </div>
                    </div>
                    <?php if(!empty($errors['payment'])): ?>
                    <p style="color:#c62828; font-size:13px; margin-top:5px;"><?= $errors['payment'] ?></p>
                    <?php endif; ?>
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
                        <button type="submit" name="apply_promo">
                            Применить
                        </button>
                    </div>
                    <?php if (!empty($errors['promo'])): ?>
                    <p style="color:#c62828; font-size:13px; margin:5px 0;"><?= $errors['promo'] ?></p>
                    <?php elseif (!empty($_SESSION['promo_success'])): ?>
                    <p style="color:#4CAF50; font-size:13px; margin:5px 0;">
                        <?= $_SESSION['promo_success'] ?><?php unset($_SESSION['promo_success']); ?></p>
                    <?php endif; ?>

                    <div class="kor_sum">
                        <p>Сумма товаров</p>
                        <h4><?= number_format($cartTotal, 0, '.', ' ') ?> ₽</h4>
                    </div>
                    <?php if ($discount > 0): ?>
                    <div class="kor_sum">
                        <p>Скидка</p>
                        <h4 style="color:#4CAF50;">-<?= number_format($discount, 0, '.', ' ') ?> ₽</h4>
                    </div>
                    <?php endif; ?>
                    <div class="kor_sum">
                        <p>Доставка</p>
                        <h4><?= $deliveryFee > 0 ? number_format($deliveryFee, 0, '.', ' ') . ' ₽' : 'Бесплатно' ?></h4>
                    </div>
                    <div class="kor_sum">
                        <p>Итого к оплате</p>
                        <h4><?= number_format($grandTotal, 0, '.', ' ') ?> ₽</h4>
                    </div>

                    <button type="submit" name="place_order"
                        style="display:block; width:100%; background:#94D201; color:#fff; padding:14px; border:none; border-radius:12px; font-weight:600; font-size:16px; cursor:pointer; margin-top:20px;">
                        Оформить заказ
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function toggleCustomAddress() {
    const select = document.getElementById('delivery_address');
    const custom = document.getElementById('custom_address');
    custom.style.display = select.value === 'other' ? 'block' : 'none';
    if (select.value !== 'other') custom.value = '';
}

document.addEventListener('DOMContentLoaded', toggleCustomAddress);

document.getElementById('phone')?.addEventListener('input', function(e) {
    let val = e.target.value.replace(/\D/g, '');
    if (val.length > 0) val = '+7 (' + val.slice(1, 4);
    if (val.length > 6) val = val.slice(0, 6) + ') ' + val.slice(6, 9);
    if (val.length > 11) val = val.slice(0, 11) + '-' + val.slice(11, 13);
    if (val.length > 14) val = val.slice(0, 14) + '-' + val.slice(14, 16);
    e.target.value = val;
});
</script>