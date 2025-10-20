document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-flash-close]').forEach(btn => {
        btn.addEventListener('click', () => btn.closest('.alert').remove());
    });
});
