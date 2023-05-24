<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cadastro</title>
    <link rel="stylesheet" href="style_cadastro.css">

</head>
<body>
    <header>
        <nav>
        <a class="logo" href="login.php">Planner Medicamentos</a>
        </nav>
    </header>
    <center>
    <div class="container"> 
    <div class="box-one">    
    <h1>Cadastro</h1>
    <div id="formulario_de_cadastro">
        
    <form action="cadastro_scripting.php" id = "form_cad" method="post">
        <!--Cadastrar nome-->
        <label for="nome">Digite seu nome :</label><br>
        <input type="text" name="nome" required><br> 

        <!--Cadastrar login-->
        <label for="login">Digite seu login :</label><br>
        <input type="text" name="login" required><br> 

        <!--Cadastrar email-->
        <label for="email">Email :</label><br>
        <input type="email" name="email" required><br>

        <!--Cadastrar senha-->
        <label for="senha">Digite sua senha :</label><br><!--<label></label> serve para que ao clicar no rótulo, o elemento de formulário associado a ele também recebe foco. Isso é útil para usuários que têm dificuldade em clicar em elementos pequenos, como caixas de seleção, ou que usam leitores de tela para acessar a página.-->
        <input type="password" name="senha" required><br>

        <label for="tipo_usuario">Tipo de usuário:</label><br>
        <select name="tipo_usuario" id="tipo_usuario">
            <option value="1">Usuário comum</option>
            <option value="2">Dependente</option><br>
        </select><br>

        <br><button type="submit">cadastrar</button>
        </form>
    <form action="login.php">
    <button type="submit">Voltar</button>
    </form>

    </form>
    </div>        
        </center>
    </div>
</div>
</body>
</html>