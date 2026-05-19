<p id="hleb" class="container">Главная > Каталог блюд</p>

<div class="catalog container">
    <h3>Каталог блюд</h3>
    <div class="adm_dob">
        <a href="admin_add_bl.html">+ Добавить блюдо</a>
        <a href="admin_addkat_bl.html">+ Добавить категорию блюда</a>
    </div>
    <div class="filter">
        <a href="">Все блюда</a>
        <a href="">Сбалансированное <img src="image/red.svg" alt="Редактировать"></a>
        <a href="">Фитнес <img src="image/red.svg" alt="Редактировать"></a>
        <a href="">Кето <img src="image/red.svg" alt="Редактировать"></a>
        <a href="">Веган <img src="image/red.svg" alt="Редактировать"></a>
        <a href="">Без глютена <img src="image/red.svg" alt="Редактировать"></a>
        <a href="">Без лактозы <img src="image/red.svg" alt="Редактировать"></a>
        <a href="">Детокс <img src="image/red.svg" alt="Редактировать"></a>
        <a href="">Полезные десерты и снеки <img src="image/red.svg" alt="Редактировать"></a>
    </div>
</div>

<div class="top-actions container">
    <div class="search-wrapper">
        <img src="image/poisk.svg" alt="Поиск">
        <input type="text" id="searchInput" placeholder="Поиск">
    </div>
    <div class="sort-select">
        <select id="sortSelect">
            <option value="popular">По популярности</option>
            <option value="price_asc">По цене (сначала дешёвые)</option>
            <option value="price_desc">По цене (сначала дорогие)</option>
            <option value="calories_asc">По калориям (возрастание)</option>
            <option value="calories_desc">По калориям (убывание)</option>
        </select>
    </div>
</div>

<div class="container catalog-layout">
    <!-- ЛЕВАЯ ЧАСТЬ: ФИЛЬТР (все ползунки, чекбоксы, переключатели) — остаётся без изменений -->
    <aside class="filter-sidebar">
        <div class="price_filter">
            <h2>Фильтр</h2>
            <div style="display: flex; flex-direction: column; gap: 10px">
                <div style="display: flex; flex-direction: column; gap: 10px">
                    <p>Ккал: 0 - 1000 ккал</p>
                    <div class="slider">
                        <input type="range" class="range-min" min="0" max="10000" value="2500" step="100">
                    </div>
                </div>
                <div style="display: flex; flex-direction: column">
                    <p>Состав</p>
                    <div class="sost_filter">
                        <label>
                            <input type="checkbox" value="category1" data-filter="value1"> Без сахара
                        </label>
                        <label>
                            <input type="checkbox" value="category2" data-filter="value2"> Без глютена
                        </label>
                        <label>
                            <input type="checkbox" value="category3" data-filter="value2"> Без лактозы
                        </label>
                        <label>
                            <input type="checkbox" value="category4" data-filter="value2"> Веган
                        </label>
                        <label>
                            <input type="checkbox" value="category5" data-filter="value2"> Вегетарианское
                        </label>
                    </div>
                </div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px">
                <div style="display: flex; flex-direction: column">
                    <p>Цена: 0 - 10 000 ₽</p>
                    <div class="slider">
                        <input type="range" class="range-min" min="0" max="10000" value="2500" step="100">
                    </div>
                </div>
                <div class="new_f">
                    <p>Только новинки</p>
                    <label class="toggle-switch">
                        <input type="checkbox" id="newOnly">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="promocod">
            <img src="image/promocod.svg" alt="Промокод">
            <div class="promocod-text">
                <h3>Бесплатная доставка</h3>
                <p>Промокод БЫСТРО минимальная сумма 2 500 ₽</p>
            </div>
        </div>
    </aside>


    <!-- ПРАВАЯ ЧАСТЬ: СВЕРХУ поиск + сортировка на одной линии, снизу каталог -->
    <div class="catalog-main">
        <!-- Верхняя линия: поиск и выпадающий список (по умолчанию "По популярности" и др.) -->


        <!-- КАТАЛОГ БЛЮД (оригинальные карточки, ничего не меняли) -->
        <div class="catalog-items">
            <div class="nowinki">
                <div class="new1">
                    <img src="image/new1.png" alt="">
                    <h5>Куриный шницель с мака...</h5>
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
                    <h6>890 ₽</h6>
                    <a href="admin_upd_bl.html">Редактировать</a>
                </div>

                <div class="new1">
                    <img src="image/new2.png" alt="">
                    <h5>Паста Карбонара</h5>
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
                    <h6>890 ₽</h6>
                    <a href="">Редактировать</a>
                </div>

                <div class="new1">
                    <img src="image/new3.png" alt="">
                    <h5>Куриный шницель с мака...</h5>
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
                    <h6>890 ₽</h6>
                    <a href="">Редактировать</a>
                </div>

                <div class="new1">
                    <img src="image/new4.png" alt="">
                    <h5>Куриный шницель с мака...</h5>
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
                    <h6>890 ₽</h6>
                    <a href="">Редактировать</a>
                </div>
            </div>
        </div>
    </div>
</div>