<script>
    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.passwordToggle);
            const isVisible = input.type === 'text';

            input.type = isVisible ? 'password' : 'text';
            button.textContent = isVisible ? 'Show' : 'Hide';
            button.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');
        });
    });
</script>
