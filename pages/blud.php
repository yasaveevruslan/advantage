<?php
$dishId = intval($_GET['id'] ?? 0);
if ($dishId <= 0) {
    header('Location: index.php?page=catalog_blud');
    exit;
}

global $connect;
$stmt = $connect->prepare("
    SELECT d.*, c.name as category_name
    FROM dishes d
    LEFT JOIN categories c ON d.category_id = c.id
    WHERE d.id = ?
");
$stmt->execute([$dishId]);
$dish = $stmt->fetch();

if (!$dish || $dish['is_available'] == 0) {
    echo '<div class="container" style="padding:40px 0; text-align:center;">
            <h2>Блюдо не найдено или временно недоступно</h2>
            <a href="index.php?page=catalog_blud" style="color:#94D201;">← Вернуться в каталог</a>
          </div>';
    exit;
}
?>

<p id="hleb" class="container">
    <a href="index.php">Главная</a> > 
    <a href="index.php?page=catalog_blud">Каталог блюд</a> > 
    <?= htmlspecialchars($dish['category_name'] ?? 'Без категории') ?> > 
    <?= htmlspecialchars($dish['name']) ?>
</p>

<div class="item container">
    <div class="i_img">
        <img src="bl/<?= htmlspecialchars($dish['image'] ?? 'placeholder.png') ?>" alt="<?= htmlspecialchars($dish['name']) ?>">
    </div>
    <div class="item_txt">
        <div class="bl1">
            <p id="sbal"><?= htmlspecialchars($dish['category_name'] ?? 'Без категории') ?></p>
            <h5><?= htmlspecialchars($dish['name']) ?></h5>
            <div class="i_kal">
                <div class="i_k"><p id="i_or"><?= (int)$dish['kcal'] ?></p><p>ккал</p></div>
                <div class="i_k"><p id="i_si"><?= (int)$dish['protein'] ?></p><p>белков</p></div>
                <div class="i_k"><p id="i_kr"><?= (int)$dish['fat'] ?></p><p>жиров</p></div>
                <div class="i_k"><p id="i_ze"><?= (int)$dish['carbs'] ?></p><p>углеводов</p></div>
            </div>
            <p id="pr"><?= number_format($dish['price'], 0, '.', ' ') ?> ₽</p>
            <div class="i_knop">
                <a href="php/add_to_cart.php?id=<?= $dish['id'] ?>&type=dish">В корзину</a>
                <img src="image/izb.svg" alt="Избранное" class="add-favorite" data-id="<?= $dish['id'] ?>">
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