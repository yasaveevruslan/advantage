<?php
global $connect;

$searchQuery = trim($_GET['search'] ?? '');
$sortBy = $_GET['sort'] ?? 'popular';
$priceMax = isset($_GET['price_max']) && $_GET['price_max'] !== '' ? floatval($_GET['price_max']) : '';
$priceMin = isset($_GET['price_min']) && $_GET['price_min'] !== '' ? floatval($_GET['price_min']) : '';

$sql = "SELECT s.*, 
               (SELECT SUM(d.kcal * sd.quantity) 
                FROM set_dishes sd 
                JOIN dishes d ON sd.dish_id = d.id 
                WHERE sd.set_id = s.id) as total_kcal,
               (SELECT SUM(d.protein * sd.quantity) 
                FROM set_dishes sd 
                JOIN dishes d ON sd.dish_id = d.id 
                WHERE sd.set_id = s.id) as total_protein,
               (SELECT SUM(d.fat * sd.quantity) 
                FROM set_dishes sd 
                JOIN dishes d ON sd.dish_id = d.id 
                WHERE sd.set_id = s.id) as total_fat,
               (SELECT SUM(d.carbs * sd.quantity) 
                FROM set_dishes sd 
                JOIN dishes d ON sd.dish_id = d.id 
                WHERE sd.set_id = s.id) as total_carbs,
               (SELECT COUNT(*) FROM set_dishes WHERE set_id = s.id) as dishes_count
        FROM sets s 
        WHERE s.is_available = 1";
$params = [];

if ($searchQuery !== '') {
    $sql .= " AND s.name LIKE ?";
    $params[] = "%$searchQuery%";
}

if ($priceMin !== '' && $priceMin >= 0) {
    $sql .= " AND s.price >= ?";
    $params[] = $priceMin;
}
if ($priceMax !== '' && $priceMax >= 0) {
    $sql .= " AND s.price <= ?";
    $params[] = $priceMax;
}

switch ($sortBy) {
    case 'price_asc': $sql .= " ORDER BY s.price ASC"; break;
    case 'price_desc': $sql .= " ORDER BY s.price DESC"; break;
    case 'calories_asc': $sql .= " ORDER BY total_kcal ASC"; break;
    case 'calories_desc': $sql .= " ORDER BY total_kcal DESC"; break;
    default: $sql .= " ORDER BY s.id DESC";
}

$stmt = $connect->prepare($sql);
$stmt->execute($params);
$sets = $stmt->fetchAll();
?>

<form action="index.php" method="GET">
    <input type="hidden" name="page" value="catalog_nabor">
    <input type="hidden" name="search" value="<?= htmlspecialchars($searchQuery) ?>">
    <input type="hidden" name="sort" value="<?= htmlspecialchars($sortBy) ?>">

    <p id="hleb" class="container">
        <a href="index.php">Главная</a> > Каталог наборов
    </p>

    <div class="catalog container">
        <h3>Каталог наборов</h3>
        <div class="filter">
            <a href="?page=catalog_nabor" class="active">Все наборы</a>
            <a href="?page=catalog_nabor&search=Похудение">Похудение</a>
            <a href="?page=catalog_nabor&search=Поддержание">Поддержание</a>
            <a href="?page=catalog_nabor&search=Набор">Набор массы</a>
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
                            value="<?= $priceMin !== '' ? htmlspecialchars($priceMin) : '' ?>" min="0" step="100"
                            style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;">
                        <span>—</span>
                        <input type="number" name="price_max" placeholder="До"
                            value="<?= $priceMax !== '' ? htmlspecialchars($priceMax) : '' ?>" min="0" step="100"
                            style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;">
                    </div>
                    <button type="submit"
                        style="background:#94D201;color:#fff;border:none;padding:8px 16px;border-radius:4px;cursor:pointer;">
                        Применить
                    </button>
                </div>

                <?php if ($priceMin !== '' || $priceMax !== '' || $searchQuery !== ''): ?>
                <a href="?page=catalog_nabor" style="color:#666;font-size:13px;text-decoration:none;">✕ Сбросить
                    фильтры</a>
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
                        <a href="index.php?page=nabor&id=<?= $set['id'] ?>">
                            <img src="na/<?= htmlspecialchars($set['image'] ?: 'placeholder.png') ?>"
                                alt="<?= htmlspecialchars($set['name']) ?>">
                        </a>

                        <h5><?= mb_strimwidth(htmlspecialchars($set['name']), 0, 25, '...') ?>
                            <small style="color:#888;font-weight:normal;">(<?= (int)$set['dishes_count'] ?>
                                блюда)</small>
                        </h5>

                        <div class="kal">
                            <div class="k">
                                <p><?= (int)($set['total_kcal'] ?? 0) ?></p>
                                <p id="s">ккал</p>
                            </div>
                            <div class="k">
                                <p><?= (int)($set['total_protein'] ?? 0) ?></p>
                                <p id="s">белков</p>
                            </div>
                            <div class="k">
                                <p><?= (int)($set['total_fat'] ?? 0) ?></p>
                                <p id="s">жиров</p>
                            </div>
                            <div class="k">
                                <p><?= (int)($set['total_carbs'] ?? 0) ?></p>
                                <p id="s">углеводов</p>
                            </div>
                        </div>

                        <h6><?= number_format($set['price'], 0, '.', ' ') ?> ₽</h6>

                        <a href="php/add_to_cart.php?id=<?= $set['id'] ?>&type=set"
                            style="display:block;background:#94D201;color:#fff;text-align:center;padding:8px;border-radius:5px;text-decoration:none;margin-top:8px;">
                            В корзину
                        </a>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</form>