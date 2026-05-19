<!-- сменить пароль -->
<h3 class="container reg_title">Сменить пароль</h3>
<div class="reg container">
    <form action="" class="registr" id="registrationForm" method="post">
        <label for="">Старый пароль *</label>
        <input type="password" id="password" placeholder="Введите старый пароль">
        <p class="error">Неверный пароль, попробуйте ввести еще раз</p>

        <label for="">Новый пароль *</label>
        <input type="password" id="password" placeholder="+7 (999) 999-99-99">
        <p class="error">Недостаточной длины (не может быть меньше 5 символов)</p>

        <label for="">Повторите пароль *</label>
        <input type="password" id="password" placeholder="Ваш пароль">
        <p class="error">Пароли не совпадают</p>

        <input type="submit" id="submit" value="Сохранить">
    </form>
</div>