<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ?page=auth');
    exit;
}

global $connect;

if (isset($_POST['review_action']) && isset($_POST['review_id'])) {
    $reviewId = intval($_POST['review_id']);
    $action = $_POST['review_action'];
    
    if ($action === 'publish') {
        $stmt = $connect->prepare("UPDATE reviews SET is_moderated = 1 WHERE id = ?");
        $stmt->execute([$reviewId]);
        $_SESSION['success'] = 'Отзыв опубликован';
    } elseif ($action === 'delete') {
        $stmt = $connect->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->execute([$reviewId]);
        $_SESSION['success'] = 'Отзыв удалён';
    }
    
    header('Location: ?page=admin_lk_otz');
    exit;
}

$stmt = $connect->prepare("
    SELECT r.id, r.rating, r.comment, r.created_at, r.is_moderated, 
           u.full_name as user_name
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    ORDER BY r.created_at DESC
");
$stmt->execute();
$reviews = $stmt->fetchAll();
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
            <a href="?page=admin_lk" id="<?= ($page === 'admin_lk') ? 'fil' : '' ?>">Заказы</a>
            <a href="?page=admin_lk_otz" id="<?= ($page === 'admin_lk_otz') ? 'fil' : '' ?>">Отзывы</a>
            <a href="?page=admin_lk_promocod" id="<?= ($page === 'admin_lk_promocod') ? 'fil' : '' ?>">Промокоды</a>
        </div>
    </div>
</div>

<div class="ist_zak container">
    <p id="ist">Отзывы пользователей</p>

    <?php if (empty($reviews)): ?>
    <p class="null2" style="text-align:center;padding:30px;color:#666;">Отзывов пока нет</p>
    <?php else: ?>
    <div class="otziv">
        <?php foreach ($reviews as $rev): ?>
        <div class="ot2">
            <div class="ot2_name2">
                <div class="name2">
                    <h6><?= htmlspecialchars($rev['user_name']) ?></h6>
                    <p><?= date('d.m.Y', strtotime($rev['created_at'])) ?></p>
                    <?php if ($rev['rating']): ?>
                    <span style="font-size:14px;"><?= str_repeat('⭐', $rev['rating']) ?></span>
                    <?php endif; ?>
                </div>
                <p id="<?= $rev['is_moderated'] ? 'hz' : 'zel' ?>">
                    <?= $rev['is_moderated'] ? 'Опубликован' : 'Ожидание публикации' ?>
                </p>
            </div>

            <div class="o_txt2">
                <p><?= nl2br(htmlspecialchars($rev['comment'])) ?></p>
            </div>

            <?php if (!$rev['is_moderated']): ?>
            <div class="i_knop1">
                <form method="POST" style="width:100%">
                    <input type="hidden" name="review_id" value="<?= $rev['id'] ?>">
                    <input type="hidden" name="review_action" value="publish">
                    <input type="submit" value="Опубликовать">
                </form>

                <img src="image/kor.svg" alt="Удалить" class="delete-review" data-review-id="<?= $rev['id'] ?>">
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<div id="modalOverlay" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Удалить отзыв</h3>
            <button class="modal-close" id="modalCloseBtn">&times;</button>
        </div>
        <div class="modal-body">
            <p>Вы точно хотите удалить этот отзыв? Отменить действие будет невозможно.</p>
        </div>
        <form method="POST" id="deleteForm">
            <input type="hidden" name="review_id" id="modalReviewId">
            <input type="hidden" name="review_action" value="delete">
        </form>
        <div class="modal-footer">
            <button type="button" class="modal-btn modal-btn-cancel" id="modalCancelBtn">Отмена</button>
            <button type="submit" form="deleteForm" class="modal-btn modal-btn-delete"
                id="modalDeleteBtn">Удалить</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modalOverlay');
    const closeBtn = document.getElementById('modalCloseBtn');
    const cancelBtn = document.getElementById('modalCancelBtn');
    const deleteIcons = document.querySelectorAll('.delete-review');
    const modalReviewId = document.getElementById('modalReviewId');

    function openModal(reviewId) {
        modalReviewId.value = reviewId;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    deleteIcons.forEach(icon => {
        icon.addEventListener('click', (e) => {
            e.preventDefault();
            const reviewId = icon.dataset.reviewId;
            openModal(reviewId);
        });
    });

    closeBtn?.addEventListener('click', closeModal);
    cancelBtn?.addEventListener('click', closeModal);
    modal?.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal?.classList.contains('active')) closeModal();
    });
});
</script>