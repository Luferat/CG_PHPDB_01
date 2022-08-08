<?php

/**
 * config.php
 * Arquivo de configuração global das páginas do aplicativo.
 */

// PHP com UTF-8:
header('Content-Type: text/html; charset=utf-8');

// Define fusohorário do aplicativo:
date_default_timezone_set('America/Sao_Paulo');

/**********************
 * Variáveis do site: *
 **********************/

// Define o nome do site:
$site_name = "Cripei";

// Define o slogan do site:
$site_slogan = "Cripe e resolva!";

// Define o logotipo do site:
$site_logo = "/img/logo01.128.png";

/********************************
 * Conexão com o banco de dados *
 ********************************/

// Lê arquivo "ini" e converte em um array:
$db = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/_config.ini', true);

// Itera elementos de $db:
// Referências: https://www.php.net/manual/pt_BR/control-structures.foreach.php
foreach ($db as $server => $values) :

    // Se estamos no servidor correto:
    if ($server == $_SERVER['SERVER_NAME']) :

        // Conecta no banco de dados com as credenciais deste servidor:
        $conn = new mysqli($values['hostname'], $values['username'], $values['password'], $values['database']);

        // Trata possíveis exceções
        if ($conn->connect_error) die("Falha de conexão com o banco e dados: " . $conn->connect_error);
    endif;
endforeach;

// Seta transações com MySQL/MariaDB para UTF-8:
$conn->query("SET NAMES 'utf8'");
$conn->query('SET character_set_connection=utf8');
$conn->query('SET character_set_client=utf8');
$conn->query('SET character_set_results=utf8');

// Seta dias da semana e meses do MySQL/MariaDB para "português do Brasil":
$conn->query('SET GLOBAL lc_time_names = pt_BR');
$conn->query('SET lc_time_names = pt_BR');

/************************
 * Funções de uso geral *
 ***********************/

// Sanitiza campos de formulários usando method="POST":
// Outros filtros podem ser implementados nesta função.
function post_clean($post_field, $type = 'string')
{

    // Escolhe o tipo de filtro
    switch ($type):
        case 'string':

            // Sanitiza strings
            $post_value = htmlspecialchars($_POST[$post_field]);
            break;

        case 'email':

            // Sanitiza endereços de e-mail
            $post_value = filter_input(INPUT_POST, $post_field, FILTER_SANITIZE_EMAIL);
            break;

    endswitch;

    // Remove excesso de espaços
    $post_value = trim($post_value);

    // Remove aspas perigosas
    $post_value = stripslashes($post_value);

    // Retorna valor do campo sanitizado
    return $post_value;
}
