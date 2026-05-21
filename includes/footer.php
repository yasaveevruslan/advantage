<?php
global $connect;
if (!isset($dishCategories)) {
    $dishCategories = $connect->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
}
if (!isset($setCategories)) {
    $setCategories = $connect->query("SELECT id, name FROM sets ORDER BY name")->fetchAll();
}
?>
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
                        <?php foreach ($dishCategories as $cat): ?>
                        <li>
                            <a href="?page=catalog_blud&category=<?= $cat['id'] ?>">
                                <?= htmlspecialchars($cat['name']) ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li class="blu_f">
                    <a href="#" class="bluda_f">Наборы <img src="image/spis.svg" alt=""></a>
                    <ul class="spisok_blud_f">
                        <?php foreach ($setCategories as $cat): ?>
                        <li>
                            <a href="?page=catalog_nabor&category=<?= $cat['id'] ?>">
                                <?= htmlspecialchars($cat['name']) ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="docum_f">
            <p>Документация</p>
            <a href="?page=doc">О конфиденциальности</a>
            <a href="?page=doc">О сотрудничестве</a>
            <a href="?page=doc">Публичной оферты</a>
            <a href="?page=doc">Политика обработки данных</a>
        </div>
    </div>
    <hr class="f_hr">
    <div class="f_adel container">
        <p>© Все права защищены, 2026.</p>
        <p>Мингараева Аделя Наилевна</p>

    </div>
</footer>

<script src="/js/app.js" type="module" defer></script>