<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: index.php?page=auth');
    exit;
}

global $connect;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_set'])) {
        $setDishId = intval($_POST['set_dish_id']);
        try {
            $connect->beginTransaction();
            $connect->prepare("DELETE FROM set_composition WHERE set_dish_id = ?")->execute([$setDishId]);
            $stmt = $connect->prepare("SELECT image FROM set_dishes WHERE id = ?");
            $stmt->execute([$setDishId]);
            $row = $stmt->fetch();
            if ($row && $row['image'] && file_exists(__DIR__ . '/../na/' . $row['image'])) {
                unlink(__DIR__ . '/../na/' . $row['image']);
            }
            $connect->prepare("DELETE FROM set_dishes WHERE id = ?")->execute([$setDishId]);
            $connect->commit();
        } catch (PDOException $e) {
            $connect->rollBack();
        }
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
    
    if (isset($_POST['toggle_availability'])) {
        $setDishId = intval($_POST['set_dish_id']);
        $connect->prepare("UPDATE set_dishes SET is_available = NOT is_available WHERE id = ?")
                ->execute([$setDishId]);
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
}

$filterCatId = intval($_GET['category'] ?? 0);
$searchQuery = trim($_GET['search'] ?? '');
$sortBy = $_GET['sort'] ?? 'popular';
$priceMax = isset($_GET['price_max']) && $_GET['price_max'] !== '' ? floatval($_GET['price_max']) : '';
$priceMin = isset($_GET['price_min']) && $_GET['price_min'] !== '' ? floatval($_GET['price_min']) : '';

$categories = $connect->query("SELECT id, name FROM sets ORDER BY name")->fetchAll();

$sql = "SELECT sd.*, s.name as category_name,
               (SELECT COUNT(*) FROM set_composition sc WHERE sc.set_dish_id = sd.id) as dishes_count
        FROM set_dishes sd
        LEFT JOIN sets s ON sd.set_id = s.id
        WHERE 1=1";
$params = [];

if ($filterCatId > 0) {
    $sql .= " AND sd.set_id = ?";
    $params[] = $filterCatId;
}
if ($searchQuery !== '') {
    $sql .= " AND sd.name LIKE ?";
    $params[] = "%$searchQuery%";
}
if ($priceMin !== '' && $priceMin >= 0) {
    $sql .= " AND sd.price >= ?";
    $params[] = $priceMin;
}
if ($priceMax !== '' && $priceMax >= 0) {
    $sql .= " AND sd.price <= ?";
    $params[] = $priceMax;
}

switch ($sortBy) {
    case 'price_asc': $sql .= " ORDER BY sd.price ASC"; break;
    case 'price_desc': $sql .= " ORDER BY sd.price DESC"; break;
    case 'calories_asc': $sql .= " ORDER BY sd.kcal ASC"; break;
    case 'calories_desc': $sql .= " ORDER BY sd.kcal DESC"; break;
    default: $sql .= " ORDER BY sd.id DESC";
}

$stmt = $connect->prepare($sql);
$stmt->execute($params);
$sets = $stmt->fetchAll();
?>

