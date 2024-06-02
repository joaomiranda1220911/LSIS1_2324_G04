function togglePasswordVisibility(id) {
    var passwordInput = document.getElementById(id);
    var toggleButton = passwordInput.nextElementSibling;

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleButton.textContent = "Ocultar";
    } else {
        passwordInput.type = "password";
        toggleButton.textContent = "Mostrar";
    }
}