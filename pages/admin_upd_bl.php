<!-- Один товар -->
<div class="item container">
    <div class="i_img"><img src="image/item1.png" alt=""></div>
    <div class="item_txt">
        <div class="bl1">
            <p id="sbal">Сбалансированное</p>
            <h5>Куриный шницель с макаронами</h5>
            <div class="i_kal">
                <div class="i_k">
                    <p id="i_or">450</p>
                    <p>ккал</p>
                </div>
                <div class="i_k">
                    <p id="i_si">35</p>
                    <p>белков</p>
                </div>
                <div class="i_k">
                    <p id="i_kr">15</p>
                    <p>жиров</p>
                </div>
                <div class="i_k">
                    <p id="i_ze">40</p>
                    <p>углеводов</p>
                </div>
            </div>
            <p id="pr">890 ₽</p>
            <div class="i_knop1">
                <a href="">Редактировать</a>
                <img src="image/kor.svg" alt="Корзина">
            </div>
        </div>

        <div class="bl2">
            <div class="o1">
                <h6>Описание</h6>
                <p>Сочный куриный шницель в хрустящей панировке подается с классическими макаронами из твердых
                    сортов
                    пшеницы. Идеальный баланс белка и сложных углеводов для сытного обеда. Готовое блюдо, которое
                    нужно
                    только разогреть.</p>
            </div>
            <div class="o1">
                <h6>Состав</h6>
                <p>Макаронные изделия из твердых сортов пшеницы, филе куриной грудки, меланж яичный жидкий, вода
                    питьевая, сухари панировочные, масло подсолнечное рафинированное дезодорированное, соль пищевая,
                    специи и приправы.</p>
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
            <p>Вы точно хотите удалить выбранное блюдо? Отменить данное действие будет невозможно.</p>
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
    console.log('Блюдо удалено');
    // Здесь можно добавить реальное удаление:
    // Например, отправить запрос на сервер или удалить блок с товаром
    alert('Блюдо успешно удалено!');
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