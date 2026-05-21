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

if ($_SESSION['user_role'] == 'admin') {
    header('Location: index.php?page=admin_lk');
    exit;
}

$addrStmt = $connect->prepare("SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC");
$addrStmt->execute([$_SESSION['user_id']]);
$addresses = $addrStmt->fetchAll();
?>

<div class="lich container">
    <?php include('includes/l_V.php') ?>

    <div class="lk container">
        <?php include('includes/lk_filter.php') ?>

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
            <div class="dob"><a href="?page=dob_adres">+ Добавить адрес</a></div>
            <div class="rusl_a">
                <?php if (empty($addresses)): ?>
                <p style="color:#666; padding:15px 0;">У вас пока нет сохранённых адресов</p>
                <?php else: ?>
                <?php foreach ($addresses as $addr): ?>
                <div class="adr1">
                    <div class="lk_txt">
                        <p class="adr">Адрес
                            <?= $addr['is_default'] ? '<small style="color:#94D201;">(основной)</small>' : '' ?></p>
                        <p>
                            <?= htmlspecialchars($addr['city']) ?>,
                            <?= htmlspecialchars($addr['street']) ?>, д. <?= htmlspecialchars($addr['house']) ?>
                            <?= $addr['apartment'] ? ', кв. ' . htmlspecialchars($addr['apartment']) : '' ?>
                            <?= $addr['floor'] ? ', этаж ' . htmlspecialchars($addr['floor']) : '' ?>
                            <?= $addr['entrance'] ? ', подъезд ' . htmlspecialchars($addr['entrance']) : '' ?>
                        </p>
                        <?php if ($addr['comment']): ?>
                        <p style="font-size:13px; color:#888; margin-top:5px;">Комментарий для курьера:
                            <?= htmlspecialchars($addr['comment']) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="i_knop2">
                        <img src="image/kor.svg" alt="Удалить" class="delete-address" data-id="<?= $addr['id'] ?>"
                            style="cursor:pointer;">
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div id="modalOverlay" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Удалить адрес</h3>
            <button class="modal-close" id="modalCloseBtn">&times;</button>
        </div>
        <div class="modal-body">
            <p>Вы точно хотите удалить выбранный адрес? Отменить данное действие будет невозможно.</p>
            <form method="POST" action="php/delete_address.php" id="deleteAddressForm">
                <input type="hidden" name="address_id" id="deleteAddressId">
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn modal-btn-cancel" id="modalCancelBtn">Отмена</button>
            <button type="submit" form="deleteAddressForm" class="modal-btn modal-btn-delete">Удалить</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalOverlay');
    const closeBtn = document.getElementById('modalCloseBtn');
    const cancelBtn = document.getElementById('modalCancelBtn');
    const deleteForms = document.querySelectorAll('.delete-address');
    const deleteAddressId = document.getElementById('deleteAddressId');

    function openModal(addressId) {
        deleteAddressId.value = addressId;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    deleteForms.forEach(icon => {
        icon.addEventListener('click', function(e) {
            e.preventDefault();
            openModal(this.dataset.id);
        });
    });

    closeBtn?.addEventListener('click', closeModal);
    cancelBtn?.addEventListener('click', closeModal);
    modal?.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal?.classList.contains('active')) closeModal();
    });
});
</script>