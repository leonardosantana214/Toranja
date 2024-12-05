<?php

$usuario = 'root';
$senha = '';
$nome = '';
$email = '';
$database = 'toranja';
$host = 'localhost';

$mysqli = new mysqli($host, $usuario, $senha, $database);
if ($mysqli->connect_error) {
    die('Falha ao conectar o banco de dados' . $mysqli->connect_error);
}
