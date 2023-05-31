<?php 
include('conexao.php');
session_start();
$login = $_SESSION['login'];

// Obtendo o tipo de usuário do banco de dados
$select_tipo_usuario = "SELECT id_tipo_usuario FROM me_usuario
 WHERE id_usuario = (SELECT id_usuario FROM me_login WHERE login = '$login')";

$query_tipo_usuario = mysqli_query($conexao, $select_tipo_usuario);
$dado_tipo_usuario = mysqli_fetch_assoc($query_tipo_usuario);

$id_tipo_usuario = $dado_tipo_usuario['id_tipo_usuario'];

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diário</title>
</head>
<body>
    <header>
        <nav>Diário</nav>
        <nav>Remédios</nav>
        <nav>Dependentes</nav>
        <nav>Sobre nós</nav>
    </header>

    <a href="addMedicamento.php">Adicionar Medicamento</a>

    <div class="container">
        <div class="card-left">
           <span>Horario</span>
        </div>

        <div class="card-right">
        <span>Nome</span>
            <h1>Bla Bla</h1>
            <p>Paragrafo</p>
        </div>
    </div>
</body>
</html>