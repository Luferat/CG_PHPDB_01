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

/**
 * Define a Regex de validação de senha:
 * 
 * IMPORTANTE!
 * Conforme nossas "políticas de segurança", a senha do usuário deve seguir as
 * seguintes regras:
 *
 *     • Entre 7 e 25 caracteres;
 *     • Pelo menos uma letra minúscula;
 *     • Pelo menos uma letra maiúscula;
 *     • Pelo menos um número.
 *
 * A REGEX abaixo especifica essas regras:
 *
 *     • HTML5 → pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{7,25}$"
 *     • JavaScript → \^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{7,25}$\
 *     • PHP → "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{7,25}$/"
 **/
$rgpass = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{7,25}$/";

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

        // Trata possíveis exceções:
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

// Se o cookie do usuário existe (usuário logado)...
if (isset($_COOKIE["{$site_name}_user"])) :

    // Gera array com dados do usuário, convertendo JSON em array ($user[]):
    $user = json_decode($_COOKIE["{$site_name}_user"], true);

// Se o cookie não existe (ninguém está logado)...
else :

    // Dados do usuário não exitem:
    $user = false;

endif;

/************************
 * Funções de uso geral *
 ***********************/

/**
 * Sanitiza campos de formulários usando method="POST":
 * Outros filtros podem ser implementados nesta função.
 **/ 
function post_clean($post_field, $type = 'string')
{

    // Escolhe o tipo de filtro:
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

// Calcula idade:
function get_age($birthdate)
{
    // inicializa variável com a idade:
    $age = 0;

    // Formata a data corretamente, se necessário:
    $birth_date = date('Y-m-d', strtotime($birthdate));

    // Obtém as partes da data:
    list($byear, $bmonth, $bday) = explode('-', $birth_date);

    // Calcula a idade pelo ano:
    $age = date("Y") - $byear;

    // Ajusta a idade pelo mês:
    if (date("m") < $bmonth) $age -= 1;

    // Ajusta a idade pelo dia:
    elseif ((date("m") == $bmonth) && (date("d") <= $bday)) $age -= 1;

    // Retorna a idade:
    return $age;
}

/**
 * Facilita o debug (Isso não é usado em produção):
 * 
 * Você pode usar tanto "print_r()" quanto "var_dump()". Ambas fazem a mesma 
 * coisa só que tem uma formatação diferente, sendo, portanto, questão de 
 * gosto.
 * 
 *    • A saída de "var_dump()" é mais detalhada, porém, "mais poluída";
 *    • A saída de "print_r()" é mais simples e menos detalhada.
 * 
 * Para escolher, mantenha descomentada apenas a função que quer usar e 
 * comente a outra.
 **/
function debug($element, $pre = true, $stop = true)
{
    if ($pre) echo '<pre>';
    print_r($element);
    // var_dump($element);
    if ($pre) echo '</pre>';
    if ($stop) exit;
}
