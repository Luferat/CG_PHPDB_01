<?php

// Inclui o arquivo de configuração global do aplicativo:
require($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

// Define o título desta página:
$page_title = 'Apagar Perfil';

// Define o conteúdo principal desta página:
$page_article = "<h2>{$page_title}</h2>";

// Define o conteúdo da barra lateral desta página:
$page_aside = '';

/***********************************************
 * Todo o código PHP desta página começa aqui! *
 ***********************************************/

// Se usuário não está logado envia usuário para login:
if (!$user) header('Location: /login');

// Se o formulário foi enviado...
if ($_SERVER["REQUEST_METHOD"] == "POST") :

    // Atualiza o status no banco de dados:
    $sql = "UPDATE users SET user_status = 'deleted' WHERE user_id = '{$user['id']}';";
    $conn->query($sql);

    // Apaga o cookie:
    setcookie("{$site_name}_user", '', -1, '/');

    // Feedback:
        // Feedback para o usuário:
$page_article .= <<<HTML

    <div class="feedback_ok">
        <h3>Olá!</h3>
        <p>Seu perfil foi apagado com sucesso.</p>
        <p><em>Foi bom ter você conosco. Esperamos que retorne um dia.</em></p>
        <p class="options">
            <a href="/">Início</a>
        </p>
    </div>
            
HTML;    

// Se ainda não confirmou...
else :

    // Consulta status do usuário no banco de dados:
    $sql = "SELECT user_id FROM users WHERE user_id = '{$user['id']}' AND user_status = 'on';";
    $res = $conn->query($sql);

    // Se o usuário não foi encontrado ou tem o status != 'on'...
    if ($res->num_rows != 1) :

        // Desloga usuário (apaga cookie):
        setcookie("{$site_name}_user", '', -1, '/');

        // Feedback para o usuário:
        $page_article .= <<<HTML

    <div class="feedback_ok">
        <h3>Oooops!</h3>
        <p>Não é possível apagar seu perfil.</p>
        <p><em>Em caso de dúvidas, entre em contato conosco.</em></p>
        <p class="options">
            <a href="/">Início</a>
            <a href="/contacts">Faça contato</a>
        </p>
    </div>
            
HTML;

    // Usuário encontrado e status = 'on':
    else :

        // Extrai o primeiro nome do usuário:
        $fst = explode(' ', $user['name'])[0];

        // Action do form:
        $action = htmlspecialchars($_SERVER["PHP_SELF"]);

        // Pede confirmação do usuário:
        $page_article .= <<<HTML
    
    <div class="feedback_ok">
        <h3>Olá {$fst}!</h3>
        <p>Tem certeza que deseja apagar seu perfil do aplicativo?</p>
        <blockquote>Esta ação não pode ser desfeita!</blockquote>
        <p><em>Se sair não poderar acessar os recursos exclusivos até que se cadastre novamente.</em></p>
        <form action="{$action}" method="post" id="delete">
            <button type="button" onclick="location.href = '/profile'">Não, não apagar...</button>
            <button type="submit">Sim, apagar...</button>
        </form>
    </div>
    
HTML;

    endif;

endif;

/***********************************
 * Fim do código PHP desta página! *
 ***********************************/

// Inclui o cabeçalho do template nesta página:
require($_SERVER['DOCUMENT_ROOT'] . '/_header.php');

// Exibe o conteúdo da página:
echo <<<HTML

<article>{$page_article}</article>

HTML;

// Inclui o rodapé do template nesta página.
require($_SERVER['DOCUMENT_ROOT'] . '/_footer.php');
