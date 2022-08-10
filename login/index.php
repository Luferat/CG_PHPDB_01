<?php

/**
 * IMPORTANTE!
 * Conforme nossas "políticas de segurança", a senha deve seguir as seguintes 
 * regras:
 *
 *     • Entre 7 e 25 caracteres;
 *     • Pelo menos uma letra minúscula;
 *     • Pelo menos uma letra maiúscula;
 *     • Pelo menos um número.
 *
 * A REGEX abaixo especifica essas regras:
 *
 *     • HTML5 → pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{7,25}$"
 *     • JavaScript → \^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{7,25}$\
 *     • PHP → "^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{7,25}$"
 * 
 * Lembre-se também de apagar os atributos value="" dos campos do formulário.
 * Eles foram inseridos apenas para facilitar os testes e não devem ser usados
 * em produção.
 */

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
$page_article = '<h2>Login / Entrar</h2>';

/**
 * Define o conteúdo da barra lateral desta página:
 */
$page_aside = '';

/***********************************************
 * Todo o código PHP desta página começa aqui! *
 ***********************************************/

// Action do form:
$action = htmlspecialchars($_SERVER["PHP_SELF"]);

// Template do formulário de login:
$html_form = <<<HTML

<form action="{$action}" method="post" id="login" autocomplete="off">
    <p>
        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" required value="joca@silva.com">
    </p>
    <p>
        <label for="password">Senha:</label>
        <input type="password" name="password" id="password" required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{7,25}$" value="12345_Qwerty">
    </p>
    <p>
        <button type="submit">Entrar</button>
    </p>
    <p class="login-tools">
        <a href="/">Lembrar senha</a>
        <a href="/">Cadastre-se</a>
    </p>
</form>

HTML;

// Se o formulário foi enviado...
if ($_SERVER["REQUEST_METHOD"] == "POST") :

    // Processar campos usando a função post_clean() que criamos em "/_config.php":
    $email = post_clean('email', 'email');
    $password = post_clean('password', 'string');

    // Se tem campos vazios...
    if ($email == '' or $password == '') :

        // Exibe informação de erro e o formulário novamente:
        $page_article .= <<<HTML

<div class="feedback_error">
    <strong>Oooops!</strong>
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

            // Exibe mensagem de erro e o formulário novamente:
            $page_article .= <<<HTML

<div class="feedback_error">
    <strong>Oooops!</strong>
    <p>Usuário e/ou senha incorretos!</p>
    <p>Por favor, preencha todos os campos e tente novamente.</p>
</div>

{$html_form}
   
HTML;

        // Achei o usuário....
        else :

            // Extrai dados do usuário e armazena em $user[]:
            $user = $res->fetch_assoc();

            // Cria array com dados que serão armazenados no cookie:
            $ck = array(
                'id' => $user['user_id'],
                'name' => $user['user_name'],
                'email' => $user['user_email'],
                'avatar' => $user['user_avatar']
            );

            // Gera cookie do usuário, armazenando array na forma de JSON:
            setcookie("{$site_name}_user", json_encode($ck), time() + (86400 * 90), '/');

            // Extrai o primeiro nome do usuário:
            $fst = explode(' ', $user['user_name'])[0];

            // Feedback para o usuário:
            $page_article .= <<<HTML

<div class="feedback_ok">
    <h3>Olá {$fst}!</h3>
    <p>Que bom te ver por aqui.</p>
    <p>Você já pode acessar nosso conteúdo exclusivo...</p>
    <p class="center"><a href="/">Início</a></p>
</div>

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
