document.addEventListener('DOMContentLoaded', () => {
    const allQuestions = document.querySelectorAll('.question-item');
    if (allQuestions) {
        allQuestions.forEach(item => {
            const header = item.querySelector('.question-header');
            const iconSpan = item.querySelector('.toggle-icon');

            // клик по заголовку вопроса
            header.addEventListener('click', () => {
                // переключаем класс open
                const isOpen = item.classList.contains('open');

                if (isOpen) {
                    // закрываем
                    item.classList.remove('open');
                    if (iconSpan) iconSpan.textContent = '🞢';
                } else {
                    // открываем
                    item.classList.add('open');
                    if (iconSpan) iconSpan.textContent = '✕';
                }
            });

            // на старте проверяем, что все закрыты и иконки плюсы
            if (!item.classList.contains('open')) {
                if (iconSpan) iconSpan.textContent = '🞢';
            } else {
                if (iconSpan) iconSpan.textContent = '✕';
            }
        });
    }
});