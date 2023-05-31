<?php
session_start(); // inicia a sessão
include('conexao.php');

$login = $_SESSION['login'];

$nomeDependente = $_POST['nomeDependente'];
$login_dep = $_POST['loginDependente'];
$email = $_POST['emailDependente'];
$senha_dep = $_POST['confSenha'];

$verificar_user = "SELECT nome_usuario, email FROM me_usuario 
WHERE nome_usuario = '$nomeDependente' AND email = '$email'";

$verificar_login = "SELECT id_usuario, login, senha FROM me_login 
WHERE login = '$login_dep' AND id_usuario = (SELECT id_usuario FROM me_login WHERE login = '$login_dep' AND senha = '$senha_dep')";

$query_verificar =  mysqli_query($conexao, $verificar_user) && mysqli_query($conexao, $verificar_login);

if ($query_verificar) {
    $quant_user = mysqli_num_rows(mysqli_query($conexao, $verificar_user));
    $quant_login = mysqli_num_rows(mysqli_query($conexao, $verificar_login));

    if ($quant_user > 0 && $quant_login > 0) {
        $incluir_dep ="INSERT INTO me_dependente(nome_dependente, id_usuario)
        VALUES ('$login_dep',(SELECT id_usuario FROM me_login WHERE login = '$login'))";

        $query_incluir = mysqli_query($conexao, $incluir_dep);

        if($query_incluir){
            header("location: addDependente.php");
        } else {
            echo "<script>alert('Erro ao cadastrar usuário.')</script>";
        }
    }//if ($quant_user > 0 && $quant_login > 0)
    else {
        echo "<script>alert('Os campos devem ser preenchidos exatamente como cadastrados.')</script>";
        echo "<script>window.location.href='dep_existente.php'</script>";
    }
}//if ($query_verificar)
else{
    echo "<script>alert('Erro ao verificar informações do perfil.')</script>";
    echo "<script>window.location.href='dep_existente.php'</script>";
}

?>