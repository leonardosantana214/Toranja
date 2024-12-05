<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Verificar se o usuário está autenticado
if (!isset($_SESSION['email'])) {
    header("location: process.php"); // Altere 'index.php' para a página inicial desejada
    exit();
}

// Inicializar variáveis para armazenar os valores atuais do perfil
$nome_atual = $_SESSION['nome'];
$email_atual = $_SESSION['email'];


// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Processar os dados do formulário
    $nome_novo = $_POST['nome'];
    $email_novo = $_POST['email'];
    $nova_imagem = $_FILES['nova_imagem']['name']; // Adicione um campo de upload de imagem ao formulário
    $nova_senha = $_POST['nova_senha'];

    // Atualizar os dados na sessão
    $_SESSION['nome'] = $nome_novo;
    $_SESSION['email'] = $email_novo;
    $_SESSION['image'] = $nova_imagem; // Atualize a imagem na sessão, se for fornecida

    // Atualizar os dados no banco de dados
    include_once('conexao.php');

    // Verificar se uma nova imagem foi enviada
    if (!empty($nova_imagem)) {
        move_uploaded_file($_FILES['nova_imagem']['tmp_name'], "img/frutas/$nova_imagem");
        $sql_update_imagem = "UPDATE usuarios SET image = '$nova_imagem' WHERE email = '$email_novo'";
        $mysqli->query($sql_update_imagem) or die($mysqli->error);
    }

    // Verificar se uma nova senha foi enviada
    if (!empty($nova_senha)) {
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        $sql_update_senha = "UPDATE usuarios SET senha = '$senha_hash' WHERE email = '$email_novo'";
        $mysqli->query($sql_update_senha) or die($mysqli->error);
    }

    // Redirecionar para a página home
    header("location: meu primeiro site.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./img/Frutas/Logo.ico" type="image/x-icon">
    <title>Editar Perfil - Toranja</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            font-family: 'Pixeboy-z8XGD';
        }

        .body {
            font-family: Arial, sans-serif;
            background-image: url('https://giffiles.alphacoders.com/212/212449.gif');
            background-repeat: no-repeat;
            background-size: cover;
            zoom: 121%;
            padding-top: 120px;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            background-size: contain;
            background-repeat: no-repeat;
            padding: 10px;
            background-color: white;
            border: solid orangered 5px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            text-transform: uppercase;
            color: orangered;
            font-weight: bold;
        }

        input {
            width: 80%;
            padding: 7px;
            margin-bottom: 16px;
            border-top: none;
            border-left: none;
            border-right: none;
            border-bottom: 1px solid gray;
            font-size: medium;
        }

        input:focus-visible {
            border-top: none;
            border-left: none;
            border-right: none;
            border-bottom: 1px solid gray;
        }

        h1 {
            text-align: center;
            color: orangered;
            text-transform: uppercase;
            font-weight: bolder;
        }

        @font-face {
            font-family: 'Pixeboy-z8XGD';
            src: url('css/pixeboy-font/Pixeboy-z8XGD.ttf');
        }

        .btn-Link {
            font-family: 'Pixeboy-z8XGD';
            font-size: large;
            width: 150px;
            height: 50px;
            color: orangered;
            text-transform: uppercase;
            background-color: orange;
            border-top: 1px orange;
            padding: 10px;
            border-left: 1px orange;
            border-color: rgba(255, 166, 0, 0.525);
            border-right: 4px solid orangered;
            border-bottom: 6px solid orangered;
            transition: 1s;
            box-shadow: -3px 0 0 0 black, 3px 0 0 0 black, 0 -3px 0 0 black, 0 3px 0 0 black;
            margin-top: 30px;
        }

        .btn-Link:active {
            background-color: rgb(173, 114, 3);
            transform: translateY(3px);
            width: 148px;
            border-right: 4px orangered;

            border-bottom: 5px solid orangered;
        }

        .btn-Link:hover {
            background-color: rgba(255, 166, 0, 0.604);
            transition: 1s;
            border-top: 1px rgba(255, 68, 0, 0.612);
            border-left: 1px rgba(255, 68, 0, 0.474);
            border-color: rgba(255, 68, 0, 0.582);
            border-right: 6px solid rgba(255, 68, 0, 0.712);
            border-bottom: 8px solid rgba(255, 68, 0, 0.59);
        }

        .fotinha {
            position: absolute;
            bottom: 220px;
            left: 750px;
            right: 0;
        }

        input[type=file] {
            display: none;
        }

        label[for=nova_imagem] {
            display: grid;
            font-family: 'Pixeboy-z8XGD';
            grid-auto-flow: column;
            grid-gap: .5em;
            width: 35%;
            justify-items: center;
            align-content: center;
            text-transform: uppercase;
            background-color: orange;
            border-top: 1px orange;
            padding: 10px;
            border-left: 1px orange;
            border-color: rgba(255, 166, 0, 0.525);
            border-right: 4px solid orangered;
            border-bottom: 6px solid orangered;
            transition: 1s;
            box-shadow: -3px 0 0 0 black, 3px 0 0 0 black, 0 -3px 0 0 black, 0 3px 0 0 black;

            &:hover,
            &:focus,
            &:active {
                background-color: rgba(255, 166, 0, 0.604);
                transition: 1s;
                border-top: 1px rgba(255, 68, 0, 0.612);
                border-left: 1px rgba(255, 68, 0, 0.474);
                border-color: rgba(255, 68, 0, 0.582);
                border-right: 6px solid rgba(255, 68, 0, 0.712);
                border-bottom: 8px solid rgba(255, 68, 0, 0.59);
            }
        }

        #nome:focus {
            border-top: none;
            border-left: none;
            border-right: none;
            border-bottom: 1px solid gray;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            border-top: none;
            border-right: none;
            border-left: none;
            border-bottom: 1px solid #ccc;
            -webkit-transition: 0.5s;
            transition: 0.5s;
            outline: none;

        }

        input:focus {
            border-bottom: 1px solid #555;
            outline: none;
            /* margin: -1px; */
        }
    </style>
</head><div class="body">
    
    <?php
            include('./navbar.php')
            ?>
    
        <aside>
            <img src="<?php echo $nova_imagem ?>" alt="">
        </aside>
        <form method="post" action="editprofile.php" enctype="multipart/form-data">
    
            <h1>Editar Perfil</h1>
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?= $nome_atual ?>" required>
    
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= $email_atual ?>" required>
    
            <label for="nova_imagem">Nova Imagem:</label>
            <input type="file" id="nova_imagem" class="file" name="nova_imagem" required>
    
            <label for="nova_senha">Nova Senha:</label>
            <input type="password" id="nova_senha" name="nova_senha">
    
            <button type="submit" class="btn-Link">Salvar</button>
        </form>
    
        <p><a href="meu primeiro site.php" class="btn-Link">home</a></p>
        <img src="https://i.pinimg.com/originals/af/e2/52/afe2524e0c5047a7024ff3e35cc2b09d.gif" alt="fotinha"
            class="fotinha">
    
</div>

</body>

</html>