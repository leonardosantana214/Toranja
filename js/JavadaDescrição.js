function AvisoToranja() {
  var numeroDigitado = parseFloat(document.getElementById('resultado1').value);
  var resultado = numeroDigitado * (number_format($product['preco']));
  document.getElementById('resultado').innerText = `Os ${numeroDigitado} deu um valor de R$${resultado},99 Boa Compra :)`
}

function trocarComImagemPrincipal(imagemClicada) {
  // Obtém a URL da imagem clicada
  var urlImagemClicada = imagemClicada.src;

  // Obtém a URL da imagem principal
  var urlImagemPrincipal = document.getElementById('imagem1').src;

  // Define a URL da imagem clicada como a nova imagem principal
  document.getElementById('imagem1').src = urlImagemClicada;

  // Define a URL da imagem principal como a imagem clicada
  imagemClicada.src = urlImagemPrincipal;
}
// fazer a funçao de calcular frete
function closeAlert() {
  var alertElement = document.getElementById("myAlert");
  alertElement.style.display = "none";
}
// fazer a função de calcular frete
function calcularFrete() {
  // preciso criar uma variável de resultado para aparecer o valor do Frete
  let res = document.querySelector("#resultado");
  // Pega o número do input e faz uma seleção aleatória de preços de R$1,00 até R$20,00
  var numInput = parseInt(document.getElementById("cep").value);
  console.log("Valor do input:", numInput); // Adicionando log para depuração
  if (isNaN(numInput)) {
    alert("Preencha com um CEP válido");
  } else {
    var minimo = 1;
    var maximo = 20;
    var dif = maximo - minimo + 1;
    var aleatorio = Math.floor(Math.random() * dif) + minimo;
    // Alterado para definir o valor diretamente no resultado
    res.innerHTML = "Valor do Frete R$ " + aleatorio + ",00";
  }
}