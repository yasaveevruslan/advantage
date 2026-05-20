<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: index.php?page=auth');
    exit;
}
global $connect;
$id = intval($_GET['id'] ?? 0);
$errors = [];

$stmt = $connect->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch();

if (!$category) {
    echo '<div class="container">Категория не найдена</div>';
    exit;
}

if (isset($_POST['edit_category'])) {
    $name = trim($_POST['category_name'] ?? '');

    if ($name === '') $errors['name'] = 'Введите название';
    elseif (mb_strlen($name) < 2) $errors['name'] = 'Минимум 2 символа';

    if (empty($errors)) {


        $check = $connect->prepare("SELECT id FROM categories WHERE (name = ?) AND id != ?");
        $check->execute([$name, $id]);

        if ($check->fetch()) {
            $errors['name'] = 'Такая категория уже существует';
        } else {
            $update = $connect->prepare("UPDATE categories SET name = ? WHERE id = ?");
            if ($update->execute([$name, $id])) {
                header('Location: index.php?page=admin_kat_bl');
                exit;
            } else {
                $errors['general'] = 'Ошибка обновления. Попробуйте позже.';
            }
        }
    }
}
?>

<div class="adm_dobkat container">
    <h3>Редактировать категорию блюда</h3>

    <?php if (!empty($errors['general'])): ?>
    <p class="error"><?= $errors['general'] ?></p>
    <?php endif; ?>

    <form action="" class="bld" method="post">
        <label for="category_name">Название *</label>
        <input type="text" id="category_name" name="category_name" placeholder="Введите название"
            value="<?= htmlspecialchars($_POST['category_name'] ?? $category['name']) ?>">

        <?php if (!empty($errors['name'])): ?>
        <p class="error"><?= $errors['name'] ?></p>
        <?php endif; ?>

        <button type="submit" class="dob_bl_a" name="edit_category">Сохранить изменения</button>
    </form>
</div>