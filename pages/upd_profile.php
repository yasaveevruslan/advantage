<?php
    if (!isset($_SESSION['user_id'])) {
        header('Location: ?page=auth');
        exit;
    }
    
    global $connect;

    $userId = $_SESSION['user_id'];
    $errors = [];

    if (isset($_POST['save'])) {
        $full_name = trim($_POST['name']);
        $phone = trim($_POST['phone']);
        $email = trim($_POST['email']);

        if (empty($full_name)) {
            $errors['name'] = 'Введите ФИО';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Введите корректную почту';
        }

        $phone_clean = preg_replace('/[^0-9]/', '', $phone);
        if ($phone_clean === '') {
            $errors['phone'] = 'Укажите телефон';
        } elseif (strlen($phone_clean) < 10) {
            $errors['phone'] = 'Неверный формат';
        } else {
            $stmt = $connect->prepare("SELECT id FROM users WHERE phone = ? AND id != ?");
            $stmt->execute([$phone_clean, $userId]);
            if ($stmt->fetch()) {
                $errors['phone'] = 'Этот телефон уже используется';
            }
        }

        if (empty($errors)) {
            $phone_clean = preg_replace('/[^0-9]/', '', $phone);
            
            $stmt = $connect->prepare("UPDATE users SET full_name = ?, phone = ?, email = ? WHERE id = ?");
            $stmt->execute([$full_name, $phone_clean, $email, $userId]);
            
            $_SESSION['user_name'] = $full_name;
            
            header('Location: ?page=profile');
            exit;
        }
    }

    $stmt = $connect->prepare("SELECT full_name, phone, email FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
?>

<!-- редактировать профиль -->
<h3 class="container reg_title">Редактировать профиль</h3>
<div class="reg container">
    <form action="" class="registr" id="registrationForm" method="post">

        <label for="">ФИО *</label>
        <input type="text" id="fullname" name="name" placeholder="Введите ваше ФИО, обязательное поле"
            value="<?= htmlspecialchars($user['full_name'] ?? '') ?>">
        <?php if(isset($errors['name'])): ?>
        <p class="error"><?= $errors['name'] ?></p>
        <?php endif; ?>

        <label for="">Номер телефон *</label>
        <input type="tel" id="phone" placeholder="+7 (999) 999-99-99" name="phone"
            value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
        <?php if(isset($errors['phone'])): ?>
        <p class="error"><?= $errors['phone'] ?></p>
        <?php endif; ?>

        <label for="">Почта *</label>
        <input type="email" id="email" placeholder="Введите почта" name="email"
            value="<?= htmlspecialchars($user['email'] ?? '') ?>">
        <?php if(isset($errors['email'])): ?>
        <p class="error"><?= $errors['email'] ?></p>
        <?php endif; ?>

        <input type="submit" id="submit" value="Сохранить" name="save">
        <a href="?page=upd_password">Сменить пароль</a>
    </form>
</div>