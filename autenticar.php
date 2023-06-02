<?php

include('conexao.php'); 

$login = isset($_POST['login'])? $_POST['login'] : ''; 
$senha = isset($_POST['senha'])? $_POST['senha'] : '';

if ($login == '' && $senha == '') {
    echo "<script>alert('O campo login e senha são obrigatórios')</script>";
    echo "<script>window.location.href='login.php'</script>";
}else{
    $select = "SELECT login, senha FROM me_login
                WHERE login = '$login' AND senha = '$senha' ";

    $query = mysqli_query($conexao, $select);
    $dado = mysqli_fetch_row($query);

    if($login == isset($dado[0]) && $senha == isset($dado[1])){ //O isset está verificando se a variável dado[1] e dado[2] é nula, pois se colocamos um usuário que não existe o valor dentro da variável será nula e retornará um aviso do php
        session_start(); // inicia a sessão
        $_SESSION['login'] = $dado[0];
        header("location: principal.php");
        
    }else{
    header("location: login.php");
    }
}
?>