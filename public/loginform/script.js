// Toggle Password Visibility
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');

if (togglePassword && passwordInput) {
    togglePassword.addEventListener('click', function () {
        const isPassword = passwordInput.type === 'password';

        passwordInput.type = isPassword ? 'text' : 'password';

        // Ganti icon
        this.classList.remove(isPassword ? 'fa-eye' : 'fa-eye-slash');
        this.classList.add(isPassword ? 'fa-eye-slash' : 'fa-eye');
    });
}

// Prevent video from being downloaded
document.addEventListener('contextmenu', function (e) {
    if (e.target.tagName === 'VIDEO') {
        e.preventDefault();
    }
});

