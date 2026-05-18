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

    <div class="lich container">
        <div class="l_v">
            <div class="l_v_panel">
                <h4>Личный кабинет</h4>
                <p id="wel">Добро пожаловать, Мингараева Аделя!</p>
            </div>
            <a href="">Выйти</a>
        </div>

        <div class="lk container">
            <div class="lk_filter">
                <a href="profile.html">Профиль</a>
                <a href="lk.html" id="fil">Заказы</a>
                <a href="lk_izb.html">Избранное</a>
                <a href="">Подписка</a>
            </div>
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
        <div class="zak">
            <div class="nom_z">
                <div class="nom1">
                    <p>Заказ #0001</p>
                    <p>15.02.2026 в 09:00</p>
                </div>
                <p id="zel">Новый</p>
            </div>
            <div class="gips">
                <img src="image/new2.png" alt="">
                <div class="gips_txt">
                    <p>Рис с курицей и овощами </p>

                    <div class="kal">
                        <div class="k">
                            <p id="or">450</p>
                            <p id="s">ккал</p>
                        </div>
                        <div class="k">
                            <p id="si">35</p>
                            <p id="s">белков</p>
                        </div>
                        <div class="k">
                            <p id="kr">15</p>
                            <p id="s">жиров</p>
                        </div>
                        <div class="k">
                            <p id="ze">40</p>
                            <p id="s">углеводов</p>
                        </div>
                    </div>
                    <h5>750 ₽</h5>
                </div>

            </div>
            <div class="gips">
                <img src="image/new1.png" alt="">
                <div class="gips_txt">
                    <p>Паста с морепродуктами</p>

                    <div class="kal">
                        <div class="k">
                            <p id="or">450</p>
                            <p id="s">ккал</p>
                        </div>
                        <div class="k">
                            <p id="si">35</p>
                            <p id="s">белков</p>
                        </div>
                        <div class="k">
                            <p id="kr">15</p>
                            <p id="s">жиров</p>
                        </div>
                        <div class="k">
                            <p id="ze">40</p>
                            <p id="s">углеводов</p>
                        </div>
                    </div>
                    <h5>720 ₽</h5>
                </div>

            </div>
            <div class="gips">
                <img src="image/new2.png" alt="">
                <div class="gips_txt">
                    <p>Рис с курицей и овощами </p>

                    <div class="kal">
                        <div class="k">
                            <p id="or">450</p>
                            <p id="s">ккал</p>
                        </div>
                        <div class="k">
                            <p id="si">35</p>
                            <p id="s">белков</p>
                        </div>
                        <div class="k">
                            <p id="kr">15</p>
                            <p id="s">жиров</p>
                        </div>
                        <div class="k">
                            <p id="ze">40</p>
                            <p id="s">углеводов</p>
                        </div>
                    </div>
                    <h5>750 ₽</h5>
                </div>

            </div>

            <div class="inf_zak">
                <div class="iz1">
                    <div class="sp_o">
                        <h5>Способ оплаты</h5>
                        <p>Картой</p>
                    </div>
                    <div class="sp_o">
                        <h5>Доставка по адресу</h5>
                        <p>ул. Пушкина, д. 10, кв. 25</p>
                    </div>
                    <div class="sp_o">
                        <h5>Дата и время доставки</h5>
                        <p>18.03.2026 в 10:00</p>
                    </div>
                </div>
                <h6>1 780 ₽</h6>

            </div>
        </div>
        <div class="zak">
            <div class="nom_z">
                <div class="nom1">
                    <p>Заказ #0002</p>
                    <p>15.02.2026 в 09:00</p>
                </div>
                <p id="sin">Готовится</p>
            </div>

            <div class="gips">
                <img src="image/new1.png" alt="">
                <div class="gips_txt">
                    <p>Паста с морепродуктами</p>

                    <div class="kal">
                        <div class="k">
                            <p id="or">450</p>
                            <p id="s">ккал</p>
                        </div>
                        <div class="k">
                            <p id="si">35</p>
                            <p id="s">белков</p>
                        </div>
                        <div class="k">
                            <p id="kr">15</p>
                            <p id="s">жиров</p>
                        </div>
                        <div class="k">
                            <p id="ze">40</p>
                            <p id="s">углеводов</p>
                        </div>
                    </div>
                    <h5>720 ₽</h5>
                </div>

            </div>
            <div class="gips">
                <img src="image/new2.png" alt="">
                <div class="gips_txt">
                    <p>Рис с курицей и овощами </p>

                    <div class="kal">
                        <div class="k">
                            <p id="or">450</p>
                            <p id="s">ккал</p>
                        </div>
                        <div class="k">
                            <p id="si">35</p>
                            <p id="s">белков</p>
                        </div>
                        <div class="k">
                            <p id="kr">15</p>
                            <p id="s">жиров</p>
                        </div>
                        <div class="k">
                            <p id="ze">40</p>
                            <p id="s">углеводов</p>
                        </div>
                    </div>
                    <h5>750 ₽</h5>
                </div>

            </div>

            <div class="inf_zak">
                <div class="iz1">
                    <div class="sp_o">
                        <h5>Способ оплаты</h5>
                        <p>Наличными</p>
                    </div>
                    <div class="sp_o">
                        <h5>Доставка по адресу</h5>
                        <p>ул. Пушкина, д. 10, кв. 25</p>
                    </div>
                    <div class="sp_o">
                        <h5>Дата и время доставки</h5>
                        <p>17.03.2026 в 10:00</p>
                    </div>
                </div>
                <h6>1 780 ₽</h6>

            </div>
        </div>
        <div class="zak">
            <div class="nom_z">
                <div class="nom1">
                    <p>Заказ #0003</p>
                    <p>15.02.2026 в 09:00</p>
                </div>
                <p id="ora">Передан курьеру</p>
            </div>

            <div class="gips">
                <img src="image/new1.png" alt="">
                <div class="gips_txt">
                    <p>Паста с морепродуктами</p>

                    <div class="kal">
                        <div class="k">
                            <p id="or">450</p>
                            <p id="s">ккал</p>
                        </div>
                        <div class="k">
                            <p id="si">35</p>
                            <p id="s">белков</p>
                        </div>
                        <div class="k">
                            <p id="kr">15</p>
                            <p id="s">жиров</p>
                        </div>
                        <div class="k">
                            <p id="ze">40</p>
                            <p id="s">углеводов</p>
                        </div>
                    </div>
                    <h5>720 ₽</h5>
                </div>

            </div>
            <div class="gips">
                <img src="image/new2.png" alt="">
                <div class="gips_txt">
                    <p>Рис с курицей и овощами </p>

                    <div class="kal">
                        <div class="k">
                            <p id="or">450</p>
                            <p id="s">ккал</p>
                        </div>
                        <div class="k">
                            <p id="si">35</p>
                            <p id="s">белков</p>
                        </div>
                        <div class="k">
                            <p id="kr">15</p>
                            <p id="s">жиров</p>
                        </div>
                        <div class="k">
                            <p id="ze">40</p>
                            <p id="s">углеводов</p>
                        </div>
                    </div>
                    <h5>750 ₽</h5>
                </div>

            </div>

            <div class="inf_zak">
                <div class="iz1">
                    <div class="sp_o">
                        <h5>Способ оплаты</h5>
                        <p>Через СПБ</p>
                    </div>
                    <div class="sp_o">
                        <h5>Доставка по адресу</h5>
                        <p>ул. Пушкина, д. 10, кв. 25</p>
                    </div>
                    <div class="sp_o">
                        <h5>Дата и время доставки</h5>
                        <p>18.03.2026 в 10:00</p>
                    </div>
                </div>
                <h6>1 780 ₽</h6>

            </div>
        </div>
        <div class="zak">
            <div class="nom_z">
                <div class="nom1">
                    <p>Заказ #0004</p>
                    <p>15.02.2026 в 09:00</p>
                </div>
                <p id="hz">Доставлен</p>
            </div>

            <div class="gips">
                <img src="image/new1.png" alt="">
                <div class="gips_txt">
                    <p>Паста с морепродуктами</p>

                    <div class="kal">
                        <div class="k">
                            <p id="or">450</p>
                            <p id="s">ккал</p>
                        </div>
                        <div class="k">
                            <p id="si">35</p>
                            <p id="s">белков</p>
                        </div>
                        <div class="k">
                            <p id="kr">15</p>
                            <p id="s">жиров</p>
                        </div>
                        <div class="k">
                            <p id="ze">40</p>
                            <p id="s">углеводов</p>
                        </div>
                    </div>
                    <h5>720 ₽</h5>
                </div>

            </div>
            <div class="gips">
                <img src="image/new2.png" alt="">
                <div class="gips_txt">
                    <p>Рис с курицей и овощами </p>

                    <div class="kal">
                        <div class="k">
                            <p id="or">450</p>
                            <p id="s">ккал</p>
                        </div>
                        <div class="k">
                            <p id="si">35</p>
                            <p id="s">белков</p>
                        </div>
                        <div class="k">
                            <p id="kr">15</p>
                            <p id="s">жиров</p>
                        </div>
                        <div class="k">
                            <p id="ze">40</p>
                            <p id="s">углеводов</p>
                        </div>
                    </div>
                    <h5>750 ₽</h5>
                </div>

            </div>

            <div class="inf_zak">
                <div class="iz1">
                    <div class="sp_o">
                        <h5>Способ оплаты</h5>
                        <p>Картой</p>
                    </div>
                    <div class="sp_o">
                        <h5>Доставка по адресу</h5>
                        <p>ул. Пушкина, д. 10, кв. 25</p>
                    </div>
                    <div class="sp_o">
                        <h5>Дата и время доставки</h5>
                        <p>18.03.2026 в 10:00</p>
                    </div>
                </div>
                <h6>1 780 ₽</h6>
            </div>
            <a href="" class="rep">
                <img src="image/repeat.svg" alt="Повторить">
                <p>Повторить заказ</p>
            </a>
        </div>
        <div class="zak">
            <div class="nom_z">
                <div class="nom1">
                    <p>Заказ #0005</p>
                    <p>15.02.2026 в 09:00</p>
                </div>
                <p id="kiz">Отменён</p>
            </div>
            <div class="gips">
                <img src="image/new2.png" alt="">
                <div class="gips_txt">
                    <p>Рис с курицей и овощами </p>

                    <div class="kal">
                        <div class="k">
                            <p id="or">450</p>
                            <p id="s">ккал</p>
                        </div>
                        <div class="k">
                            <p id="si">35</p>
                            <p id="s">белков</p>
                        </div>
                        <div class="k">
                            <p id="kr">15</p>
                            <p id="s">жиров</p>
                        </div>
                        <div class="k">
                            <p id="ze">40</p>
                            <p id="s">углеводов</p>
                        </div>
                    </div>
                    <h5>750 ₽</h5>
                </div>
            </div>

            <div class="gips">
                <img src="image/new2.png" alt="">
                <div class="gips_txt">
                    <p>Рис с курицей и овощами </p>

                    <div class="kal">
                        <div class="k">
                            <p id="or">450</p>
                            <p id="s">ккал</p>
                        </div>
                        <div class="k">
                            <p id="si">35</p>
                            <p id="s">белков</p>
                        </div>
                        <div class="k">
                            <p id="kr">15</p>
                            <p id="s">жиров</p>
                        </div>
                        <div class="k">
                            <p id="ze">40</p>
                            <p id="s">углеводов</p>
                        </div>
                    </div>
                    <h5>750 ₽</h5>
                </div>
            </div>

            <div class="inf_zak">
                <div class="iz1">
                    <div class="sp_o">
                        <h5>Способ оплаты</h5>
                        <p>Картой</p>
                    </div>
                    <div class="sp_o">
                        <h5>Доставка по адресу</h5>
                        <p>ул. Пушкина, д. 10, кв. 25</p>
                    </div>
                    <div class="sp_o">
                        <h5>Дата и время доставки</h5>
                        <p>18.03.2026 в 10:00</p>
                    </div>
                </div>
                <h6>1 780 ₽</h6>
            </div>
            <a href="" class="rep">
                <img src="image/repeat.svg" alt="Повторить">
                <p>Повторить заказ</p>
            </a>
        </div>
        <p class="null2">У вас еще не было заказов, совершите ваш первый заказ</p>
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