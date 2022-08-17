/* JavaScript desta página */

// Captura div com mensagem de erro → class="feedback_error":
let feedback_error = document.getElementsByClassName('feedback_error');

// Se encontrou a div...
if (feedback_error.length != 0) {

    // Oculta mensagem de erro após 5 segundos (5000 milissegundos):
    //      Descomente a linha abaixo para usar.
    // setTimeout(() => { feedback_error[0].style.display = 'none'; }, 5000);

    // Seta os campos do formulário:
    email.value = '{$email}';
    password.value = '';
    logged.checked = { $logged };
}
