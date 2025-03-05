<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$usuario = 'root';
$senha = '';
$database = 'toranja';

include_once('conexao.php');


$mysqli = new mysqli($host, $usuario, $senha, $database);
if ($mysqli->connect_error) {
    die('Falha ao conectar ao banco de dados: ' . $mysqli->connect_error);
}

function getProdutoInfo($mysqli, $produto_id)
{
    $stmt = $mysqli->prepare("SELECT nome, preco, imagem FROM produtos WHERE id = ?");
    if (!$stmt) return false;
    $stmt->bind_param('i', $produto_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function adicionarProdutoAoCarrinho($mysqli, $usuario_id, $produto_id, $quantidade)
{
    $produto = getProdutoInfo($mysqli, $produto_id);
    if (!$produto) return 'Produto não encontrado.';

    $stmt = $mysqli->prepare("INSERT INTO carrinho (usuario_id, produto_id, quantidade, preco, produto_nome, produto_imagem) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) return 'Erro na consulta: ' . $mysqli->error;

    $stmt->bind_param('iiidss', $usuario_id, $produto_id, $quantidade, $produto['preco'], $produto['nome'], $produto['imagem']);
    $stmt->execute();
    return $stmt->affected_rows > 0 ? 'Produto adicionado ao carrinho!' : 'Erro ao adicionar produto.';
}

function atualizarQuantidadeProduto($mysqli, $carrinho_id, $nova_quantidade)
{
    $stmt = $mysqli->prepare("UPDATE carrinho SET quantidade = ? WHERE id = ?");
    if (!$stmt) return 'Erro na atualização: ' . $mysqli->error;

    $stmt->bind_param('ii', $nova_quantidade, $carrinho_id);
    $stmt->execute();
    return $stmt->affected_rows > 0 ? true : 'Nenhuma linha afetada ou erro ao atualizar.';
}

function excluirProdutoDoCarrinho($mysqli, $carrinho_id)
{
    $stmt = $mysqli->prepare("DELETE FROM carrinho WHERE id = ?");
    if (!$stmt) return false;

    $stmt->bind_param('i', $carrinho_id);
    return $stmt->execute();
}

function calcularTotalSemDesconto($mysqli, $usuario_id)
{
    $stmt = $mysqli->prepare("SELECT SUM(preco * quantidade) AS total FROM carrinho WHERE usuario_id = ?");
    if (!$stmt) return 0;

    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['total'] ?? 0;
}

function calcularDescontoPercentual($quantidadeTotal)
{
    return $quantidadeTotal >= 9 ? 25 : ($quantidadeTotal >= 6 ? 10 : ($quantidadeTotal >= 3 ? 5 : 0));
}

function calcularDesconto($total, $descontoPercentual)
{
    return $total * ($descontoPercentual / 100);
}

function calcularTotalComDesconto($total, $desconto)
{
    return $total - $desconto;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['adicionar_carrinho'])) {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: LoginToranjão.php");
            exit();
        }
        $produto_id = $_POST['produto_id'] ?? null;
        $quantidade = $_POST['quantidade'] ?? null;
        $usuario_id = $_SESSION['usuario_id'];
        $_SESSION['mensagem_sucesso'] = adicionarProdutoAoCarrinho($mysqli, $usuario_id, $produto_id, $quantidade);
        header("Location: Meu Primeiro Site.php");
        exit();
    }

    if (isset($_POST['carrinho_id'])) {
        $carrinho_id = $_POST['carrinho_id'];
        if (isset($_POST['excluir_produto']) && excluirProdutoDoCarrinho($mysqli, $carrinho_id)) {
            header("Location: Meu Primeiro Site.php");
            exit();
        }
    }

    if (isset($_POST['ajax_request']) && $_POST['ajax_request'] === 'true') {
        if (isset($_POST['excluir_produto'])) {
            echo excluirProdutoDoCarrinho($mysqli, $_POST['carrinho_id']) ? 'Produto removido!' : 'Erro ao remover';
            exit();
        }

        if (isset($_POST['nova_quantidade'])) {
            $carrinho_id = $_POST['carrinho_id'];
            $nova_quantidade = $_POST['nova_quantidade'];

            if (atualizarQuantidadeProduto($mysqli, $carrinho_id, $nova_quantidade) === true) {
                $totalSemDesconto = calcularTotalSemDesconto($mysqli, $_SESSION['usuario_id']);
                $descontoPercentual = calcularDescontoPercentual($totalSemDesconto);
                $desconto = calcularDesconto($totalSemDesconto, $descontoPercentual);
                $totalComDesconto = calcularTotalComDesconto($totalSemDesconto, $desconto);

                echo json_encode([
                    'totalSemDesconto' => $totalSemDesconto,
                    'desconto' => $desconto,
                    'descontoPercentual' => $descontoPercentual,
                    'totalComDesconto' => $totalComDesconto
                ]);
            } else {
                echo 'Erro ao atualizar quantidade';
            }
            exit();
        }
    }
}

