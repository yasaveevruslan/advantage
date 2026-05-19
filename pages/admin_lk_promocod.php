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
            <a href="admin_lk_otz.html">Отзывы</a>
            <a href="admin_lk_promocod.html" id="fil">Промокоды</a>
        </div>
    </div>
</div>

<div class="ist_zak container">
    <p id="ist">Промокоды</p>

    <div class="addpromocod">
        <a href="admin_addpromocod.html">🞢 Добавить промокод</a>
    </div>

    <div class="otziv">
        <div class="ot2">
            <div class="promocod_type">
                <div class="name3">
                    <p>Название</p>
                    <h6>ВКУСНО</h6>
                </div>
                <div class="name3">
                    <p>Тип</p>
                    <h6>Процент 25% от цены</h6>
                </div>
                <div class="name3">
                    <p>От суммы</p>
                    <h6>2 500 ₽</h6>
                </div>
            </div>
            <div class="i_knop1">
                <input type="submit" value="Редактировать">
                <img src="image/kor.svg" alt="Корзина">
            </div>
        </div>
    </div>
    <div class="otziv">
        <div class="ot2">
            <div class="promocod_type">
                <div class="name3">
                    <p>Название</p>
                    <h6>СПАСИБО</h6>
                </div>
                <div class="name3">
                    <p>Тип</p>
                    <h6>Процент 10% от цены</h6>
                </div>
                <div class="name3">
                    <p>От суммы</p>
                    <h6>1 500 ₽</h6>
                </div>
            </div>
            <div class="i_knop1">
                <input type="submit" value="Редактировать">
                <img src="image/kor.svg" alt="Корзина">
            </div>
        </div>
    </div>
    <div class="otziv">
        <div class="ot2">
            <div class="promocod_type">
                <div class="name3">
                    <p>Название</p>
                    <h6>БЫСТРО</h6>
                </div>
                <div class="name3">
                    <p>Тип</p>
                    <h6>Бесплатная доставка</h6>
                </div>
                <div class="name3">
                    <p>От суммы</p>
                    <h6>2 500 ₽</h6>
                </div>
            </div>
            <div class="i_knop1">
                <input type="submit" value="Редактировать">
                <img src="image/kor.svg" alt="Корзина">
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
            <p>Вы точно хотите удалить выбранный промокод Отменить данное действие будет невозможно.</p>
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
    console.log('Промокод удален');
    // Здесь можно добавить реальное удаление:
    // Например, отправить запрос на сервер или удалить блок с товаром
    alert('Промокод успешно удален!');
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