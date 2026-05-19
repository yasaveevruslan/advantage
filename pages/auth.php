<?php
global $connect;

if (isset($_SESSION['user_id'])) {
    header('Location: index.php?page=profile');
    exit;
}

$errors = [];

if (isset($_POST['login'])) {
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    // Валидация
    if ($phone === '') {
        $errors['phone'] = 'Введите номер телефона';
    }
    if ($password === '') {
        $errors['password'] = 'Введите пароль';
    }

    // Авторизация
    if (empty($errors)) {
        $phone_clean = preg_replace('/[^0-9]/', '', $phone);
        
        $stmt = $connect->prepare("SELECT * FROM users WHERE phone = ?");
        $stmt->execute([$phone_clean]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role'];
            
            header('Location: index.php?page=profile');
            exit;
        } else {
            $errors['password'] = 'Неверный телефон или пароль';
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

<!-- авторизация -->
<h3 class="container reg_title">Авторизация</h3>
<div class="reg container">
    <form action="" class="registr" id="registrationForm" method="post">
        <label for="">Номер телефона</label>
        <input type="tel" id="phone" name="phone" placeholder="+7 (999) 999-99-99"
            value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        <?php if(isset($errors['phone'])): ?>
        <p class="error"><?= $errors['phone'] ?></p>
        <?php else: ?>
        <p class="error"></p>
        <?php endif; ?>

        <label for="">Пароль</label>
        <input type="password" id="password" name="password" placeholder="Введите пароль">
        <?php if(isset($errors['password'])): ?>
        <p class="error"><?= $errors['password'] ?></p>
        <?php else: ?>
        <p class="error"></p>
        <?php endif; ?>

        <input type="submit" id="submit" name="login" value="Войти">
        <a href="?page=reg">Создать аккаунт</a>
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