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
    
    <div id="formulario-login">        
        <form action="autenticar.php" id="form_login" method="post">
    <div class="container-login">
        <div class="img-box">
            <img src="img/ekg-2069872_1280.png">
        </div>
        <div class="content-box">
            <div class="form-box">
                <h2>login</h2>
                <form action="autenticar.php" id="form_login" method="post">
                    <div class="input-box">
                        <span>username</span>
                        <input type="text" name="login" required placeholder="Usuario"><!--O required faz com que o login e a senha sejam obrigatorios, pois hã, um bug que se o usuario entrar com tudo nulo, ele entra mesmo assim no sistema-->
                    </div>                         
                    <div class="input-box">
                        <span>Password</span>
                        <input type="password" name="senha" id="senha" required placeholder="Senha">

                        <input type="checkbox" onclick="mostrarOcultarSenha()">Mostar Senha
                        <script type="text/javascript" src="verificar_senha.js"></script>
                    </div>
                    <div class="remenber">
                        <a href="confirmar_dados.php">Esqueceu a Senha?</a>
                    </div>
                    <br><div class="input-box">
                        <input type="submit" value="entrar">
                    </div>
                    <div class="input-box">
                        <p>Não tem uma conta?<a href="cadastro.php">Increver-se</a></p> 
                    </div>
                </form>       
                
            </div>

        </div>
    </div>
    
</body>
</html>
