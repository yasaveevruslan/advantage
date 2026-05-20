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

<div class="ocen container">
    <p>Оцените наше качество и получите <br>
        промокод на 10% к следующему заказу</p>
    <a href="">Оставить отзыв</a>
</div>

<div class="ist_zak container">
    <p id="ist">История заказов</p>
    <div class="navigat">
        <a href="" id="vse">Все заказы</a>
        <a href="">Новый</a>
        <a href="">Готовится</a>
        <a href="">Передан курьеру</a>
        <a href="">Доставлен</a>
        <a href="">Отменён</a>
    </div>
    <div class="zak">
        <div class="nom_z">
            <div class="nom1">
                <p>Заказ #0001</p>
                <p>15.02.2026 в 09:00</p>
            </div>
            <p id="zel">Новый</p>
        </div>
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
        <div class="gips">
            <img src="image/new1.png" alt="">
            <div class="gips_txt">
                <p>Паста с морепродуктами</p>

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
                <h5>720 ₽</h5>
            </div>

        </div>
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

        <div class="inf_zak">
            <div class="iz1">
                <div class="sp_o">
                    <h5>Способ оплаты</h5>
                    <p>Картой</p>
                </div>
                <div class="sp_o">
                    <h5>Доставка по адресу</h5>
                    <p>ул. Пушкина, д. 10, кв. 25</p>
                </div>
                <div class="sp_o">
                    <h5>Дата и время доставки</h5>
                    <p>18.03.2026 в 10:00</p>
                </div>
            </div>
            <h6>1 780 ₽</h6>

        </div>
    </div>
    <div class="zak">
        <div class="nom_z">
            <div class="nom1">
                <p>Заказ #0002</p>
                <p>15.02.2026 в 09:00</p>
            </div>
            <p id="sin">Готовится</p>
        </div>

        <div class="gips">
            <img src="image/new1.png" alt="">
            <div class="gips_txt">
                <p>Паста с морепродуктами</p>

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
                <h5>720 ₽</h5>
            </div>

        </div>
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

        <div class="inf_zak">
            <div class="iz1">
                <div class="sp_o">
                    <h5>Способ оплаты</h5>
                    <p>Наличными</p>
                </div>
                <div class="sp_o">
                    <h5>Доставка по адресу</h5>
                    <p>ул. Пушкина, д. 10, кв. 25</p>
                </div>
                <div class="sp_o">
                    <h5>Дата и время доставки</h5>
                    <p>17.03.2026 в 10:00</p>
                </div>
            </div>
            <h6>1 780 ₽</h6>

        </div>
    </div>
    <div class="zak">
        <div class="nom_z">
            <div class="nom1">
                <p>Заказ #0003</p>
                <p>15.02.2026 в 09:00</p>
            </div>
            <p id="ora">Передан курьеру</p>
        </div>

        <div class="gips">
            <img src="image/new1.png" alt="">
            <div class="gips_txt">
                <p>Паста с морепродуктами</p>

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
                <h5>720 ₽</h5>
            </div>

        </div>
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

        <div class="inf_zak">
            <div class="iz1">
                <div class="sp_o">
                    <h5>Способ оплаты</h5>
                    <p>Через СПБ</p>
                </div>
                <div class="sp_o">
                    <h5>Доставка по адресу</h5>
                    <p>ул. Пушкина, д. 10, кв. 25</p>
                </div>
                <div class="sp_o">
                    <h5>Дата и время доставки</h5>
                    <p>18.03.2026 в 10:00</p>
                </div>
            </div>
            <h6>1 780 ₽</h6>

        </div>
    </div>
    <div class="zak">
        <div class="nom_z">
            <div class="nom1">
                <p>Заказ #0004</p>
                <p>15.02.2026 в 09:00</p>
            </div>
            <p id="hz">Доставлен</p>
        </div>

        <div class="gips">
            <img src="image/new1.png" alt="">
            <div class="gips_txt">
                <p>Паста с морепродуктами</p>

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
                <h5>720 ₽</h5>
            </div>

        </div>
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

        <div class="inf_zak">
            <div class="iz1">
                <div class="sp_o">
                    <h5>Способ оплаты</h5>
                    <p>Картой</p>
                </div>
                <div class="sp_o">
                    <h5>Доставка по адресу</h5>
                    <p>ул. Пушкина, д. 10, кв. 25</p>
                </div>
                <div class="sp_o">
                    <h5>Дата и время доставки</h5>
                    <p>18.03.2026 в 10:00</p>
                </div>
            </div>
            <h6>1 780 ₽</h6>
        </div>
        <a href="" class="rep">
            <img src="image/repeat.svg" alt="Повторить">
            <p>Повторить заказ</p>
        </a>
    </div>
    <div class="zak">
        <div class="nom_z">
            <div class="nom1">
                <p>Заказ #0005</p>
                <p>15.02.2026 в 09:00</p>
            </div>
            <p id="kiz">Отменён</p>
        </div>
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

        <div class="inf_zak">
            <div class="iz1">
                <div class="sp_o">
                    <h5>Способ оплаты</h5>
                    <p>Картой</p>
                </div>
                <div class="sp_o">
                    <h5>Доставка по адресу</h5>
                    <p>ул. Пушкина, д. 10, кв. 25</p>
                </div>
                <div class="sp_o">
                    <h5>Дата и время доставки</h5>
                    <p>18.03.2026 в 10:00</p>
                </div>
            </div>
            <h6>1 780 ₽</h6>
        </div>
        <a href="" class="rep">
            <img src="image/repeat.svg" alt="Повторить">
            <p>Повторить заказ</p>
        </a>
    </div>
    <p class="null2">У вас еще не было заказов, совершите ваш первый заказ</p>
</div>