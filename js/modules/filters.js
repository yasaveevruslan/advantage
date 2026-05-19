// js/filters.js
document.addEventListener('DOMContentLoaded', () => {

    const rangeSliders = document.querySelectorAll('input[type="range"].range-min');
    
    function updateSliderBackground(slider) {
        const percent = ((slider.value - slider.min) / (slider.max - slider.min)) * 100;
        slider.style.background = `linear-gradient(to right, #94D201 0%, #94D201 ${percent}%, #DBDBDB ${percent}%, #DBDBDB 100%)`;
    }

    rangeSliders.forEach(slider => {
        updateSliderBackground(slider);
        slider.addEventListener('input', () => updateSliderBackground(slider));
    });

    const searchInput = document.getElementById('searchInput');
    const catalogItems = document.querySelectorAll('.new1');
    
    if (searchInput && catalogItems.length > 0) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            
            catalogItems.forEach(item => {
                const title = item.querySelector('h5')?.textContent.toLowerCase() || '';
                const isVisible = title.includes(query);
                item.style.display = isVisible ? '' : 'none';
            });
        });
    }

    // === Сортировка ===
    const sortSelect = document.getElementById('sortSelect');
    const catalogContainer = document.querySelector('.nowinki');
    
    if (sortSelect && catalogContainer) {
        sortSelect.addEventListener('change', (e) => {
            const sortBy = e.target.value;
            const items = Array.from(catalogContainer.querySelectorAll('.new1'));
            
            items.sort((a, b) => {
                const getPrice = (el) => {
                    const text = el.querySelector('h6')?.textContent || '0';
                    return parseInt(text.replace(/[^\d]/g, '')) || 0;
                };
                const getCalories = (el) => {
                    const text = el.querySelector('#or')?.textContent || '0';
                    return parseInt(text) || 0;
                };
                
                switch(sortBy) {
                    case 'price_asc': return getPrice(a) - getPrice(b);
                    case 'price_desc': return getPrice(b) - getPrice(a);
                    case 'calories_asc': return getCalories(a) - getCalories(b);
                    case 'calories_desc': return getCalories(b) - getCalories(a);
                    case 'popular':
                    default: return 0;
                }
            });
            
            items.forEach(item => catalogContainer.appendChild(item));
        });
    }

    // === Фильтр "Только новинки" ===
    const newOnlyToggle = document.getElementById('newOnly');
    if (newOnlyToggle && catalogItems.length > 0) {
        newOnlyToggle.addEventListener('change', (e) => {
            const showOnlyNew = e.target.checked;
            
            catalogItems.forEach((item, index) => {
                // Условный пример: считаем "новинкой" каждый второй товар
                // Замените на реальную логику (data-атрибут из БД)
                const isNew = index % 2 === 0; 
                item.style.display = (showOnlyNew && !isNew) ? 'none' : '';
            });
        });
    }

    // === Фильтры по составу (чекбоксы) ===
    const compositionFilters = document.querySelectorAll('.sost_filter input[type="checkbox"]');
    if (compositionFilters.length > 0 && catalogItems.length > 0) {
        compositionFilters.forEach(checkbox => {
            checkbox.addEventListener('change', applyCompositionFilters);
        });
        
        function applyCompositionFilters() {
            const activeFilters = Array.from(compositionFilters)
                .filter(cb => cb.checked)
                .map(cb => cb.dataset.filter);
            
            if (activeFilters.length === 0) {
                catalogItems.forEach(item => item.style.display = '');
                return;
            }
            
            catalogItems.forEach(item => {
                // Пример: проверяем наличие data-атрибута у товара
                // В реальности нужно передавать данные из БД
                const itemFilters = item.dataset.filters?.split(',') || [];
                const matches = activeFilters.some(f => itemFilters.includes(f));
                item.style.display = matches ? '' : 'none';
            });
        }
    }
});