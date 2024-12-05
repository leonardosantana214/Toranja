<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Chatbot Toranjinha</title>
    <style>
        .chat-box {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .message {
            margin-bottom: 10px;
        }

        .user-message {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="chat-box">
        <div id="messages"></div>
        <input type="text" id="userInput" placeholder="Digite sua mensagem...">
        <button onclick="sendMessage()">Enviar</button>
    </div>

    <script>
        function sendMessage() {
            const input = document.getElementById('userInput');
            const message = input.value;
            const messagesDiv = document.getElementById('messages');

            // Adiciona a mensagem do usuário
            const userMessageDiv = document.createElement('div');
            userMessageDiv.className = 'message user-message';
            userMessageDiv.innerText = message;
            messagesDiv.appendChild(userMessageDiv);

            // Envia a mensagem para o servidor
            fetch('process.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        query: message
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Adiciona a resposta do chatbot
                    const botMessageDiv = document.createElement('div');
                    botMessageDiv.className = 'message';
                    botMessageDiv.innerText = data.response;
                    messagesDiv.appendChild(botMessageDiv);
                    input.value = '';
                });
        }
    </script>
</body>

</html>