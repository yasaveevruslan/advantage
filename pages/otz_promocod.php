<?php
if (empty($_SESSION['review_submitted'])) {
    header('Location: index.php?page=otz');
    exit;
}
unset($_SESSION['review_submitted']);

global $connect;

$promoCode = 'СПАСИБО';
$discount  = 10;

$stmt = $connect->prepare("SELECT code, discount_value FROM promocodes WHERE is_active = 1 LIMIT 1");
$stmt->execute();
$promo = $stmt->fetch();

if ($promo) {
    $promoCode = $promo['code'];
    $discount  = $promo['discount_value'];
}
?>

<p id="hleb" class="container">Главная > Отзывы</p>

<div class="otz_p container">
    <h4>Спасибо за отзыв<br>
        ваша скидка <?= $discount ?>%</h4>
    <div class="prom_p">
        <p>Промокод</p>
        <a><?= htmlspecialchars($promoCode) ?></a>
    </div>
    <h6>При заказе от 1 500 ₽</h6>
    <a href="?page=catalog_blud" class="otz_btn">Перейти в каталог</a>
</div>