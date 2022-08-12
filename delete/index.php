<?php

// Inclui o arquivo de configuração global do aplicativo:
require($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

// Define o título desta página:
$page_title = 'Apagar perfil';

// Define o conteúdo principal desta página:
$page_article = "<h2>{$page_title}</h2>";

// Define o conteúdo da barra lateral desta página:
$page_aside = '<h3>Barra lateral</h3>';

/***********************************************
 * Todo o código PHP desta página começa aqui! *
 ***********************************************/

// Se o usuário não está logado, envia ele para login:
if (!$user) header('Location: /login');

// Action do form:
$action = htmlspecialchars($_SERVER["PHP_SELF"]);

// Se o formulário foi enviado...
if ($_SERVER["REQUEST_METHOD"] == "POST") :

///////////////////////////////

// Se ainda não confirmou...
else :

    /**
     * DICA IMPORTANTE!
     * Antes de "remover" o perfil do usuário, vamos verificar se ele está 
     * disponível no sistema porque pode ser que ele ainda esteja logado, 
     * mas foi removido do sistema por algum motivo.
     */

    // Pesquisa o usuário no banco de dados:
    $sql = "SELECT user_id FROM users WHERE user_id = '{$user['id']}' AND user_status = 'on';";
    $res = $conn->query($sql);

    // Se o usuário não foi achado ou está com o "status != 'on'"...
    if ($res->num_rows != 1) :

        // Apaga o cookie do usuário:
        setcookie("{$site_name}_user", '', -1, '/');

        // Feedback para o usuário:
        $page_article .= <<<HTML

<div class="feedback_ok">
    <h3>Oooops!</h3>
    <p>Você não pode editar / apagar este perfil...</p>
    <p>Em caso de dúvidas, entre em contato com a equipe do site.</p>
    <p class="menu-tools">
        <a href="/">Início</a>
        <a href="/contacts">Faça contato</a>
    </p>
</div>

HTML;

    // Se o usuário está com o "status = 'on'"...
    else :

        // Extrai o primeiro nome do usuário:
        $fst = explode(' ', $user['name'])[0];

        // Feedback para o usuário:
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
