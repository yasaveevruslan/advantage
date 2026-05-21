<?php
global $connect;

$stmt = $connect->prepare("
    SELECT id, name, image, kcal, protein, fat, carbs, price 
    FROM dishes 
    WHERE is_available = 1 
    ORDER BY id DESC 
    LIMIT 4
");
$stmt->execute();
$featuredDishes = $stmt->fetchAll();
?>
<!-- баннер -->
<div class="banner">
    <div class="slider">
        <!-- Контейнер, который содержит все слайды -->
        <div class="slides">
            <div class="slide"><img src="image/banner.png" alt=""></div>
            <div class="slide"><img src="image/banner2.png" alt=""></div>
            <div class="slide"><img src="image/banner3.png" alt=""></div>
        </div>

        <!-- Кнопки управления слайдером -->
        <button class="prev"><img src="image/levo.png" alt=""></button>
        <button class="next"><img src="image/pravo.png" alt=""></button>

        <!-- Текст поверх слайдера (один и тот же для всех слайдов) -->
        <div class="banner_text container">
            <h1>Доставка правильного питания</h1>
            <p>Подарите себе стройность, крепкий иммунитет и энергию со здоровым питанием</p>
            <a href="?page=catalog_blud">Заказать</a>
        </div>
    </div>
</div>

<!-- преимущества -->
<div class="adv container">
    <h3>Как это работает</h3>
    <div class="advant">
        <div class="adv1">
            <img src="image/adv1.svg" alt="Вилка и нож">
            <h4>Выбери кол-во блюд</h4>
            <p>В зависимости от потребностей<br>
                и любые ингредиенты</p>
        </div>
        <div class="adv1">
            <img src="image/adv2.svg" alt="Доставка">
            <h4>Получи еду</h4>
            <p>Наш курьер бесплатно<br>
                доставит еду</p>
        </div>
        <div class="adv1">
            <img src="image/adv3.svg" alt="Наслаждайся">
            <h4>Наслаждайся</h4>
            <p>Просто разогрей<br>
                в микроволновке и сэкономь</p>
        </div>
    </div>
</div>

<!-- скидка -->
<div class="skidka container">
    <h4>Скидка 25%
        на первый заказ уже</h4>
    <div class="prom">
        <p>Промокод</p>
        <a>Вкусно</a>
    </div>
    <h6>При заказе от 2 500 ₽</h6>
</div>

<!-- новинки -->
<div class="new container">
    <h3>Новинки</h3>
    <div class="nowinki">
        <?php if (!empty($featuredDishes)): ?>
        <?php foreach ($featuredDishes as $dish): ?>
        <div class="new1">
            <a href="index.php?page=blud&id=<?= $dish['id'] ?>" style="background-color: transparent; padding: 0px;">
                <img src="bl/<?= htmlspecialchars($dish['image'] ?: 'placeholder.png') ?>"
                    alt="<?= htmlspecialchars($dish['name']) ?>">
            </a>
            <h5>
                <?= mb_strimwidth(htmlspecialchars($dish['name']), 0, 25, '...') ?>
            </h5>

            <div class="kal">
                <div class="k">
                    <p id="or"><?= (int)$dish['kcal'] ?></p>
                    <p id="s">ккал</p>
                </div>
                <div class="k">
                    <p id="si"><?= (int)$dish['protein'] ?></p>
                    <p id="s">белков</p>
                </div>
                <div class="k">
                    <p id="kr"><?= (int)$dish['fat'] ?></p>
                    <p id="s">жиров</p>
                </div>
                <div class="k">
                    <p id="ze"><?= (int)$dish['carbs'] ?></p>
                    <p id="s">углеводов</p>
                </div>
            </div>

            <h6><?= number_format($dish['price'], 0, '.', ' ') ?> ₽</h6>

            <a href="php/add_to_cart.php?id=<?= $dish['id'] ?>&type=dish">
                В корзину
            </a>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <p style="padding:20px;color:#666;">Блюда пока не добавлены</p>
        <?php endif; ?>
    </div>
</div>

<!-- Вопросы -->
<div class="faq-card container">
    <h3>Часто задаваемые вопросы</h3>

    <!-- все вопросы в одном блоке (один общий список) -->
    <div class="question-item">
        <div class="question-header">
            <span class="question-text">Как понять что и когда есть?</span>
            <span class="toggle-icon">🞢</span>
        </div>
        <div class="answer">
            <div class="answer-content">
                <p>Ориентируйтесь на этикетку продукта и рекомендации по приёму пищи. Обычно на упаковке указано
                    оптимальное время употребления (завтрак, обед или перекус). Также можно следовать личным
                    ощущениям голода и режиму дня.</p>
            </div>
        </div>
    </div>

    <div class="question-item">
        <div class="question-header">
            <span class="question-text">Как часто употреблять рационы?</span>
            <span class="toggle-icon">🞢</span>
        </div>
        <div class="answer">
            <div class="answer-content">
                <p>Рационы разработаны для ежедневного сбалансированного питания. Рекомендуем употреблять 1–2 порции
                    в день в зависимости от ваших целей (поддержание формы или активный образ жизни). Для точных
                    рекомендаций лучше проконсультироваться с диетологом.</p>
            </div>
        </div>
    </div>

    <div class="question-item">
        <div class="question-header">
            <span class="question-text">Кто занимается составлением каталога?</span>
            <span class="toggle-icon">🞢</span>
        </div>
        <div class="answer">
            <div class="answer-content">
                <p>Каталог составляют профессиональные нутрициологи и технологи пищевого производства. Каждое блюдо
                    проходит проверку на баланс белков, жиров и углеводов, а также на вкусовые качества.</p>
            </div>
        </div>
    </div>

    <div class="question-item">
        <div class="question-header">
            <span class="question-text">Как часто обновляется каталог?</span>
            <span class="toggle-icon">🞢</span>
        </div>
        <div class="answer">
            <div class="answer-content">
                <p>Каталог обновляется каждый сезон (раз в 3–4 месяца). Мы добавляем новые позиции, учитывая
                    сезонные продукты и пожелания клиентов. Следите за новостями на сайте.</p>
            </div>
        </div>
    </div>

    <div class="question-item">
        <div class="question-header">
            <span class="question-text">Могу ли я заменить или исключить блюдо?</span>
            <span class="toggle-icon">🞢</span>
        </div>
        <div class="answer">
            <div class="answer-content">
                <p>Да, вы можете заменить блюдо на равноценное из каталога или исключить ингредиент при оформлении
                    заказа (если есть аллергия). Для индивидуальной замены свяжитесь с нашей поддержкой — поможем
                    подобрать альтернативу.</p>
            </div>
        </div>
    </div>

    <div class="question-item">
        <div class="question-header">
            <span class="question-text">Могу ли я заказать у вас домашнюю еду?</span>
            <span class="toggle-icon">🞢</span>
        </div>
        <div class="answer">
            <div class="answer-content">
                <p>Да, в нашем ассортименте есть полноценные готовые рационы, которые готовятся как домашняя еда —
                    без консервантов, с натуральными продуктами. Вы можете выбрать набор на неделю или разовое
                    блюдо.</p>
            </div>
        </div>
    </div>
</div>

<!-- Карта--------------------------------------------------------------- -->

<div class="map-fullwidth">
    <div class="map-container container">
        <div class="ost_vopr">
            <h6>Остались вопросы?</h6>
            <p>Оставьте свои контакты, и мы вам перезвоним</p>
            <form action="">
                <label for="">Номер телефона</label>
                <input type="text" placeholder="+7 (999) 999-99-99">
                <label for="">Пароль</label>
                <input type="text" placeholder="Введите пароль">
            </form>
            <p>Нажимая на кнопку «Отправить», я даю свое согласие на обработку персональных данных и соглашаюсь
                с условиями политики конфиденциальности.</p>
            <button>Отправить</button>

        </div>
    </div>
    <iframe class="fullwidth-map"
        src="https://yandex.ru/map-widget/v1/?um=constructor%3Ad16ccccfa89085ad20c1fdf264095095c0b43b81ea89143f508526f9b059772c&amp;source=constructor"
        frameborder="0">
    </iframe>
</div>