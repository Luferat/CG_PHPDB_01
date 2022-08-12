<?php

// Inclui o arquivo de configuração global do aplicativo:
require($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

// Define o título desta página:
$page_title = 'Logout / Sair';

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

    // Apaga o cookie do usuário:
    setcookie("{$site_name}_user", '', -1, '/');

    // Redireciona para a página inicial:
    header('Location: /');

// Se ainda não foi enviado...
else :

    // Extrai o primeiro nome do usuário:
    $fst = explode(' ', $user['name'])[0];

    // Feedback para o usuário:
    $page_article .= <<<HTML
    
    <div class="feedback_ok">
        <h3>Olá {$fst}!</h3>
        <p>Tem certeza que deseja sair do aplicativo?</p>
        <p><em>Se sair não poderar acessar os recursos exclusivos até que se logue novamente.</em></p>
        <form action="{$action}" method="post" id="logout">
            <button type="button" onclick="location.href = '/profile'">Não, não sair...</button>
            <button type="submit">Sim, quero sair...</button>
        </form>
    </div>
    
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
