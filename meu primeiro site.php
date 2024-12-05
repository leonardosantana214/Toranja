<?php

session_start();
// Verifica se o usuário tem o modo escuro ativado no sistema operacional
function isDarkMode()
{
	return isset($_SERVER['HTTP_SEC_FETCH_MODE']) && $_SERVER['HTTP_SEC_FETCH_MODE'] === 'navigate' &&
		isset($_SERVER['HTTP_SEC_FETCH_SITE']) && $_SERVER['HTTP_SEC_FETCH_SITE'] === 'none' &&
		isset($_SERVER['HTTP_SEC_FETCH_USER']) && $_SERVER['HTTP_SEC_FETCH_USER'] === '?1';
}

// Adiciona uma classe ao body se o modo escuro estiver ativado
$darkModeClass = isDarkMode() ? 'modo-escuro' : '';
// Verificar se o usuário está autenticado
if (!isset($_SESSION['email'])) {
	header("location: process.php");
	exit();
}

$username_toshow = $_SESSION['nome'];





?>

<!DOCTYPE html>

<html>

<head>
	<title>Toranjinha - Site</title>

	<meta charset="utf-8" />
	<meta name="description" content="A parallax scrolling experiment using jQuery" />

	<link rel="stylesheet" media="all" href="css/main.css" />



	<link rel="shortcut icon" href="./img/Frutas/Logo.png" type="image/x-icon">

	<script src="js/modernizr.custom.37797.js"></script>
	<!-- Grab Google CDN's jQuery. fall back to local if necessary -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
	<script>
		!window.jQuery && document.write('<script src="/js/jquery-1.6.1.min.js"><\/script>')
	</script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
	<script src="js/parallax.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
	<!-- Adicionando AOS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

	<style>
		body {
			transition: opacity 1s;
		}

		body.fade-out {
			opacity: 0;
			transition: 1s;
		}

		* {
			cursor: url(https://img1.gratispng.com/20171220/vxe/mouse-cursor-png-5a3a6ccb16a2b8.9260013115137783790927.jpg) auto;
		}

		.img-background {
			position: absolute;
			z-index: -999;
			top: 82px;
			left: 469px;
			width: 1430px;
			height: 717px;
			background: url('https://i.pinimg.com/originals/c0/a3/29/c0a329aa3e4d2ed122874f0d977974e3.gif') center no-repeat;
			mask-image: linear-gradient(to left, transparent 3%, white);
			background-size: cover;
			transition: ease-in 1s;
		}

		:root {
			--fundo-claro: #ffffff;
			/* Cor de fundo para o modo claro */
			--texto-claro: #000000;
			/* Cor do texto para o modo claro */
			--fundo-escuro: #000000;
			/* Cor de fundo para o modo escuro */
			--texto-escuro: #ffffff;
			/* Cor do texto para o modo escuro */
			--bubble-border: 0 -1*4px #fff,
				0 -2*4px #000,
				4px 0 #fff,
				4px -1*4px #000,
				2*4px 0 #000,
				0 4px #fff,
				0 2*4px #000,
				-1*4px 0 #fff,
				-1*4px 4px #000,
				-2*4px 0 #000,
				-1*4px -1*4px #000,
				4px 4px #000;
		}

		@keyframes fadeIn {
			0% {
				opacity: 0;
				transform: translateY(-10px);
				visibility: visible;
			}

			100% {
				opacity: 1;
				transform: translateY(0);
				visibility: hidden;
			}
		}

		@font-face {
			font-family: 'Pixeboy-z8XGD';
			src: url('pixeboy-font/Pixeboy-z8XGD.ttf');
		}

		.bubble {
			position: relative;
			display: inline-block;
			margin: 20px;
			gap: 1.2rem;
			text-align: center;
			font-family: 'Pixeboy-z8XGD';
			font-size: 16px;
			line-height: 1.3em;
			background-color: #fff;
			color: #000;
			padding: 20px;
			box-sizing: border-box;
			width: 200px;
			animation: fadeIn 5s;
		}

		.bubble::after {
			content: '';
			display: block;
			position: absolute;
			box-sizing: border-box;
		}

		.bubble.shadow {
			box-shadow: 0 -4px rgba(0, 0, 0, 0.1),
				0 -8px #000,
				4px 0 rgba(0, 0, 0, 0.1),
				4px -4px #000,
				8px 0 #000,
				0 4px rgba(0, 0, 0, 0.1),
				0 8px #000,
				-4px 0 rgba(0, 0, 0, 0.1),
				-4px -8px #000,
				-8px 0 #000,
				-4px 2px #000;
		}

		.bubble.mini {
			width: 110px;
			font-size: 16px;
			position: absolute;
			top: 80px;
			left: 250px;
			padding: 4px;
		}

		.bubble.medium {
			width: 350px;
		}

		.bubble.large {
			width: 560px;
			font-size: 24px;
			text-align: left;
			text-transform: uppercase;
		}

		.bubble.grow {
			width: initial;
		}

		.bubble.top::after {
			height: 4px;
			width: 4px;
			top: -8px;
			left: 32px;
			box-shadow:
				0 -4px #000,
				0 -8px #000,
				0 -12px #000,
				0 -16px #000,
				-4px -12px #000,
				-8px -8px #000,
				-12px -4px #000,
				-4px -4px #fff,
				-8px -4px #fff,
				-4px -8px #fff,
				-4px 0 #fff,
				-8px 0 #fff,
				-12px 0 #fff;
		}

		.bubble.right::after {
			height: 4px;
			width: 4px;
			top: 84px;
			right: -8px;
			background: #fff;
			box-shadow:
				4px -4px #fff,
				4px 0 #fff,
				8px 0 #fff,
				0 -8px #fff,
				4px 4px #000,
				8px 4px #000,
				12px 4px #000,
				16px 4px #000,
				12px 0 #000,
				8px -4px #000,
				4px -8px #000,
				0 -4px #fff;
		}

		.bubble.bottom::after {
			height: 4px;
			width: 4px;
			bottom: -8px;
			left: 32px;
			box-shadow:
				0 4px #000,
				0 8px #000,
				0 12px #000,
				0 16px #000,
				-4px 12px #000,
				-8px 8px #000,
				-12px 4px #000,
				-4px 4px #fff,
				-8px 4px #fff,
				-4px 8px #fff,
				-4px 0 #fff,
				-8px 0 #fff,
				-12px 0 #fff;
		}

		.bubble.left::after {
			height: 4px;
			width: 4px;
			top: 20px;
			left: -8px;
			background: #fff;
			box-shadow:
				-4px -4px #fff,
				-4px 0 #fff,
				-8px 0 #fff,
				0 -8px #fff,
				-4px 4px #000,
				-8px 4px #000,
				-12px 4px #000,
				-16px 4px #000,
				-12px 0 #000,
				-8px -4px #000,
				-4px -8px #000,
				0 -4px #fff;
		}

		.user-info {
			background: url('https://i.pinimg.com/originals/b2/2a/a2/b22aa22b2f3f55b6468361158d52e2e7.gif');
			border: solid 1.5rem #00000057;
			background-size: cover;
			background-repeat: no-repeat;
			padding-bottom: 20px;
			margin-bottom: 20px;


		}

		.user-info:before {
			position: absolute;
			z-index: -1;
			height: 435px;
			width: 580px;
			top: 180px;
			right: -1.5em;
			bottom: -1.5em;
			left: -1.5em;
			border: inherit;
			border-color: transparent;
			background: inherit;
			background-clip: border-box;
			content: '';
			-webkit-filter: blur(9px);
			filter: blur(9px);
			content: '';

		}

		.user-info img {
			animation: borda 5s infinite;
			display: flex;
			justify-content: center;
			align-items: center;
			z-index: 100;
			margin-top: 20px;
			filter: grayscale(100%);
			transition: 1s;
			margin-left: 230px;
		}

		.user-info img:hover {
			filter: grayscale(0%);
			transition: 1s;

		}

		@keyframes borda {
			0% {
				box-shadow: 0 5px 15px 0px rgba(0, 0, 0, 0.6);
				transform: translatey(0px);
			}

			50% {
				box-shadow: 0 25px 15px 0px rgba(0, 0, 0, 0.2);
				transform: translatey(-10px);
			}

			100% {
				box-shadow: 0 5px 15px 0px rgba(0, 0, 0, 0.6);
				transform: translatey(0px);
			}
		}

		.fade-in {
			opacity: 1;
			transition: opacity 1s ease;
		}

		.user-info p {
			font-family: 'Pixeboy-z8XGD';
			animation: texto 5s;
			text-transform: uppercase;
			color: #fff;
			text-align: center;
			font-size: large;


		}

		.user-info h5 {
			color: orangered;
			font-size: xx-large;
			font-weight: bolder;
			text-transform: uppercase;
			text-align: center;
			font-family: 'Pixeboy-z8XGD';
		}

		#carrinho-janela {
			position: fixed;
			top: 0;
			right: -37%;
			/* Inicialmente fora da tela */
			width: 35%;
			height: 100%;
			background-color: #fff;
			overflow-y: auto;
			transition: right 0.5s ease-in-out;
			z-index: 9999;
		}

		#carrinho-janela.carrinho-aberto {
			box-shadow: 0 0 150px darkblue;
			background: url('https://i.pinimg.com/originals/fd/92/80/fd9280e9f13e53bbbd44dcc46fd7aec2.gif');
		}

		#carrinho-conteudo {
			padding: 20px;

		}

		#loading {
			background-color: black;
		}

		.btn-cart {
			display: block;
			position: absolute;
			bottom: 5px;
			left: 120px;
		}

		::-webkit-scrollbar {
			width: 1px;
			color: #969696;

		}

		::-webkit-scrollbar-button {
			width: 5px;
			color: #969696;

		}

		/* Estilo para a modal */
		.modal {
			display: none;
			position: fixed;
			top: 0;
			left: 250px;
			width: 20%;
			height: 100%;
			background: url('https://i.pinimg.com/originals/0f/f2/9f/0ff29f6cc02c39406fc490459e053a55.gif');
			overflow: auto;
			padding-top: 50px;
			box-sizing: border-box;
			background-repeat: no-repeat;
			background-size: cover;
		}

		/* Estilo para o conteúdo da modal */
		.modal-content {
			width: 90%;
			padding: 20px;
			box-sizing: border-box;
		}

		/* Estilo para o botão de fechar */
		.close {
			color: #fff;
			float: right;
			font-size: 20px;
			font-weight: bold;
			cursor: pointer;
		}

		.modal {
			transition: transform 0.5s ease-in-out;
		}

		.modal-hidden {
			transform: translateY(-100%);
		}

		.img-user {
			position: absolute;
			top: 15px;
			left: 215px;
		}

		@keyframes fadeIn {
			0% {
				opacity: 0;
				transform: translateY(-10px);
				visibility: visible;
			}

			100% {
				opacity: 1;
				transform: translateY(0);
				visibility: hidden;
			}
		}


		.content-loading {
			animation: fadeIn 2.4s ease-in;
		}

		body {
			zoom: 120%;
			overflow-y: hidden;
		}

		.animation-box {
			opacity: 0;
			margin-top: 50px;
			transition: opacity 0.5s ease-in;
		}

		.invisivel {
			opacity: 0;
			transition: all 1s;
		}
	</style>
