document.addEventListener('DOMContentLoaded', () => {
    const toggles = document.querySelectorAll('.warzone-nav .toggle');
    toggles.forEach(btn => {
        btn.addEventListener('click', () => {
            btn.parentElement?.classList.toggle('open');
        });
    });
});
