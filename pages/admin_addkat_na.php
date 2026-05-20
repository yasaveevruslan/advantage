<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: index.php?page=auth');
    exit;
}

global $connect;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = floatval(str_replace(',', '.', $_POST['price'] ?? 0));
    $is_available = isset($_POST['is_available']) ? 1 : 0;

    if ($name === '') $errors['name'] = 'Введите название';
    if ($price <= 0) $errors['price'] = 'Введите корректную цену';

    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($_FILES['image']['type'], $allowed)) {
            $errors['image'] = 'Разрешены только JPG, JPEG, PNG';
        } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            $errors['image'] = 'Файл слишком большой (макс. 5 МБ)';
        } else {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = 'set_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $uploadDir = __DIR__ . '/../na/';
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName)) {
                $imagePath = $imageName;
            } else {
                $errors['image'] = 'Ошибка сохранения файла';
            }
        }
    }

    if (empty($errors)) {
        $stmt = $connect->prepare("
            INSERT INTO sets (name, description, price, image, is_available) 
            VALUES (?, ?, ?, ?, ?)
        ");
        if ($stmt->execute([$name, $description, $price, $imagePath, $is_available])) {
            header('Location: index.php?page=admin_kat_na');
            exit;
        } else {
            $errors['general'] = 'Ошибка сохранения: ' . $connect->errorInfo()[2];
        }
    }
}
?>

<div class="adm_dobkat container">
    <h3>Добавить категорию набор</h3>
    <form action="" class="bld" method="post" enctype="multipart/form-data">

        <label for="name">Название *</label>
        <input type="text" id="name" name="name" placeholder="Введите название"
            value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        <?php if(!empty($errors['name'])): ?><p class="error"><?= $errors['name'] ?></p><?php endif; ?>

        <label for="price">Цена *</label>
        <input type="number" id="price" name="price" step="0.01" placeholder="Введите цену"
            value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">
        <?php if(!empty($errors['price'])): ?><p class="error"><?= $errors['price'] ?></p><?php endif; ?>

        <label for="description">Описание</label>
        <textarea id="description" name="description"
            placeholder="Введите описание"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>

        <div class="out_of">
            <p>В наличии</p>
            <label class="toggle-switch">
                <input type="checkbox" id="is_available" name="is_available" value="1"
                    <?= (!isset($_POST['add_set']) || isset($_POST['is_available'])) ? 'checked' : '' ?>>
                <span class="toggle-slider"></span>
            </label>
        </div>

        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg" style="display:none;">
        <label for="image" class="zagr_img">
            <div class="zagf">
                <img src="image/dow.svg" alt="">
                <h5>Загрузите файл в эту область</h5>
                <p>Формат изображения только jpg, jpeg, png</p>
            </div>
        </label>
        <?php if(!empty($errors['image'])): ?><p class="error"><?= $errors['image'] ?></p><?php endif; ?>

        <button type="submit" class="dob_bl_a" name="add_set">Добавить</button>
    </form>
</div>