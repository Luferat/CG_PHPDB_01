<?php

/**
 * Inclui o arquivo de configuração global do aplicativo:
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

/**
 * Define o título desta página:
 */
$page_title = 'Login / Entrar';

/**
 * Define o conteúdo principal desta página:
 */
$page_article = '';

/**
 * Define o conteúdo da barra lateral desta página:
 */
$page_aside = '';

/***********************************************
 * Todo o código PHP desta página começa aqui! *
 ***********************************************/

// Action do form:
$action = htmlspecialchars($_SERVER["PHP_SELF"]);

// Formulário de login:
$html_form = <<<HTML

<form action="{$action}" method="post" id="login">

    <p>
        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" value="joca@silva.com">
    </p>

    <p>
        <label for="password">Senha:</label>
        <input type="password" name="password" id="password" value="12345_Qwerty">
    </p>

    <p>
        <button type="submit">Entrar</button>
    </p>

</form>

HTML;

// Se o formulário foi enviado...
if ($_SERVER["REQUEST_METHOD"] == "POST") :

    // Processar campos:
    $email = post_clean('email', 'email');
    $password = post_clean('password', 'string');

    // Se tem campos vazios...
    if ($email == '' or $password == '') :

        // Exibe erro e o formulário novamente:
        $page_article = <<<HTML

        <h2>Logue-se!</h2>
          
        <div class="feedback_error">
            <strong>Olá!</strong>
            <p>Um ou mais campos do formulário estão vazios!</p>
            <p>Por favor, preencha todos os campos e tente novamente.</p>
        </div>

        {$html_form}
   
HTML;

    // Se todos os campos foram preenchidos...
    else :

        // Query de consulta ao banco de dados:
        $sql = <<<SQL

SELECT * FROM users 
WHERE
    user_email = '{$email}'
    AND user_password = SHA1('{$password}')
    AND user_status = 'on'

SQL;

        // Executa a query
        $res = $conn->query($sql);

        // Se não achou o usuário...
        if ($res->num_rows != 1) :

            // Exibe erro e o formulário novamente:
            $page_article = <<<HTML

        <h2>Logue-se!</h2>
          
        <div class="feedback_error">
            <strong>Olá!</strong>
            <p>Usuário e/ou senha incorretos!</p>
            <p>Por favor, preencha todos os campos e tente novamente.</p>
        </div>

        {$html_form}
   
HTML;

        // Achei o usuário....
        else :

            // Extrai dados do usuário:
            $user = $res->fetch_assoc();

            // Gera cookie do usuário:
            setcookie('cripei_user', $user['user_id'], time() + (86400 * 90), '/');

            // Feedback para o usuário:
            $page_article = <<<HTML

<h3>Olá {$user['user_name']}!</h3>
<p>Você já pode acessar nosso conteúdo exclusivo...</p>

HTML;

        endif;

    endif;

else :

    // Exibe o formulário
    $page_article = <<<HTML

<h2>Logue-se!</h2>

{$html_form}

HTML;

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
