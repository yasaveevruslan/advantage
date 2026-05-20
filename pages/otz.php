<?php
    global $connect;

    //(is_moderated = 1)
    $stmt = $connect->prepare("
        SELECT r.rating, r.comment, r.created_at, u.full_name 
        FROM reviews r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.is_moderated = 1 
        ORDER BY r.created_at DESC
    ");
    $stmt->execute();
    $reviews = $stmt->fetchAll();
?>
<!-- Отзывы -->
<p id="hleb" class="container">Главная > Отзывы</p>

<div class="otz container">
    <h3>Отзывы</h3>

    <?php if (empty($reviews)): ?>
    <p style="text-align:center; color:#666; padding:30px 0;">
        Пока нет отзывов. <a href="index.php?page=dob_otz" style="color:#94D201;">Оставьте первый!</a>
    </p>
    <?php else: ?>
    <div class="otziv">
        <?php foreach ($reviews as $rev): ?>
        <div class="ot1">
            <div class="name">
                <h6><?= htmlspecialchars($rev['full_name'] ?: 'Пользователь') ?></h6>
                <p><?= date('d.m.Y', strtotime($rev['created_at'])) ?></p>
                <div class="rating-stars"><?= str_repeat('⭐', max(0, min(5, $rev['rating']))) ?></div>
            </div>
            <div class="o_txt">
                <p><?= nl2br(htmlspecialchars($rev['comment'])) ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>