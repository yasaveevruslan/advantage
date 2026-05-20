<?php
global $connect;
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=auth');
    exit;
}



$stmt = $connect->prepare("SELECT id, full_name, phone, email, role FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header('Location: index.php?page=auth');
    exit;
}

if ($_SESSION['user_role'] == 'admin') {
    header('Location: index.php?page=admin_lk');
    exit;
}
?>
<div class="lich container">
    <?php include('includes/l_V.php') ?>

    <div class="lk container">
        <?php include('includes/lk_filter.php') ?>
    </div>
</div>

<div class="ocen container">
    <p>Оцените наше качество и получите <br>
        промокод на 10% к следующему заказу</p>
    <a href="">Оставить отзыв</a>
</div>

<div class="ist_zak container">
    <p id="ist">История заказов</p>
    <div class="navigat">
        <a href="" id="vse">Все заказы</a>
        <a href="">Новый</a>
        <a href="">Готовится</a>
        <a href="">Передан курьеру</a>
        <a href="">Доставлен</a>
        <a href="">Отменён</a>
    </div>
    <p class="null2">У вас еще не было заказов, совершите ваш первый заказ</p>
</div>