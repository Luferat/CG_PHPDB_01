<?php

/***************
 * Comentários *
 ***************/

// Define variáveis:
$comments = '';

// Se usuário está logado...
if ($user) :

    // Se um comentário foi enviado...
    if ($_SERVER["REQUEST_METHOD"] == "POST") :

        // Obtém e sanitiza comentário enviado:
        $comment_content = post_clean('commentContent', 'string');

        // Obtém o Id do comentário:
        $comment_id = intval($_POST['commentId']);

        // Se o comentário está vazio...
        if ($comment_content == '') :

            // Exibe mensagem de erro:
            $comments .= '<div class="comment-error">Nenhum comentário enviado!<br>Comentário em branco.</div>';

        // Se o comentário está ok...
        else :

            // Se está editando um comentário...
            if ($comment_id != 0) :

                // Pesquisa todos os comentários, exceto este:
                $cmt_sql = "AND cmt_status != '{$comment_id}'";

            // Se está criando um comentário...
            else :

                // Pesquisa todos os comentários:
                $cmt_sql = '';

            endif;

            // Pesquisa comentário no banco de dados:
            $sql = <<<SQL

SELECT cmt_id FROM comments 
WHERE cmt_content = '{$comment_content}'
    AND cmt_author = '{$user['id']}'
    AND cmt_article = '{$id}'
    AND cmt_status = 'on'
    {$cmt_sql}

SQL;
            debug($sql);
            $res = $conn->query($sql);

            // Se o comentário enviado já existe no banco de dados...
            if ($res->num_rows == 1) :

                // Exibe mensagem de erro:
                $comments .= '<div class="comment-error">Nenhum comentário enviado!<br>Comentário já existe.</div>';

            // Se o comentário não exite no banco de dados...
            else :

                // Se está editando um comentário...
                if ($comment_id != 0) :

                    // Atualiza comentário no banco de dados...
                    $sql = "UPDATE comments SET cmt_content = '{$comment_content}' WHERE cmt_id = '{$comment_id}';";

                    // Mensagem de confirmação:
                    $comments .= '<div class="comment-ok">Comentário atualizado com sucesso!</div>';

                // Se está criando um comentário...
                else :

                    // Salva comentário no banco de dados...
                    $sql = "INSERT INTO comments (cmt_author, cmt_article, cmt_content) VALUES ('{$user['id']}', '{$id}', '{$comment_content}');";

                    // Mensagem de confirmação:
                    $comments .= '<div class="comment-ok">Comentário enviado com sucesso!</div>';

                endif;

                // Executa query:
                // debug($sql);
                $conn->query($sql);

            endif;

        endif;

    endif;

    // Formulário de comentários
    $comments .= <<<HTML

<form action="/view/?{$id}#comments" method="post" id="comment">
    <input type="hidden" name="commentId" id="commentId" value="0">
    <textarea name="commentContent" id="commentContent" required></textarea>
    <button type="submit">Enviar</button>
</form>

<hr class="separator">

HTML;

// Se não tem usuário logado...
else :

    // Convite para logar-se e comentar:
    $comments .= <<<HTML

<p class="center"><a href="/login">Logue-se</a> para comentar.</p>
<hr class="separator">

HTML;

endif;

/************************
 * Lista de comentários *
 ************************/

// Obtém todos os comentários para o artigo atual:
$sql = <<<SQL

SELECT
    comments.*,
    users.user_name,
    DATE_FORMAT(cmt_date, '%d/%m/%Y às %h:%i') AS date_br
FROM comments
INNER JOIN users ON cmt_author = user_id
WHERE cmt_article = '{$id}'
    AND cmt_status = 'on'
ORDER BY cmt_date DESC;

SQL;
$res = $conn->query($sql);

// Conta os comentários:
$total_comments = $res->num_rows;

// Se não tem comentários...
if ($total_comments < 1) :

    // Exibe convite para comentar:
    $comments .= '<p class="center">Nenhum comentário encontrado. Seja a(o) primeira(o) a comentar!</p>';

// Se tem comentários...
else :

    // Loop para extrair cada comentário:
    while ($cmt = $res->fetch_assoc()) :

        // Trata comentário, quebrando linhas de forma correta:
        $cmt_body = nl2br($cmt['cmt_content']);

        // Se usuário logado é autor do comentário e o comentário tem menos de 15 dias...
        if ($cmt['cmt_author'] == $user['id'] and strtotime($cmt['cmt_date']) < time() + (86400 * 15)) :

            // Exibe ferramentas de edição do comentário:
            $cmt_tools = <<<HTML

<div class="comment-tools">
    <a href="/view/?{$id}#comments" data-comment="{$cmt['cmt_id']}" class="button-edit" title="Editar comentário."><i class="fa-solid fa-pen-to-square fa-fw"></i></a>
    <a href="/cmtDelete/?a={$id}&c={$cmt['cmt_id']}" class="button-delete" title="Apagar comentário."><i class="fa-solid fa-trash-can fa-fw"></i></a>
</div>

HTML;

        // Se usuário logado não é autor do comentário...
        else :

            $cmt_tools = '';

        endif;

        // Formata comentário:
        $comments .= <<<HTML

<div class="comment-item" id="comment-{$cmt['cmt_id']}">

    <div class="comment-info">
        <div class="comment-meta">Por {$cmt['user_name']}.<br>Em ${cmt['date_br']}.</div>
        {$cmt_tools}
    </div>
    <div class="comment-block">
        <div class="comment-content">{$cmt_body}</div>
    </div>

</div>

HTML;

    endwhile;

endif;
