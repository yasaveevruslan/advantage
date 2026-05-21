<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: index.php?page=catalog_blud');
    exit;
}

global $connect;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_dish'])) {
        $dishId = intval($_POST['dish_id']);
        $stmt = $connect->prepare("SELECT image FROM dishes WHERE id = ?");
        $stmt->execute([$dishId]);
        $dish = $stmt->fetch();
        if ($dish && $dish['image'] && file_exists(__DIR__ . '/../bl/' . $dish['image'])) {
            unlink(__DIR__ . '/../bl/' . $dish['image']);
        }
        $connect->prepare("DELETE FROM dishes WHERE id = ?")->execute([$dishId]);
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
    
    if (isset($_POST['toggle_availability'])) {
        $dishId = intval($_POST['dish_id']);
        $connect->prepare("UPDATE dishes SET is_available = NOT is_available WHERE id = ?")
                ->execute([$dishId]);
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
    
    if (isset($_POST['edit_category'])) {
        $catId = intval($_POST['category_id']);
        $newName = trim($_POST['category_name'] ?? '');
        if ($newName !== '') {
            $connect->prepare("UPDATE categories SET name = ? WHERE id = ?")
                    ->execute([$newName, $catId]);
        }
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
}

$filterCatId = intval($_GET['filter'] ?? 0);
$searchQuery = trim($_GET['search'] ?? '');
$sortBy = $_GET['sort'] ?? 'popular';
$priceMax = isset($_GET['price_max']) && $_GET['price_max'] !== '' ? floatval($_GET['price_max']) : '';
$priceMin = isset($_GET['price_min']) && $_GET['price_min'] !== '' ? floatval($_GET['price_min']) : '';

$categories = $connect->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();

$sql = "SELECT d.*, c.name as category_name 
        FROM dishes d 
        LEFT JOIN categories c ON d.category_id = c.id 
        WHERE 1=1";
$params = [];

if ($filterCatId > 0) {
    $sql .= " AND d.category_id = ?";
    $params[] = $filterCatId;
}
if ($searchQuery !== '') {
    $sql .= " AND d.name LIKE ?";
    $params[] = "%$searchQuery%";
}
if ($priceMin !== '' && $priceMin >= 0) {
    $sql .= " AND d.price >= ?";
    $params[] = $priceMin;
}
if ($priceMax !== '' && $priceMax >= 0) {
    $sql .= " AND d.price <= ?";
    $params[] = $priceMax;
}

switch ($sortBy) {
    case 'price_asc': $sql .= " ORDER BY d.price ASC"; break;
    case 'price_desc': $sql .= " ORDER BY d.price DESC"; break;
    case 'calories_asc': $sql .= " ORDER BY d.kcal ASC"; break;
    case 'calories_desc': $sql .= " ORDER BY d.kcal DESC"; break;
    default: $sql .= " ORDER BY d.id DESC";
}

$stmt = $connect->prepare($sql);
$stmt->execute($params);
$dishes = $stmt->fetchAll();
?>

<form action="?page=admin_kat_bl" method="GET">
    <input type="hidden" name="page" value="admin_kat_bl">
    <input type="hidden" name="filter" value="<?= $filterCatId ?>">
    <input type="hidden" name="search" value="<?= htmlspecialchars($searchQuery) ?>">
    <input type="hidden" name="sort" value="<?= htmlspecialchars($sortBy) ?>">
    <p id="hleb" class="container">
        <a href="index.php">Главная</a> > <a href="index.php?page=admin_kat_bl">Каталог блюд (админ)</a>
    </p>

    <div class="catalog container">
        <h3>Каталог блюд</h3>
        <div class="adm_dob">
            <a href="?page=admin_add_bl">+ Добавить блюдо</a>
            <a href="?page=admin_addkat_bl">+ Добавить категорию блюда</a>
        </div>

        <div class="filter">
            <a href="?page=admin_kat_bl" class="<?= $filterCatId == 0 ? 'active' : '' ?>">Все блюда</a>
            <?php foreach ($categories as $cat): ?>
            <div style="display:flex; flex-direction:row;">
                <a href="?page=admin_kat_bl&filter=<?= $cat['id'] ?>"
                    class="<?= $filterCatId == $cat['id'] ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                    <a href="?page=admin_editkat_bl&id=<?= $cat['id'] ?>"><img src="image/red.svg"
                            alt="Редактировать"></a>
                </a>
            </div>
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
                <a href="?page=admin_kat_bl" style="color:#666;font-size:13px;text-decoration:none;">✕ Сбросить
                    фильтры</a>
                <?php endif; ?>
            </div>
        </aside>

        <div class="catalog-main">
            <div class="catalog-items">
                <div class="nowinki">
                    <?php if (empty($dishes)): ?>
                    <p style="padding:20px;color:#666;">Блюда не найдены</p>
                    <?php else: ?>
                    <?php foreach ($dishes as $dish): ?>
                    <div class="new1">
                        <a href="index.php?page=admin_upd_bl&id=<?= $dish['id'] ?>"
                            style="background-color: transparent; padding: 0px;">
                            <img src="bl/<?= htmlspecialchars($dish['image'] ?: 'placeholder.png') ?>"
                                alt="<?= htmlspecialchars($dish['name']) ?>">
                        </a>

                        <h5><?= mb_strimwidth(htmlspecialchars($dish['name']), 0, 25, '...') ?></h5>
                        <div class="kal">
                            <div class="k">
                                <p id="or"><?= (int)$dish['kcal'] ?></p>
                                <p id="s">ккал</p>
                            </div>
                            <div class="k">
                                <p id="si"><?= (int)$dish['protein'] ?></p>
                                <p id="s">белков</p>
                            </div>
                            <div class="k">
                                <p id="kr"><?= (int)$dish['fat'] ?></p>
                                <p id="s">жиров</p>
                            </div>
                            <div class="k">
                                <p id="ze"><?= (int)$dish['carbs'] ?></p>
                                <p id="s">углеводов</p>
                            </div>
                        </div>
                        <h6><?= number_format($dish['price'], 0, '.', ' ') ?> ₽</h6>
                        <a href="index.php?page=admin_edit_bl&id=<?= $dish['id'] ?>">
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