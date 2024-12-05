<?php
session_start();

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

	<title>Toranja - Jogos</title>

	<link rel="stylesheet" href="./css/styleJogos.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
	<style>
		@font-face {
			font-family: 'Pixeboy-z8XGD';
			src: url('css/pixeboy-font/Pixeboy-z8XGD.ttf');
		}

		.body {
			background: url(https://64.media.tumblr.com/668d105fc2701311bfcef33d2771a40e/370b02f259511df9-d6/s1280x1920/b22c8e6e834c0722cf2951aedfcb90bddfef8f87.gif);
			/* padding: 10px; */
			display: flex;
			flex-wrap: wrap;
			justify-content: center;
			align-items: center;
			padding: 20px;
		}

		* {
			margin: auto;
			font-family: 'Pixeboy-z8XGD';
		}




		/* Estilos para o main */
		main {
			display: flex;
			flex-wrap: wrap;
			justify-content: center;
			align-items: center;
			padding: 20px;
		}

		/* Estilos para as divs dos jogos */
		.jogo {
			width: 300px;
			height: 300px;
			margin: 20px;
			border-radius: 10px;
			overflow: hidden;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
			transition: transform 0.3s ease-in-out;
		}

		/* Estilos para o hover das divs dos jogos */
		.jogo:hover {
			transform: scale(1.05);
		}

		/* Estilos para as imagens dos jogos */
		.jogo img {
			width: 100%;
			height: 100%;
			object-fit: cover;
		}
	</style>
</head>

<body>
	<div class="body animate__animated animate__slower animate__fadeIn">
		<?php
		include_once('./navbar.php')
		?>
		<p>
			Aqui e a parte de jogos, se voce quiser aprender a desenvolver se divertindo no <br> <span>toranjinha
				games</span>
		</p> <br>
		<main>
			<div class="jogo">
				<a href="https://flexboxfroggy.com/"><img src="assets/3318923-flexbox-froggy.png"></a>
				<h2>FlexBox Froggy</h2>
				<p>Descrição do Jogo 1</p>
			</div>
			<div class="jogo">
				<a href="Hardware/Atividade/Binary Game.html"><img src="assets/Captura de tela 2023-04-28 134515.png"></a>
				<h2>Jogo 2</h2>
				<p>Descrição do Jogo 2</p>
			</div>
			<div class="jogo">
				<a href="https://cssbattle.dev/"><img src="assets/BIu19jPP_400x400.png"></a>
				<h2>Jogo 3</h2>
				<p>Descrição do Jogo 3</p>
			</div>
			<div class="jogo">
				<a href="https://flukeout.github.io/"><img src="assets/maxresdefault.jpg"></a>
				<h2>Jogo 4</h2>
				<p>Descrição do Jogo 4</p>
			</div>
		</main>
		<!-- <script>
			const modoEscuroBtn = document.getElementById('modoEscuroBtn');
			const body = document.body;
			modoEscuroBtn.addEventListener('click', () => {
				body.classList.toggle('modo-escuro');
			});
		</script> -->
	</div>
</body>

</html>