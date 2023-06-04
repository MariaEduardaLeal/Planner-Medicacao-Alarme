<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="style_cadastro.css">

</head>

<body>
    <div class="container">
        <div class="box-one">
            <div class="box-logo">
                <a href="login.php">
                    <img src="img/logo_plannermed.png" class="logo-image">
                </a>
            </div>

            <h1>Cadastro</h1>
            <div id="formulario_de_cadastro">

                <form action="cadastro_scripting.php" id="form_cad" method="post">
                    <div class="form-control">
                        <!--Cadastrar nome-->
                        <label for="nome">Digite seu nome </label><br>
                        <input type="text" name="nome" id="username" required><br>
                        <i class="img-success"><img src="./images/success-icon.svg" alt=""></i>
                        <i class="img-error"><img src="./images/error-icon.svg" alt=""></i>
                        <small>Error Message</small>
                    </div>

                    <div class="form-control">
                        <!--Cadastrar login-->
                        <label for="login">Digite seu login </label><br>
                        <input type="text" name="login" id="login" required><br>
                        <small>Error Message</small>
                    </div>

                    <div class="form-control">
                        <!--Cadastrar email-->
                        <label for="email">Email </label><br>
                        <input type="email" name="email" id="email" required><br>
                        <small>Error Message</small>
                    </div>

                    <div class="form-control">
                        <!--Cadastrar senha-->
                        <label for="senha">Digite sua senha </label><br>
                        <input type="password" name="senha" id="password" required><br>
                        <small>Error Message</small>
                    </div>

                    <label for="tipo_usuario">Tipo de usuário</label><br>
                    <select name="tipo_usuario" id="tipo_usuario">
                        <option value="1">Usuário comum</option>
                        <option value="2">Dependente</option>
                    </select><br>

                    <br><button type="submit">Cadastrar</button>
                </form>

                <form action="login.php">
                    <button type="submit">Voltar</button>
                </form>

            </div>
        </div>
    </div>
    <script src="cadastro.js"></script>
</body>

</html>
