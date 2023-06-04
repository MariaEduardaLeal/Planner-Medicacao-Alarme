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

            <h1>Login</h1>
            <div id="formulario_de_cadastro">

                <form action="autenticar.php" id="form_login" method="post">

                    <div class="form-control">
                        <!--Cadastrar login-->
                        <label for="login">Digite seu login</label><br>
                        <input type="text" name="login" required placeholder="Usuário">
                    </div>


                    <div class="form-control">
                        <!--Cadastrar senha-->
                        <label for="senha">Digite sua senha </label><br>
                        <input type="password" name="senha" id="senha" required placeholder="Senha">
                        Mostrar Senha: <input type="checkbox" onclick="mostrarOcultarSenha()">
                        <script type="text/javascript" src="verificar_senha.js"></script>
                    </div>
                    <div class="remember">
                        <a href="confirmar_dados.php" style="color: black; text-decoration: none;">Esqueceu a Senha?</a>
                    </div>
                    <br><button type="submit">Entrar</button>
                    <div class="input-box">
                        <p>Não tem uma conta?<a href="cadastro.php" style="color: #4aa4ee;">Increver-se</a></p>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script src="cadastro.js"></script>
</body>

</html>
