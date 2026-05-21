<?php
$setDishId = intval($_GET['id'] ?? 0);
if ($setDishId <= 0) {
    header('Location: index.php?page=catalog_nabor');
    exit;
}

global $connect;

$stmt = $connect->prepare("
    SELECT sd.*, s.name as category_name
    FROM set_dishes sd
    LEFT JOIN sets s ON sd.set_id = s.id
    WHERE sd.id = ?
");
$stmt->execute([$setDishId]);
$set = $stmt->fetch();

if (!$set || $set['is_available'] == 0) {
    echo '<div class="container" style="padding:40px 0; text-align:center;">
            <h2>Набор не найден или временно недоступен</h2>
            <a href="index.php?page=catalog_nabor" style="color:#94D201;">← Вернуться в каталог</a>
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

$isFav = false;
if (isset($_SESSION['user_id'])) {
    $f = $connect->prepare("SELECT id FROM favorites WHERE user_id = ? AND item_type = 'set' AND item_id = ?");
    $f->execute([$_SESSION['user_id'], $set['id']]);
    $isFav = (bool) $f->fetch();
}
?>

<p id="hleb" class="container">
    <a href="index.php">Главная</a> >
    <a href="index.php?page=catalog_nabor">Каталог наборов</a> >
    <?= htmlspecialchars($set['category_name'] ?? 'Набор') ?> >
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
                    <p id="i_or"><?= (int)$set['kcal'] ?></p>
                    <p id="s">ккал</p>
                </div>
                <div class="i_k">
                    <p id="i_si"><?= (int)$set['protein'] ?></p>
                    <p id="s">белков</p>
                </div>
                <div class="i_k">
                    <p id="i_kr"><?= (int)$set['fat'] ?></p>
                    <p id="s">жиров</p>
                </div>
                <div class="i_k">
                    <p id="i_ze"><?= (int)$set['carbs'] ?></p>
                    <p id="s">углеводов</p>
                </div>
            </div>

            <p id="pr"><?= number_format($set['price'], 0, '.', ' ') ?> ₽</p>

            <div class="i_knop">
                <a href="php/add_to_cart.php?id=<?= $set['id'] ?>&type=set">В корзину</a>
                <form method="POST" action="php/toggle_favorite.php">
                    <input type="hidden" name="type" value="set">
                    <input type="hidden" name="id" value="<?= $set['id'] ?>">
                    <button type="submit" style='border:none' class="add-favorite">
                        <?php if ($isFav): ?>
                        <img src="image/izb2.svg" alt="В избранном">
                        <?php else: ?>
                        <img src="image/izb.svg" alt="Добавить в избранное" class="add-favorite">
                        <?php endif; ?>
                    </button>
                </form>
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

<?php if (isset($_SESSION['user_id'])): ?>
<script>
<?php
    $stmt = $connect->prepare("SELECT CONCAT(item_type, '_', item_id) as key FROM favorites WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $favs = $stmt->fetchAll(PDO::FETCH_COLUMN);
    ?>
window.userFavorites = <?= json_encode($favs) ?>;
</script>
<?php endif; ?>

<script src="/js/modules/favorites" type="module" defer></script>