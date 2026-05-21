<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: index.php?page=auth');
    exit;
}

global $connect;
$setDishId = intval($_GET['id'] ?? 0);
$errors = [];

$stmt = $connect->prepare("SELECT * FROM set_dishes WHERE id = ?");
$stmt->execute([$setDishId]);
$currentSet = $stmt->fetch();

if (!$currentSet) {
    echo '<div class="container">Набор не найден</div>';
    exit;
}

$stmtComp = $connect->prepare("
    SELECT sc.dish_id, d.name, d.price, d.image, d.kcal, d.protein, d.fat, d.carbs, sc.quantity
    FROM set_composition sc
    JOIN dishes d ON sc.dish_id = d.id
    WHERE sc.set_dish_id = ?
");
$stmtComp->execute([$setDishId]);
$currentComposition = $stmtComp->fetchAll(PDO::FETCH_ASSOC);

$setCategories = $connect->query("SELECT id, name FROM sets ORDER BY name")->fetchAll();
$categories    = $connect->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
$dishesForJs   = $connect->query("
    SELECT d.id, d.name, d.price, d.image, d.kcal, d.protein, d.fat, d.carbs, c.name as category_name
    FROM dishes d LEFT JOIN categories c ON d.category_id = c.id
    WHERE d.is_available = 1 ORDER BY d.name
")->fetchAll(PDO::FETCH_ASSOC);

$dishesJson = json_encode($dishesForJs, JSON_UNESCAPED_UNICODE);
$jsComposition = json_encode(array_map(fn($row) => [
    'id' => $row['dish_id'],
    'name' => $row['name'],
    'price' => (float)$row['price'],
    'image' => $row['image'] ?: '',
    'kcal' => (int)$row['kcal'],
    'protein' => (int)$row['protein'],
    'fat' => (int)$row['fat'],
    'carbs' => (int)$row['carbs'],
    'quantity' => (int)$row['quantity']
], $currentComposition), JSON_UNESCAPED_UNICODE);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_set'])) {
    $set_idи= intval($_POST['set_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $description  = trim($_POST['description'] ?? '');
    $is_available = isset($_POST['is_available']) ? 1 : 0;

    $totalPrice = $totalKcal = $totalProtein = $totalFat = $totalCarbs = 0;
    if (!empty($_POST['dish_ids'])) {
        $stmtDish = $connect->prepare("SELECT price, kcal, protein, fat, carbs FROM dishes WHERE id = ?");
        foreach ($_POST['dish_ids'] as $i => $dishId) {
            $qty = max(1, intval($_POST['quantities'][$i] ?? 1));
            $stmtDish->execute([$dishId]);
            $d = $stmtDish->fetch();
            if ($d) {
                $totalPrice += $d['price'] * $qty;
                $totalKcal += $d['kcal'] * $qty;
                $totalProtein += $d['protein'] * $qty;
                $totalFat += $d['fat'] * $qty;
                $totalCarbs += $d['carbs'] * $qty;
            }
        }
    }

    if ($set_id <= 0) $errors['set_id'] = 'Выберите категорию набора';
    if ($name === '') $errors['name'] = 'Введите название набора';
    if (empty($_POST['dish_ids'])) $errors['general'] = 'Добавьте хотя бы одно блюдо в состав';

    $imagePath = $currentSet['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
        if (in_array($_FILES['image']['type'], $allowed) && $_FILES['image']['size'] <= 5 * 1024 * 1024) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = 'set_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $uploadDir = __DIR__ . '/../na/';
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName)) {
                if ($currentSet['image'] && file_exists($uploadDir . $currentSet['image'])) {
                    unlink($uploadDir . $currentSet['image']);
                }
                $imagePath = $imageName;
            }
        }
    }

    if (empty($errors)) {
        try {
            $connect->beginTransaction();

            $stmt = $connect->prepare("
                UPDATE set_dishes 
                SET set_id = ?, name = ?, image = ?, description = ?, price = ?, kcal = ?, protein = ?, fat = ?, carbs = ?, is_available = ?
                WHERE id = ?
            ");
            $stmt->execute([$set_id, $name, $imagePath, $description, $totalPrice, $totalKcal, $totalProtein, $totalFat, $totalCarbs, $is_available, $setDishId]);

            $connect->prepare("DELETE FROM set_composition WHERE set_dish_id = ?")->execute([$setDishId]);
            if (!empty($_POST['dish_ids'])) {
                $compStmt = $connect->prepare("INSERT INTO set_composition (set_dish_id, dish_id, quantity) VALUES (?, ?, ?)");
                foreach ($_POST['dish_ids'] as $i => $dishId) {
                    $qty = max(1, intval($_POST['quantities'][$i] ?? 1));
                    $compStmt->execute([$setDishId, intval($dishId), $qty]);
                }
            }

            $connect->commit();
            header('Location: index.php?page=admin_kat_na');
            exit;

        } catch (PDOException $e) {
            $connect->rollBack();
            $errors['general'] = 'Ошибка сохранения: ' . $e->getMessage();
        }
    }
}

$val = fn($f) => $_POST[$f] ?? ($currentSet[$f] ?? '');
?>

<div class="dobavit_bludo container">
    <h3>Редактировать набор</h3>
    <div class="dob_bl">
        <form action="" class="bld" method="POST" enctype="multipart/form-data">

            <label for="set_id">Категория набора *</label>
            <select name="set_id" id="set_id">
                <option value="">— Выберите категорию —</option>
                <?php foreach ($setCategories as $sc): ?>
                <option value="<?= $sc['id'] ?>" <?= ($val('set_id') == $sc['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($sc['name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <?php if(!empty($errors['set_id'])): ?><p class="error"><?= $errors['set_id'] ?></p><?php endif; ?>

            <label for="name">Название набора *</label>
            <input type="text" id="name" name="name" placeholder="Например: 'Кето-старт'"
                value="<?= htmlspecialchars($val('name')) ?>">
            <?php if(!empty($errors['name'])): ?><p class="error"><?= $errors['name'] ?></p><?php endif; ?>

            <label for="categoryFilter">Фильтр блюд для подбора</label>
            <select id="categoryFilter" onchange="filterDishes()">
                <option value="">Все категории</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat['name']) ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="description">Описание набора</label>
            <textarea id="description" name="description"
                placeholder="Что входит в программу?"><?= htmlspecialchars($val('description')) ?></textarea>

            <label>Добавить блюдо в состав *</label>
            <div class="o8" onclick="document.getElementById('dishSelector').style.display='block'">
                <p>🞢</p>
                <p>Добавить блюдо</p>
            </div>

            <div id="dishSelector"
                style="display:none; margin-bottom:15px; background:#f5f5f5; padding:10px; border-radius:6px;">
                <select id="newDishSelect" style="width:100%; padding:8px; margin-bottom:8px;">
                    <option value="">— Выберите блюдо —</option>
                    <?php foreach ($dishesForJs as $d): ?>
                    <option value="<?= $d['id'] ?>" class="dish-option"
                        data-category="<?= htmlspecialchars($d['category_name'] ?? '') ?>"
                        data-name="<?= htmlspecialchars($d['name']) ?>" data-price="<?= $d['price'] ?>"
                        data-image="<?= htmlspecialchars($d['image'] ?? '') ?>" data-kcal="<?= $d['kcal'] ?>"
                        data-protein="<?= $d['protein'] ?>" data-fat="<?= $d['fat'] ?>" data-carbs="<?= $d['carbs'] ?>">
                        <?= htmlspecialchars($d['name']) ?> (<?= number_format($d['price'], 0, '.', ' ') ?> ₽) —
                        <?= htmlspecialchars($d['category_name'] ?? '—') ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <button type="button" onclick="addDish()"
                    style="background:#94D201;color:#fff;border:none;padding:12px 12px;border-radius:4px;cursor:pointer;">Добавить</button>
            </div>

            <div id="dishesContainer"></div>
            <div id="hiddenInputs"></div>

            <?php if(!empty($errors['general'])): ?>
            <p class="error"><?= $errors['general'] ?></p>
            <?php endif; ?>

            <label for="image">Обложка набора</label>
            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg" style="display:none;">
            <label for="image" class="zagr_img">
                <div class="zagf">
                    <img src="image/dow.svg" alt="">
                    <h5>Загрузите файл в эту область</h5>
                    <p>Формат изображения только jpg, jpeg, png</p>
                </div>
            </label>

            <div class="out_of">
                <p>В наличии</p>
                <label class="toggle-switch">
                    <input type="checkbox" id="is_available" name="is_available" value="1"
                        <?= (isset($_POST['update_set']) ? (isset($_POST['is_available']) ? 'checked' : '') : ($currentSet['is_available'] ? 'checked' : '')) ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <button type="submit" class="dob_bl_a" name="update_set">Сохранить изменения</button>
        </form>

        <div class="kb-zagf">
            <div class="kb1">
                <h4>Итого КБЖУ</h4>
                <div class="kor_kal">
                    <p>Калории</p>
                    <h4 id="totalKcal">0</h4>
                </div>
                <div class="kor_kal2">
                    <div class="kor_b">
                        <h4 id="totalProtein">0</h4>
                        <p>белков</p>
                    </div>
                    <div class="kor_b">
                        <h4 id="totalFat">0</h4>
                        <p>жиров</p>
                    </div>
                    <div class="kor_b">
                        <h4 id="totalCarbs">0</h4>
                        <p>углеводов</p>
                    </div>
                </div>
                <div style="margin-top:15px; padding-top:10px; border-top:1px solid #eee;">
                    <p>Итоговая цена:</p>
                    <h4 id="totalPrice">0 ₽</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const dishesDB = <?= $dishesJson ?>;
let selectedDishes = <?= $jsComposition ?>;

document.addEventListener('DOMContentLoaded', () => {
    renderDishes();
    updateTotals();
});

function filterDishes() {
    const filter = document.getElementById('categoryFilter').value.toLowerCase();
    const options = document.querySelectorAll('#newDishSelect .dish-option');
    options.forEach(opt => {
        const cat = (opt.dataset.category || '').toLowerCase();
        opt.style.display = (filter === '' || cat === filter) ? '' : 'none';
    });
    document.getElementById('newDishSelect').value = '';
}

function addDish() {
    const select = document.getElementById('newDishSelect');
    const option = select.options[select.selectedIndex];
    if (!option.value) return alert('Выберите блюдо');

    const dish = {
        id: option.value,
        name: option.dataset.name,
        price: parseFloat(option.dataset.price),
        image: option.dataset.image || '',
        kcal: parseInt(option.dataset.kcal) || 0,
        protein: parseInt(option.dataset.protein) || 0,
        fat: parseInt(option.dataset.fat) || 0,
        carbs: parseInt(option.dataset.carbs) || 0,
        quantity: 1
    };

    if (selectedDishes.find(d => d.id == dish.id)) return alert('Блюдо уже в наборе');
    selectedDishes.push(dish);
    renderDishes();
    updateTotals();
    select.value = '';
    document.getElementById('dishSelector').style.display = 'none';
}

function removeDish(id) {
    selectedDishes = selectedDishes.filter(d => d.id != id);
    renderDishes();
    updateTotals();
}

function changeQuantity(id, val) {
    const qty = Math.max(1, parseInt(val) || 1);
    const dish = selectedDishes.find(d => d.id == id);
    if (dish) {
        dish.quantity = qty;
        updateTotals();
        updateHiddenInputs();
    }
}

function renderDishes() {
    const container = document.getElementById('dishesContainer');
    container.innerHTML = '';

    selectedDishes.forEach(dish => {
        const imgSrc = dish.image ? `bl/${dish.image}` : 'bl/placeholder.png';
        container.innerHTML += `
            <div class="o6">
                <div class="gips">
                    <img src="${imgSrc}" alt="${dish.name}">
                    <div class="gips_txt">
                        <p>${dish.name}</p>
                        <div class="kal">
                            <div class="k"><p id='or'>${dish.kcal * dish.quantity}</p><p id="s">ккал</p></div>
                            <div class="k"><p id="si">${dish.protein * dish.quantity}</p><p id="s">белков</p></div>
                            <div class="k"><p id="kr">${dish.fat * dish.quantity}</p><p id="s">жиров</p></div>
                            <div class="k"><p id="ze">${dish.carbs * dish.quantity}</p><p id="s">углеводов</p></div>
                        </div>
                        <h5>${(dish.price * dish.quantity).toFixed(0)} ₽</h5>
                    </div>
                </div>
                <div class="rep_cross">
                    <div style='display:flex; flex-direction:column; gap:6px;'>
                        <p>Количество</p>
                        <input type="number" value="${dish.quantity}" min="1" 
                            onchange="changeQuantity(${dish.id}, this.value)" 
                            style="width:80px;padding:4px;margin-right:8px;text-align:center;">
                    </div>
                    <a href="javascript:void(0)" onclick="removeDish(${dish.id})">⨉</a>
                </div>
            </div>
        `;
    });
    updateHiddenInputs();
}

function updateHiddenInputs() {
    const hiddenDiv = document.getElementById('hiddenInputs');
    hiddenDiv.innerHTML = '';
    selectedDishes.forEach(dish => {
        hiddenDiv.innerHTML += `<input type="hidden" name="dish_ids[]" value="${dish.id}">`;
        hiddenDiv.innerHTML += `<input type="hidden" name="quantities[]" value="${dish.quantity}">`;
    });
}

function updateTotals() {
    let kcal = 0,
        protein = 0,
        fat = 0,
        carbs = 0,
        price = 0;
    selectedDishes.forEach(d => {
        kcal += d.kcal * d.quantity;
        protein += d.protein * d.quantity;
        fat += d.fat * d.quantity;
        carbs += d.carbs * d.quantity;
        price += d.price * d.quantity;
    });
    document.getElementById('totalKcal').textContent = kcal;
    document.getElementById('totalProtein').textContent = protein;
    document.getElementById('totalFat').textContent = fat;
    document.getElementById('totalCarbs').textContent = carbs;
    document.getElementById('totalPrice').textContent = price.toFixed(0) + ' ₽';
}
</script>