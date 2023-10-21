function validarUsuario() {
    var usuarioInput = document.querySelector('input[name="usuario"]');
    var usuario = usuarioInput.value;
    var regex = /^[a-zA-Z0-9]+$/; // Apenas letras e números são permitidos

    if (!regex.test(usuario)) {
        alert("Caracteres especiais não são permitidos no campo de usuário.");
        usuarioInput.value = ""; // Limpa o campo
        usuarioInput.focus();
    }
}
