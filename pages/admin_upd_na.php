<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: index.php?page=auth');
    exit;
}

global $connect;
$setDishId = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_set'])) {
    $idToDelete = intval($_POST['set_dish_id'] ?? 0);
    if ($idToDelete > 0) {
        try {
            $connect->beginTransaction();
            
            $connect->prepare("DELETE FROM set_composition WHERE set_dish_id = ?")->execute([$idToDelete]);
            
            $stmt = $connect->prepare("SELECT image FROM set_dishes WHERE id = ?");
            $stmt->execute([$idToDelete]);
            $row = $stmt->fetch();
            if ($row && $row['image'] && file_exists(__DIR__ . '/../na/' . $row['image'])) {
                unlink(__DIR__ . '/../na/' . $row['image']);
            }
            
            $connect->prepare("DELETE FROM set_dishes WHERE id = ?")->execute([$idToDelete]);
            
            $connect->commit();
            header('Location: index.php?page=admin_kat_na');
            exit;
            
        } catch (PDOException $e) {
            $connect->rollBack();
            $error = 'Ошибка удаления: ' . $e->getMessage();
        }
    }
}

$stmt = $connect->prepare("
    SELECT sd.*, s.name as category_name
    FROM set_dishes sd
    LEFT JOIN sets s ON sd.set_id = s.id
    WHERE sd.id = ?
");
$stmt->execute([$setDishId]);
$set = $stmt->fetch();

if (!$set) {
    echo '<div class="container" style="padding:40px 0; text-align:center;">
            <h2>Набор не найден</h2>
            <a href="index.php?page=admin_kat_na" style="color:#94D201;">← Вернуться в каталог</a>
          </div>';
    exit;
}

$stmt = $connect->prepare("
    SELECT d.*, sc.quantity,
           d.kcal * sc.quantity as item_kcal,
           d.protein * sc.quantity as item_protein,
           d.fat * sc.quantity as item_fat,
           d.carbs * sc.quantity as item_carbs,
           d.price * sc.quantity as item_price
    FROM set_composition sc
    JOIN dishes d ON sc.dish_id = d.id
    WHERE sc.set_dish_id = ?
    ORDER BY d.name
");
$stmt->execute([$setDishId]);
$setDishes = $stmt->fetchAll();

$totalKcal = array_sum(array_column($setDishes, 'item_kcal'));
$totalProtein = array_sum(array_column($setDishes, 'item_protein'));
$totalFat = array_sum(array_column($setDishes, 'item_fat'));
$totalCarbs = array_sum(array_column($setDishes, 'item_carbs'));
?>

<p id="hleb" class="container">
    <a href="index.php">Главная</a> >
    <a href="index.php?page=admin_kat_na">Каталог наборов (админ)</a> >
    <?= htmlspecialchars($set['category_name'] ?? '') ?> >
    <?= htmlspecialchars($set['name']) ?>
</p>

<div class="item container">
    <div class="i_img">
        <img src="na/<?= htmlspecialchars($set['image'] ?: 'placeholder.png') ?>"
            alt="<?= htmlspecialchars($set['name']) ?>">
    </div>
    <div class="item_txt">
        <div class="bl1">
            <p id="sbal"><?= htmlspecialchars($set['category_name'] ?? 'Без категории') ?></p>
            <h5><?= htmlspecialchars($set['name']) ?></h5>

            <div class="i_kal">
                <div class="i_k">
                    <p id="or"><?= (int)$set['kcal'] ?></p>
                    <p id="s">ккал</p>
                </div>
                <div class="i_k">
                    <p id="si"><?= (int)$set['protein'] ?></p>
                    <p id="s">белков</p>
                </div>
                <div class="i_k">
                    <p id="kr"><?= (int)$set['fat'] ?></p>
                    <p id="s">жиров</p>
                </div>
                <div class="i_k">
                    <p id="ze"><?= (int)$set['carbs'] ?></p>
                    <p id="s">углеводов</p>
                </div>
            </div>

            <p id="pr"><?= number_format($set['price'], 0, '.', ' ') ?> ₽</p>

            <div class="i_knop1">
                <a href="index.php?page=admin_edit_na&id=<?= $set['id'] ?>">Редактировать</a>

                <img src="image/kor.svg" alt="Удалить" id="deleteTrigger">
            </div>
        </div>

        <div class="bl2">
            <div class="o1">
                <h6>Описание</h6>
                <p><?= nl2br(htmlspecialchars($set['description'] ?: 'Описание отсутствует.')) ?></p>
            </div>

            <div class="o1">
                <h6>Состав набора</h6>
                <div class="sos_nab">
                    <?php if (!empty($setDishes)): ?>
                    <?php foreach ($setDishes as $dish): ?>
                    <div class="gips">
                        <img src="bl/<?= htmlspecialchars($dish['image'] ?: 'placeholder.png') ?>"
                            alt="<?= htmlspecialchars($dish['name']) ?>">
                        <div class="gips_txt">
                            <p><?= htmlspecialchars($dish['name']) ?>
                                <?= $dish['quantity'] > 1 ? '×' . $dish['quantity'] : '' ?></p>
                            <div class="kal">
                                <div class="k">
                                    <p id="or"><?= (int)$dish['item_kcal'] ?></p>
                                    <p id="s">ккал</p>
                                </div>
                                <div class="k">
                                    <p id="si"><?= (int)$dish['item_protein'] ?></p>
                                    <p id="s">белков</p>
                                </div>
                                <div class="k">
                                    <p id="kr"><?= (int)$dish['item_fat'] ?></p>
                                    <p id="s">жиров</p>
                                </div>
                                <div class="k">
                                    <p id="ze"><?= (int)$dish['item_carbs'] ?></p>
                                    <p id="s">углеводов</p>
                                </div>
                            </div>
                            <h5><?= number_format($dish['item_price'], 0, '.', ' ') ?> ₽</h5>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p style="color:#666;">Состав не указан</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalOverlay" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Удалить набор</h3>
            <button class="modal-close" id="modalCloseBtn">&times;</button>
        </div>
        <div class="modal-body">
            <p>Вы точно хотите удалить набор <strong>"<?= htmlspecialchars($set['name']) ?>"</strong>?<br>Отменить
                данное действие будет невозможно.</p>
            <form method="POST" id="deleteForm">
                <input type="hidden" name="set_dish_id" value="<?= $set['id'] ?>">
                <input type="hidden" name="delete_set" value="1">
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