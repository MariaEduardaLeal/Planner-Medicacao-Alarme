<?php
include('conexao.php');

$nome = $_POST['nome'];
$login = $_POST['login'];
$email= $_POST['email'];
$senha = $_POST['senha'];

$verificar_login = "SELECT login FROM me_login WHERE login ='$login'";
$query_ver_login =  mysqli_query($conexao, $verificar_login);
$quant_login = mysqli_num_rows($query_ver_login);

if ($quant_login > 0) {
    echo "<script>alert('O login já existe, por favor escolha outro')</script>";
    echo "<script>window.location.href='cadastro.php'</script>";
} else {
    if (!preg_match('/^[a-zA-Z0-9]+$/', $login)) {
        //TRY e CATCH
        //stackTrace
        echo "<script>alert('O login deve conter apenas letras e números.')</script>";
        echo "<script>window.location.href='cadastro.php'</script>";
      } else {
        if ($_POST['tipo_usuario'] == 1) {
            $verificar_email ="SELECT email FROM me_usuario WHERE email = '$email'";
            $query_verificar = mysqli_query($conexao, $verificar_email);
        
            if (mysqli_num_rows($query_verificar) > 0){
                echo('Usuário já cadastrado');
            } else {
                $incluir_usuario = "INSERT INTO me_usuario (nome_usuario, email, id_tipo_usuario)
                VALUES ('$nome', '$email', 1);";
        
                $incluir_login = "INSERT INTO me_login (login, senha, id_usuario)
                VALUES ('$login', '$senha', (SELECT id_usuario FROM me_usuario WHERE nome_usuario = '$nome'))";
        
                $query_incluir = mysqli_query($conexao, $incluir_usuario) && mysqli_query($conexao, $incluir_login);
        
                if($query_incluir){
                    header("location: login.php");
                } else {
                    echo "<script>alert('Erro ao cadastrar usuário.')</script>";
                }
            }
        } else if ($_POST['tipo_usuario'] == 2) {
            $incluir_usuario = "INSERT INTO me_usuario (nome_usuario, email, id_tipo_usuario)
            VALUES ('$nome', '$email', 2);";
        
            $incluir_login = "INSERT INTO me_login (login, senha, id_usuario)
            VALUES ('$login', '$senha', (SELECT id_usuario FROM me_usuario WHERE nome_usuario = '$nome'))";
        
            $query_incluir = mysqli_query($conexao, $incluir_usuario) && mysqli_query($conexao, $incluir_login);
        
            if($query_incluir){
                header("location: login.php");
            } else {
                echo "<script>alert('Erro ao cadastrar usuário.')</script>";
            }
        }
      }
    
}


?>