if (isset($_SESSION['usuario_id'])) {
    $stmt = $mysqli->prepare("SELECT carrinho.*, produtos.nome, produtos.imagem FROM carrinho JOIN produtos ON carrinho.produto_id = produtos.id WHERE usuario_id = ?");
    if ($stmt) {
        $stmt->bind_param('i', $_SESSION['usuario_id']);
        $stmt->execute();
        $result_verificar_carrinho = $stmt->get_result();
    }
}

$mysqli->close();
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            font-family: 'Pixeboy-z8XGD';
        }

        .quantidade {
            background-color: transparent;
            border: 1px gray solid;
            width: 25px;
            margin: 5px;

        }

        .pp span {
            font-family: 'Pixeboy-z8XGD';
            font-size: xx-large;
        }

        .pp {
            font-family: 'Pixeboy-z8XGD';
            font-size: xx-large;
            background-color: #fff;
            padding: 5px;
            border-radius: 25px;
        }

        .carrinho-principal {
            overflow-y: auto;
            max-block-size: 450px;
        }


        .carrinho-item {
            margin-bottom: 10px;
            background-color: #9696966b;
            padding: 5px;
            border-radius: 20px;
        }

        .carrinho-item img {
            border-radius: 20px;
            padding: 5px;
            filter: grayscale(100%);
            transition: 1s;
        }

        .carrinho-item img:hover {
            filter: grayscale(0%);
            transition: 1s;
        }

        .carrinho {
            align-items: center;
            display: flex;
            justify-content: space-evenly;
            flex-direction: column;
            text-align: center;
        }

        @font-face {
            font-family: 'Pixeboy-z8XGD';
            src: url('css/pixeboy-font/Pixeboy-z8XGD.ttf');
        }

        .carrinho p {
            font-size: 80px;
            font-family: 'Pixeboy-z8XGD';
            text-transform: uppercase;
            position: absolute;
            bottom: -170px;
        }

        .carrinho img {
            width: 300px;
            height: 300px;
            border-bottom: 5px black solid;
            border-width: 90%;
        }

        .carrinho-item {
            border-left: none;
            border-right: none;
            border-top: none;
            border-bottom: solid 0.5px #969696;
            padding: 10px;
            width: 90%;
            display: flex;
            flex-direction: row;
            justify-content: space-evenly;
            transform: translateY(0px);
            transition: 1s;
            align-items: center;

        }

        .carrinho-item:hover {
            transform: translateY(-5px);
            transition: 1s;
        }

        .carrinho-item p {
            font-size: 16px;
            font-family: 'Pixeboy-z8XGD';
            padding: 10px;
            display: flex;
            flex-direction: row;
            justify-content: center;
            text-transform: uppercase;
            color: black;
        }

        .p {
            font-size: 50px;
            color: orangered;
            font-family: 'Pixeboy-z8XGD';
            font-weight: bolder;
            margin: 0;
            padding: 0;
            text-align: center;

        }

        .pp {
            font-family: 'Pixeboy-z8XGD';
            font-size: 20px;
            text-align: center;
            margin-top: 0px;
            background-color: rgba(255, 255, 255, 0.658);
            position: absolute;
            left: 120px;
            top: 575px;
            border: white 2px solid;
            color: black;
        }


        .pp span {
            color: orangered;
            font-size: 20px;
            margin-right: 10px;
        }

        .quantidade-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: -10px;
        }

        .quantidade-controls button {
            padding: 5px 10px;
            font-size: 14px;
            cursor: pointer;
        }

        .excluir-produto:hover {
            cursor: pointer;
            text-decoration: underline;
            color: red;
        }

        button {
            background-color: transparent;
            border: none;
            outline: none;
            color: darkorange;
        }

        .Img-cart {
            margin-left: 75px;

        }

        .animation {
            animation: borda 5s infinite;
        }

        @keyframes borda {
            0% {

                transform: translatey(0px);
            }

            50% {

                transform: translatey(-10px);
            }

            100% {

                transform: translatey(0px);
            }
        }

        .carrinho-all-tools{
            overflow-y: none;
        }
    </style>

</head>


