<?php

/**
 * Inclui o arquivo de configuração global do aplicativo:
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

/**
 * Define o título desta página:
 * 
 *  → Na página inicial usaremos o 'slogan' do site.
 */
$page_title = $site_slogan;

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

// Obtém todos os artigos do site:
$sql = <<<SQL

SELECT 
	art_id, art_title, art_thumb, art_intro
FROM `articles`
WHERE art_status = 'on'
	AND art_date <= NOW()
ORDER BY art_date DESC;

SQL;

// Executa a query:
$res = $conn->query($sql);

// Formata conteúdo da página:
$page_article = <<<HTML

<h2>Artigos Recentes</h2>

HTML;

// Loop que obtém cada registro recebido do banco de dados:
while ($art = $res->fetch_assoc()) :

    // Inclui cada artigo no conteúdo da página:
    $page_article .= <<<HTML

<div class="art_block" onclick="location.href = '/view/?{$art['art_id']}'">

    <img src="{$art['art_thumb']}" alt="{$art['art_title']}">
    <div>
        <h4>{$art['art_title']}</h4>
        <span>{$art['art_intro']}</span>
    </div>

</div>

HTML;

endwhile;

// Obtém a lista dos artigos mais acessados:
$sql = <<<SQL

SELECT art_id, art_title 
FROM `articles` 
WHERE art_status = 'on' 
	AND art_date <= NOW() 
ORDER BY art_counter DESC 
LIMIT 5;

SQL;
$res = $conn->query($sql);

// Variável da barra lateral:
$page_aside = '<h4>+ Visitados</h4><ul class="u_list">';

// Loop para obter cada artigo:
while ($top_art = $res->fetch_assoc()) :

    // Concatena o artigo na listagem:
    $page_aside .= <<<HTML

<li><a href="/view/?{$top_art['art_id']}">{$top_art['art_title']}</a></li>

HTML;

endwhile;

// Obtém os comentários mais recentes:
$sql = <<<SQL

SELECT cmt_article, cmt_content 
FROM `comments` 
WHERE cmt_status = 'on' 
ORDER BY cmt_date 
DESC LIMIT 5;

SQL;
$res = $conn->query($sql);

// Variável da barra lateral:
$page_aside .= '</ul><h4>Novos comentários</h4><ul class="u_list">';

// Loop para obter cada comentário:
while ($comments = $res->fetch_assoc()) :

    // Cria um resumo do comentário:
    $resume = substr($comments['cmt_content'], 0, 100) . '...';

    $page_aside .= <<<HTML

<li><a href="/view/?{$comments['cmt_article']}#comments">{$resume}</a></li>

HTML;

endwhile;

// Fecha a listagem de comentários:
$page_aside .= '</ul>';

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
