<?php
$setId = intval($_GET['id'] ?? 0);
if ($setId <= 0) {
    header('Location: index.php?page=catalog_nabor');
    exit;
}

global $connect;

$stmt = $connect->prepare("SELECT * FROM sets WHERE id = ?");
$stmt->execute([$setId]);
$set = $stmt->fetch();

if (!$set || $set['is_available'] == 0) {
    echo '<div class="container" style="padding:40px 0; text-align:center;">
            <h2>Набор не найден или временно недоступен</h2>
            <a href="index.php?page=catalog_nabor" style="color:#94D201;">← Вернуться в каталог</a>
          </div>';
    exit;
}

$stmt = $connect->prepare("
    SELECT d.*, sd.quantity,
           d.kcal * sd.quantity as item_kcal,
           d.protein * sd.quantity as item_protein,
           d.fat * sd.quantity as item_fat,
           d.carbs * sd.quantity as item_carbs,
           d.price * sd.quantity as item_price
    FROM set_dishes sd
    JOIN dishes d ON sd.dish_id = d.id
    WHERE sd.set_id = ?
    ORDER BY d.name
");
$stmt->execute([$setId]);
$setDishes = $stmt->fetchAll();

$totalKcal = array_sum(array_column($setDishes, 'item_kcal'));
$totalProtein = array_sum(array_column($setDishes, 'item_protein'));
$totalFat = array_sum(array_column($setDishes, 'item_fat'));
$totalCarbs = array_sum(array_column($setDishes, 'item_carbs'));
$totalPrice = array_sum(array_column($setDishes, 'item_price'));
?>

<p id="hleb" class="container">
    <a href="index.php">Главная</a> >
    <a href="index.php?page=catalog_nabor">Каталог наборов</a> >
    <?= htmlspecialchars($set['name']) ?>
</p>

<div class="item container">
    <div class="i_img">
        <img src="na/<?= htmlspecialchars($set['image'] ?: 'placeholder.png') ?>"
            alt="<?= htmlspecialchars($set['name']) ?>">
    </div>
    <div class="item_txt">
        <div class="bl1">
            <p id="sbal"><?= htmlspecialchars($set['name']) ?></p>
            <h5><?= htmlspecialchars($set['name']) ?> (<?= count($setDishes) ?> блюда)</h5>

            <div class="i_kal">
                <div class="i_k">
                    <p id="i_or"><?= (int)$totalKcal ?></p>
                    <p>ккал</p>
                </div>
                <div class="i_k">
                    <p id="i_si"><?= (int)$totalProtein ?></p>
                    <p>белков</p>
                </div>
                <div class="i_k">
                    <p id="i_kr"><?= (int)$totalFat ?></p>
                    <p>жиров</p>
                </div>
                <div class="i_k">
                    <p id="i_ze"><?= (int)$totalCarbs ?></p>
                    <p>углеводов</p>
                </div>
            </div>

            <p id="pr"><?= number_format($set['price'], 0, '.', ' ') ?> ₽</p>

            <div class="i_knop">
                <a href="php/add_to_cart.php?id=<?= $set['id'] ?>&type=set">В корзину</a>
                <img src="image/izb.svg" alt="Избранное" class="add-favorite" data-id="<?= $set['id'] ?>"
                    data-type="set">
            </div>
        </div>

        <div class="bl2">
            <div class="o1">
                <h6>Описание</h6>
                <p><?= nl2br(htmlspecialchars($set['description'] ?: 'Описание отсутствует.')) ?></p>
            </div>

            <div class="o1">
                <h6>Состав</h6>
                <div class="sos_nab">
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
                </div>
            </div>
        </div>
    </div>
</div>