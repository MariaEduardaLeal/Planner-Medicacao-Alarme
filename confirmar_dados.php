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
    <header>
        <nav>
            <a class="logo" href="login.php">Planner Medicamentos</a>
        </nav>
    </header>
    <div class="container">
        <div class="box-one">
            <form action="confirmar_scripting.php" id="form_confirma_dados" method="post">
                <label for="email">Confirme seu email</label>
                <input type="email" name="email">
                <label for="login">Confrime Seu login</label>
                <input type="text" name="login">
                <label for="senha">Informe sua nova senha</label>
                <input type="password" name="senha" id="senha" required>
                <input type="checkbox" onclick="mostrarOcultarSenha()">Mostar Senha
                <script type="text/javascript" src="verificar_senha.js"></script>
                <button type="submit" class="enviar">Enviar</button>

            </form>
            <form action="login.php">
                <button type="submit" class="voltar">Voltar</button><br>
            </form>
        </div>
    </div>
    
</body>
</html>