</head>

<body>


	<div id="loader-wrapper">
		<div class="animate__animated animate__fadeInDown animate__slower" id="loader">


			<img src="https://i.pinimg.com/originals/1e/85/d8/1e85d85797303b24c110f39e72ce3de2.gif" style="	width: 500px; height: 500px; margin-bottom: -250px; z-index: 100000; filter: drop-shadow((10px 10px 10px gray)); margin-right: 140px;" alt="loading">
			<img src="https://art.pixilart.com/00a282a3eeceb97.gif" style="	width: 600px; height: 600px; margin-bottom: 100px;" alt="loading2">

		</div>
	</div>

	<div class="content-loading" style="display: none;">

		<div id="wrapper">
			<div class="animated-box">
				<header id="branding">
					<h1>
						<?php $imagem = $_SESSION['image']; ?>


						<?php
						if (!empty($_SESSION['nome'])) {
							echo '<p style="flex-direction: row;">
								Seja bem-vindo ' . $username_toshow . '<img src="./img/frutas/' . $_SESSION["image"] . '" Align="center" class="img-user"
								alt="Imagem do usuário" style="border-radius:50%;" width="50px"
								height="50px">
							</p>';
						} else {
							echo 'Ola Visitante';
						}

						?>
					</h1>
				</header>

				<nav id="primary">
					<ul>
						<li>
							<h1>MODO ESCURO</h1>
							<a class="manned-flight animate__animated animate__slower animate__bounceInRight" id="modoEscuroBtn">
								<img src="https://i.pinimg.com/originals/79/ea/6f/79ea6ffa1ca3345b59042a9ce9638dfc.gif" alt="Modo Escuro" id="modoEscuroImg"></a>
						</li>
						<li>
							<h1>HOME</h1>
							<a class="manned-flight animate__animated animate__slower animate__bounceInRight" href="#manned-flight"><img src="img/Pukemon.gif" alt=""></a>
						</li>
						<li>
							<h1>LOJA DO TORANJINHA</h1>
							<a class="frameless-parachute animate__animated animate__slower animate__bounceInRight" href="#frameless-parachute"><img src="https://i.pinimg.com/originals/0b/7a/cc/0b7acc43ae7c3a5ae35654fdf1b9bc4b.gif" alt=""></a>
						</li>
						<li>
							<h1>JOGOS DO TORANJAS</h1>
							<a class="english-channel animate__animated animate__slower animate__bounceInRight" href="#english-channel"><img src="https://i.pinimg.com/originals/10/27/f8/1027f80aeabcbb74a2e698be71829e9e.gif" alt=""></a>
						</li>
						<li>
							<h1>SUA CONTA DO TORANJAS</h1>
							<a class="about animate__animated animate__slower animate__bounceInRight" href="#about"><img src="https://i.pinimg.com/originals/66/2c/da/662cda1ea6bdac6afb16973961c2c8d1.gif" alt=""></a>
						</li>
					</ul>
				</nav>

				<div id="content">
					<article id="manned-flight">
						<header>
							<h1>SITE</h1>
						</header>
						<p style="color: #fff;">
							Este é o site do Toranjinha que tem de tudo para você, voce pode jogar e aprender metodos de
							desenvolvimentos, temos lojas para voce poder desfrutar das paisagens, um otimo login e
							cadastro
							e
							temos alguns patrocinadores que são os TL (Tonhão Tora Larga) e a Torcida Organizada do
							PalPreto
							(Palmeiras e Ribeirão Preto) e Aproveitem o Site
						</p>

						<hr />
						<a href="Sobre-nos.php" class="btn-Link fade-link">Sobre-nos</a>
						<a class="next frameless-parachute" href="#frameless-parachute">Next</a>
						</nav>
					</article>

					<article id="frameless-parachute" class="fade-in invisivel">
						<header>
							<h1>LOJA</h1>
						</header>
						<p>
							Aqui é a loja do Toranjinha onde você pode comprar e viver, amar, sonhar e desafios em
							varios
							mundos
							cheios de vidas com monstros, bosses, varias etnias e raças com cidades medievais. Os nossos
							mundos
							são para as pessoas que querem viver mais que a vida mutua do cotidiando, podemos viver em
							mundo
							cheio de aventuras e tesouros
						</p>
						<a href="TORANJA 2.0.php" class="btn-Link fade-link">Ver a Loja</a>
						<nav class="next-prev">
							<a class="prev manned-flight" href="#manned-flight">Prev</a>
							<hr />
							<a class="next english-channel" href="#english-channel">Next</a>
						</nav>
					</article>

					<article id="english-channel" class="fade-in invisivel">
						<header>
							<h1>JOGOS</h1>
						</header>
						<p>
							Você está na pagina de jogos aonde você possa aprender a desenvolvover sites e batalhas com
							outros
							devenvolvedores no Battle CSS, como aprender a direção dos itens do site como o flex Frog, e
							outros
							jogos que irão ajudar muitos voces a desenvolvover jogando apenas. Aproveitem os Jogos
						</p>
						<a href="TELA DE JOGOS.php" class="btn-Link fade-link ">Acessar aos jogos</a>

						<nav class="next-prev">
							<a class="prev frameless-parachute" href="#frameless-parachute">Prev</a>
							<hr />
							<a class="next about" href="#about">Next</a>
						</nav>
					</article>

					<article id="about">
						<header>
							<h1>Conta</h1>
						</header>


						<?php if ($_SESSION['email'] != "") : ?>
							<div class="user-info">
								<?php $imagem = $_SESSION['image']; ?>
								<img src="./img/frutas/<?php echo $_SESSION['image']; ?>" Align="center" id="img-user" alt="Imagem do usuário" style="border-radius:50%; margin-right:20px; " width="100px" height="100px">

								<h5>Nome</h5>

								<p>
									<?php echo $_SESSION['nome']; ?>
								</p>


								<hr>


								<h5>E-mail:</h5>

								<p>
									<?php echo $_SESSION['email']; ?>
								</p>

								</form>
							</div>
							<?php if ($_SESSION['email'] == 'Toranjas@toto.com') : ?>
								<button id="Add-btn" class="btn-Link" style="left: 205px; position: absolute; width:6%; height:4%; justify-content:center; text-align:center; display:flex; justify-content:center; align-items:center; bottom:111px; color:orangered;">Add</button>

								<div id="myModal" class="modal">
									<div class="modal-content">
										<span id="closeModal" class="close">&times;</span>
										<?php include_once('adicionar-produtos.php') ?>
									</div>
								</div>
								<hr>
							<?php endif; ?>

							<a href="editprofile.php" class="btn-Link fade-link">Editar Perfil</a>

							<button type="buttom" id="abrir-carrinho" class="btn-Link" style="margin-left:10px; color:orangered; width:45px; height:40px;">
								<i class="fa-solid fa-cart-shopping"></i>
							</button>

							<div id="carrinho-janela">
								<div id="carrinho-conteudo">
									<!-- Conteúdo da janela -->
									<?php
									include('carrinho.php');

									?>

									<hr>
									<div class="btn-cart">
										<button id="fechar-carrinho" class="btn-Link" style="color:red; position:relative; right:10px;
				background-color: orange;">Fechar</button>
										<button class="btn-Link" style="color:Green; position:relative; right:10px; margin-left:10px;
				background-color: orange;">Comprar</button>
									</div>

									<div id="loading" class="mini-loading"></div>

								</div>
							</div>
							<form action="process.php" method="POST">
								<input type="hidden" value="logout" name="type">
								<button type="submit" class="btn-Link" style="width:150px; height:40px; color:red;">Logout</button>
							</form>

						<?php else : ?>
							<div class="login-options">
								<p>Faça o seu Login no Toranjinha para obter Pontos de descontos nas compras e jogos
								</p>
								<a href="LoginToranjão.php" class="btn-Link fade-link">Fazer Login</a>
							</div>
						<?php endif; ?>
						<nav class="next-prev">
							<a class="prev english-channel" href="#english-channel">Faça Login</a>
							<hr />
						</nav>

					</article>
				</div>

				<!-- Parallax foreground -->
				<div id="parallax-bg3">
					<img id="bg3-1" src="https://66.media.tumblr.com/33d5fb884ba350b0495501625d07da03/tumblr_mqatxtNyuW1rfjowdo1_500.gif" width="529" height="557" alt="Montgolfier hot air balloon" />
					<img id="bg3-2" src="https://i.pinimg.com/originals/15/26/5a/15265af91d058d33da9d448a7cd070f9.gif" width="603" height="583" alt="Frameless parachute" />
					<img id="bg3-3" src="https://media3.giphy.com/media/iJsjsm6dhNPiQBvztq/200w.gif?cid=6c09b952j2m0vilymz1682k52w5eupz1ft9gqetod287py74&ep=v1_gifs_search&rid=200w.gif&ct=s" width="446" height="413" alt="Blanchard's air balloon" />
					<img id="bg3-4" src="https://i.pinimg.com/originals/9d/dd/0c/9ddd0cf77c1d495dd57e8ca2a37a7906.gif" width="400" height="500" alt="Landscape with trees and cows" />
					<div class="img-background">
					</div>
				</div>
				<!-- Parallax  midground clouds -->
				<div id="parallax-bg2">
					<img id="bg2-1" src="https://media2.giphy.com/media/FHcRX5OWtvIaKTWyTX/giphy.gif?cid=6c09b952ys9xz0oihx6vzec3i9ond0dgxxn5vll8gfnk0riy&ep=v1_stickers_related&rid=giphy.gif&ct=s" height="40" width="40" alt="cloud" />
					<img id="bg2-2" src="https://i.pinimg.com/originals/29/a2/22/29a222d0e480acf061b959b933ede8e9.gif" alt="cloud" />
					<img id="bg2-3" src="https://media2.giphy.com/media/FHcRX5OWtvIaKTWyTX/giphy.gif?cid=6c09b952ys9xz0oihx6vzec3i9ond0dgxxn5vll8gfnk0riy&ep=v1_stickers_related&rid=giphy.gif&ct=s" height="40" width="40" alt="cloud" />
					<img id="bg2-4" src="https://i.pinimg.com/originals/29/a2/22/29a222d0e480acf061b959b933ede8e9.gif" alt="cloud" />
					<img id="bg2-5" src="https://media2.giphy.com/media/FHcRX5OWtvIaKTWyTX/giphy.gif?cid=6c09b952ys9xz0oihx6vzec3i9ond0dgxxn5vll8gfnk0riy&ep=v1_stickers_related&rid=giphy.gif&ct=s" alt="cloud" />
				</div>

				<!-- Parallax  background clouds -->
				<div id="parallax-bg1">
					<img id="bg1-1" src="https://i.pinimg.com/originals/29/a2/22/29a222d0e480acf061b959b933ede8e9.gif" alt="cloud" />
					<img id="bg1-2" src="https://media2.giphy.com/media/FHcRX5OWtvIaKTWyTX/giphy.gif?cid=6c09b952ys9xz0oihx6vzec3i9ond0dgxxn5vll8gfnk0riy&ep=v1_stickers_related&rid=giphy.gif&ct=s" alt="cloud" />
					<img id="bg1-3" src="https://i.pinimg.com/originals/29/a2/22/29a222d0e480acf061b959b933ede8e9.gif" alt="cloud" />
					<img id="bg1-4" src="https://media2.giphy.com/media/FHcRX5OWtvIaKTWyTX/giphy.gif?cid=6c09b952ys9xz0oihx6vzec3i9ond0dgxxn5vll8gfnk0riy&ep=v1_stickers_related&rid=giphy.gif&ct=s" alt="cloud" />
				</div>

			</div>
		</div>

	</div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
	// Função para verificar a interseção
	function handleIntersection(entries, observer) {
		entries.forEach(entry => {
			if (entry.isIntersecting) {
				console.log('Elemento visível:', entry.target);
				// Adiciona classes de animação e define opacidade para 1 quando visível
				entry.target.classList.add('fade-in', 'animate__animated', 'animate__slow', 'animate__fadeInLeft');
				entry.target.classList.remove('invisivel'); // Remove a classe 'invisivel'
			} else {
				console.log('Elemento não visível:', entry.target);
				// Remove classes e define opacidade para 0 quando não visível
				// entry.target.classList.remove('fade-in', 'animate__animated', 'animate__slow', 'animate__fadeInLeft');
				entry.target.classList.add('invisivel'); // Adiciona a classe 'invisivel'
			}
		});
	}

	document.addEventListener('DOMContentLoaded', () => {
		// Configuração do IntersectionObserver
		const observer = new IntersectionObserver(handleIntersection, {
			threshold: 0.5
		});

		// Seleciona todos os elementos com a classe .fade-in e observa cada um
		document.querySelectorAll('.fade-in').forEach(element => {
			observer.observe(element);
			console.log('Observando elemento:', element);
		});
	});

	// Inicialize AOS
	AOS.init({
		easing: 'ease-in',
		duration: 1000
	});
	document.addEventListener('DOMContentLoaded', () => {
		const modoEscuroBtn = document.getElementById('modoEscuroBtn');
		const modoEscuroImg = document.getElementById('modoEscuroImg');
		const body = document.body;

		// Verifica se o modo escuro está ativado no localStorage
		const isDarkMode = localStorage.getItem('modoEscuro') === 'true';

		// Função para aplicar o modo escuro
		function aplicarModoEscuro() {
			modoEscuroImg.src = 'https://cdnb.artstation.com/p/assets/images/images/017/571/291/original/emil-nikolov-purple-mage-resized.gif?1556540213';
			body.classList.add('modo-escuro');
			localStorage.setItem('modoEscuro', 'true');
		}

		// Função para desativar o modo escuro
		function desativarModoEscuro() {
			modoEscuroImg.src = 'https://i.pinimg.com/originals/79/ea/6f/79ea6ffa1ca3345b59042a9ce9638dfc.gif';
			body.classList.remove('modo-escuro');
			localStorage.setItem('modoEscuro', 'false');
		}

		// Se o modo escuro estiver ativado, aplica-o na inicialização
		if (isDarkMode) {
			aplicarModoEscuro();
		}

		// Alterna entre os modos escuro e claro
		modoEscuroBtn.addEventListener('click', () => {
			if (body.classList.contains('modo-escuro')) {
				desativarModoEscuro();
			} else {
				aplicarModoEscuro();
			}
		});
	});

	// Mostra a tela de carregamento
	document.getElementById('loader-wrapper').style.display = 'block';

	// Aguarda 5 segundos e, em seguida, esconde a tela de carregamento
	setTimeout(() => {
		document.getElementById('loader-wrapper').style.display = 'none';
		document.querySelector('.content-loading').style.display = 'block'; // Mostra o conteúdo após o carregamento
		document.getElementById('loader-wrapper').style.transition = '2s';
		document.querySelector('.content-loading').style.transition = '2s'; // Mostra o conteúdo após o carregamento
	}, 4000);

	$(document).ready(function() {
		$('a.fade-link').on('click', function(event) {
			event.preventDefault();
			var newLocation = this.href;

			$('body').addClass('fade-out');

			setTimeout(function() {
				window.location = newLocation;
			}, 1000); 
		});
	});
