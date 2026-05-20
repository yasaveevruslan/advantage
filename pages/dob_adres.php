<?php
    global $connect;
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=auth');
        exit;
    }

    $stmt = $connect->prepare("SELECT id, full_name, phone, email, role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user) {
        session_destroy();
        header('Location: index.php?page=auth');
        exit;
    }
?>
<div class="lich container">
    <?php include('includes/l_V.php') ?>

    <div class="lk container">
    <?php include('includes/l_V.php') ?>

        <div class="lk_inf">
            <p>Личная информация</p>
            <div class="lk_den">
                <div class="lk1">
                    <p id="ser">ФИО</p>
                    <p><?= htmlspecialchars($user['full_name']) ?></p>
                </div>
                <div class="lk1">
                    <p id="ser">Номер телефона</p>
                    <p><?= htmlspecialchars($user['phone']) ?></p>
                </div>
                <div class="lk1">
                    <p id="ser">Почта</p>
                    <p><?= htmlspecialchars($user['email']) ?></p>
                </div>
            </div>
            <a href="?page=upd_profile">Редактировать</a>
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
const modal = document.getElementById('modalOverlay');
const closeBtn = document.getElementById('modalCloseBtn');
const cancelBtn = document.getElementById('modalCancelBtn');
const deleteBtn = document.getElementById('modalDeleteBtn');

const deleteIcon = document.querySelector('.i_knop2 img');

function openModal() {
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    modal.classList.remove('active');
    document.body.style.overflow = '';
}

function onConfirmDelete() {
    console.log('Адрес удален');
    alert('Адрес успешно удалено!');
    closeModal();

}

if (deleteIcon) {
    deleteIcon.addEventListener('click', function(e) {
        e.preventDefault();
        openModal();
    });
}

closeBtn.addEventListener('click', closeModal);
cancelBtn.addEventListener('click', closeModal);

deleteBtn.addEventListener('click', onConfirmDelete);

modal.addEventListener('click', function(e) {
    if (e.target === modal) {
        closeModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && modal.classList.contains('active')) {
        closeModal();
    }
});
</script>