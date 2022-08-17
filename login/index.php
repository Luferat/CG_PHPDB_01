<?php

// Inclui o arquivo de configuração global do aplicativo:
require($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

// Define o título desta página:
$page_title = 'Login / Entrar';

// Define o conteúdo principal desta página:
$page_article = "<h2>{$page_title}</h2><p>Logue-se para ter acesso ao nosso conetúdo exclusivo.</p>";

// Oculta a barra lateral:
$page_aside = '';

/***********************************************
 * Todo o código PHP desta página começa aqui! *
 ***********************************************/

// Se o usuário já está logado, envia ele para o perfil:
if ($user) header('Location: /profile');

/**
 * IMPORTANTE!
 * 
 * Apague os valores das variáveis "$email" e "$password" em momento de 
 * produção. Esse valores foram inseridos somente para testes estáticos.
 **/

// Inicializa variáveis:
$email = 'mari@neuza.com'; // Apague este e-mail!
$password = '12345_Qwerty'; // Apague esta senha!
$logged = 'false';

// Action do form:
$action = htmlspecialchars($_SERVER["PHP_SELF"]);

// Template do formulário de login:
$html_form = <<<HTML

<form action="{$action}" method="post" id="login">
    <p>
        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" required value="{$email}">
    </p>
    <p>
        <label for="password">Senha:</label>
        <input type="password" name="password" id="password" required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{7,25}$" autocomplete="off" value="{$password}">
    </p>
    <p class="logged">
        <label>
            <input type="checkbox" name="logged" id="logged" value="true">
            Mantenha-me logado.
        </label>
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

    // Processa campos usando a função post_clean() que criamos em "/_config.php":
    $email = post_clean('email', 'email');
    $password = post_clean('password', 'string');
    $logged = isset($_POST['logged']) ? 'true' : 'false';

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

    // Se a senha é inválida...
    elseif (!preg_match($rgpass, $password)) :

        // Exibe informação de erro e o formulário novamente:
        $page_article .= <<<HTML

<div class="feedback_error">
    <strong>Oooops!</strong>
    <p>A senha digitada é inválida, porque deve ter o seguinte formato:</p>
    <small>
    <ul>
        <li>Entre 7 e 25 caracteres;</li>
        <li>Pelo menos uma letra minúscula;</li>
        <li>Pelo menos uma letra maiúscula;</li>
        <li>Pelo menos um número.</li>
    </ul>
    </small>
    <p>Por favor, preencha todos os campos e tente novamente.</p>
</div>

{$html_form}
    
HTML;

    // Se tudo está correto...
    else :

        // Consulta no banco de dados:
        $sql = <<<SQL

SELECT * FROM users 
WHERE
    user_email = '{$email}'
    AND user_password = SHA1('{$password}')
    AND user_status = 'on'

SQL;
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

        // Se achou o usuário....
        else :

            // Extrai dados do usuário e armazena em $ck_user[]:
            $ck_user = $res->fetch_assoc();

            // Formata dados que vão para o cookie em $ck[]:
            $ck = array(
                'id' => $ck_user['user_id'],
                'name' => $ck_user['user_name'],
                'email' => $ck_user['user_email'],
                'avatar' => $ck_user['user_avatar']
            );

            // Se marcou para manter logado...
            if ($logged == 'true') :

                // Validade do cookie de 90 dias:
                $ck_validate = time() + (86400 * 90);

            // Se não marcou para manter logado...
            else :

                // Validade do cookie de sessão:
                $ck_validate = 0;

            endif;

            // Gera cookie do usuário, gravando dados em formato JSON:
            setcookie("{$site_name}_user", json_encode($ck), $ck_validate, '/');

            // Extrai o primeiro nome do usuário:
            $fst = explode(' ', $ck_user['user_name'])[0];

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

    // Exibe o formulário em <article>:
    $page_article .= $html_form;

endif;

/**************************************
 * Fim do código PHP desta página!    *
 * Cuidado ao alterar o código abaixo *
 **************************************/

// Inclui o cabeçalho do template nesta página:
require($_SERVER['DOCUMENT_ROOT'] . '/_header.php');

// Exibe o conteúdo da página:
echo "<article>{$page_article}</article>";

// Exibe a barra lateral da página, mas só se ela não estiver vazia:
if($page_aside != '') echo "<aside>{$page_aside}</aside>";

// Inclui o rodapé do template nesta página.
require($_SERVER['DOCUMENT_ROOT'] . '/_footer.php');
