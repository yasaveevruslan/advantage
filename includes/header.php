<?php
global $connect;
if (!isset($dishCategories)) {
    $dishCategories = $connect->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
}
if (!isset($setCategories)) {
    $setCategories = $connect->query("SELECT id, name FROM sets ORDER BY name")->fetchAll();
}

$cartCount = 0;
if (isset($_SESSION['user_id'])) {
    $stmt = $connect->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $cartCount = $stmt->fetchColumn() ?: 0;
}
?>
<!-- шапка -->
<header>
    <div class="head container">
        <div class="head1">
            <button class="burger-btn" id="burger-btn">
                <img src="image/burger.svg" alt="Бургер меню">
            </button>

            <a href="?page=main" class="logo">
                <img src="image/logo.png" alt="">
            </a>
            <div class="geo">
                <img src="image/geo.png" alt="">
                <p>Казань</p>
            </div>
            <a class="tel" href="tel:+799999999">+7 (999) 999-99-99</a>
        </div>
        <div class="head2">
            <?php if (isset($_SESSION['user_id'])): ?>
            <a href="?page=korzina" id="korzina" style="position:relative;">
                Корзина
                <?php if ($cartCount > 0): ?>
                    <span style="position:absolute;top:-8px;right:-12px;background:#f44336;color:#fff;font-size:11px;padding:2px 6px;border-radius:50%;min-width:18px;text-align:center;">
                        <?= $cartCount ?>
                    </span>
                <?php endif; ?>
            </a>
            <a href="?page=lk" id="korzina">ЛК</a>
            <a href="php/logout.php" id="voiti">Выйти</a>
            <?php else: ?>
            <a href="?page=auth" id="voiti">Войти</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<nav class="header-nav container">
    <ul>
        <li class="blu">
            <a href="" class="bluda">Блюда <img src="image/spis.svg" alt=""></a>
            <ul class="spisok_blud">
                <?php foreach ($dishCategories as $cat): ?>
                <li>
                    <a href="?page=catalog_blud&category=<?= $cat['id'] ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </li>
        <li class="blu">
            <a href="" class="bluda">Наборы <img src="image/spis.svg" alt=""></a>
            <ul class="spisok_blud">
                <?php foreach ($setCategories as $cat): ?>
                <li>
                    <a href="?page=catalog_nabor&category=<?= $cat['id'] ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </li>
        <a href="">Конструктор рациона</a>
        <a href="">Подписка</a>
        <a href="">Как это работает</a>
        <a href="?page=doc">Документация</a>
        <a href="?page=otz">Отзывы</a>
    </ul>
</nav>

<div class="mobile-menu-overlay" id="mobile-menu">
    <div class="mobile-menu-content">
        <div class="mobile-header">
            <button class="close-btn" id="close-btn">✕</button>
        </div>

        <div class="mobile-nav">
            <div class="geo">
                <img src="image/geo.png" alt="">
                <p>Казань</p>
            </div>
            <ul>
                <li><a href="?page=catalog_blud">Блюда</a></li>
                <li><a href="?page=catalog_nabor">Наборы</a></li>
                <li><a href="">Конструктор рациона</a></li>
                <li><a href="">Подписка</a></li>
                <li><a href="">Как это работает</a></li>
                <li><a href="?page=doc">Документация</a></li>
                <li><a href="?page=otz">Отзывы</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="?page=korzina">Корзина</a></li>
                <li><a href="?page=lk">Личный кабинет</a></li>
                <li><a href="php/logout.php">Выйти</a></li>
                <?php endif; ?>
            </ul>
            <div class="mobile-contacts">
                <a class="tel" href="tel:+799999999">+7 (999) 999-99-99</a>
            </div>
        </div>
    </div>
</div>