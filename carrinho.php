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
    $query_produto = "SELECT nome, preco, imagem FROM produtos WHERE id = ?";
    $stmt_produto = $mysqli->prepare($query_produto);

    if (!$stmt_produto) {
        return false;
    }

    $stmt_produto->bind_param('i', $produto_id);

    if (!$stmt_produto->execute()) {
        return false;
    }

    $result_produto = $stmt_produto->get_result();

    if ($result_produto->num_rows > 0) {
        return $result_produto->fetch_assoc();
    }

    return false;
}

function adicionarProdutoAoCarrinho($mysqli, $usuario_id, $produto_id, $quantidade)
{
    $produto = getProdutoInfo($mysqli, $produto_id);

    if (!$produto) {
        return 'Nenhum produto encontrado com o ID especificado.';
    }

    $sql = "INSERT INTO carrinho (usuario_id, produto_id, quantidade, preco, produto_nome, produto_imagem) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_carrinho = $mysqli->prepare($sql);

    if (!$stmt_carrinho) {
        return 'Erro na preparação da consulta de carrinho: ' . $mysqli->error;
    }

    $stmt_carrinho->bind_param('iiidss', $usuario_id, $produto_id, $quantidade, $produto['preco'], $produto['nome'], $produto['imagem']);

    if ($stmt_carrinho->execute() && $stmt_carrinho->affected_rows > 0) {
        return 'Produto adicionado ao carrinho com sucesso!';
    } else {
        return 'Erro ao adicionar produto ao carrinho: ' . $stmt_carrinho->error;
    }
}

function atualizarQuantidadeProduto($mysqli, $carrinho_id, $nova_quantidade)
{
    $sql = "UPDATE carrinho SET quantidade = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        return 'Erro na preparação da atualização de quantidade: ' . $mysqli->error;
    }

    $stmt->bind_param('ii', $nova_quantidade, $carrinho_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            return true; // Retorna verdadeiro se a atualização foi bem-sucedida
        } else {
            return 'Nenhuma linha afetada. Verifique se o ID do carrinho é válido.';
        }
    } else {
        return 'Erro ao executar a atualização: ' . $stmt->error;
    }
}

function excluirProdutoDoCarrinho($mysqli, $carrinho_id)
{
    $sql = "DELETE FROM carrinho WHERE id = ?";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param('i', $carrinho_id);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}
// Função para calcular o total sem desconto
function calcularTotalSemDesconto($mysqli, $usuario_id)
{
    $sql = "SELECT SUM(preco * quantidade) AS totalSemDesconto FROM carrinho WHERE usuario_id = ?";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('i', $usuario_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            return $row['totalSemDesconto'];
        }
    }

    return 0;
}

// Função para calcular o desconto percentual com base na quantidade total
function calcularDescontoPercentual($quantidadeTotal)
{
    if ($quantidadeTotal >= 3 && $quantidadeTotal < 6) {
        return 5;
    } elseif ($quantidadeTotal >= 6 && $quantidadeTotal < 9) {
        return 10;
    } elseif ($quantidadeTotal >= 9) {
        return 25;
    }

    return 0;
}

// Função para calcular o desconto com base no total sem desconto e no desconto percentual
function calcularDesconto($totalSemDesconto, $descontoPercentual)
{
    return $totalSemDesconto * ($descontoPercentual / 100);
}

