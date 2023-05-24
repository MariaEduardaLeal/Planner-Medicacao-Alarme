<?php
session_start(); // inicia a sessão
include('conexao.php');

$login = $_SESSION['login'];

$nomeDependente = $_POST['nomeDependente'];
$login_dep = $_POST['login_dep'];
$email = $_POST['email'];
$senha = $_POST['senha'];

$verificar_login = "SELECT login FROM me_login WHERE login ='$login_dep'";
$query_ver_login =  mysqli_query($conexao, $verificar_login);
$quant_login = mysqli_num_rows($query_ver_login);

//Verifica se já existe o login
if ($quant_login > 0) {
    echo "<script>alert('O login já existe, por favor escolha outro')</script>";
    echo "<script>window.location.href='addDependente.php'</script>";
} else {
    //Verifica se o login tem caracteres especiais
    if (!preg_match('/^[a-zA-Z0-9]+$/', $login_dep)) {
        echo "<script>alert('O login deve conter apenas letras e números.')</script>";
        echo "<script>window.location.href='addDependente.php.php'</script>";
      }else{
        $incluir_usuario = "INSERT INTO me_usuario (nome_usuario, email, id_tipo_usuario)
        VALUES ('$nomeDependente', '$email', 2);";

        $incluir_login = "INSERT INTO me_login (login, senha, id_usuario)
        VALUES ('$login_dep', '$senha', (SELECT id_usuario FROM me_usuario WHERE nome_usuario = '$nomeDependente'))";

        $incluir_dep ="INSERT INTO me_dependente(nome_dependente, id_usuario)
        VALUES ('$login_dep',(SELECT id_usuario FROM me_login WHERE login = '$login'))";

        $query_incluir = mysqli_query($conexao, $incluir_usuario) && mysqli_query($conexao, $incluir_login) && mysqli_query($conexao, $incluir_dep);

        if($query_incluir){
            header("location: addDependente.php");
        } else {
            echo "<script>alert('Erro ao cadastrar usuário.')</script>";
        }
      }

      
}


?>