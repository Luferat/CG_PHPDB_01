<?php

/**
 * Inclui o arquivo de configuração global do aplicativo:
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

/**
 * Define i título desta página:
 * 
 * A variável '$page_title' especifica o título da página, que aparece 
 * na tag <title>...</title>. Esta variável não pode ser vazia.
 * 
 * Conforme o case, a tag title terá os seguintes formatos:
 * 
 *  • Na página inicial → <title>Nome do site .:. Slogan do site</title>
 *  • Em páginas estáticas → <title>Nome do site .:. Nome da página</title>
 *  • Na página de artigos → <title>Nome do site .:. Título do artigo</title>
 * 
 */
$page_title = 'Página modelo';

/**
 * Define o conteúdo principal desta página:
 * 
 * Esta viarável será exibida dentro da tag <article>...</article>.
 */
$page_article = '';

/**
 * Define o conteúdo da barra lateral desta página:
 * 
 * Esta variável será exibida na tag <aside>...</aside>.
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

$page_article = <<<HTML

<h2>Artigos Recentes</h2>

HTML;

// Loop que lista cada registro recebido:
while( $art = $res->fetch_assoc() ):

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

/***********************************
 * Fim do código PHP desta página! *
 ***********************************/

/**
 * Inclui o cabeçalho do template nesta página:
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_header.php');

?>

<article><?php echo $page_article ?></article>

<aside><?php echo $page_aside ?></aside>

<?php

/**
 * Inclui o rodapé do template nesta página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_footer.php');

?>