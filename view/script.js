// Obtém elementos pelas classes:
let commentOk = document.querySelectorAll('.comment-ok')[0];
let commentError = document.querySelectorAll('.comment-error')[0];

// Se o elemento é visível, oculta em 5000 milessegundos:
setTimeout(() => {
    if (commentOk != undefined) commentOk.style.display = 'none';
    if (commentError != undefined) commentError.style.display = 'none';
}, 5000);

// Obtém todos os botões "Editar comentário":
let btnEdit = document.querySelectorAll('.button-edit');

// Processa clicks nos elementos:
btnEdit.forEach(el => {
    el.addEventListener('click', editComment); 
});

// Processa editor de comentários:
function editComment() {
    
    // Obtém Id do comentário a ser editado:
    cmtId = parseInt(this.getAttribute('data-comment'));

    // Envia Id para o formulário:
    commentId.value = cmtId;

    // Obtém o comentário e envia para o formulário:
    commentContent.value = document.querySelector(`#comment-${cmtId} .comment-content`).innerHTML;

    // Foca no formulário de comentário:
    comment.focus();
    comment.scrollIntoView();

    // Retorna sem fazer nada:
    return false;
}

