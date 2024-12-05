<?php
session_start();
ob_start(); // Adicionado para forçar o buffer de saída
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Operações de Login
    if (isset($_POST['email']) && isset($_POST['senha'])) {
        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $mysqli->real_escape_string($_POST['senha']);

        $sql_code = "SELECT * FROM usuarios WHERE email = '$email'";
        $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código: " . $mysqli->error);
        $quantidade = $sql_query->num_rows;

        if ($quantidade == 1) {
            $usuario = $sql_query->fetch_assoc();
        
            if (password_verify($senha, $usuario['senha'])) {
                // Iniciar a sessão e armazenar informações relevantes
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['image'] = $usuario['image'];
        
                // Redirecionar para a página home
                header("location: meu primeiro site.php");
                exit();
            } else {
                // Defina a mensagem de erro para senha incorreta
                $_SESSION['error_message'] = "Senha incorreta -_-";
                header('Location: LoginToranjão.php');
                exit();
            }
        } else {
            // Defina a mensagem de erro para email não encontrado
            $_SESSION['error_message'] = "E-mail não encontrado";
            header('Location: LoginToranjão.php');
            exit();
        }
        
    }
}

$type = filter_input(INPUT_POST, "type");

if ($type == "logout") {
    $_SESSION['email'] = "";
    $_SESSION['nome'] = "";
    $_SESSION['usuario_id'] = "";
    // Redirecionar para a página inicial
    header("location: meu primeiro site.php");
    exit();
} else {
    header("location: LoginToranjão.php");
}

ob_end_flush(); // Finaliza o buffer de saída