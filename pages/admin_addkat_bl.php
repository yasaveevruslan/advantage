<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: index.php?page=auth');
    exit;
}
global $connect;
$errors = [];
$success = '';

if (isset($_POST['add_category'])) {
    $name = trim($_POST['category_name'] ?? '');

    if ($name === '') {
        $errors['name'] = 'Введите название категории';
    } elseif (mb_strlen($name) < 2) {
        $errors['name'] = 'Название должно содержать минимум 2 символа';
    }

    if (empty($errors)) {
        $check = $connect->prepare("SELECT id FROM categories WHERE name = ?");
        $check->execute([$name]);
        
        if ($check->fetch()) {
            $errors['name'] = 'Такая категория уже существует';
        } else {
            $stmt = $connect->prepare("INSERT INTO categories (name) VALUES (?, ?)");
            if ($stmt->execute([$name])) {
                header('Location: index.php?page=admin_kat_bl');
            } else {
                $errors['general'] = 'Ошибка сохранения: ' . $connect->errorInfo()[2];
            }
        }
    }
}
?>

<div class="adm_dobkat container">
    <h3>Добавить категорию блюда</h3>

    <?php if ($success): ?>
    <p class="success-msg"><?= $success ?></p>
    <?php endif; ?>

    <form action="" class="bld" id="registrationForm" method="post">
        <label for="category_name">Название *</label>
        <input type="text" id="category_name" name="category_name" placeholder="Введите название"
            value="<?= htmlspecialchars($_POST['category_name'] ?? '') ?>">

        <?php if (!empty($errors['name'])): ?>
        <p class="error"><?= $errors['name'] ?></p>
        <?php endif; ?>

        <?php if (!empty($errors['general'])): ?>
        <p class="error"><?= $errors['general'] ?></p>
        <?php endif; ?>

        <button type="submit" class="dob_bl_a" name="add_category">Добавить</button>
    </form>
</div>