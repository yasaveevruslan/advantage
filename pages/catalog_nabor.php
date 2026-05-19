<p id="hleb" class="container">Главная > Каталог наборов</p>

<div class="catalog container">
    <h3>Каталог наборов</h3>
    <div class="filter">
        <a href="">Все наборы</a>
        <a href="">Похудение</a>
        <a href="">Поддержание</a>
        <a href="">Набор массы</a>
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
                    <img src="image/nab1.png" alt="">
                    <h5>Набор (3 блюда)</h5>
                    <div class="kal">
                        <div class="k">
                            <p id="or">1310</p>
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
                    <h6>2 490 ₽</h6>
                    <a href="nabor.html">В корзину</a>
                </div>

                <div class="new1">
                    <img src="image/nab2.png" alt="">
                    <h5>Набор (4 блюда)</h5>
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
                    <h6>4 190 ₽</h6>
                    <a href="">В корзину</a>
                </div>

                <div class="new1">
                    <img src="image/nab3.png" alt="">
                    <h5>Набор (5 блюд)</h5>
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
                    <h6>4 990 ₽</h6>
                    <a href="">В корзину</a>
                </div>

                <div class="new1">
                    <img src="image/nab4.png" alt="">
                    <h5>Набор (6 блюд)</h5>
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
                    <h6>5 490 ₽</h6>
                    <a href="">В корзину</a>
                </div>
            </div>
        </div>
        <p class="null">Нет наборов, соответствующих выбранным фильтрам</p>
    </div>
</div>

<script>
// Находим все ползунки на странице
const sliders = document.querySelectorAll('input[type="range"]');

sliders.forEach(slider => {
    function updateGreenPart() {
        // Вычисляем процент заполнения
        const percent = (slider.value - slider.min) / (slider.max - slider.min) * 100;
        // Меняем фон: слева зелёный, справа серый
        slider.style.background =
            `linear-gradient(to right, #94D201 0%, #94D201 ${percent}%, #DBDBDB ${percent}%, #DBDBDB 100%)`;
    }

    // Запускаем при загрузке
    updateGreenPart();

    // Запускаем при движении ползунка
    slider.addEventListener('input', updateGreenPart);
});
</script>