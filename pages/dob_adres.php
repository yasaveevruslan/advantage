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
                <a href="profile.html" id="fil">Профиль</a>
                <a href="lk.html">Заказы</a>
                <a href="lk_izb.html">Избранное</a>
                <a href="">Подписка</a>
            </div>

            <div class="lk_inf">
                <p>Личная информация</p>
                <div class="lk_den">
                    <div class="lk1">
                        <p id="ser">ФИО</p>
                        <p>Мингараева Аделя Наилевна</p>
                    </div>
                    <div class="lk1">
                        <p id="ser">Номер телефона</p>
                        <p>+7 (999) 999-99-99</p>
                    </div>
                    <div class="lk1">
                        <p id="ser">Почта</p>
                        <p>MingaraevaAdela@mail.ru</p>
                    </div>
                </div>
                <a href="">Редактировать</a>
            </div>

            <div class="adres">
                <p id="li">Адреса доставки</p>
                <div class="dob2">
                    <p>Новый адрес</p>
                    <form action="" id="new_adr">
                        <div id="u_d"><label for="">Улица и дом *</label>
                            <input type="text" placeholder="Введите улицу и дом">
                            <p class="error">Имеет неверное значение</p>
                        </div>
                        <div id="u_d"><label for="">Квартира *</label>
                            <input type="text" placeholder="Введите номер квартиры">
                            <p class="error">Имеет неверное значение</p>
                        </div>
                    </form>
                    <input type="submit" value="Сохранить адрес">
                </div>
                <div class="rusl_d">
                    <div class="adr1">
                        <div class="lk_txt">
                            <p id="adr">Адрес</p>
                            <p>Казань, ул. Пушкина, д. 10, кв. 25</p>
                        </div>
                        <div class="i_knop2">
                            <img src="image/kor.svg" alt="Корзина">
                        </div>
                    </div>
                    <div class="adr1">
                        <div class="lk_txt">
                            <p id="adr">Адрес</p>
                            <p>Казань, ул. пр-кт Победы, д. 120, кв. 27</p>
                        </div>
                        <div class="i_knop2">
                            <img src="image/kor.svg" alt="Корзина">
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- Модальное окно подтверждения удаления -->
    <div id="modalOverlay" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Удалить</h3>
                <button class="modal-close" id="modalCloseBtn">&times;</button>
            </div>
            <div class="modal-body">
                <p>Вы точно хотите удалить выбранный адрес? Отменить данное действие будет невозможно.</p>
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-cancel" id="modalCancelBtn">Отмена</button>
                <button class="modal-btn modal-btn-delete" id="modalDeleteBtn">Удалить</button>
            </div>
        </div>
    </div>

    <script>
        // Получаем элементы
        const modal = document.getElementById('modalOverlay');
        const closeBtn = document.getElementById('modalCloseBtn');
        const cancelBtn = document.getElementById('modalCancelBtn');
        const deleteBtn = document.getElementById('modalDeleteBtn');

        // Находим картинку (иконку удаления) рядом с кнопкой "Редактировать"
        const deleteIcon = document.querySelector('.i_knop2 img');

        // Функция открытия модального окна
        function openModal() {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden'; // блокируем прокрутку страницы
        }

        // Функция закрытия модального окна
        function closeModal() {
            modal.classList.remove('active');
            document.body.style.overflow = ''; // возвращаем прокрутку
        }

        // Обработчик для подтверждения удаления
        function onConfirmDelete() {
            console.log('Адрес удален');
            // Здесь можно добавить реальное удаление:
            // Например, отправить запрос на сервер или удалить блок с товаром
            alert('Адрес успешно удалено!');
            closeModal();

            // Дополнительно: можно удалить весь блок с товаром со страницы
            // const itemBlock = document.querySelector('.item');
            // if (itemBlock) itemBlock.remove();
        }

        // Открываем модальное окно при клике на картинку (иконку удаления)
        if (deleteIcon) {
            deleteIcon.addEventListener('click', function (e) {
                e.preventDefault();
                openModal();
            });
        }

        // Вешаем обработчики на крестик и кнопку "Отмена"
        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        // Подтверждение удаления
        deleteBtn.addEventListener('click', onConfirmDelete);

        // Закрытие по клику на затемненную область (оверлей)
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Закрытие по клавише ESC
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
        });
    </script>
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