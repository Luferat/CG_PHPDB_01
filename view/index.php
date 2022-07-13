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
if($res->num_rows != 1) header('Location: /');

// Converte dados obtidos para array e armazena em $art:
$art = $res->fetch_assoc();

// Título do artigo como título da página:


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

?>