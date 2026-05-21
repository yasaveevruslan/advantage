<?php
session_start();
global $connect;

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=auth');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_address'])) {
    $city = trim($_POST['city'] ?? 'Казань');
    $street = trim($_POST['street'] ?? '');
    $house = trim($_POST['house'] ?? '');
    $apartment = trim($_POST['apartment'] ?? '');
    $floor = trim($_POST['floor'] ?? '');
    $entrance = trim($_POST['entrance'] ?? '');
    $comment = trim($_POST['comment'] ?? '');
    $is_default = isset($_POST['is_default']) ? 1 : 0;

    if ($street === '') $errors['street'] = 'Введите улицу';
    if ($house === '') $errors['house'] = 'Введите номер дома';

    if (empty($errors)) {
        try {
            if ($is_default) {
                $connect->prepare("UPDATE addresses SET is_default = 0 WHERE user_id = ?")->execute([$_SESSION['user_id']]);
            }

            $stmt = $connect->prepare("
                INSERT INTO addresses (user_id, city, street, house, apartment, floor, entrance, comment, is_default)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$_SESSION['user_id'], $city, $street, $house, $apartment, $floor, $entrance, $comment, $is_default]);
            
            header('Location: index.php?page=profile');
            exit;
            
        } catch (PDOException $e) {
            $errors['general'] = 'Ошибка сохранения: ' . $e->getMessage();
        }
    }
}
?>

<div class="dobavit_bludo container">
    <h3>Добавить адрес доставки</h3>
    <div class="dob_bl">
        <form action="" class="bld" method="POST">

            <label for="city">Город *</label>
            <input type="text" id="city" name="city" placeholder="Например: Казань"
                value="<?= htmlspecialchars($_POST['city'] ?? 'Казань') ?>">

            <label for="street">Улица *</label>
            <input type="text" id="street" name="street" placeholder="Введите улицу"
                value="<?= htmlspecialchars($_POST['street'] ?? '') ?>">
            <?php if(!empty($errors['street'])): ?><p class="error"><?= $errors['street'] ?></p><?php endif; ?>

            <div class="dkal">
                <div class="dk1">
                    <label for="house">Дом *</label>
                    <input type="text" id="house" name="house" placeholder="№ дома"
                        value="<?= htmlspecialchars($_POST['house'] ?? '') ?>">
                    <?php if(!empty($errors['house'])): ?><p class="error"><?= $errors['house'] ?></p><?php endif; ?>
                </div>
                <div class="dk1">
                    <label for="apartment">Квартира</label>
                    <input type="text" id="apartment" name="apartment" placeholder="№ кв."
                        value="<?= htmlspecialchars($_POST['apartment'] ?? '') ?>">
                </div>
            </div>

            <div class="dkal">
                <div class="dk1">
                    <label for="floor">Этаж</label>
                    <input type="text" id="floor" name="floor" placeholder="Этаж"
                        value="<?= htmlspecialchars($_POST['floor'] ?? '') ?>">
                </div>
                <div class="dk1">
                    <label for="entrance">Подъезд</label>
                    <input type="text" id="entrance" name="entrance" placeholder="Подъезд"
                        value="<?= htmlspecialchars($_POST['entrance'] ?? '') ?>">
                </div>
            </div>

            <label for="comment">Комментарий для курьера</label>
            <textarea id="comment" name="comment"
                placeholder="Например: домофон не работает, код 1234"><?= htmlspecialchars($_POST['comment'] ?? '') ?></textarea>

            <div class="out_of">
                <p>Сделать основным адресом</p>
                <label class="toggle-switch">
                    <input type="checkbox" id="is_default" name="is_default" value="1"
                        <?= (!isset($_POST['add_address']) || isset($_POST['is_default'])) ? 'checked' : '' ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <?php if(!empty($errors['general'])): ?>
            <p class="error"><?= $errors['general'] ?></p>
            <?php endif; ?>

            <button type="submit" class="add" name="add_address">Сохранить адрес</button>
        </form>
    </div>
</div>