<form action="index.php" method="GET">
    <input type="hidden" name="page" value="admin_kat_na">
    <input type="hidden" name="search" value="<?= htmlspecialchars($searchQuery) ?>">
    <input type="hidden" name="sort" value="<?= htmlspecialchars($sortBy) ?>">

    <p id="hleb" class="container">
        <a href="index.php">Главная</a> > <a href="index.php?page=admin_kat_na">Каталог наборов (админ)</a>
    </p>

    <div class="catalog container">
        <h3>Каталог наборов</h3>
        <div class="adm_dob">
            <a href="index.php?page=admin_add_na">+ Добавить набор</a>
            <a href="index.php?page=admin_addkat_na">+ Добавить категорию набора</a>
        </div>

        <div class="filter">
            <a href="?page=admin_kat_na" class="<?= $filterCatId == 0 ? 'active' : '' ?>">Все наборы</a>
            <?php foreach ($categories as $cat): ?>
            <a href="?page=admin_kat_na&category=<?= $cat['id'] ?>"
                class="<?= $filterCatId == $cat['id'] ? 'active' : '' ?>"
                style="display:inline-flex;align-items:center;gap:5px;text-decoration:none;">
                <?= htmlspecialchars($cat['name']) ?>
                <a href="index.php?page=admin_editkat_na&id=<?= $cat['id'] ?>" onclick="event.stopPropagation()"
                    style="display:inline-block;margin-left:4px;">
                    <img src="image/red.svg" alt="Редактировать" style="width:16px;">
                </a>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="top-actions container">
        <div class="search-wrapper">
            <img src="image/poisk.svg" alt="Поиск">
            <input type="text" name="search" placeholder="Поиск" value="<?= htmlspecialchars($searchQuery) ?>">
        </div>
        <div class="sort-select">
            <select name="sort" onchange="this.form.submit()">
                <option value="popular" <?= $sortBy == 'popular' ? 'selected' : '' ?>>По популярности</option>
                <option value="price_asc" <?= $sortBy == 'price_asc' ? 'selected' : '' ?>>По цене (сначала дешёвые)
                </option>
                <option value="price_desc" <?= $sortBy == 'price_desc' ? 'selected' : '' ?>>По цене (сначала дорогие)
                </option>
                <option value="calories_asc" <?= $sortBy == 'calories_asc' ? 'selected' : '' ?>>По калориям ↑</option>
                <option value="calories_desc" <?= $sortBy == 'calories_desc' ? 'selected' : '' ?>>По калориям ↓</option>
            </select>
        </div>
    </div>

    <div class="container catalog-layout">
        <aside class="filter-sidebar">
            <div class="price_filter">
                <h2>Фильтр</h2>
                <div style="display:flex;flex-direction:column;gap:15px;margin-bottom:20px;">
                    <p>Цена, ₽</p>
                    <div style="display:flex;gap:10px;align-items:center;">
                        <input type="number" name="price_min" placeholder="От"
                            value="<?= $priceMin !== '' ? htmlspecialchars($priceMin) : '' ?>" min="0"
                            style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;">
                        <span>—</span>
                        <input type="number" name="price_max" placeholder="До"
                            value="<?= $priceMax !== '' ? htmlspecialchars($priceMax) : '' ?>" min="0"
                            style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;">
                    </div>
                    <button type="submit"
                        style="background:#94D201;color:#fff;border:none;padding:8px 16px;border-radius:4px;cursor:pointer;">
                        Применить
                    </button>
                </div>

                <?php if ($priceMin !== '' || $priceMax !== '' || $filterCatId > 0 || $searchQuery !== ''): ?>
                <a href="?page=admin_kat_na" style="color:#666;font-size:13px;text-decoration:none;">✕
                    Сброситьфильтры</a>
                <?php endif; ?>
            </div>
            <div class="promocod">
                <img src="image/promocod.svg" alt="Промокод">
                <div class="promocod-text">
                    <h3>Бесплатная доставка</h3>
                    <p>Промокод БЫСТРО от 2 500 ₽</p>
                </div>
            </div>
        </aside>

        <div class="catalog-main">
            <div class="catalog-items">
                <div class="nowinki">
                    <?php if (empty($sets)): ?>
                    <p style="padding:20px;color:#666;">Наборы не найдены</p>
                    <?php else: ?>
                    <?php foreach ($sets as $set): ?>
                    <div class="new1">
                        <a href="index.php?page=admin_upd_na&id=<?= $set['id'] ?>"
                            style="background-color: transparent; padding: 0px;">
                            <img src="na/<?= htmlspecialchars($set['image'] ?: 'placeholder.png') ?>"
                                alt="<?= htmlspecialchars($set['name']) ?>">
                        </a>

                        <h5><?= mb_strimwidth(htmlspecialchars($set['name']), 0, 25, '...') ?>
                            <small style="color:#888;font-weight:normal;">(<?= (int)$set['dishes_count'] ?>
                                блюда)</small>
                        </h5>

                        <div class="kal">
                            <div class="k">
                                <p id="or"><?= (int)$set['kcal'] ?></p>
                                <p id="s">ккал</p>
                            </div>
                            <div class="k">
                                <p id="si"><?= (int)$set['protein'] ?></p>
                                <p id="s">белков</p>
                            </div>
                            <div class="k">
                                <p id="kr"><?= (int)$set['fat'] ?></p>
                                <p id="s">жиров</p>
                            </div>
                            <div class="k">
                                <p id="ze"><?= (int)$set['carbs'] ?></p>
                                <p id="s">углеводов</p>
                            </div>
                        </div>

                        <h6><?= number_format($set['price'], 0, '.', ' ') ?> ₽</h6>
                        <a href="index.php?page=admin_edit_na&id=<?= $set['id'] ?>">
                            Редактировать
                        </a>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</form>