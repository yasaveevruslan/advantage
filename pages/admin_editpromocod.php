<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: index.php?page=auth');
    exit;
}

global $connect;
$id = intval($_GET['id'] ?? 0);
$errors = [];

$stmt = $connect->prepare("SELECT * FROM promocodes WHERE id = ?");
$stmt->execute([$id]);
$promo = $stmt->fetch();

if (!$promo) {
    echo '<div class="container">Промокод не найден</div>';
    exit;
}

if (isset($_POST['update_promo'])) {
    $code           = strtoupper(trim($_POST['code'] ?? ''));
    $discount_type  = $_POST['discount_type'] ?? '';
    $discount_value = floatval(str_replace(',', '.', $_POST['discount_value'] ?? 0));
    $min_order      = floatval(str_replace(',', '.', $_POST['min_order'] ?? 0));
    $valid_until    = $_POST['valid_until'] !== '' ? $_POST['valid_until'] : null;
    $usage_limit    = intval($_POST['usage_limit'] ?? 0);
    $is_active      = isset($_POST['is_active']) ? 1 : 0;

    if ($code === '') $errors['code'] = 'Введите код промокода';
    if (!in_array($discount_type, ['percentage', 'fixed'])) $errors['discount_type'] = 'Выберите тип скидки';
    if ($discount_type === 'percentage' && ($discount_value <= 0 || $discount_value > 100)) {
        $errors['discount_value'] = 'Процент должен быть от 1 до 100';
    }
    if ($discount_type === 'fixed' && $discount_value <= 0) {
        $errors['discount_value'] = 'Введите сумму скидки';
    }

    if (empty($errors)) {
        $check = $connect->prepare("SELECT id FROM promocodes WHERE code = ? AND id != ?");
        $check->execute([$code, $id]);

        if ($check->fetch()) {
            $errors['code'] = 'Такой промокод уже существует';
        } else {
            $stmt = $connect->prepare("UPDATE promocodes SET code = ?, discount_type = ?, discount_value = ?, min_order = ?, valid_until = ?, usage_limit = ?, is_active = ? WHERE id = ?");

            if ($stmt->execute([$code, $discount_type, $discount_value, $min_order, $valid_until, $usage_limit, $is_active, $id])) {
                header('Location: index.php?page=admin_lk_promocod');
                exit;
            } else {
                $errors['general'] = 'Ошибка обновления. Попробуйте позже.';
            }
        }
    }
}

$currentCode   = $_POST['code'] ?? $promo['code'];
$currentType   = $_POST['discount_type'] ?? $promo['discount_type'];
$currentValue  = $_POST['discount_value'] ?? $promo['discount_value'];
$currentMin    = $_POST['min_order'] ?? $promo['min_order'];
$currentUntil  = $_POST['valid_until'] ?? $promo['valid_until'];
$currentLimit  = $_POST['usage_limit'] ?? $promo['usage_limit'];
$currentActive = $_POST['is_active'] ?? $promo['is_active'];
?>

<div class="dobavit_bludo container">
    <h3>Редактировать промокод</h3>
    <div class="dob_bl">
        <form action="" class="bld" method="POST">

            <label for="code">Название *</label>
            <input type="text" id="code" name="code" placeholder="Например: СПАСИБО10"
                value="<?= htmlspecialchars($currentCode) ?>">
            <?php if(!empty($errors['code'])): ?><p class="error"><?= $errors['code'] ?></p><?php endif; ?>

            <label for="discount_type">Тип скидки *</label>
            <select name="discount_type" id="discount_type">
                <option value="">— Выберите тип —</option>
                <option value="percentage" <?= ($currentType === 'percentage') ? 'selected' : '' ?>>Процент (%)</option>
                <option value="fixed" <?= ($currentType === 'fixed') ? 'selected' : '' ?>>Фиксированная сумма (₽)
                </option>
            </select>
            <?php if(!empty($errors['discount_type'])): ?><p class="error"><?= $errors['discount_type'] ?></p>
            <?php endif; ?>

            <label for="discount_value">Размер скидки *</label>
            <input type="number" id="discount_value" name="discount_value" step="0.01"
                value="<?= htmlspecialchars($currentValue) ?>">
            <?php if(!empty($errors['discount_value'])): ?><p class="error"><?= $errors['discount_value'] ?></p>
            <?php endif; ?>

            <label for="min_order">Минимальная сумма заказа</label>
            <input type="number" id="min_order" name="min_order" step="0.01"
                value="<?= htmlspecialchars($currentMin) ?>">

            <label for="valid_until">Действует до (необязательно)</label>
            <input type="date" id="valid_until" name="valid_until" value="<?= htmlspecialchars($currentUntil) ?>">

            <label for="usage_limit">Лимит использований (0 = безлимит)</label>
            <input type="number" id="usage_limit" name="usage_limit" value="<?= htmlspecialchars($currentLimit) ?>">

            <label>
                <input style="max-height:30px;" type="checkbox" name="is_active" value="1"
                    <?= $currentActive ? 'checked' : '' ?>> Активен
            </label>

            <button type="submit" name="update_promo" class="dob_bl_a">Сохранить изменения</button>
        </form>
    </div>
</div>