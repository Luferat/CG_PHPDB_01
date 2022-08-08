<?php

$validate = 1; // Dias

setcookie(
    'cookie_test',
    'Valor do cookie', time() + 60 * 60 * 24 * $validate,
    '/',
    'localhost',
    true
);
?>
Isso é um teste