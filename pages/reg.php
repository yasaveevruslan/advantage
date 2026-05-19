<?php
global $connect;

if (isset($_SESSION['user_id'])) {
    header('Location: index.php?page=profile');
    exit;
}

$errors = [];
$old = $_POST ?? [];

if (isset($_POST['register'])) {
    $full_name = trim($old['name'] ?? '');
    $phone = trim($old['phone'] ?? '');
    $email = trim($old['email'] ?? '');
    $password = $old['password'] ?? '';
    $password_confirm = $old['password_confirm'] ?? '';

    // ВАЛИДАЦИЯ
    if ($full_name === '') {
        $errors['name'] = 'Укажите ФИО';
    } elseif (mb_strlen($full_name) < 2) {
        $errors['name'] = 'Минимум 2 символа';
    }

    $phone_clean = preg_replace('/[^0-9]/', '', $phone);
    if ($phone_clean === '') {
        $errors['phone'] = 'Укажите телефон';
    } elseif (strlen($phone_clean) < 10) {
        $errors['phone'] = 'Неверный формат';
    } else {
        $stmt = $connect->prepare("SELECT id FROM users WHERE phone = ?");
        $stmt->execute([$phone_clean]);
        if ($stmt->fetch()) {
            $errors['phone'] = 'Телефон уже зарегистрирован';
        }
    }

    // Email
    if ($email === '') {
        $errors['email'] = 'Введите почту';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Неверный формат';
    } else {
        $stmt = $connect->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors['email'] = 'Почта уже занята';
        }
    }

    // Пароль
    if ($password === '') {
        $errors['password'] = 'Введите пароль';
    } elseif (mb_strlen($password) < 5) {
        $errors['password'] = 'Минимум 5 символов';
    }

    if ($password_confirm !== $password) {
        $errors['password_confirm'] = 'Пароли не совпадают';
    }

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $connect->prepare("INSERT INTO users (full_name, phone, email, password, role, created_at) VALUES (?, ?, ?, ?, 'user', NOW())");
        
        if ($stmt->execute([$full_name, $phone_clean, $email, $hash])) {
            $_SESSION['success'] = 'Регистрация успешна! Теперь войдите.';
            header('Location: index.php?page=auth');
            exit;
        } else {
            $errors['general'] = 'Ошибка регистрации. Попробуйте позже.';
        }
    }
}
?>

<!-- сообщение об успехе -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="success-message" style="background: #4CAF50; color: white; padding: 10px; margin-bottom:15px;">
        <?= htmlspecialchars($_SESSION['success']) ?>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<!-- сообщение об ошибке -->
<?php if (!empty($errors['general'])): ?>
    <div class="error-message" style="background: #f44336; color: white; padding: 10px; text-align: center; margin-bottom:15px;">
        <?= htmlspecialchars($errors['general']) ?>
    </div>
<?php endif; ?>

<!-- регистрация -->
<h3 class="container reg_title">Регистрация</h3>
<div class="reg container">
    <form action="" class="registr" id="registrationForm" method="post">
        <label for="">ФИО</label>
        <input type="text" id="fullname" name="name" placeholder="Введите ФИО" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        <?php if(isset($errors['name'])): ?>
            <p class="error"><?= $errors['name'] ?></p>
        <?php endif; ?>

        <label for="">Номер телефона</label>
        <input type="tel" id="phone" name="phone" placeholder="+7 (999) 999-99-99" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        <?php if(isset($errors['phone'])): ?>
            <p class="error"><?= $errors['phone'] ?></p>
        <?php endif; ?>

        <label for="">Почта</label>
        <input type="email" id="email" name="email" placeholder="Введите почту" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        <?php if(isset($errors['email'])): ?>
            <p class="error"><?= $errors['email'] ?></p>
        <?php endif; ?>
        
        <label for="">Пароль</label>
        <input type="password" id="password" name="password" placeholder="Введите пароль">
        <?php if(isset($errors['password'])): ?>
            <p class="error"><?= $errors['password'] ?></p>
        <?php endif; ?>

        <label for="">Повторите пароль</label>
        <input type="password" id="confirmPassword" name="password_confirm" placeholder="Повторите пароль">
        <?php if(isset($errors['password_confirm'])): ?>
            <p class="error"><?= $errors['password_confirm'] ?></p>
        <?php endif; ?>

        <p>Нажимая кнопку «Зарегистрироваться», Вы принимаете условия Пользовательского соглашения.</p>
        <input type="submit" id="submit" name="register" value="Зарегистрироваться">
        <a href="?page=auth">Есть учётная запись? Войти</a>
    </form>

    <div class="skidka_r container">
        <h4>Скидка 25%
            на первый заказ уже</h4>
        <div class="prom_r">
            <p>Промокод</p>
            <a href="">Вкусно</a>
        </div>
        <h6>При заказе от 2 500 ₽</h6>
    </div>
</div>