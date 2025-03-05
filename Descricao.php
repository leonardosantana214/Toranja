    <?php
    session_start();
    ob_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include_once('conexao.php'); // Inclua a conexão com o banco de dados

    $usuario = 'root';
    $senha = '';
    $database = 'toranja';
    $host = 'localhost';

    $mysqli = new mysqli($host, $usuario, $senha, $database);
    if ($mysqli->connect_error) {
        die('Falha ao conectar o banco de dados: ' . $mysqli->connect_error);
    }

    if (isset($_POST['adicionar_carrinho'])) {
        // Verificar se a sessão está definida
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: LoginToranjão.php");
            exit();
        }

        $produto_id = $_POST['produto_id'];
        $quantidade = $_POST['quantidade'];
        $usuario_id = $_SESSION['usuario_id'];

        // Buscar informações do produto no banco de dados
        $query_produto = "SELECT * FROM produtos WHERE id = ?";
        $stmt_produto = $mysqli->prepare($query_produto);
        $stmt_produto->bind_param("i", $produto_id);
        $stmt_produto->execute();
        $result_produto = $stmt_produto->get_result();

        if ($result_produto->num_rows > 0) {
            $produto = $result_produto->fetch_assoc();
            $preco = $produto['preco'];
            // Inserir no carrinho
            $sql = "INSERT INTO carrinho (usuario_id, produto_id, produto_nome, produto_imagem, quantidade, preco) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("iissid", $usuario_id, $produto_id, $produto['nome'], $produto['imagem'], $quantidade, $preco);
            $stmt->execute();
        }
    }

    // Consultar 3 produtos aleatórios da loja
    $sqlRandomProducts = "SELECT id, nome, imagem1, preco FROM produtos ORDER BY RAND() LIMIT 3";
    $resultRandomProducts = $mysqli->query($sqlRandomProducts);

    // Array para armazenar os resultados dos produtos aleatórios
    $randomProducts = array();

    while ($row = $resultRandomProducts->fetch_assoc()) {
        $randomProducts[] = $row;
    }

    // Verificar se o ID do produto está definido na URL
    $idProduto = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Consultar os detalhes do produto específico
    if ($idProduto > 0) {
        $sqlProdutoEspecifico = "SELECT * FROM produtos WHERE id = $idProduto";
        $resultProdutoEspecifico = $mysqli->query($sqlProdutoEspecifico);

        // Array para armazenar os resultados do produto específico
        $productData = array();

        if ($resultProdutoEspecifico) {
            // Verificar se a consulta foi bem-sucedida
            if ($resultProdutoEspecifico->num_rows > 0) {
                // Armazenar os resultados em um array associativo
                $productData = $resultProdutoEspecifico->fetch_assoc();
            }
        } else {
            // Adicionar uma mensagem de erro ao log ou exibir na tela
            echo "Erro na consulta SQL: " . $mysqli->error;
        }
    } else {
        echo "ID do produto não especificado.";
    }

    // Fechar a conexão com o banco de dados
    $mysqli->close();
    ?>

    <head>

        <title>
            <?php echo $productData['nome'] ?> - Toranjinha
        </title>
        <link rel="stylesheet" href="./css/styleDescription.css">

    </head>

    <body>


        <?php
        include_once('./navbar.php');
        ?>
        <main>
            <?php
            if (!empty($productData)) {
                // Se $productData é um array associativo

                // echo '<br>';
                echo '<section>';
                echo '<div id="Imgbig">';
                // Exibir as imagens dos produtos
                echo '<img src="' . $productData['imagem1'] . '" style="width: 400px; heigth: 400px;" alt="Imagem do Produto" class="product-images" id="imagem1">';
                echo '</div>';
                echo '<div id="ImgSmall">';
                echo '<img src="' . $productData['imagem2'] . '" alt="1 Produto" class="imgSmall" onclick="trocarComImagemPrincipal(this)">';
                echo '<img src="' . $productData['imagem3'] . '" alt="Imagem do Produto" class="imgSmall" onclick="trocarComImagemPrincipal(this)">';
                echo '<img src="' . $productData['imagem4'] . '" alt="Imagem do Produto" class="imgSmall" onclick="trocarComImagemPrincipal(this)"> ';
                echo '</div>';
                echo '<div class="product-details">';
                echo '<p class="product-description">' . $productData['nome'] . '</p>';
                echo '<p class="product-price">R$ ' . number_format($productData['preco'], 2, ',', '.') . '</p>';
                echo '<form method="post" action="carrinho.php">';
                echo '<div id="quantidade-input">';
                echo '<input type="hidden" name="produto_id" value="' . $productData['id'] . '">';
                echo '<label class="Quant-Label" for="quantidade">Quantidade:</label>';
                echo '<input type="number" id="quantidade" name="quantidade" min="1" max="10" value="1">';
                echo '<input type="submit" name="adicionar_carrinho" class="btn-Link" value="Carrinho">';
                echo '</div>';
                echo '</form>';
                echo '<div id="freteInput">';
                echo '<form action="#">';
                echo '<label class="Frete-Label" for="cep">CEP de entrega:</label>';
                echo '<input type="text" id="cep" name="cep" minlength="8" maxlength="8" placeholder="Digite seu CEP" required>';
                echo '<button onclick="calcularFrete()" type="button" class="btn-Link btn-Calcular" style="padding:0; margin:0;">Calcular Frete</button>';
                echo '<p class="value"><div id="resultado"></div></p>';
                echo '</form>';
                echo '</div>';
                echo '<h2 style="color:orangered;">Descricao</h2>';
                echo '<p style="color:white;">' . $productData['descricao'] . '</p>';
                echo '</div>';
                echo '<br><br><br>';
                // echo '<hr style=" color: #33333360;">';
            } else {
                echo "Nenhum Produto Encontrado!";
            }

            echo '<p class="product-description" style="position:relative; top:-400px;">Produtos Recomendados</p>';
            if (!empty($randomProducts)) {
                echo '<div class="random-products">';
                foreach ($randomProducts as $randomProduct) {
                    echo '<a href="descricao.php?id=' . $randomProduct['id'] . '" class="random-product">';
                    echo '<img src="' . $randomProduct['imagem1'] . '" style="width: 100px; height: 100px;" alt="Imagem do Produto" id="imagem1">';
                    echo '<p>' . $randomProduct['nome'] . '</p>';
                    echo '<s>frete gratis</s>';
                    echo '<p>R$ ' . number_format($randomProduct['preco'], 2, ',', '.') . '</p>';
                    echo '</a>';
                }
                echo '</div>';
            } else {
                echo '<p>Nenhum produto aleatório encontrado.</p>';
            }
            echo '</section>';
            ?>

        </main>
    </body>
    <script src="./js/JavadaDescrição.js"></script>

    </html>