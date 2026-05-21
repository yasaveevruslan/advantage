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

$isFav = false;
if (isset($_SESSION['user_id'])) {
    $f = $connect->prepare("SELECT id FROM favorites WHERE user_id = ? AND item_type = 'dish' AND item_id = ?");
    $f->execute([$_SESSION['user_id'], $dish['id']]);
    $isFav = (bool) $f->fetch();
}

$cartQuantity = 0;
if (isset($_SESSION['user_id'])) {
    $stmt = $connect->prepare("SELECT quantity FROM cart WHERE user_id = ? AND item_type = 'dish' AND item_id = ?");
    $stmt->execute([$_SESSION['user_id'], $dish['id']]);
    $cartQuantity = $stmt->fetchColumn() ?: 0;
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
        <img src="bl/<?= htmlspecialchars($dish['image'] ?? 'placeholder.png') ?>"
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
            <div class="i_knop">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($cartQuantity > 0): ?>
                        <form method="POST" action="php/update_cart_quantity.php" style="display:flex;align-items:center;gap:8px;">
                            <input type="hidden" name="item_type" value="dish">
                            <input type="hidden" name="item_id" value="<?= $dish['id'] ?>">
                            <button type="submit" name="action" value="decrease">−</button>
                            <span><?= $cartQuantity ?></span>
                            <button type="submit" name="action" value="increase">+</button>
                        </form>
                    <?php else: ?>
                        <a href="php/add_to_cart.php?id=<?= $dish['id'] ?>&type=dish" >
                        В корзину
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="index.php?page=auth">
                        В корзину
                    </a>
                <?php endif; ?>

                <form method="POST" action="php/toggle_favorite.php">
                    <input type="hidden" name="type" value="dish">
                    <input type="hidden" name="id" value="<?= $dish['id'] ?>">
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
                <p><?= nl2br(htmlspecialchars($dish['description'] ?: 'Описание отсутствует.')) ?></p>
            </div>
            <div class="o1">
                <h6>Состав</h6>
                <p><?= nl2br(htmlspecialchars($dish['ingredients'] ?: 'Состав не указан.')) ?></p>
            </div>
        </div>
    </div>
</div>