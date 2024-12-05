<?php
session_start();

// Destruir todas as variáveis de sessão
session_destroy();

// Redirecionar para a tela inicial ou outra página desejada
header("location: meu primeiro site.php");
exit();
?>