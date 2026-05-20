<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: index.php?page=auth');
    exit;
}

global $connect;
$dishId = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_dish'])) {
    $idToDelete = intval($_POST['dish_id'] ?? 0);
    if ($idToDelete > 0) {
        $stmt = $connect->prepare("SELECT image FROM dishes WHERE id = ?");
        $stmt->execute([$idToDelete]);
        $row = $stmt->fetch();
        if ($row && $row['image'] && file_exists(__DIR__ . '/../bl/' . $row['image'])) {
            unlink(__DIR__ . '/../bl/' . $row['image']);
        }
        $connect->prepare("DELETE FROM dishes WHERE id = ?")->execute([$idToDelete]);
        header('Location: index.php?page=admin_kat_bl');
        exit;
    }
}

$stmt = $connect->prepare("
    SELECT d.*, c.name as category_name 
    FROM dishes d 
    LEFT JOIN categories c ON d.category_id = c.id 
    WHERE d.id = ?
");
$stmt->execute([$dishId]);
$dish = $stmt->fetch();

if (!$dish) {
    echo '<div class="container" style="padding:40px 0; text-align:center;">
            <h2>Блюдо не найдено</h2>
            <a href="index.php?page=admin_kat_bl" style="color:#94D201;">← Вернуться в каталог</a>
          </div>';
    exit;
}
?>

<p id="hleb" class="container">
    <a href="index.php">Главная</a> >
    <a href="index.php?page=admin_kat_bl">Каталог блюд (админ)</a> >
    <?= htmlspecialchars($dish['name']) ?>
</p>

<div class="item container">
    <div class="i_img">
        <img src="bl/<?= htmlspecialchars($dish['image'] ?: 'placeholder.png') ?>"
            alt="<?= htmlspecialchars($dish['name']) ?>">
    </div>
    <div class="item_txt">
        <div class="bl1">
            <p id="sbal"><?= htmlspecialchars($dish['category_name'] ?? 'Без категории') ?></p>
            <h5><?= htmlspecialchars($dish['name']) ?></h5>
            <div class="i_kal">
                <div class="i_k">
                    <p id="i_or"><?= (int)$dish['kcal'] ?></p>
                    <p>ккал</p>
                </div>
                <div class="i_k">
                    <p id="i_si"><?= (int)$dish['protein'] ?></p>
                    <p>белков</p>
                </div>
                <div class="i_k">
                    <p id="i_kr"><?= (int)$dish['fat'] ?></p>
                    <p>жиров</p>
                </div>
                <div class="i_k">
                    <p id="i_ze"><?= (int)$dish['carbs'] ?></p>
                    <p>углеводов</p>
                </div>
            </div>
            <p id="pr"><?= number_format($dish['price'], 0, '.', ' ') ?> ₽</p>

            <div class="i_knop1">
                <a href="index.php?page=admin_edit_bl&id=<?= $dish['id'] ?>">Редактировать</a>

                <img src="image/kor.svg" alt="Удалить" id="deleteTrigger">
            </div>
        </div>

        <div class="bl2">
            <div class="o1">
                <h6>Описание</h6>
                <p><?= nl2br(htmlspecialchars($dish['description'] ?: 'Описание отсутствует.')) ?></p>
            </div>
            <div class="o1">
                <h6>Состав</h6>
                <p><?= nl2br(htmlspecialchars($dish['ingredients'] ?: 'Состав не указан.')) ?></p>
            </div>
        </div>
    </div>
</div>

<div id="modalOverlay" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Удалить блюдо</h3>
            <button class="modal-close" id="modalCloseBtn">&times;</button>
        </div>
        <div class="modal-body">
            <p>Вы точно хотите удалить блюдо <strong>"<?= htmlspecialchars($dish['name']) ?>"</strong>?<br>Отменить
                данное действие будет невозможно.</p>

            <form method="POST" id="deleteForm">
                <input type="hidden" name="dish_id" value="<?= $dish['id'] ?>">
                <input type="hidden" name="delete_dish" value="1">
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
    const deleteTrigger = document.getElementById('deleteTrigger');

    function openModal() {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (deleteTrigger) {
        deleteTrigger.addEventListener('click', function(e) {
            e.preventDefault();
            openModal();
        });
    }

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