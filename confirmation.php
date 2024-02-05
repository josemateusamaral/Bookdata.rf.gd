<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmação de Conta</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h1 class="text-center">Account Creation Confirmation</h1>
                    </div>
                    <div class="card-body">
                        <p class="text-center">Insert below the confirmation code we sent to your email. It may be identified as a span.</p>
                        <div class="form-group">
                            <input type="text" class="form-control" id="confirmationCode" placeholder="Confirmation code">
                        </div>
                        <button class="btn btn-primary btn-block" onclick="submitConfirmationCode()">Confirmar</button>
                        <p id="countdown" class="text-center mt-3">Tempo restante: 5:00</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="post" action="api.php" id="meuFormulario">

        <input type="hidden" name="action" value="activateAccount">
        <input type="hidden" name="token" id="token">
        <input type="hidden" name="username" value="<?php echo $_SESSION["postData"]?>">

    </form>

    <script>
        // Função para processar o código de confirmação
        function submitConfirmationCode() {
            var confirmationCode = document.getElementById("confirmationCode").value;
            document.getElementById("token").value = confirmationCode;
            document.getElementById("meuFormulario").submit();
        }

        // Função para iniciar o contador regressivo
        function startCountdown(duration, display) {
            var timer = duration, minutes, seconds;
            setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = "Tempo restante: " + minutes + ":" + seconds;

                if (--timer < 0) {
                    timer = duration;
                }
            }, 1000);
        }

        window.onload = function () {
            var fiveMinutes = 60 * 5;
            var display = document.getElementById("countdown");
            startCountdown(fiveMinutes, display);
        };
    </script>
</body>
</html>
