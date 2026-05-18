<?php
session_start();

// Проверяем, если пользователь уже авторизован - перенаправляем на главную
if(isset($_SESSION['user_id'])){
    echo '<script>location.href="?"</script>';
    exit();
}

$errors = [];

if(isset($_POST['login'])){
    $phone = trim($_POST['phone']);      // телефон из формы
    $password = $_POST['password'];

    // Валидация
    if(empty($phone)){
        $errors['phone'] = 'Введите номер телефона';
    }

    if(empty($password)){
        $errors['password'] = 'Введите пароль';
    } elseif(mb_strlen($password) < 5){
        $errors['password'] = 'Пароль не может быть меньше 5 символов';
    }

    // Если ошибок нет - ищем пользователя
    if(empty($errors)){
        // Очищаем телефон от лишних символов
        $phone_clean = preg_replace('/[^0-9]/', '', $phone);
        
        // Ищем пользователя по телефону
        $sql = "SELECT * FROM users WHERE phone = '$phone_clean'";
        $result = $connect->query($sql);
        $user = $result->fetch();
        
        if($user && password_verify($password, $user['password'])){
            // Успешная авторизация
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            
            $_SESSION['success'] = 'Добро пожаловать, ' . $user['full_name'] . '!';
            echo '<script>location.href="?"</script>';
            exit();
        } else {
            $errors['password'] = 'Неверный номер телефона или пароль';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/media.css">
    <link rel="shortcut icon" href="image/fav.png" type="image/x-icon">
</head>

<body>

<!-- сообщение об успехе -->
<?php if(isset($_SESSION['success'])): ?>
    <div class="success-message" style="background: #4CAF50; color: white; padding: 10px; text-align: center;">
        <?= $_SESSION['success'] ?>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<!-- авторизация -->
<h3 class="container reg_title">Авторизация</h3>
<div class="reg container">
    <form action="" class="registr" id="registrationForm" method="post">
        <label for="">Номер телефона</label>
        <input type="tel" id="phone" name="phone" placeholder="+7 (999) 999-99-99" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
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

<!-- футер -->
<footer>
    <div class="foot container">
        <div class="logo_f">
            <img src="image/log_f.png" alt="">
            <a href="tel:+79228805707">+7 (999) 999-99-99</a>
        </div>
        <div class="glav">
            <p>Главная страница</p>
            <a href="">Конструктор рациона</a>
            <a href="">Подписка</a>
            <a href="">Как это работает</a>
            <a href="">Часто задаваемые вопросы</a>
            <a href="">Остались вопросы?</a>
        </div>

        <div class="n_f">
            <ul class="dolb">
                <li class="blu_f">
                    <a href="#" class="bluda_f">Блюда <img src="image/spis.svg" alt=""></a>
                    <ul class="spisok_blud_f">
                        <li><a href="">Сбалансированное</a></li>
                        <li><a href="">Фитнес</a></li>
                        <li><a href="">Кето</a></li>
                        <li><a href="">Веган</a></li>
                        <li><a href="">Детокс</a></li>
                        <li><a href="">Полезные десерты и снеки</a></li>
                    </ul>
                </li>
                <li class="blu_f">
                    <a href="#" class="bluda_f">Наборы <img src="image/spis.svg" alt=""></a>
                    <ul class="spisok_blud_f">
                        <li><a href="">Похудение</a></li>
                        <li><a href="">Поддержание</a></li>
                        <li><a href="">Набор массы</a></li>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="docum_f">
            <p>Документация</p>
            <a href="">О конфиденциальности</a>
            <a href="">О сотрудничестве</a>
            <a href="">Публичной оферты</a>
            <a href="">Политика обработки данных</a>
        </div>
    </div>
    <hr class="f_hr">
    <div class="f_adel container">
        <p>© Все права защищены, 2026.</p>
        <p>Мингараева Аделя Наилевна</p>
    </div>
</footer>

</body>
</html>