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

        <div class="lk_inf">
            <p>Личная информация</p>
            <div class="lk_den">
                <div class="lk1">
                    <p id="ser">ФИО</p>
                    <p><?= htmlspecialchars($user['full_name']) ?></p>
                </div>
                <div class="lk1">
                    <p id="ser">Номер телефона</p>
                    <p><?= htmlspecialchars($user['phone']) ?></p>
                </div>
                <div class="lk1">
                    <p id="ser">Почта</p>
                    <p><?= htmlspecialchars($user['email']) ?></p>
                </div>
            </div>
            <a href="?page=upd_profile">Редактировать</a>
        </div>

        <div class="adres">
            <p id="li">Адреса доставки</p>
            <div class="dob"><a href="dob_adres.html">+ Добавить адрес</a></div>
            <div class="rusl_a">
                <div class="adr1">
                    <div class="lk_txt">
                        <p id="adr">Адрес</p>
                        <p>Казань, ул. Пушкина, д. 10, кв. 25</p>
                    </div>
                    <img src="image/kor.svg" alt="Корзина">
                </div>
                <div class="adr1">
                    <div class="lk_txt">
                        <p id="adr">Адрес</p>
                        <p>Казань, ул. пр-кт Победы, д. 120, кв. 27</p>
                    </div>
                    <img src="image/kor.svg" alt="Корзина">
                </div>
            </div>
        </div>
    </div>
</div>