// Função para calcular o total com desconto
function calcularTotalComDesconto($totalSemDesconto, $desconto)
{
    return $totalSemDesconto - $desconto;
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

        $resultado = adicionarProdutoAoCarrinho($mysqli, $usuario_id, $produto_id, $quantidade);
        $_SESSION['mensagem_sucesso'] = $resultado;

        header("Location: Meu Primeiro Site.php");
        exit();
    }
}
if (isset($_POST['carrinho_id'])) {
    $carrinho_id = $_POST['carrinho_id'];

    if (isset($_POST['excluir_produto'])) {
        $resultado = excluirProdutoDoCarrinho($mysqli, $carrinho_id);

        if ($resultado) {
            // Se a exclusão for bem-sucedida, redirecione ou exiba uma mensagem, conforme necessário
            header("Location: Meu Primeiro Site.php");
            exit();
        } else {
            // Se a exclusão falhar, você pode exibir uma mensagem de erro ou fazer outras ações
            echo 'Erro ao remover o produto do carrinho';
            exit();
        }
    }

}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ajax_request']) && $_POST['ajax_request'] === 'true') {
        // Verificar se é uma requisição para excluir o produto do carrinho
        if (isset($_POST['excluir_produto'])) {
            $carrinho_id = $_POST['carrinho_id'];
            $resultado = excluirProdutoDoCarrinho($mysqli, $carrinho_id);

            if ($resultado) {
                echo 'Produto removido do carrinho com sucesso!';
            } else {
                echo 'Erro ao remover o produto do carrinho';
            }
            exit();
        }

        // Verificar se é uma requisição para atualizar a quantidade do produto no carrinho
        if (isset($_POST['nova_quantidade'])) {
            $carrinho_id = $_POST['carrinho_id'];
            $nova_quantidade = $_POST['nova_quantidade'];

            $resultado = atualizarQuantidadeProduto($mysqli, $carrinho_id, $nova_quantidade);

            if ($resultado === true) {
                // Se a atualização foi bem-sucedida, buscar os novos totais
                $novoTotalSemDesconto = calcularTotalSemDesconto($mysqli, $_SESSION['usuario_id']);
                $descontoPercentual = calcularDescontoPercentual($novoTotalSemDesconto);
                $novoDesconto = calcularDesconto($novoTotalSemDesconto, $descontoPercentual);
                $novoTotalComDesconto = calcularTotalComDesconto($novoTotalSemDesconto, $novoDesconto);

                // Retornar os novos totais
                echo json_encode(
                    array(
                        'totalSemDesconto' => $novoTotalSemDesconto,
                        'desconto' => $novoDesconto,
                        'descontoPercentual' => $descontoPercentual,
                        'totalComDesconto' => $novoTotalComDesconto
                    )
                );
            } else {
                echo 'Erro ao atualizar a quantidade do produto no carrinho: ' . $resultado;
            }
            exit();
        }
    }

}


