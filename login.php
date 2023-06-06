<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style_index.css">
</head>

<body>
    <div class="container">
        <div class="box-one">
            <div class="box-logo">
                <a href="login.php">
                    <img src="img/logo_plannermed.png" class="logo-image">
                </a>
            </div>

            <div id="formulario_de_cadastro">
                <form action="autenticar.php" id="form_login" method="post">
                    <div class="form-control">
                        <label for="login" style="position: relative;">
                            <span>Usuário</span>
                            <input type="text" id="login" name="login" required placeholder="">
                            <img src="img/icon-box-usuario.svg" alt="icon-box-usuario" class="input-icon" style="position: absolute; top: 32px; left: 28px; width: 28px; height: 35px;">
                        </label>
                    </div>
                    <div class="form-control">
                        <label for="senha" style="position: relative;">
                            <br><span>Senha</span><br>
                            <input type="password" id="senha" name="senha" required placeholder="">
                            <img src="img/icon-box-senha.svg" alt="icon-box-senha" class="input-icon" style="position: absolute; top: 60px; left: 28px; width: 28px; height: 35px;">
                            <br>Mostrar Senha: <input type="checkbox" onclick="mostrarOcultarSenha()">
                            <script type="text/javascript" src="verificar_senha.js"></script>
                        </label>
                    </div>
                    <div class="remember">
                        <a href="confirmar_dados.php" style="color: black; text-decoration: none;">Esqueceu a Senha?</a>
                    </div>
                    <br><button type="submit">Conectar</button>
                    <div class="input-box">
                        <br>
                        <p>Não possui conta? <a class="criar_conta" href="cadastro.php" style="color: #255885;">Criar conta</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="cadastro.js"></script>
    <script>
        var inputLogin = document.getElementById('login');
        var inputSenha = document.getElementById('senha');
        var imgLogin = document.querySelector('label[for="login"] .input-icon');
        var imgSenha = document.querySelector('label[for="senha"] .input-icon');

        inputLogin.addEventListener('focus', function() {
            imgLogin.style.display = 'none';
        });

        inputLogin.addEventListener('blur', function() {
            if (inputLogin.value.length === 0) {
                imgLogin.style.display = 'inline-block';
            }
        });

        inputSenha.addEventListener('focus', function() {
            imgSenha.style.display = 'none';
        });

        inputSenha.addEventListener('blur', function() {
            if (inputSenha.value.length === 0) {
                imgSenha.style.display = 'inline-block';
            }
        });
    </script>
</body>

</html>