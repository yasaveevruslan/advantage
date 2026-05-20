<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=auth');
    exit;
}

global $connect;
$userId = $_SESSION['user_id'];
$errors = [];

$selectedRating = $_POST['rating'] ?? '';
$commentText = $_POST['comment'] ?? '';

if (isset($_POST['submit_review'])) {
    $rating  = intval($selectedRating);
    $comment = trim($commentText);

    if ($rating < 1 || $rating > 5) {
        $errors['rating'] = 'Поставьте оценку от 1 до 5';
    }
    if ($comment === '') {
        $errors['comment'] = 'Напишите отзыв';
    } elseif (mb_strlen($comment) < 10) {
        $errors['comment'] = 'Отзыв слишком короткий (минимум 10 символов)';
    }

    if (empty($errors)) {
        $stmt = $connect->prepare("INSERT INTO reviews (user_id, rating, comment, is_moderated) VALUES (?, ?, ?, 0)");
        
        if ($stmt->execute([$userId, $rating, $comment])) {
            $_SESSION['success'] = 'Отзыв отправлен на модерацию!';
            $_SESSION['review_submitted'] = true;
            header('Location: ?page=otz_promocod');
            exit;
        } else {
            $errors['general'] = 'Ошибка при сохранении. Попробуйте позже.';
        }
    }
}
?>

<h3 class="container reg_title">Добавить отзыв</h3>
<div class="reg container">
    <form action="" class="registr" method="post">
        <label>Оценка *</label>
        <select name="rating" class="rating-select <?= !empty($errors['rating']) ? 'error' : '' ?>">
            <option value="">Выберите оценку</option>
            <option value="5" <?= ($selectedRating == 5) ? 'selected' : '' ?>>⭐⭐⭐⭐⭐ (5)</option>
            <option value="4" <?= ($selectedRating == 4) ? 'selected' : '' ?>>⭐⭐⭐⭐ (4)</option>
            <option value="3" <?= ($selectedRating == 3) ? 'selected' : '' ?>>⭐⭐⭐ (3)</option>
            <option value="2" <?= ($selectedRating == 2) ? 'selected' : '' ?>>⭐⭐ (2)</option>
            <option value="1" <?= ($selectedRating == 1) ? 'selected' : '' ?>>⭐ (1)</option>
        </select>
        <?php if(!empty($errors['rating'])): ?>
        <p class="error"><?= $errors['rating'] ?></p>
        <?php endif; ?>

        <label for="comment">Комментарий *</label>
        <textarea id="comment" name="comment" rows="5"
            placeholder="Введите отзыв"><?= htmlspecialchars($_POST['comment'] ?? '') ?></textarea>
        <?php if(!empty($errors['comment'])): ?>
        <p class="error"><?= $errors['comment'] ?></p>
        <?php endif; ?>

        <input type="submit" id="submit" value="Отправить" name="submit_review">
        <a href="?page=profile">Вернутся назад</a>
    </form>
</div>