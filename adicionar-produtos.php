<?php
include_once('conexao.php');


$usuario = 'root';
$senha = '';
$database = 'toranja';
$host = 'localhost';

$mysqli = new mysqli($host, $usuario, $senha, $database);
if ($mysqli->connect_error) {
    die('Falha ao conectar o banco de dados' . $mysqli->connect_error);
}

class Produto
{
    public $id;
    public $nome;
    public $descricao;
    public $imagens = [];
    public $preco;

    public function __construct($id, $nome, $descricao, $imagens, $preco)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->imagens = $imagens;
        $this->preco = $preco;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Processar os dados do formulário
    $nome = $_POST["nome"];
    $descricao = $_POST["descricao"];
    $imagens = [
        $_POST["imagem"],
        $_POST["imagem1"],
        $_POST["imagem2"],
        $_POST["imagem3"],
        $_POST["imagem4"]
    ];
    $preco = $_POST["preco"];

    // Criar uma nova instância da classe Produto
    $novo_produto = new Produto(null, $nome, $descricao, $imagens, $preco);

    // Preparar a consulta SQL
    $sql = "INSERT INTO produtos (nome, descricao, imagem, imagem1, imagem2, imagem3, imagem4, preco) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);

    // Bind dos parâmetros
    $stmt->bind_param("sssssssd", $novo_produto->nome, $novo_produto->descricao, $novo_produto->imagens[0], $novo_produto->imagens[1], $novo_produto->imagens[2], $novo_produto->imagens[3], $novo_produto->imagens[4], $novo_produto->preco);

    // Executar a consulta
    $stmt->execute();

    // Fechar a conexão e liberar recursos
    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto</title>
</head>
<style>
    * {
        margin: auto;
        font-family: 'Pixeboy-z8XGD';
    }

    label {
        font-family: 'Pixeboy-z8XGD';
    }

    .form {
        text-align: center;
        background-color: rgba(255, 255, 255, 0.562);
        color: black;
        padding: 5px;
    }
</style>

<body>
    <h2>Adicionar Produto</h2>
    <form method="post" class="form" action="<?php echo $_SERVER['PHP_SELF']; ?>">

        <label for="nome">Nome:</label> <br>
        <input type="text" name="nome" required><br>

        <label for="descricao">Descrição:</label> <br>
        <textarea name="descricao" required></textarea><br>

        <label for="imagem">Imagem:</label> <br>
        <input type="text" name="imagem" required><br>

        <label for="imagem1">Imagem1:</label> <br>
        <input type="text" name="imagem1" required><br>

        <label for="imagem2">Imagem2:</label> <br>
        <input type="text" name="imagem2" required><br>

        <label for="imagem3">Imagem3:</label> <br>
        <input type="text" name="imagem3" required><br>

        <label for="imagem4">Imagem4:</label> <br>
        <input type="text" name="imagem4" required><br>

        <label for="preco">Preco:</label> <br>
        <input type="text" name="preco" required><br>

        <input type="submit" class="btn-Link" style="color:green; background-color:orange;" value="Adicionar">
    </form>
</body>

</html>