<?php 
include('conexao.php');
session_start();
$login = $_SESSION['login'];

$novo_nome = $_POST['novo_nome'];
$novo_login = $_POST['novo_login'];
$novo_email= $_POST['novo_email'];
$nova_senha = $_POST['nova_senha'];

if ($novo_nome == '' || $novo_login == '' || $novo_email == '' || $nova_senha == '') {
    //Se as informações forem nulas o código volta para a página de inserir dados
    echo "<script>window.alert('Os campos são obrigatórios')</script>";
    echo "<script>window.location.href='mudar_dados.php'</script>";
} else {
    //verificar se o login já existe
    $verificar_login = "SELECT login FROM me_login WHERE login ='$novo_login'";
    $query_ver_login =  mysqli_query($conexao, $verificar_login);
    $quant_login = mysqli_num_rows($query_ver_login);

    if ($quant_login > 0) {
        //Se o login já existir ele manda o usuário mudar
        echo "<script>alert('O login já existe, por favor escolha outro')</script>";
        echo "<script>window.location.href='mudar_dados.php'</script>";
    }else {
        //Código update da tabela usuario
        $renovar_usuario = "UPDATE me_usuario
        SET nome_usuario = '$novo_nome', 
            email = '$novo_email'
        WHERE id_usuario = (SELECT id_usuario FROM me_login WHERE login = '$login')";

        $query_update_usuario = mysqli_query($conexao, $renovar_usuario);

        if ($query_update_usuario ) {
              //Código update da tabela login
        $renovar_login = "UPDATE me_login 
        SET login = '$novo_login', 
            senha = '$nova_senha'
        WHERE login = '$login'";
  
        $query_update_login = mysqli_query($conexao, $renovar_login);
          if ( $query_update_login) {
            echo "<script>alert('Atualização feita com sucesso')</script>";
            echo "<script>window.location.href='login.php'</script>";
            }

        }else{
            echo "<script>alert('Erro ao fazer Update')</script>";
        }

    }
}

?>