<div class="carrinho-all-tools">
    <p class="p">CARRINHO</p>
    <div class="carrinho-principal">

        <?php
        if (isset($result_verificar_carrinho) && $result_verificar_carrinho && $result_verificar_carrinho->num_rows > 0):
            $totalSemDesconto = 0;
            $quantidadeTotal = 0;
            while ($row = $result_verificar_carrinho->fetch_assoc()):
                $totalSemDesconto += $row['preco'] * $row['quantidade'];
                $quantidadeTotal += $row['quantidade'];
        ?>

                <div class="carrinho-item">
                    <img src="<?= $row['produto_imagem'] ?>" alt="Imagem do Produto" height="90px" width="90px">
                    <p class="nome"><?= $row['produto_nome'] ?></p>
                    <p class="preco" data-preco="<?= $row['preco'] ?>">R$ <?= $row['preco'] ?></p>
                    <div class="quantidade-controls">
                        <input type="number" class="quantidade" max="10" data-carrinho-id="<?= $row['id'] ?>" value="<?= $row['quantidade'] ?>" min="1">
                        <a href="carrinho.php?excluir_produto=<?= $row['id'] ?>" class="excluir-produto">Excluir</a>
                    </div>
                </div>


            <?php
            endwhile;
            // Verifique se a requisição é AJAX e está passando os dados corretamente
            if (isset($_POST['ajax_request']) && $_POST['ajax_request'] == 'true') {
                if (isset($_POST['carrinho_id']) && isset($_POST['nova_quantidade'])) {
                    $carrinho_id = $_POST['carrinho_id'];
                    $nova_quantidade = $_POST['nova_quantidade'];
                    // Atualizar a quantidade no banco de dados
                    $sql_update_quantidade = "UPDATE carrinho SET quantidade = $nova_quantidade WHERE id = $carrinho_id";
                    if ($mysqli->query($sql_update_quantidade)) {
                        // Se a quantidade foi atualizada, calculamos os novos totais
                        $sql_get_carrinho = "SELECT * FROM carrinho WHERE usuario_id = {$_SESSION['usuario_id']}";
                        $result = $mysqli->query($sql_get_carrinho);
                        $totalSemDesconto = 0;
                        $quantidadeTotal = 0;
                        while ($row = $result->fetch_assoc()) {
                            $totalSemDesconto += $row['preco'] * $row['quantidade'];
                            $quantidadeTotal += $row['quantidade'];
                        }
                        // Cálculo de descontos com base na quantidade total
                        $descontoPercentual = 0;
                        if ($quantidadeTotal >= 3 && $quantidadeTotal < 6) {
                            $descontoPercentual = 5;
                        } elseif ($quantidadeTotal >= 6 && $quantidadeTotal < 9) {
                            $descontoPercentual = 10;
                        } elseif ($quantidadeTotal >= 9) {
                            $descontoPercentual = 25;
                        }
                        $desconto = $totalSemDesconto * ($descontoPercentual / 100);
                        $totalComDesconto = $totalSemDesconto - $desconto;
                        // Retornar os totais em formato JSON
                        echo json_encode([
                            'totalSemDesconto' => number_format($totalSemDesconto, 2, ',', '.'),
                            'desconto' => number_format($desconto, 2, ',', '.'),
                            'percentualDesconto' => $descontoPercentual,
                            'totalComDesconto' => number_format($totalComDesconto, 2, ',', '.'),
                        ]);
                    } else {
                        echo json_encode(['error' => 'Erro ao atualizar quantidade']);
                    }
                }
                exit;
            }

            ?>
    </div>
<?php else: ?>
    <div class="animation">
        <div class="p">
            <img src="https://i.pinimg.com/originals/90/9c/b8/909cb8f105fc533e86901a2f0dcf5d7d.gif" Align="center"
                class="Img-cart" alt="fotinha">
            <p style="color: orangered;">Vazio</p>
        </div>
    </div>
