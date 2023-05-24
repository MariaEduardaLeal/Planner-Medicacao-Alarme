<?php
include('conexao.php');

$email = isset($_POST['email'])? $_POST['email'] : '';
$login = isset($_POST['login'])? $_POST['login'] : '';
$senha = isset($_POST['senha'])? $_POST['senha'] : '';
if ($email == '' || $login == '' || $login =='') { 
    echo "<script>window.alert('Os campos são obrigatórios')</script>";
    echo "<script>window.location.href='confirmar_dados.php'</script>";
} else {
    $query_verificar_usuario = $query_verificar_usuario = "SELECT * FROM me_usuario
    INNER JOIN me_login
    ON me_usuario.id_usuario = me_login.id_usuario
    WHERE me_usuario.email = '$email' AND me_login.login = '$login'
    ";


    $resultado_verificar_usuario = mysqli_query($conexao, $query_verificar_usuario);

    if (mysqli_num_rows($resultado_verificar_usuario) > 0) { 
        $renovar = "UPDATE me_login SET senha = '$senha' WHERE login = '$login'";
        $query_validacao = mysqli_query($conexao, $renovar);
    
        if(!$query_validacao){
            die("Erro na consulta: " . mysqli_error($conexao));
        }else{
            header("location: login.php");
        }
    } else {
        echo "<script>window.alert('Email e ou senhas inválidos')</script>";
        echo "<script>window.location.href='confirmar_dados.php'</script>";
    }
}
?>