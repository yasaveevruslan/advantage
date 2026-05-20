<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: index.php?page=auth');
    exit;
}

global $connect;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $kcal = intval($_POST['kcal'] ?? 0);
    $protein = intval($_POST['protein'] ?? 0);
    $fat = intval($_POST['fat'] ?? 0);
    $carbs = intval($_POST['carbs'] ?? 0);
    $category_id = intval($_POST['category_id'] ?? 0);
    $price = floatval(str_replace(',', '.', $_POST['price'] ?? 0));
    $description = trim($_POST['description'] ?? '');
    $ingredients = trim($_POST['ingredients'] ?? '');
    
    $is_available = isset($_POST['is_available']) ? 1 : 0;

    if ($name === '') $errors['name'] = 'Введите название';
    if ($price <= 0) $errors['price'] = 'Введите корректную цену';
    if ($category_id <= 0) $errors['category_id'] = 'Выберите категорию';

    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($_FILES['image']['type'], $allowed)) {
            $errors['image'] = 'Разрешены только JPG, JPEG, PNG';
        } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            $errors['image'] = 'Файл слишком большой (макс. 5 МБ)';
        } else {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = 'dish_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $uploadDir = __DIR__ . '/../bl/';
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName)) {
                $imagePath = $imageName;
            } else {
                $errors['image'] = 'Ошибка сохранения файла';
            }
        }
    }

    if (empty($errors)) {
        $stmt = $connect->prepare("
            INSERT INTO dishes (name, description, price, image, category_id, is_available, kcal, protein, fat, carbs, ingredients) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        if ($stmt->execute([$name, $description, $price, $imagePath, $category_id, $is_available, $kcal, $protein, $fat, $carbs, $ingredients])) {
            header('Location: index.php?page=admin_kat_bl');
            exit;
        } else {
            $errors['general'] = 'Ошибка сохранения';
        }
    }
}

$categories = $connect->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
?>

<div class="dobavit_bludo container">
    <h3>Добавить блюдо</h3>
    <div class="dob_bl">
        <form action="" class="bld" method="POST" enctype="multipart/form-data">
            <label for="name">Название *</label>
            <input type="text" id="name" name="name" placeholder="Введите название"
                value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            <?php if(!empty($errors['name'])): ?><p class="error"><?= $errors['name'] ?></p><?php endif; ?>

            <div class="dkal">
                <div class="dk1">
                    <label for="kcal">Калорий (ккал) *</label>
                    <input type="number" id="kcal" name="kcal" placeholder="Введите калории"
                        value="<?= htmlspecialchars($_POST['kcal'] ?? '') ?>">
                </div>
                <div class="dk1">
                    <label for="protein">Белков *</label>
                    <input type="number" id="protein" name="protein" placeholder="Введите белки"
                        value="<?= htmlspecialchars($_POST['protein'] ?? '') ?>">
                </div>
                <div class="dk1">
                    <label for="fat">Жиры *</label>
                    <input type="number" id="fat" name="fat" placeholder="Введите жиры"
                        value="<?= htmlspecialchars($_POST['fat'] ?? '') ?>">
                </div>
                <div class="dk1">
                    <label for="carbs">Углеводы *</label>
                    <input type="number" id="carbs" name="carbs" placeholder="Введите углеводы"
                        value="<?= htmlspecialchars($_POST['carbs'] ?? '') ?>">
                </div>
            </div>

            <label for="category_id">Категория *</label>
            <select name="category_id" id="category_id">
                <option value="">— Выберите категорию —</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($_POST['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <?php if(!empty($errors['category_id'])): ?><p class="error"><?= $errors['category_id'] ?></p>
            <?php endif; ?>

            <label for="price">Цена *</label>
            <input type="number" id="price" name="price" step="0.01" placeholder="Введите цену"
                value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">
            <?php if(!empty($errors['price'])): ?><p class="error"><?= $errors['price'] ?></p><?php endif; ?>

            <label for="description">Описание</label>
            <textarea id="description" name="description"
                placeholder="Введите описание"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>

            <label for="ingredients">Состав</label>
            <textarea id="ingredients" name="ingredients"
                placeholder="Введите состав"><?= htmlspecialchars($_POST['ingredients'] ?? '') ?></textarea>

            <div class="out_of">
                <p>В наличии</p>
                <label class="toggle-switch">
                    <input type="checkbox" id="is_available" name="is_available" value="1"
                        <?= isset($_POST['is_available']) ? 'checked' : 'checked' ?>>
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

            <button type="submit" class="add" name="add_dish">Добавить</button>
        </form>
    </div>
</div>