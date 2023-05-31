<?php
include('conexao.php');
session_start();
$login = $_SESSION['login'];
$nome_medicamento = $_GET['nome_medicamento'];



// Consulta SQL para obter os dependentes associados ao login
$consulta = "SELECT d.nome_dependente 
FROM me_dependente d
INNER JOIN me_login l ON l.id_usuario = d.id_usuario
WHERE l.login = '$login'";
$resultado = mysqli_query($conexao, $consulta);



?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alarme</title>

    <link rel="stylesheet" href="style_alarme.css">
  
</head>
<body>
    <center>
    <header>
        <nav>
        <a class="logo" href="login.php">Planner Medicamentos</a>
        </nav>
    </header>

    <form action="alarme_scripting.php" method="post" onsubmit="redirecionar()">
        <input type="hidden" name="nome_medicamento" value="<?php echo $nome_medicamento ?>">

        <label>Escolha seu usuário</label>
        <select name="opcao" required>
        <?php 
       // Loop através dos resultados e exibe cada dependente como uma opção no select
        while ($row = mysqli_fetch_assoc($resultado)) {?>
        <option value="<?php echo $row['nome_dependente']; ?>"><?php echo $row['nome_dependente']; ?></option>
        <?php } ?>
        <option value="<?php echo $login ?>"><?php echo $login ?></option>
        </select><br>

        <label>Dosagem</label>
            <select name="dosagem" required>
                <option>Selecione</option>
                <option value="comprimido">comprimido</option>
                <option value="capsula">cápsula</option>
                <option value="gota">gota</option>
                <option value="colher">colher</option>
                <option value="unidade">unidade</option>
            </select>

        <label>Concentração</label>
            <select name="concentracao" required>
                <option>Concentração</option>
                <option value="mcg">mcg</option>
                <option value="mg">mg</option>
                <option value="g">g</option>
                <option value="mL">mL</option>
                <option value="L">L</option>
            </select>

        <label for="inicio">Horário de início:</label>
        <input type="datetime-local" name="inicio" required>

        <label for="duracao">Duração do tratamento (em dias):</label>
        <input type="number" name="duracao" required>

        <label for="frequencia">Frequência (em horas):</label>
        <input type="number" name="frequencia" required>
        
        <button type="submit">Enviar</button>
        
       
    </form>
    <script>
    document.querySelector('form').addEventListener('submit', function() {
        location.href = 'alarme_scripting.php';
    });
    </script>

    <!--TEM QUE VOLTAR PARA A PÁGINA PRINCIPAL, SE VOLTAR PARA pesquisa_med 
    VAI DAR MERDA PQ ELE NÃO VOLTA COM O CACHÊ DO NOME-->

    <!--NÃO TENTE MUDAR-->
    <form action="principal2.php">
        <button type="submit">Voltar</button>
    </form>
    
    </center>

    
</body>
</html>