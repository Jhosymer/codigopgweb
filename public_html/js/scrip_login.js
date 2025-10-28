/*document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    alert(`Correo: ${email}\nContraseña: ${password}`);
});*/

document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    togglePassword.addEventListener('click', function() {
        // Alterna el tipo de input entre 'password' y 'text'
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Alterna los íconos de Boxicons
        const icon = this.querySelector('i');
        icon.classList.toggle('bx-show');
        icon.classList.toggle('bx-hide');
    });
});