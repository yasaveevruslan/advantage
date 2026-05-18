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

    <div class="korzina container">
        <h3>Заказ на доставку</h3>
        <div class="korz">
            <div class="o7">
                <div class="aaa">
                    <label for="">ФИО</label>
                    <p>Мингараева Аделя Наилевна</p>
                    <label for="">Номер телефон</label>
                    <input type="text" value="+7 (999) 999-99-99">
                </div>
                <div class="aaa">
                    <label for="">Адрес</label>
                    <select name="adr" id="kor_adr">
                        <option value="light">Казань, ул. Пушкина, д. 10, кв. 25</option>
                        <option value="dark">Казань, ул. Галеева, д. 3, кв. 25</option>
                        <option value="dark">Казань, ул. Козина, д. 3, кв. 25</option>
                    </select>
                    <a href="">+ Добавить адрес</a>
                    <label for="">Время доставки</label>
                    <select name="adr" id="kor_adr">
                        <option value="light">10:00</option>
                        <option value="dark">12:00</option>
                        <option value="dark">14:00</option>
                        <option value="dark">16:00</option>

                    </select>
                    <label for="">Коментарий для курьера</label>
                    <textarea name="" id="" placeholder="Введите комментарий"></textarea>
                </div>
                <div class="aaa">
                    <h5>Способы оплаты</h5>
                    <div class="sp_opl">
                        <div class="sp1"><input type="radio" id="opt1" name="opl"><label for="opt1"><img
                                    src="image/card.png" alt="">Картой на
                                сайте</label></div>

                        <div class="sp1"><input type="radio" id="opt2" name="opl"><label for="opt2"><img
                                    src="image/nal.png" alt="">Наличными</label></div>
                        <div class="sp1"><input type="radio" id="opt3" name="opl"><label for="opt3"><img
                                    src="image/spb.png" alt="">Через
                                СБП</label></div>

                    </div>
                </div>
            </div>

            <div class="kbzu">
                <div class="kb1">
                    <h4>Итого КБЖУ</h4>
                    <div class="kor_kal">
                        <p>Калории</p>
                        <h4>2050</h4>
                    </div>
                    <div class="kor_kal2">
                        <div class="kor_b">
                            <h4 id="si">130</h4>
                            <p>белков</p>
                        </div>
                        <div class="kor_b">
                            <h4 id="kr">91</h4>
                            <p>жиров</p>
                        </div>
                        <div class="kor_b">
                            <h4 id="ze">180</h4>
                            <p>углеводов</p>
                        </div>
                    </div>
                </div>

                <div class="kb1">
                    <div class="kor_prom">
                        <input type="text" value="ВКУСНО" id="vk">
                        <a href="" id="udal">⨉</a>
                    </div>
                    <div class="zak_prom">
                        <p>Промокод</p>
                        <h5>-1037 ₽ </h5>
                    </div>
                    <div class="kor_sum">
                        <p>Доставка</p>
                        <h4>190 ₽</h4>
                    </div>
                    <div class="kor_sum">
                        <p>Сумма заказа</p>
                        <h4>3 960 ₽</h4>
                    </div>
                    <a href="">Оформить заказ</a>

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