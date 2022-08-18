<?php

/********************
 * Apaga comentário *
 ********************/

// Inclui arquivo de configuração global:
require($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

// Obtém Ids do artigo e do comentário:
$article = isset($_GET['a']) ? intval($_GET['a']) : 0;
$comment = isset($_GET['c']) ? intval($_GET['c']) : 0;

// Se o Id do artigo é inválido, redireciona para a página inicial:
if ($article == 0) header('Location: /');

// Se o comentário é inválido, redireciona para o artigo:
if ($comment == 0) header("Location: /view/?{$article}#comments");

// Se o usuário não está logado, redireciona para o artigo:
if (!$user) header("Location: /view/?{$article}#comments");

// Verifica a validade do comentário:
$sql = <<<SQL
SELECT cmt_id FROM comments
WHERE cmt_id = '{$comment}'
    AND cmt_author = '{$user['id']}'
    AND cmt_article = '{$article}'
    AND cmt_status = 'on';
SQL;
// debug($sql);
$res = $conn->query($sql);

// Se o comentário é inválido, redireciona para o artigo:
if ($res->num_rows != 1) header("Location: /view/?{$article}#comments");

// Atualiza status do comentário:
$sql = "UPDATE comments SET cmt_status = 'deleted' WHERE cmt_id = '{$comment}';";
// debug($sql);
$conn->query($sql);

// Redireciona para o artigo:
header("Location: /view/?{$article}#comments");

/***********************************
 * Fim do código PHP desta página! *
 ***********************************/
