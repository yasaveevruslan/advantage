document.addEventListener('DOMContentLoaded', () => {
    const burgerBtn = document.getElementById('burger-btn');
    if (burgerBtn) {
        const closeBtn = document.getElementById('close-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        burgerBtn.addEventListener('click', () => {
            mobileMenu.classList.add('active');
        });

        closeBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('active');
        });

        mobileMenu.addEventListener('click', (e) => {
            if (e.target === mobileMenu) {
                mobileMenu.classList.remove('active');
            }
        });
    }
});