</script>
<script>
	document.addEventListener("DOMContentLoaded", function() {
		var addButton = document.getElementById("Add-btn");
		var modal = document.getElementById("myModal");
		var closeButton = document.getElementById("closeModal");
		var isModalOpen = false;

		addButton.addEventListener("click", function() {
			if (!isModalOpen) {
				modal.style.display = "block";
				setTimeout(function() {
					modal.classList.remove("modal-hidden");
					modal.style.transform = "translateY(0%)";
				}, 50);
				modal.style.width = "25%";
				modal.style.height = "90%";
				modal.style.boxShadow = "0px 10px 150px brown"; // Adicione o box-shadow
				isModalOpen = true;
			}
		});

		closeButton.addEventListener("click", function() {
			closeModal();
		});

		window.addEventListener("click", function(event) {
			if (isModalOpen && event.target == modal) {
				closeModal();
			}
		});

		function closeModal() {
			if (isModalOpen) {
				modal.style.transform = "translateY(-100%)"; // Desloca a modal para cima (ocultação)
				setTimeout(function() {
					modal.style.display = "none";
					modal.classList.add("modal-hidden");
					modal.style.width = ""; // Limpa a largura
					modal.style.height = ""; // Limpa a altura
					modal.style.boxShadow = ""; // Remove o box-shadow
					isModalOpen = false;
				}, 500); // Tempo correspondente à transição CSS
			}
		}
	});


	document.addEventListener('DOMContentLoaded', (event) => {
		const carrinhoJanela = document.getElementById('carrinho-janela');
		const abrirCarrinhoBtn = document.getElementById('abrir-carrinho');
		const loading = document.getElementById('loading');
		let carregamentoRealizado = false;

		// Evento para abrir o carrinho
		abrirCarrinhoBtn.addEventListener('click', (event) => {
			event.preventDefault();

			// Se o carregamento não foi realizado, inicia o carregamento
			if (!carregamentoRealizado) {
				// Exibe o loading
				loading.style.display = 'block';

				// Simula um tempo de carregamento (substitua por seu código de carregamento real)
				setTimeout(() => {
					carregamentoRealizado = true; // Atualiza a variável para indicar que o carregamento foi realizado
					carrinhoJanela.style.right = '0';
					carrinhoJanela.classList.add('carrinho-aberto');
					loading.style.display = 'none'; // Esconde o loading após o carregamento
				}, 1000); // Ajuste o tempo conforme necessário
			} else {
				// Se o carregamento já foi realizado, abre o carrinho imediatamente
				carrinhoJanela.style.right = '0';
				carrinhoJanela.classList.add('carrinho-aberto');
			}
		});

		const carrinhoConteudo = document.getElementById('carrinho-conteudo');

		carrinhoConteudo.addEventListener('click', (event) => {
			// Impede a propagação do evento para evitar que atinja o manipulador de fechamento da janela
			event.stopPropagation();
		});

		// Evento para fechar o carrinho ao clicar fora dele
		document.addEventListener('click', (event) => {
			if (event.target !== carrinhoJanela && event.target !== abrirCarrinhoBtn) {
				carrinhoJanela.style.right = '-35%';
				carrinhoJanela.classList.remove('carrinho-aberto');
			}
		});

		const fecharCarrinhoBtn = document.getElementById('fechar-carrinho');

		fecharCarrinhoBtn.addEventListener('click', (event) => {
			event.preventDefault(); // Evita qualquer comportamento padrão do botão
			carrinhoJanela.style.right = '-35%';
			carrinhoJanela.classList.remove('carrinho-aberto');
		});

	});
</script>



</html>