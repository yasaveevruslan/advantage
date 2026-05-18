<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Польза</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/media.css">
    <link rel="shortcut icon" href="image/fav.png" type="image/x-icon">
</head>

<body>
    <!-- шапка -->
    <header>
        <div class="head container">
            <div class="head1">
                <button class="burger-btn" id="burger-btn">
                    <img src="image/burger.svg" alt="Бургер меню">
                </button>

                <a href="index.html" class="logo">
                    <img src="image/logo.png" alt="">
                </a>
                <div class="geo">
                    <img src="image/geo.png" alt="">
                    <p>Казань</p>
                </div>
                <a class="tel" href="tel:+799999999">+7 (999) 999-99-99</a>
            </div>
            <div class="head2">
                <a href="korzina.html" id="korzina">Корзина</a>
                <a href="reg.html" id="voiti">Войти</a>
            </div>
        </div>
    </header>

    <!-- Навигация -->

    <nav class="header-nav container">
        <ul>
            <li class="blu">
                <a href="" class="bluda">Блюда <img src="image/spis.svg" alt=""></a>
                <ul class="spisok_blud">
                    <li><a href="catalog_blud.html">Сбалансированное</a></li>
                    <li><a href="">Фитнес</a></li>
                    <li><a href="">Кето</a></li>
                    <li><a href="">Веган</a></li>
                    <li><a href="">Детокс</a></li>
                    <li><a href="">Полезные десерты и снеки</a></li>

                </ul>

            </li>
            <li class="blu">
                <a href="" class="bluda">Наборы <img src="image/spis.svg" alt=""></a>
                <ul class="spisok_blud">
                    <li><a href="catalog_nabor.html">Похудение</a></li>
                    <li><a href="">Поддержание</a></li>
                    <li><a href="">Набор массы</a></li>
                </ul>
            </li>
            <a href="">Конструктор рациона</a>
            <a href="">Подписка</a>
            <a href="">Как это работает</a>
            <a href="doc.html">Документация</a>
            <a href="otz.html">Отзывы</a>
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
                    <li><a href="">Блюда</a></li>
                    <li><a href="">Наборы</a></li>
                    <li><a href="">Конструктор рациона</a></li>
                    <li><a href="">Подписка</a></li>
                    <li><a href="">Как это работает</a></li>
                    <li><a href="">Документация</a></li>
                    <li><a href="">Отзывы</a></li>
                </ul>
                <div class="mobile-contacts">
                    <a class="tel" href="tel:+799999999">+7 (999) 999-99-99</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Отзывы -->

    <p id="hleb" class="container">Главная > Отзывы</p>

    <div class="otz container">
        <h3>Отзывы</h3>

        <div class="otziv">
            <div class="ot1">
                <div class="name">
                    <h6>Аделя</h6>
                    <p>01.03.2026</p>
                </div>
                <div class="o_txt">
                    <p>Попробовала двухдневку Детокс от левелкитчен. Должна сказать, что мне не просто понравилось, я
                        получила заряд бодрости. Соки вкусные. Во всем теле возникла энергетика и легкость. Снижение
                        веса было небольшое, но было. За два дня это тоже достижение. Но главное, конечно, хорошее
                        самочувствие. Так что решила, что при общей программе в 1500 пара дней летокса в месяц просто
                        отличная разрядка.</p>

                </div>
            </div>
            <div class="ot1">
                <div class="name">
                    <h6>Юлия</h6>
                    <p>25.02.2026</p>
                </div>
                <div class="o_txt">
                    <p>Заказали с мамой пробники - даже не ожидали, что съедим все до крошечки и будем хитро друг на
                        друга поглядывать, не осталось ли чего - поделить. Мой любимый омлет сделан на совесть. Я всегда
                        ленюсь взбивать яйца в крутую пену, а хздесь постарались. И вкуснейший черничный крем.
                        Тарталетка показалась совсем маленькой, хоть и по размерам ничего себе такая, на хороший
                        перекус. В общем, убедили: не обязательно убиваться в кухне, можно просто заказать и получить
                        приличную еду</p>


                </div>
            </div>
            <div class="ot1">
                <div class="name">
                    <h6>Карима</h6>
                    <p>14.02.2026</p>
                </div>
                <div class="o_txt">
                    <p>Офигенное место, отличная кухня, обалденные вишневые настойки, супер приветливый, дружелюбный<br>
                        персонал Понравилось то, что в заведении чувствуешь себя «своим», чувствуешь что тебе рады,<br>
                        однозначно рекомендую к посещению !❤️</p>


                </div>
            </div>
            <div class="ot1">
                <div class="name">
                    <h6>Лиза</h6>
                    <p>08.02.2026</p>
                </div>
                <div class="o_txt">
                    <p>Для ПП — вполне годный вариант, учитывая, что готовить не надо, а еда вкусна и разная</p>


                </div>
            </div>
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