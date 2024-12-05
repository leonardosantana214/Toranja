<?php
// Verificar se a sessão já foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Verificar se o usuário está autenticado
if (!isset($_SESSION['email'])) {
    header("location: process.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toranja</title>
    <link rel="stylesheet" href="css/styleNavBar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="./img/Frutas/Logo.ico" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

</head>

<body class="animate__animated animate__slower animate__fadeIn">
    <header>
        <nav>
            <ul>

                <li><a href="./meu primeiro site.php" class="nav-link btn-5">Home</a></li>
                <li><a href="./TORANJA 2.0.php" class="nav-link btn-5">Paisagens</a></li>
                <li><a href="./meu primeiro site.php"><img src="https://i.pinimg.com/originals/9c/d8/3b/9cd83b85b453a572ea1f0dbc9b15d137.gif" class="Logo-Nav" alt="Fotinha teste"></a></li>
                <li><a href="./TELA DE JOGOS.php" class="nav-link btn-5">Jogos</a></li>
                <li>
                    <?php
                    if (isset($_SESSION['nome']) && !empty($_SESSION['nome'])) {
                        echo '<a href="./editprofile.php" class="Profile-Perfil"><img src="./img/Frutas/' . $_SESSION['image'] . '" alt="foto do usuario"> ' . $_SESSION['nome'] . '</a>';
                    } else {
                        echo '<a href="LoginToranjão.php" class="nav-link">Login</a>';
                    }
                    ?>
                </li>
            </ul>
        </nav>
    </header>
    <script>
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 80) { // Ajuste o valor conforme necessário
                header.classList.add('header-reduced');
            } else {
                header.classList.remove('header-reduced');
            }
        });
    </script>