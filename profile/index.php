<?php

/**
 * Inclui o arquivo de configuração global do aplicativo:
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

/**
 * Define o título desta página:
 */
$page_title = 'Página modelo';

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

// Se o usuário não está logado, envia ele para o login:
if (!$user) header('Location: /login');

// Gera consulta dos dados do usuário:
$sql = <<<SQL

SELECT 
    *,
    DATE_FORMAT(user_date, '%d/%m/%Y às %H:%i') AS date_br,
    DATE_FORMAT(user_birth, '%d/%m/%Y') as birth_br
FROM users
WHERE user_id = '{$user['id']}'
    AND user_status = 'on'

SQL;

// Executa SQL:
$res = $conn->query($sql);

// Se não encontrou o usuário...
if($res->num_rows != 1):

    // Apaga o cookie:
    // setcookie("{$site_name}_user", '', -1, '/');

    // Envia para login:
    header('Location: /login');

endif;

// Extrai os dados do usuário para $profile[]:
$profile = $res->fetch_assoc();

// Obtém o nome do usuário em partes:
$parts = explode(' ', $profile['user_name']);
$profile['view_name'] = $parts[0] . " " . $parts[count($parts) - 1];

// Obtém a idade:
$profile['age'] = get_age($profile['user_birth']) . ' anos';

// Traduz o tipo de perfil:
$type = array(
    'admin' => 'Administrador',
    'author' => 'Autor',
    'moderator' => 'Moderador',
    'user' => 'Usuário'
);

// Formata perfil:
$page_article = <<<HTML

<h2>Perfil de usuário</h2>

<div class="profile">

    <img src="{$profile['user_avatar']}" alt="Avatar de {$profile['user_name']}">
    <h3>{$profile['view_name']}</h3>
    <p>{$profile['user_bio']}</p>
    <ul>
        <li><strong>Nome: </strong>{$profile['user_name']}</li>
        <li><strong>E-mail: </strong>{$profile['user_email']}</li>
        <li><strong>Nascimento: </strong>{$profile['birth_br']} - {$profile['age']}</li>
    </ul>    
    <hr>
    <ul>
        <li><strong>Id de usuário: </strong>{$profile['user_id']}</li>
        <li><strong>Cadastrado em: </strong>{$profile['date_br']}</li>
        <li><strong>Perfil: </strong>{$type[$profile['user_type']]}</li>
    </ul>

</div>

HTML;

$page_aside = <<<HTML

<h4>Ações:</h4>
<ul>
    <li><a href="/edit">Editar perfil</a></li>
    <li><a href="/passwd">Trocar senha</a></li>
    <li><a href="/avatar">Trocar avatar</a></li>
</ul>
<hr>
<ul>
    <li><a href="/delete">Apagar perfil</a></li>
</ul>
<hr>
<ul>
    <li><a href="/logout">Logout / Sair</a></li>
</ul>

HTML;

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
