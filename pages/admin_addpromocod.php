<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: index.php?page=auth');
    exit;
}

global $connect;
$errors = [];

if (isset($_POST['add_promo'])) {
    $code           = strtoupper(trim($_POST['code'] ?? ''));
    $discount_type  = $_POST['discount_type'] ?? '';
    $discount_raw   = trim($_POST['discount_value'] ?? '');
    $min_order_raw  = trim($_POST['min_order'] ?? '');
    $valid_until    = $_POST['valid_until'] !== '' ? $_POST['valid_until'] : null;
    $usage_limit    = $_POST['usage_limit'] !== '' ? intval($_POST['usage_limit']) : null;
    $is_active      = isset($_POST['is_active']) ? 1 : 0;

    $discount_value = $discount_raw !== '' ? floatval(str_replace(',', '.', $discount_raw)) : 0.0;
    $min_order      = $min_order_raw !== '' ? floatval(str_replace(',', '.', $min_order_raw)) : 0.0;

    if ($code === '') $errors['code'] = 'Введите код промокода';
    
    if (!in_array($discount_type, ['percentage', 'fixed'])) {
        $errors['discount_type'] = 'Выберите тип скидки';
    }
    
    if ($discount_raw === '') {
        $errors['discount_value'] = 'Введите размер скидки';
    } elseif ($discount_type === 'percentage' && ($discount_value <= 0 || $discount_value > 100)) {
        $errors['discount_value'] = 'Процент должен быть от 1 до 100';
    } elseif ($discount_type === 'fixed' && $discount_value <= 0) {
        $errors['discount_value'] = 'Сумма скидки должна быть больше 0';
    }

    if ($min_order_raw === '' || $min_order < 0) {
        $errors['min_order'] = 'Введите минимальную сумму заказа';
    }

    if (empty($errors)) {
        $check = $connect->prepare("SELECT id FROM promocodes WHERE code = ?");
        $check->execute([$code]);
        
        if ($check->fetch()) {
            $errors['code'] = 'Такой промокод уже существует';
        } else {
            $stmt = $connect->prepare("INSERT INTO promocodes (code, discount_type, discount_value, min_order, valid_until, usage_limit, used_count, is_active) VALUES (?, ?, ?, ?, ?, ?, 0, ?)");
            
            if ($stmt->execute([$code, $discount_type, $discount_value, $min_order, $valid_until, $usage_limit, $is_active])) {
                header('Location: index.php?page=admin_lk_promocod');
                exit;
            } else {
                $errors['general'] = 'Ошибка сохранения: ' . $connect->errorInfo()[2];
            }
        }
    }
}
?>

<div class="dobavit_bludo container">
    <h3>Добавить промокод</h3>
    <div class="dob_bl">
        <form action="" class="bld" method="POST">
            <label for="code">Название *</label>
            <input type="text" id="code" name="code" placeholder="Например: СПАСИБО10"
                value="<?= htmlspecialchars($_POST['code'] ?? '') ?>">
            <?php if(!empty($errors['code'])): ?><p class="error"><?= $errors['code'] ?></p><?php endif; ?>

            <label for="discount_type">Тип скидки *</label>
            <select name="discount_type" id="discount_type">
                <option value=""
                    <?= (!isset($_POST['discount_type']) || $_POST['discount_type'] === '') ? 'selected' : '' ?>>—
                    Выберите тип —</option>
                <option value="percentage" <?= ($_POST['discount_type'] ?? '') === 'percentage' ? 'selected' : '' ?>>
                    Процент (%)</option>
                <option value="fixed" <?= ($_POST['discount_type'] ?? '') === 'fixed' ? 'selected' : '' ?>>Фиксированная
                    сумма (₽)</option>
            </select>
            <?php if(!empty($errors['discount_type'])): ?><p class="error"><?= $errors['discount_type'] ?></p>
            <?php endif; ?>

            <label for="discount_value">Размер скидки *</label>
            <input type="number" id="discount_value" name="discount_value" placeholder="10" step="0.01"
                value="<?= htmlspecialchars($_POST['discount_value'] ?? '') ?>">
            <?php if(!empty($errors['discount_value'])): ?><p class="error"><?= $errors['discount_value'] ?></p>
            <?php endif; ?>

            <label for="min_order">Минимальная сумма заказа *</label>
            <input type="number" id="min_order" name="min_order" placeholder="1500" step="0.01"
                value="<?= htmlspecialchars($_POST['min_order'] ?? '') ?>">
            <?php if(!empty($errors['min_order'])): ?><p class="error"><?= $errors['min_order'] ?></p><?php endif; ?>

            <label for="valid_until">Действует до (необязательно)</label>
            <input type="date" id="valid_until" name="valid_until"
                value="<?= htmlspecialchars($_POST['valid_until'] ?? '') ?>">

            <label for="usage_limit">Лимит использований (пусто = безлимит)</label>
            <input type="number" id="usage_limit" name="usage_limit" placeholder="0"
                value="<?= htmlspecialchars($_POST['usage_limit'] ?? '') ?>">

            <label>
                <input style="max-height:30px;" type="checkbox" name="is_active" value="1"
                    <?= (!isset($_POST['add_promo']) || isset($_POST['is_active'])) ? 'checked' : '' ?>> Активен
            </label>

            <button type="submit" class="dob_bl_a" name="add_promo">Добавить</button>
        </form>
    </div>
</div>