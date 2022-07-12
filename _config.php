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
 **/
$conn = new mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);

// Seta transações com MySQL/MariaDB para UTF-8:
$conn->query("SET NAMES 'utf8'");
$conn->query('SET character_set_connection=utf8');
$conn->query('SET character_set_client=utf8');
$conn->query('SET character_set_results=utf8');

// Seta dias da semana e meses do MySQL/MariaDB para "português do Brasil":
$conn->query('SET GLOBAL lc_time_names = pt_BR');
$conn->query('SET lc_time_names = pt_BR');