if (isset($_SESSION['usuario_id'])) {
    $sql = "SELECT carrinho.*, produtos.nome, produtos.imagem FROM carrinho 
            JOIN produtos ON carrinho.produto_id = produtos.id 
            WHERE usuario_id = ?";
    $stmt = $mysqli->prepare($sql);

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
            max-height: 450px;
            overflow-y: auto;
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
            position: absolute;
            justify-content: space-around;
            flex-direction: column;
            left: 20px;
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
            justify-content: center;
            transform: translateY(0px);
            transition: 1s;
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

        .excluir-produto {
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
    </style>

</head>

<body>
    <p class="p">CARRINHO</p>

    <div class="carrinho-principal">
        <?php
        if (isset($result_verificar_carrinho) && $result_verificar_carrinho && $result_verificar_carrinho->num_rows > 0):
            $totalSemDesconto = 0;
            $quantidadeTotal = 0; // Adiciona uma variável para armazenar a quantidade total
        
            while ($row = $result_verificar_carrinho->fetch_assoc()):
                $totalSemDesconto += $row['preco'] * $row['quantidade'];
                $quantidadeTotal += $row['quantidade']; // Incrementa a quantidade total
                ?>
                <div class="carrinho-item">
                    <img src="<?= $row['imagem'] ?>" alt="Imagem do Produto" height="90px" width="90px">
                    <p class="nome">
                        <?= $row['nome'] ?>
                    </p>
                    <p class="preco" data-preco="<?= $row['preco'] ?>">R$
                        <?= $row['preco'] ?>
                    </p>

                    <div class="quantidade-controls">
                        <button class="diminuir" data-carrinho-id="<?= $row['id'] ?>">-</button>
                        <span class="quantidade" contenteditable="true" style="color:black;"
                            data-carrinho-id="<?= $row['id'] ?>">
                            <?= $row['quantidade'] ?>
                        </span>
                        <button class="aumentar" data-carrinho-id="<?= $row['id'] ?>">+</button>
                        <form method="post" action="carrinho.php">
                            <input type="hidden" name="carrinho_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="excluir_produto">Excluir</button>
                        </form>
                    </div>
                </div>


                <?php
            endwhile;

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
            ?>
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
        $(document).ready(function () {
            function calcularTotais() {
                var totalSemDesconto = 0;
                var quantidadeTotal = 0;

                $('.carrinho-item').each(function () {
                    var preco = parseFloat($(this).find('.preco').data('preco'));
                    var quantidade = parseInt($(this).find('.quantidade').text(), 10);

                    totalSemDesconto += preco * quantidade;
                    quantidadeTotal += quantidade;
                });

                var descontoPercentual = 0;
                if (quantidadeTotal >= 3 && quantidadeTotal < 6) {
                    descontoPercentual = 5;
                } else if (quantidadeTotal >= 6 && quantidadeTotal < 9) {
                    descontoPercentual = 10;
                } else if (quantidadeTotal >= 9) {
                    descontoPercentual = 25;
                }

                var desconto = totalSemDesconto * (descontoPercentual / 100);
                var totalComDesconto = totalSemDesconto - desconto;

                return {
                    totalSemDesconto: totalSemDesconto,
                    quantidadeTotal: quantidadeTotal,
                    descontoPercentual: descontoPercentual,
                    desconto: desconto,
                    totalComDesconto: totalComDesconto
                };
            }

            function atualizarTotaisNaInterface(totais) {
                $('.total-sem-desconto').html(totais.totalSemDesconto.toFixed(2).replace('.', ','));
                $('.desconto').html(totais.desconto.toFixed(2).replace('.', ','));
                $('.percentual-desconto').html(totais.descontoPercentual + '%');
                $('.total-com-desconto').html(totais.totalComDesconto.toFixed(2).replace('.', ','));
            }

            function atualizarQuantidadeNoServidor(carrinhoId, novaQuantidade) {
                $.ajax({
                    type: 'POST',
                    url: 'carrinho.php',
                    data: {
                        carrinho_id: carrinhoId,
                        nova_quantidade: novaQuantidade,
                        ajax_request: 'true'
                    },
                    success: function (data) {
                        if (data && data.success) {
                            var totais = calcularTotais();
                            atualizarTotaisNaInterface(totais);
                        } else {
                            console.error('Erro ao atualizar quantidade:', data.message);
                        }
                    },
                    error: function (error) {
                        console.error('Erro ao atualizar quantidade:', error);
                    }
                });
            }

            $('.diminuir, .aumentar').on('click', function () {
                var carrinhoId = $(this).data('carrinho-id');
                var spanQuantidade = $(this).siblings('.quantidade');
                var novaQuantidade;

                if ($(this).hasClass('diminuir')) {
                    novaQuantidade = Math.max(1, parseInt(spanQuantidade.text(), 10) - 1);
                } else {
                    novaQuantidade = parseInt(spanQuantidade.text(), 10) + 1;
                }

                spanQuantidade.text(novaQuantidade);
                atualizarQuantidadeNoServidor(carrinhoId, novaQuantidade);
            });

            // Adicionar ouvinte de eventos input ao campo de quantidade
            $('.quantidade').on('input', function () {
                var carrinhoId = $(this).data('carrinho-id');
                var novaQuantidade = Math.max(1, parseInt($(this).text(), 10)) || 1;
                atualizarQuantidadeNoServidor(carrinhoId, novaQuantidade);

                // Calcular e atualizar os totais imediatamente
                var totais = calcularTotais();
                atualizarTotaisNaInterface(totais);
            });

            $('.quantidade').on('blur', function () {
                var carrinhoId = $(this).data('carrinho-id');
                var novaQuantidade = Math.max(1, parseInt($(this).text(), 10)) || 1;
                atualizarQuantidadeNoServidor(carrinhoId, novaQuantidade);
            });

            // Chamar calcularTotais() inicialmente para exibir os totais iniciais
            var initialTotais = calcularTotais();
            atualizarTotaisNaInterface(initialTotais);
        });
    </script>
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


</body>

</html>