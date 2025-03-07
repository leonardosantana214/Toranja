<?php
session_start();
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
                $_SESSION['usuario_id'] = $usuario['id']; // Presumindo que 'id' é a chave primária do usuário
                // Redirecionar para a página home
                header("location: meu primeiro site.php");
                exit();
            } else {
                // Defina a mensagem de erro para senha incorreta
                $_SESSION['error_message'] = "Senha incorreta";
                header('Location: LoginToranjão.php');
                exit();
            }
        } else {
            // Defina a mensagem de erro para email não encontrado
            $_SESSION['error_message'] = "E-mail não Existente";
            header('Location: LoginToranjão.php');
            exit();
        }
    }
}
// Verifique se existe uma mensagem de erro
if (isset($_SESSION['error_message'])) {
    echo '<div id="error-div" class="div-error" style="color: red;">' . $_SESSION['error_message'] . ' <button class="close-btn" onclick="closeDiv()">X</button></div>';
    // Limpe a mensagem de erro da sessão após exibi-la
    unset($_SESSION['error_message']);
}

// Operações de Cadastro
if (isset($_POST['nomeCadastro']) && isset($_POST['emailCadastro']) && isset($_POST['senhaCadastro']) && isset($_POST['CPF'])) {
    $nomeCadastro = $mysqli->real_escape_string($_POST["nomeCadastro"]);
    $emailCadastro = $mysqli->real_escape_string($_POST["emailCadastro"]);
    $senhaCadastro = $mysqli->real_escape_string($_POST["senhaCadastro"]);
    $cpfCadastro = $mysqli->real_escape_string($_POST["CPF"]);

    // Verifica se nenhum campo está vazio
    if (!empty($nomeCadastro) && !empty($emailCadastro) && !empty($senhaCadastro) && !empty($cpfCadastro)) {
        $senhaCadastro = password_hash($senhaCadastro, PASSWORD_BCRYPT);

        // Verifica se o e-mail já está cadastrado
        $sqlVerificaEmail = "SELECT * FROM usuarios WHERE email = '$emailCadastro'";
        $result = $mysqli->query($sqlVerificaEmail);
        if ($result->num_rows > 0) {
            echo '<div id="error-div" class="div-error" style="color: red;">E-mail Já cadastrado <button class="close-btn" onclick="closeDiv()">x</button></div>';
        } else {
            // Inserção no banco de dados
            $sqlCadastro = "INSERT INTO usuarios (nome, email, senha, cpf) VALUES ('$nomeCadastro', '$emailCadastro', '$senhaCadastro', '$cpfCadastro')";
            if ($mysqli->query($sqlCadastro)) {
                echo '<div id="success-div" class="div-success" style="color: Green;">Cadastrado com Sucesso <button class="close-btn" onclick="closeDiv()">x</button></div>';
                // Redirecionar para a página de sucesso após um curto atraso
                echo '<meta http-equiv="refresh" content="2;url=LoginToranjão.php">';
            } else {
                echo '<div id="error-div" class="div-error" style="color: red;">Erro Ao Cadastrar: ' . $mysqli->error . ' <button class="close-btn" onclick="closeDiv()">x</button></div>';
            }
        }
    } else {
        echo '<div id="error-div" class="div-error" style="color: red;">Preencha Tudo -_- <button class="close-btn" onclick="closeDiv()">x</button></div>';
    }
}

?>
<!DOCTYPE html>
<html lang="pt-Br">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/styleLogin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <title>Login - Toranjinha</title>
    <script src="./js/LoginToranja.js"></script>
</head>
<?php if (isset($_GET["erro"])): ?>
    <p id="error-message" style="color: white;"></p>
<?php endif; ?>

<body class="body">

    <div class="container">
        <div class="card-container">
            <div class="card">
                <!-- LOGIN -->
                <div class="login-form">
                    <div class="header">Login</div>
                    <div class="content">

                        <form action="process.php" method="post">
                            <!-- Campos do formulário de login -->
                            <label for="email">E-Mail</label>
                            <input type="email" name="email" id="email" placeholder="Coloque o seu E-mail" class="input">
                            <br><br>
                            <label for="senha">Senha</label>
                            <input type="password" name="senha" id="senha" class="input" placeholder="Coloque sua Senha">
                            <br><br>
                            <button class="custom-btn btn-6"><span>LOGIN</span></button>
                        </form>
                    </div>
                    <button class="btn btn-rotate" id="btn-login">Faça o cadastro</button>
                </div>
                <!-- CADASTRO -->
                <div class="signup-form" style="height: 500px;">
                    <div class="header">CADASTRO</div>
                    <div class="content">
                        <!-- Formulário de cadastro -->
                        <form action="LoginToranjão.php" method="post">
                            <br><br><br>
                            <!-- Campos do formulário de cadastro -->
                            <label for="nome">Nome</label>
                            <input type="text" name="nomeCadastro" placeholder="Coloque seu nome" class="input"><br>
                            <br>
                            <label for="emailCadastro">E-Mail</label>
                            <input type="email" name="emailCadastro" id="emailCadastro" placeholder="Coloque o seu E-mail" class="input"><br>
                            <br><br>
                            <label for="senhaCadastro">Senha</label>
                            <input type="password" name="senhaCadastro" id="senhaCadastro" class="input" placeholder="Coloque sua Senha" minlength="4"><br>

                            <br>
                            &nbsp;&nbsp;&nbsp;&nbsp;<label for="CPF">CPF</label>
                            <input oninput="mascara(this)" type="text" name="CPF" placeholder="xxx.xxx.xxx-xx" class="input CPF" maxlength="11" minlength="11" id="CPF"> <br>
                            <button type="submit" class="btn btn-rotate btn-7">Salvar</button>
                        </form>
                    </div>
                    <button class="btn btn-rotate btn-7" id="btn-signup">Faça Login</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function closeDiv() {
            var errorDiv = document.getElementById('error-div');
            var successDiv = document.getElementById('success-div');
            if (errorDiv) {
                errorDiv.style.display = 'none';
            }
            if (successDiv) {
                successDiv.style.display = 'none';
            }
        }
    </script>
</body>

</html>