<?php endif; ?>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        // Função para calcular os totais com base nas quantidades (localmente no frontend)
        function calcularTotais() {
            var totalSemDesconto = 0;
            var quantidadeTotal = 0;
            $('.carrinho-item').each(function() {
                var preco = parseFloat($(this).find('.preco').data('preco')); // Pegando o preço atual do item
                var quantidade = parseInt($(this).find('.quantidade').val(), 10); // Pegando a quantidade
                totalSemDesconto += preco * quantidade; // Calculando o total sem desconto
                quantidadeTotal += quantidade; // Somando a quantidade total de itens
            });
            // Cálculo do desconto com base na quantidade total de itens
            var descontoPercentual = 0;
            if (quantidadeTotal >= 3 && quantidadeTotal < 6) {
                descontoPercentual = 5;
            } else if (quantidadeTotal >= 6 && quantidadeTotal < 9) {
                descontoPercentual = 10;
            } else if (quantidadeTotal >= 9) {
                descontoPercentual = 25;
            }
            var desconto = totalSemDesconto * (descontoPercentual / 100); // Calculando o valor do desconto
            var totalComDesconto = totalSemDesconto - desconto; // Calculando o total com desconto
            return {
                totalSemDesconto: totalSemDesconto,
                quantidadeTotal: quantidadeTotal,
                descontoPercentual: descontoPercentual,
                desconto: desconto,
                totalComDesconto: totalComDesconto
            };
        }
        // Atualiza os totais na interface do carrinho
        function atualizarTotaisNaInterface(totais) {
            $('.total-sem-desconto').html('R$ ' + totais.totalSemDesconto.toFixed(2).replace('.', ','));
            $('.desconto').html('R$ ' + totais.desconto.toFixed(2).replace('.', ','));
            $('.percentual-desconto').html(totais.descontoPercentual + '%');
            $('.total-com-desconto').html('R$ ' + totais.totalComDesconto.toFixed(2).replace('.', ','));
        }
        // Função que envia os dados para o servidor para atualizar a quantidade no banco de dados
        function atualizarQuantidadeNoServidor(carrinhoId, novaQuantidade) {
            $.ajax({
                type: 'POST',
                url: 'carrinho.php', // A URL que processa o pedido AJAX
                data: {
                    carrinho_id: carrinhoId,
                    nova_quantidade: novaQuantidade,
                    ajax_request: 'true' // Indica que a requisição é AJAX
                },
                success: function(response) {
                    var data = JSON.parse(response); // Espera-se que a resposta seja em formato JSON
                    if (data.success) {
                        var totais = calcularTotais();
                        atualizarTotaisNaInterface(totais); // Atualiza os totais na interface
                    } else {
                        console.error('Erro ao atualizar quantidade:', response);
                    }
                },
                error: function(error) {
                    console.error('Erro ao atualizar quantidade:', error);
                }
            });
        }
        // Lida com os cliques no botão de aumentar ou diminuir a quantidade
        $('.diminuir, .aumentar').on('click', function() {
            var carrinhoId = $(this).data('carrinho-id');
            var inputQuantidade = $(this).siblings('.quantidade');
            var novaQuantidade;
            if ($(this).hasClass('diminuir')) {
                novaQuantidade = Math.max(1, parseInt(inputQuantidade.val(), 10) - 1); // Usando .val() ao invés de .text()
            } else {
                novaQuantidade = parseInt(inputQuantidade.val(), 10) + 1;
            }
            inputQuantidade.val(novaQuantidade); // Atualiza o valor no input
            atualizarQuantidadeNoServidor(carrinhoId, novaQuantidade); // Envia a nova quantidade para o servidor
        });
        // Atualiza a quantidade diretamente quando o valor do input mudar
        $('.quantidade').on('input', function() {
            var carrinhoId = $(this).data('carrinho-id');
            var novaQuantidade = Math.max(1, parseInt($(this).val(), 10)); // Usando .val() ao invés de .text()
            // Recalcula o valor do item e atualiza o valor do preço na interface
            var precoItem = parseFloat($(this).siblings('.preco').data('preco'));
            var novoPreco = precoItem * novaQuantidade;
            $(this).siblings('.preco').html('R$ ' + novoPreco.toFixed(2).replace('.', ','));
            // Envia a nova quantidade para o servidor
            atualizarQuantidadeNoServidor(carrinhoId, novaQuantidade);
            // Recalcula e atualiza os totais
            var totais = calcularTotais();
            atualizarTotaisNaInterface(totais);
        });
        // Chama calcularTotais() inicialmente para exibir os totais
        var initialTotais = calcularTotais();
        atualizarTotaisNaInterface(initialTotais);
    });
</script>

<div class="carrinho-function">
    <p class="pp" style="text-align:center; font-size: 20px;">
        <span style="color: orangered; font-size: 20px;">Total</span><br>
        <s>
            R$<span class="total-sem-desconto" style="font-size: 20px;">
                0,00
            </span>
        </s>
        - Desconto: R$<span class="desconto" style="font-size: 20px;">
            0,00
        </span>
        (<span class="percentual-desconto" style="font-size: 20px;">
            0%
        </span>)
        <br>R$<span class="total-com-desconto" style="font-size: 20px;">
            0,00
        </span>
    </p>
</div>
</div>