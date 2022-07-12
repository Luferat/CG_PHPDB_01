<?php

/**
 * Teste de CRUD
 * 
 *  Estes são exemplos de CRUD usando PHP e MySQL/MariaDB.
 * 
 *  Todas as referências foram tiradas à partir de:
 * 
 *      • https://www.php.net/manual/en/class.mysqli.php
 *      • https://www.w3schools.com/php/php_mysql_intro.asp
 **/

// Setando o PHP para UTF-8 para evitar problemas com caracteres especiais:
header('Content-Type: text/html; charset=utf-8');

/**
 * Define fusohorário do do PHP para não conflitar com o do MySQL:
 * 
 *  Referências: https://www.php.net/manual/en/function.date-default-timezone-set.php
 **/
date_default_timezone_set('America/Sao_Paulo');

/********************************
 * Conexão com o banco de dados *
 ********************************/

/**
 * Variável com dados da conexão:
 * 
 *  Os dados abaixo são do XAMPP. Quando publicar o site,
 *  obtenha esses dados do painel de controles do serviço.
 **/
$db = array(
    "hostname" => "localhost",
    "database" => "cripei",
    "username" => "root",
    "password" => ""
);

/**
 * Conexão com MySQL usando "mysqli" → POO:
 * 
 *  Ordem dos parâmetros → hostname, username, password, database
 * 
 *  Referências: https://www.w3schools.com/php/php_mysql_connect.asp
 **/
$conn = new mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);

/********************
 * Exemplos de CRUD * 
 ********************/

/**
 * CREATE:
 * 
 *  Referências: https://www.w3schools.com/php/php_mysql_insert.asp
 **/

/**
 * A variável $sql contém a consulta a ser executada.
 *  Estamos usando a formatação HereDoc para facilitar a inserção
 *  do código SQL sem restrições, aceitando qualquer tipo de aspas
 *  e ainda permitindo interpolação de variáveis.
 * 
 *  Referências: https://www.php.net/manual/pt_BR/language.types.string.php#language.types.string.syntax.heredoc
 **/
$sql = <<<SQL

INSERT INTO contacts (
    name, email, subject, message
) VALUES (
    'Joca da Silva',
    'joca@silva.com',
    'Teste de contato',
    'Essa é a mensagem do Joca.'
);

SQL;

/**
 * Executa a query:
 * 
 *  Como não se espera um retorno de INSERT, a consulta 
 *  é simplesmente executada, sem uma variável de retorno.
 **/
$conn->query($sql);

/**
 * READ:
 * 
 *  Referências: https://www.w3schools.com/php/php_mysql_select.asp
 **/
$sql = <<<SQL

SELECT * FROM users 
    ORDER BY user_birth DESC;

SQL;

/**
 * Executa a query:
 * 
 *  Como esperamos um retorno de SELECT, armazenamos
 *  este em uma variável, no caso, $res.
 **/
$res = $conn->query($sql);

/**
 * Os dados estão armazenados em $res na forma binária, então,
 * vamos extraí-los usando o método "fetch_assoc()" que retorna
 * um array associativo com os dados do registro atual.
 * 
 * Referências: https://www.php.net/manual/en/mysqli-result.fetch-assoc.php
 **/

// Loop para receber cada registro na variável "$user":
while ($user = $res->fetch_assoc()) {
    print_r($user);
}

/**
 * UPDATE:
 * 
 *  Referências: https://www.w3schools.com/php/php_mysql_update.asp
 **/
$sql = <<<SQL

UPDATE contacts SET 
    name = "Joca Silva",
    email = "joca@silva.com.br"
WHERE
    id = 8;

SQL;

/**
 * Executa a query:
 * 
 *  Como não se espera um retorno de UPDATE, a consulta 
 *  é simplesmente executada, sem uma variável de retorno.
 **/
$conn->query($sql);

/**
 * DELETE:
 * 
 *  Referências: https://www.w3schools.com/php/php_mysql_delete.asp
 */
$sql = <<<SQL

DELETE FROM contacts WHERE id = 8;

SQL;

/**
 * Executa a query:
 * 
 *  Como não se espera um retorno de DELETE, a consulta 
 *  é simplesmente executada, sem uma variável de retorno.
 **/
$conn->query($sql);

/***************
 * IMPORTANTE! *
 ***************/

/**
 * Na maioria dos casos, não se recomenda o uso de DELETE.
 * Ao criar suas tabelas no MySQL, disponibilize sempre um
 * campo "status" que permita marcar o registro como "deleted".
 * Assim, em vez de apagar um registro, apenas o marcamos e
 * impedimos seu uso no aplicativo usando, por exemplo:
 *     ... WHERE status != 'deleted'
 **/
