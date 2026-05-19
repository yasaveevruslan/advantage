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
            <a href="admin_lk.html">Заказы</a>
            <a href="admin_lk_otz.html" id="fil">Отзывы</a>
            <a href="admin_lk_promocod.html">Промокоды</a>
        </div>
    </div>
</div>

<div class="ist_zak container">
    <p id="ist">Отзывы пользователей</p>
    <div class="otziv">
        <div class="ot2">
            <div class="ot2_name2">
                <div class="name2">
                    <h6>Аделя</h6>
                    <p>01.03.2026</p>
                </div>
                <p id="zel">Ожидание публикации</p>
            </div>
            <div class="o_txt2">
                <p>Попробовала двухдневку Детокс от левелкитчен. Должна сказать, что мне не просто понравилось, я
                    получила заряд бодрости. Соки вкусные. Во всем теле возникла энергетика и легкость. Снижение
                    веса было небольшое, но было. За два дня это тоже достижение. Но главное, конечно, хорошее
                    самочувствие. Так что решила, что при общей программе в 1500 пара дней летокса в месяц просто
                    отличная разрядка.</p>
            </div>
            <div class="i_knop1">
                <input type="submit" value="Опубликовать">
                <img src="image/kor.svg" alt="Корзина">
            </div>
        </div>
        <div class="ot2">
            <div class="ot2_name2">
                <div class="name2">
                    <h6>Юлия</h6>
                    <p>25.02.2026</p>
                </div>
                <p id="hz">Опубликован</p>
            </div>
            <div class="o_txt2">
                <p>Заказали с мамой пробники - даже не ожидали, что съедим все до крошечки и будем хитро друг на
                    друга поглядывать, не осталось ли чего - поделить. Мой любимый омлет сделан на совесть. Я всегда
                    ленюсь взбивать яйца в крутую пену, а хздесь постарались. И вкуснейший черничный крем.
                    Тарталетка показалась совсем маленькой, хоть и по размерам ничего себе такая, на хороший
                    перекус. В общем, убедили: не обязательно убиваться в кухне, можно просто заказать и получить
                    приличную еду</p>
            </div>
        </div>
        <div class="ot2">
            <div class="ot2_name2">
                <div class="name2">
                    <h6>Карима</h6>
                    <p>14.02.2026</p>
                </div>
                <p id="hz">Опубликован</p>
            </div>

            <div class="o_txt2">
                <p>Офигенное место, отличная кухня, обалденные вишневые настойки, супер приветливый, дружелюбный<br>
                    персонал Понравилось то, что в заведении чувствуешь себя «своим», чувствуешь что тебе рады,<br>
                    однозначно рекомендую к посещению !❤️</p>
            </div>
        </div>
        <div class="ot2">
            <div class="ot2_name2">
                <div class="name2">
                    <h6>Лиза</h6>
                    <p>08.02.2026</p>
                </div>
                <p id="hz">Опубликован</p>
            </div>
            <div class="o_txt2">
                <p>Для ПП — вполне годный вариант, учитывая, что готовить не надо, а еда вкусна и разная</p>
            </div>
        </div>
    </div>
    <p class="null2">У вас еще не было заказов, совершите ваш первый заказ</p>
</div>

<!-- Модальное окно подтверждения удаления -->
<div id="modalOverlay" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Удалить</h3>
            <button class="modal-close" id="modalCloseBtn">&times;</button>
        </div>
        <div class="modal-body">
            <p>Вы точно хотите удалить выбранный отзыв? Отменить данное действие будет невозможно.</p>
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
const deleteIcon = document.querySelector('.i_knop1 img');

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
    console.log('Отзыв удален');
    // Здесь можно добавить реальное удаление:
    // Например, отправить запрос на сервер или удалить блок с товаром
    alert('Отзыв успешно удален!');
    closeModal();

    // Дополнительно: можно удалить весь блок с товаром со страницы
    // const itemBlock = document.querySelector('.item');
    // if (itemBlock) itemBlock.remove();
}

// Открываем модальное окно при клике на картинку (иконку удаления)
if (deleteIcon) {
    deleteIcon.addEventListener('click', function(e) {
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
modal.addEventListener('click', function(e) {
    if (e.target === modal) {
        closeModal();
    }
});

// Закрытие по клавише ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && modal.classList.contains('active')) {
        closeModal();
    }
});
</script>