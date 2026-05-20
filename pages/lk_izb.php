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
    <div class="izb1">
        <div class="gips">
            <img src="image/new2.png" alt="">
            <div class="gips_txt">
                <p>Рис с курицей и овощами </p>

                <div class="kal">
                    <div class="k">
                        <p id="or">450</p>
                        <p id="s">ккал</p>
                    </div>
                    <div class="k">
                        <p id="si">35</p>
                        <p id="s">белков</p>
                    </div>
                    <div class="k">
                        <p id="kr">15</p>
                        <p id="s">жиров</p>
                    </div>
                    <div class="k">
                        <p id="ze">40</p>
                        <p id="s">углеводов</p>
                    </div>
                </div>
                <h5>750 ₽</h5>
            </div>

        </div>
        <a href=""><img src="image/izb.svg" alt="Избранное"></a>
    </div>
    <div class="izb1">
        <div class="gips">
            <img src="image/new2.png" alt="">
            <div class="gips_txt">
                <p>Рис с курицей и овощами </p>

                <div class="kal">
                    <div class="k">
                        <p id="or">450</p>
                        <p id="s">ккал</p>
                    </div>
                    <div class="k">
                        <p id="si">35</p>
                        <p id="s">белков</p>
                    </div>
                    <div class="k">
                        <p id="kr">15</p>
                        <p id="s">жиров</p>
                    </div>
                    <div class="k">
                        <p id="ze">40</p>
                        <p id="s">углеводов</p>
                    </div>
                </div>
                <h5>750 ₽</h5>
            </div>

        </div>
        <a href=""><img src="image/izb2.svg" alt="Избранное"></a>
    </div>
    <div class="izb1">
        <div class="gips">
            <img src="image/new2.png" alt="">
            <div class="gips_txt">
                <p>Рис с курицей и овощами </p>

                <div class="kal">
                    <div class="k">
                        <p id="or">450</p>
                        <p id="s">ккал</p>
                    </div>
                    <div class="k">
                        <p id="si">35</p>
                        <p id="s">белков</p>
                    </div>
                    <div class="k">
                        <p id="kr">15</p>
                        <p id="s">жиров</p>
                    </div>
                    <div class="k">
                        <p id="ze">40</p>
                        <p id="s">углеводов</p>
                    </div>
                </div>
                <h5>750 ₽</h5>
            </div>

        </div>
        <a href=""><img src="image/izb2.svg" alt="Избранное"></a>
    </div>

</div>