<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=auth');
    exit;
}

global $connect;
$userId = $_SESSION['user_id'];
$errors = [];

$stmt = $connect->prepare("SELECT password FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header('Location: ?page=auth');
    exit;
}

if (isset($_POST['save_password'])) {
    $oldPass = $_POST['old_password'] ?? '';
    $newPass = $_POST['new_password'] ?? '';
    $confirmPass = $_POST['confirm_password'] ?? '';

    if ($oldPass === '') {
        $errors['old_password'] = 'Введите текущий пароль';
    }
    if ($newPass === '') {
        $errors['new_password'] = 'Введите новый пароль';
    } elseif (mb_strlen($newPass) < 5) {
        $errors['new_password'] = 'Минимум 5 символов';
    }
    if ($newPass !== $confirmPass) {
        $errors['confirm_password'] = 'Пароли не совпадают';
    }

    if (empty($errors)) {
        if (!password_verify($oldPass, $user['password'])) {
            $errors['old_password'] = 'Неверный текущий пароль';
        } elseif ($oldPass === $newPass) {
            $errors['new_password'] = 'Новый пароль должен отличаться';
        }
    }

    if (empty($errors)) {
        $newHash = password_hash($newPass, PASSWORD_DEFAULT);
        $stmt = $connect->prepare("UPDATE users SET password = ? WHERE id = ?");
        
        if ($stmt->execute([$newHash, $userId])) {
            $_SESSION['success'] = 'Пароль успешно изменён!';
            header('Location: ?page=profile');
            exit;
        } else {
            $errors['general'] = 'Ошибка сохранения. Попробуйте позже.';
        }
    }
}
?>

<!-- сменить пароль -->
<h3 class="container reg_title">Сменить пароль</h3>
<div class="reg container">
    <form action="" class="registr" method="post">
        <label for="">Старый пароль *</label>
        <input type="password" id="old_password" name="old_password" placeholder="Введите текущий пароль">
        <?php if(!empty($errors['old_password'])): ?>
            <p class="error"><?= $errors['old_password'] ?></p>
        <?php endif; ?>

        <label for="">Новый пароль *</label>
        <input type="password" id="new_password" name="new_password" placeholder="Придумайте новый пароль">
        <?php if(!empty($errors['new_password'])): ?>
            <p class="error"><?= $errors['new_password'] ?></p>
        <?php endif; ?>

        <label for="">Повторите пароль *</label>
        <input type="password" id="password" name="confirm_password" placeholder="Повторите новый пароль">
        <?php if(!empty($errors['new_password'])): ?>
            <p class="error"><?= $errors['new_password'] ?></p>
        <?php endif; ?>

        <input type="submit" id="submit" value="Сохранить" name="save_password">
    </form>
</div>