<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: index.php?page=auth');
    exit;
}

global $connect;
$errors = [];

if (isset($_POST['add_set'])) {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $is_available = isset($_POST['is_available']) ? 1 : 0;

    if ($name === '') $errors['name'] = 'Введите название набора';

    $totalPrice = 0;
    $totalKcal = $totalProtein = $totalFat = $totalCarbs = 0;

    if (empty($errors) && !empty($_POST['dish_ids'])) {
        foreach ($_POST['dish_ids'] as $i => $dishId) {
            $qty = max(1, intval($_POST['quantities'][$i] ?? 1));
            $dish = $connect->prepare("SELECT price, kcal, protein, fat, carbs FROM dishes WHERE id = ?");
            $dish->execute([$dishId]);
            $d = $dish->fetch();
            if ($d) {
                $totalPrice += $d['price'] * $qty;
                $totalKcal += $d['kcal'] * $qty;
                $totalProtein += $d['protein'] * $qty;
                $totalFat += $d['fat'] * $qty;
                $totalCarbs += $d['carbs'] * $qty;
            }
        }

        try {
            $connect->beginTransaction();

            $stmt = $connect->prepare("
                INSERT INTO sets (name, description, price, is_available) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$name, $description, $totalPrice, $is_available]);
            $setId = $connect->lastInsertId();

            $linkStmt = $connect->prepare("
                INSERT INTO set_dishes (set_id, dish_id, quantity) 
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE quantity = VALUES(quantity)
            ");
            foreach ($_POST['dish_ids'] as $i => $dishId) {
                $qty = max(1, intval($_POST['quantities'][$i] ?? 1));
                $linkStmt->execute([$setId, intval($dishId), $qty]);
            }

            $connect->commit();
            header('Location: index.php?page=admin_kat_na');
            exit;

        } catch (PDOException $e) {
            $connect->rollBack();
            $errors['general'] = 'Ошибка: ' . $e->getMessage();
        }
    } elseif (empty($_POST['dish_ids'])) {
        $errors['general'] = 'Добавьте хотя бы одно блюдо в набор';
    }
}

$dishesForJs = $connect->query("
    SELECT d.id, d.name, d.price, d.image, d.kcal, d.protein, d.fat, d.carbs, c.name as category_name
    FROM dishes d
    LEFT JOIN categories c ON d.category_id = c.id
    WHERE d.is_available = 1 
    ORDER BY d.name
")->fetchAll(PDO::FETCH_ASSOC);
$dishesJson = json_encode($dishesForJs, JSON_UNESCAPED_UNICODE);

$categories = $connect->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
?>

<div class="dobavit_bludo container">
    <h3>Добавить набор</h3>
    <div class="dob_bl">
        <form action="" class="bld" method="POST">

            <label for="name">Название набора *</label>
            <input type="text" id="name" name="name" placeholder="Например: 'Кето-старт'"
                value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            <?php if(!empty($errors['name'])): ?><p class="error"><?= $errors['name'] ?></p><?php endif; ?>

            <label for="categoryFilter">Фильтр блюд</label>
            <select id="categoryFilter" onchange="filterDishes()">
                <option value="">Все категории</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat['name']) ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="description">Описание набора</label>
            <textarea id="description" name="description"
                placeholder="Что входит в программу?"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>

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
                    style="background:#94D201;color:#fff;border:none;padding:6px 12px;border-radius:4px;cursor:pointer;">Добавить</button>
            </div>

            <div id="dishesContainer"></div>
            <div id="hiddenInputs"></div>

            <?php if(!empty($errors['general'])): ?>
            <p class="error"><?= $errors['general'] ?></p>
            <?php endif; ?>

            <button type="submit" class="dob_bl_a" name="add_set">Создать набор</button>
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
                    <small style="color:#888;">(считается автоматически из блюд)</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const dishesDB = <?= $dishesJson ?>;
let selectedDishes = [];

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
        const dishImage = dish.image;
        container.innerHTML += `
            <div class="o6">
                <div class="gips">
                    <img src="/../bl/${dishImage}" alt="${dish.name}"">
                    <div class="gips_txt">
                        <p>${dish.name}</p>
                        <div class="kal">
                            <div class="k"><p>${dish.kcal * dish.quantity}</p><p class="s">ккал</p></div>
                            <div class="k"><p>${dish.protein * dish.quantity}</p><p class="s">белков</p></div>
                            <div class="k"><p>${dish.fat * dish.quantity}</p><p class="s">жиров</p></div>
                            <div class="k"><p>${dish.carbs * dish.quantity}</p><p class="s">углеводов</p></div>
                        </div>
                        <h5>${(dish.price * dish.quantity).toFixed(0)} ₽</h5>
                    </div>
                </div>
                <div class="rep_cross">
                    <input type="number" value="${dish.quantity}" min="1" 
                           onchange="changeQuantity(${dish.id}, this.value)" 
                           style="width:45px;padding:4px;margin-right:8px;text-align:center;">
                    <a href="javascript:void(0)" class="cross" onclick="removeDish(${dish.id})">⨉</a>
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