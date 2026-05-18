<?php
session_start();

$errors = [];

if(isset($_POST['register'])){
    $full_name = trim($_POST['name']);      // full_name в БД
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Валидация ФИО
    if(empty($full_name)){
        $errors['name'] = 'Укажите ваше ФИО, обязательное поле';
    } elseif(mb_strlen($full_name) < 2){
        $errors['name'] = 'ФИО должно содержать минимум 2 символа';
    }

    // Валидация телефона
    if(empty($phone)){
        $errors['phone'] = 'Укажите номер телефона';
    } else {
        $phone_clean = preg_replace('/[^0-9]/', '', $phone);
        if(strlen($phone_clean) < 10){
            $errors['phone'] = 'Неверный формат номера телефона';
        } else {
            // Проверка уникальности телефона
            $sql = "SELECT * FROM users WHERE phone = '$phone_clean'";
            $result = $connect->query($sql);
            if($result && $result->fetch()){
                $errors['phone'] = 'Этот номер телефона уже зарегистрирован';
            }
        }
    }

    // Валидация email
    if(empty($email)){
        $errors['email'] = 'Введите почту';
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors['email'] = 'Неверный формат email';
    } else {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $connect->query($sql);
        if($result && $result->fetch()){
            $errors['email'] = 'Почта уже занята';
        }
    }

    // Валидация пароля
    if(empty($password)){
        $errors['password'] = 'Введите пароль';
    } elseif(mb_strlen($password) < 5){
        $errors['password'] = 'Пароль не может быть меньше 5 символов';
    }

    // Проверка совпадения паролей
    if(empty($password_confirm)){
        $errors['password_confirm'] = 'Подтвердите пароль';
    } elseif($password != $password_confirm){
        $errors['password_confirm'] = 'Пароли не совпадают';
    }

    // Если ошибок нет - регистрируем пользователя
    if(empty($errors)){
        $phone_clean = preg_replace('/[^0-9]/', '', $phone);
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Вставляем данные в соответствии со структурой БД
        $sql = "INSERT INTO users (full_name, phone, email, password, role, created_at) 
                VALUES ('$full_name', '$phone_clean', '$email', '$hash', 'user', NOW())";
        
        if($connect->query($sql)){
            $_SESSION['success'] = 'Успешная регистрация';
            echo '<script>location.href="?"</script>';
        } else {
            $errors['general'] = 'Ошибка регистрации: ' . $connect->errorInfo()[2];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
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

<!-- сообщение об ошибке -->
<?php if(isset($errors['general'])): ?>
    <div class="error-message" style="background: #f44336; color: white; padding: 10px; text-align: center;">
        <?= $errors['general'] ?>
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