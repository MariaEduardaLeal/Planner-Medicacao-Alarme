<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Dados</title>
    <link rel="stylesheet" href="style_confirmar_dados.css">
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
                <form action="confirmar_scripting.php" id="form_confirma_dados" method="post">
                    <div class="form-control">
                        <label for="email">Confirme seu email</label>
                        <input type="email" name="email">
                    </div>

                    <div class="form-control">
                        <label for="login">Confrime Seu login</label>
                        <input type="text" name="login">
                    </div>

                    <div class="form-control">
                        <label for="senha">Informe sua nova senha</label>
                        <input type="text" name="senha">
                        <br>Mostrar Senha: <input type="checkbox" onclick="mostrarOcultarSenha()">
                        <script type="text/javascript" src="verificar_senha.js"></script>
                    </div>

                    <br><button type="submit">Enviar</button>
                </form>

                <form action="login.php">
                    <br><button type="submit">Voltar</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>