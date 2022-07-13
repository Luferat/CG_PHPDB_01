<?php

/**
 * Página modelo → Instruções
 * 
 * Esta é a página modelo para todas as novas páginas deste site. Para criar 
 * uma nova página neste site, siga os passos abaixo:
 *      1. Crie a pasta que conterá os componentes da nova página;
 *      2. Faça uma cópia deste arquivo "/_model.php";
 *      2. Mova a copia para a pasta criada no item 1;
 *      3. Na pasta criada no item 1, renomeie o arquivo para "index.php";
 *      4. Adicione um arquivo chamado "style.css" à pasta;
 *      5. Importe neste arquivo "style.css" o arquivo "/style.css" global;
 *          @import url('/style.css');
 *      6. Adicione um arquivo "script.js";
 *      7. Apague este bloco de comentários.
 */

/**
 * Inclui o arquivo de configuração global do aplicativo:
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

/**
 * Define o título desta página:
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
