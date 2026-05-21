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
?>

<div class="lich container">
    <?php include('includes/l_V.php') ?>

    <div class="lk container">
        <?php include('includes/lk_filter.php') ?>
    </div>
</div>

<div class="izbr container">
    <h5>Избранное</h5>

    <?php
    $sql = "SELECT f.id as fav_id, f.item_type, f.item_id,
                   COALESCE(d.name, sd.name) as name,
                   COALESCE(d.price, sd.price) as price,
                   COALESCE(d.image, sd.image) as image,
                   COALESCE(d.kcal, sd.kcal) as kcal,
                   COALESCE(d.protein, sd.protein) as protein,
                   COALESCE(d.fat, sd.fat) as fat,
                   COALESCE(d.carbs, sd.carbs) as carbs
            FROM favorites f
            LEFT JOIN dishes d ON f.item_type = 'dish' AND f.item_id = d.id
            LEFT JOIN set_dishes sd ON f.item_type = 'set' AND f.item_id = sd.id
            WHERE f.user_id = ?
            ORDER BY f.created_at DESC";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
    $favorites = $stmt->fetchAll();
    ?>

    <?php if (empty($favorites)): ?>
    <p style="color:#666; padding:20px 0; text-align:center;">В избранном пока ничего нет</p>
    <?php else: ?>
    <?php foreach ($favorites as $fav): ?>
    <?php
                $imgPath = ($fav['item_type'] === 'set') ? 'na/' : 'bl/';
                $pageLink = ($fav['item_type'] === 'set') ? 'nabor' : 'dish';
                $itemImg = $imgPath . htmlspecialchars($fav['image'] ?: 'placeholder.png');
            ?>
    <div class="izb1">
        <div class="gips">
            <img src="<?= $itemImg ?>" alt="<?= htmlspecialchars($fav['name']) ?>">
            <div class="gips_txt">
                <p><?= htmlspecialchars($fav['name']) ?></p>

                <div class="kal">
                    <div class="k">
                        <p id="or"><?= (int)$fav['kcal'] ?></p>
                        <p id="s">ккал</p>
                    </div>
                    <div class="k">
                        <p id="si"><?= (int)$fav['protein'] ?></p>
                        <p id="s">белков</p>
                    </div>
                    <div class="k">
                        <p id="kr"><?= (int)$fav['fat'] ?></p>
                        <p id="s">жиров</p>
                    </div>
                    <div class="k">
                        <p id="ze"><?= (int)$fav['carbs'] ?></p>
                        <p id="s">углеводов</p>
                    </div>
                </div>
                <h5><?= number_format($fav['price'], 0, '.', ' ') ?> ₽</h5>
            </div>
        </div>

        <form method="POST" action="php/toggle_favorite.php">
            <input type="hidden" name="type" value="<?= $fav['item_type'] ?>">
            <input type="hidden" name="id" value="<?= $fav['item_id'] ?>">
            <button type="submit">
                <img src="image/izb2.svg" alt="Удалить из избранного">
            </button>
        </form>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>