<?php

/**
 * Inclui o arquivo de configuração global do aplicativo:
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

/**
 * Define o título desta página:
 **/
$page_title = 'Faça Contato';

/**
 * Define o conteúdo principal desta página:
 * 
 * Esta viarável será exibida dentro da tag <article>...</article>.
 */
$page_article = '<h2>Faça Contato</h2>';

/**
 * Define o conteúdo da barra lateral desta página:
 * 
 * Esta variável será exibida na tag <aside>...</aside>.
 */
$page_aside = '';

/***********************************************
 * Todo o código PHP desta página começa aqui! *
 ***********************************************/

// Valores iniciais dos campos:
$form = array(
    'name' => '',
    'email' => '',
    'subject' => '',
    'message' => ''
);

// Action do form:
$action = htmlspecialchars($_SERVER["PHP_SELF"]);

// Formulário de contatos:
$html_form = <<<HTML

<form action="{$action}" method="post" id="contact">

    <p>
        <label for="name">Nome:</label>
        <input type="text" name="name" id="name" placeholder="Seu nome completo." required minlength="3" class="valid" value="{$form['name']}">
    </p>

    <p>
        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" placeholder="Exemplo: joca@silva.com" required class="valid" value="{$form['email']}">
    </p>

    <p>
        <label for="subject">Assunto:</label>
        <input type="text" name="subject" id="subject" placeholder="Assunto do contato." required minlength="3" class="valid" value="{$form['subject']}">
    </p>

    <p>
        <label for="message">Mensagem:</label>
        <textarea name="message" id="message" placeholder="Sua mensagem." required minlength="5" class="valid">{$form['message']}</textarea>
    </p>

    <p>
        <button type="submit" class="primary">Enviar</button>
    </p>

</form>

HTML;

// Se o formulário foi enviado...
if ($_SERVER["REQUEST_METHOD"] == "POST") :

    /**
     * Processa o formulário:
     */

    // Limpa (saminitiza) o valor do campos:
    $name = post_clean('name', 'string');
    $email = post_clean('email', 'email');
    $subject = post_clean('subject', 'string');
    $message = post_clean('message', 'string');

    // Detecta campos vazios (ou, não aprovados na sanitização)
    if ($name == '' or $email == '' or $subject == '' or $message == '') :

        $page_article = <<<HTML

        <h2>Faça Contato</h2>
        <p>Preencha todos os campos abaixo para nos enviar uma mensagem.</p>
   
        <div class="feedback_error">
            <strong>Olá!</strong>
            <p>Um ou mais campos do formulário estão vazios!</p>
            <p>Não foi possível enviar seu contato.</p>
            <p>Por favor, preencha todos os campos e tente novamente.</p>
        </div>

        {$html_form}
   
HTML;

    else :

        // Query para salvar contato no banco de dados:
        $sql = <<<SQL

INSERT INTO contacts (name, email, subject, message)
VALUES (
    '{$name}',
    '{$email}',
    '{$subject}',
    '{$message}'
);

SQL;

        // Executa a query:
        $conn->query($sql);

        // Obtém o primeiro nome do remetente:
        $parts = explode(' ', $name)[0];

        // Feedback para o usuário:
        $page_article = <<<HTML

<h2>Faça Contato</h2>
<p><strong>Olá {$parts}!</strong></p>
<p>Seu contato foi enviado com sucesso!</p>
<p><a href="/">Início</a></p>

HTML;

        // Envia contato por e-mail para o administrador do site:
        @mail('admin@cripei.com', 'Formulário de contatos', 'Um novo contato foi enviado para o site Cripei.');

    endif;

else :

    // Exibe o formulário:
    $page_article = $html_form;

endif;

/***********************************
 * Fim do código PHP desta página! *
 ***********************************/

/**
 * Inclui o cabeçalho do template nesta página:
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_header.php');

/**
 * Exibe o conteúdo da página:
 */

echo <<<HTML

<article>{$page_article}</article>

<aside>{$page_aside}</aside>

HTML;

/**
 * Inclui o rodapé do template nesta página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_footer.php');
