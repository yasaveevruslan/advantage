<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: index.php?page=auth');
    exit;
}

global $connect;

if (isset($_POST['delete_promo']) && isset($_POST['promo_id'])) {
    $stmt = $connect->prepare("DELETE FROM promocodes WHERE id = ?");
    $stmt->execute([intval($_POST['promo_id'])]);
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

$stmt = $connect->prepare("SELECT * FROM promocodes ORDER BY id DESC");
$stmt->execute();
$promos = $stmt->fetchAll();
?>

<div class="lich container">
    <div class="l_v">
        <div class="l_v_panel">
            <h4>Админ-панель</h4>
            <p id="wel">Добро пожаловать, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Админ') ?>!</p>
        </div>
        <a href="php/logout.php">Выйти</a>
    </div>

    <div class="lk container">
        <div class="lk_filter">
            <a href="index.php?page=admin_lk" id="<?= ($page === 'admin_lk') ? 'fil' : '' ?>">Заказы</a>
            <a href="index.php?page=admin_lk_otz" id="<?= ($page === 'admin_lk_otz') ? 'fil' : '' ?>">Отзывы</a>
            <a href="index.php?page=admin_lk_promocod"
                id="<?= ($page === 'admin_lk_promocod') ? 'fil' : '' ?>">Промокоды</a>
        </div>
    </div>
</div>

<div class="ist_zak container">
    <p id="ist">Промокоды</p>

    <div class="addpromocod">
        <a href="index.php?page=admin_addpromocod">🞢 Добавить промокод</a>
    </div>

    <?php if (empty($promos)): ?>
    <p style="text-align:center; padding:20px; color:#666;">Промокодов пока нет</p>
    <?php else: ?>
    <?php foreach ($promos as $promo): 
            if ($promo['discount_type'] === 'percentage') {
                $typeText = $promo['discount_value'] . '% от цены';
            } elseif ($promo['discount_type'] === 'fixed') {
                $typeText = number_format($promo['discount_value'], 0, '.', ' ') . ' ₽ от цены';
            } else {
                $typeText = htmlspecialchars($promo['discount_type']);
            }
            $minOrderText = $promo['min_order'] > 0 ? number_format($promo['min_order'], 0, '.', ' ') . ' ₽' : '—';
        ?>
    <div class="otziv">
        <div class="ot2">
            <div class="promocod_type">
                <div class="name3">
                    <p>Название</p>
                    <h6><?= htmlspecialchars($promo['code']) ?></h6>
                </div>
                <div class="name3">
                    <p>Тип</p>
                    <h6><?= $typeText ?></h6>
                </div>
                <div class="name3">
                    <p>От суммы</p>
                    <h6><?= $minOrderText ?></h6>
                </div>
            </div>
            <div class="i_knop1">
                <a style="width:100%" href="index.php?page=admin_editpromocod&id=<?= $promo['id'] ?>">Редактировать</a>
                <img src="image/kor.svg" alt="Удалить" class="delete-promo" data-id="<?= $promo['id'] ?>">
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Модальное окно подтверждения удаления -->
<div id="modalOverlay" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Удалить промокод</h3>
            <button class="modal-close" id="modalCloseBtn">&times;</button>
        </div>
        <div class="modal-body">
            <p>Вы точно хотите удалить выбранный промокод? Отменить данное действие будет невозможно.</p>
            <form method="POST" id="deleteForm">
                <input type="hidden" name="promo_id" id="modalPromoId">
                <input type="hidden" name="delete_promo" value="1">
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn modal-btn-cancel" id="modalCancelBtn">Отмена</button>
            <button type="submit" form="deleteForm" class="modal-btn modal-btn-delete"
                id="modalDeleteBtn">Удалить</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalOverlay');
    const closeBtn = document.getElementById('modalCloseBtn');
    const cancelBtn = document.getElementById('modalCancelBtn');
    const deleteIcons = document.querySelectorAll('.delete-promo');
    const modalPromoId = document.getElementById('modalPromoId');

    function openModal(id) {
        modalPromoId.value = id;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    deleteIcons.forEach(function(icon) {
        icon.addEventListener('click', function(e) {
            e.preventDefault();
            openModal(this.getAttribute('data-id'));
        });
    });

    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });
});
</script>