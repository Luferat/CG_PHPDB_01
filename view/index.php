<?php

/**
 * Inclui o arquivo de configuração global do aplicativo:
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

/**
 * Define o título desta página:
 */
$page_title = 'Artigo Completo';

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

// Obtém o ID do artigo a ser visualizado, da URL da página:
$id = intval($_SERVER['QUERY_STRING']);

// Se o ID retornado é '0', redireciona para a página inicial:
if ($id == 0) header('Location: /');

// Cria a query para obter o artigo completo do banco de dados:
$sql = <<<SQL

SELECT *, DATE_FORMAT(art_date, '%d/%m/%Y às %H:%i') AS datebr FROM articles
INNER JOIN users ON art_author = user_id
WHERE art_id = '{$id}'
	AND art_status = 'on'
    AND art_date <= NOW();

SQL;

// Executa a query e armazena em $res:
$res = $conn->query($sql);

// Se não obteve um artigo com esses requisitos, volta para 'index':
// O atributo 'num_rows' mostra quantos registro vieram do DB.
if ($res->num_rows != 1) header('Location: /');

// Converte dados obtidos para array e armazena em $art:
$art = $res->fetch_assoc();

// Título do artigo como título da página:
$page_title = $art['art_title'];

/**
 * Formata nome do autor e data da postagem:
 */
// Obtém o nome do autor em partes:
$parts = explode(' ', $art['user_name']);
$autor_name = $parts[0] . " " . $parts[count($parts) - 1];

$author_date = "Por {$autor_name} em {$art['datebr']}.";

// Formata o conteúdo:
$page_article = <<<HTML

<h2>{$art['art_title']}</h2>
<small>{$author_date}</small>
<div>{$art['art_content']}</div>

<a id="comments"></a>

HTML;

/**
 * Calcula idade do autor:
 */

// Partes da data de nascimento:
$birth_parts = explode('-', $art['user_birth']);

// Partes da data atual:
$now_parts = explode('-', date('Y-m-d'));

// Calcula idade pelo ano:
$age = $now_parts[0] - $birth_parts[0];

// Ajusta idade pelo mês e dia:
if ($now_parts[1] < $birth_parts[1])
    $age--;
elseif (($now_parts[1] == $birth_parts[1]) && ($now_parts[2] < $birth_parts[2]))
    $age--;

// Obtém mais artigos do autor:
$sql = <<<SQL

SELECT art_id, art_title FROM articles
WHERE art_author = '{$art['art_author']}'
	AND art_status = 'on'
    AND art_date <= NOW()
    AND art_id != '{$id}'
ORDER BY RAND()  
LIMIT 5; 

SQL;
$res = $conn->query($sql);

// Inicializa lista de artigos do autor:
$author_arts = '';

// Se o autor tem mais artigos...
if ($res->num_rows > 0) :

    // Prepara a listagem dos artigos:
    $author_arts = '<h5 class="more-articles">+ Artigos</h5><ul>';

    // Loop para obter cada artigo:
    while ($author_art = $res->fetch_assoc()) :

        // Concatena na listagem:
        $author_arts .= <<<HTML
    <li><a href="/view/?{$author_art['art_id']}">{$author_art['art_title']}</a></li>
HTML;

    endwhile;

    // Fecha a listagem de artigos:
    $author_arts .= '</ul>';

endif;

// Formata barra lateral:
$page_aside = <<<HTML

<div class="author">

    <img src="{$art['user_avatar']}" alt="{$art['user_name']}">
    <h4>{$art['user_name']}</h4>
    <h5 class="age">{$age} anos</h5>
    <p>{$art['user_bio']}</p>
    {$author_arts}

</div>

HTML;

// Atualiza as visualizações deste artigo:
$counter = intval($art['art_counter']) + 1;

// Atualiza o contador no database:
$sql = "UPDATE articles SET art_counter = '{$counter}' WHERE art_id = '{$id}';";
$conn->query($sql);

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
