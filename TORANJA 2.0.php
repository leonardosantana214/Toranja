<head>
  <link rel="stylesheet" href="./css/styleRevoluction.css">
</head>
<style>
  body {
    background-color: #000;
  }

  @font-face {
    font-family: 'Pixeboy-z8XGD';
    src: url('css/pixeboy-font/Pixeboy-z8XGD.ttf');
  }

  * {
    margin: auto;
    font-family: 'Pixeboy-z8XGD';
    margin: 0;
    padding: 0;
  }

  .body {
    background-image: url(https://i.redd.it/cxhfjeq9abcb1.gif);
    background-size: cover;
    color: var(--texto-claro);
    transition: 3s;
    zoom: 125%;
  }

  a button {
    background-color: transparent;
  }

  /* Estilos para o modo escuro */
  .body.modo-escuro {
    background-image: url('https://i.redd.it/cxhfjeq9abcb1.gif');
    background-size: cover;
    color: var(--texto-escuro);
    transition: 3s;
  }

  .products {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
  }

  .product {
    width: 100%;
    transition: 1s;
    border: orangered solid 1px;
    background-color: #000000a6;
    padding: 5px;
  }

  /* Adicionando alguns estilos para ajustar o layout em telas menores */
  @media (max-width: 768px) {
    .products {
      grid-template-columns: 1fr;
    }

    .product {
      width: 100%;
    }
  }
</style>

<body class="animate__animated animate__slower animate__fadeIn">
  <div class="body">
    <?php
    include_once('./navbar.php')
    ?>
    <main>
      <h1>Os Melhores Mundos do PalPreto</h1>
      <p>Viva em Mundos Desafiadores e Magicos</p>
      <div class="products">
        <?php
        $usuario = 'root';
        $senha = '';
        $database = 'toranja';
        $host = 'localhost';
        $mysqli = new mysqli($host, $usuario, $senha, $database);
        if ($mysqli->connect_error) {
          die('Falha ao conectar o banco de dados' . $mysqli->connect_error);
        }
        $result = $mysqli->query("SELECT * FROM produtos");
        if ($result) {
          while ($row = $result->fetch_assoc()) {
            echo '<div class="product">';
            echo '<a href="Descricao.php?id=' . $row['id'] . '">';
            echo '<img src="' . $row['imagem'] . '" alt="' . $row['nome'] . '">';
            echo '<h2>' . $row['nome'] . '</h2>';
            echo '<p>' . $row['descricao'] . '</p>';
            echo '<button>Comprar</button>';
            echo '</a>';
            echo '</div>';
          }
          $result->free();
        }
        ?>
      </div>
    </main>
    <br><br><br><br><br>

    <script>
      const modoEscuroBtn = document.getElementById('modoEscuroBtn');
      const body = document.getElementsByClassName('body');
      modoEscuroBtn.addEventListener('click', () => {
        body.classList.toggle('modo-escuro');
      })
    </script>
  </div>
</body>

</html>