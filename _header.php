<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/img/favicon.png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <title><?php echo $site_name ?> .:. <?php echo $page_title ?></title>
</head>

<body>

    <a id="top"></a>

    <div class="wrap">

        <header>
            <a href="/" title="Página inicial"><img src="<?php echo $site_logo ?>" alt="Logotipo de <?php echo $site_name ?>"></a>
            <h1><?php echo $site_name ?><small><?php echo $site_slogan ?></small></h1>
        </header>

        <nav>
            <a href="/" title="Página inicial">
                <i class="fa-solid fa-house fa-fw"></i>
                <span>Início</span>
            </a>
            <a href="/contacts" title="Faça contato">
                <i class="fa-solid fa-comments fa-fw"></i>
                <span>Contatos</span>
            </a>
            <a href="/about" title="Sobre...">
                <i class="fa-solid fa-circle-info fa-fw"></i>
                <span>Sobre</span>
            </a>
<?php

// Se o usuário está logado...
if($user):
?>

            <a href="/profile" title="Ver perfil de <?php echo $user['name'] ?>" class="menu-user">
                <img src="<?php echo $user['avatar'] ?>" alt="<?php echo $user['name'] ?>">
                <span>Perfil</span>
            </a>

<?php 
// Se usuário não está logado...
else:
?>
            <a href="/login" title="Logue-se...">
                <i class="fa-solid fa-user fa-fw"></i>
                <span>Entrar</span>
            </a>
<?php 
endif;
?>
        </nav>

        <main>