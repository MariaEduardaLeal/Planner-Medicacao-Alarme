<?php
include('conexao.php');
session_start();
$login = $_SESSION['login'];

$login_dep = $_GET['login_dep'];
$id_usuario = $_GET['id_usuario'];

$deletar_dep = "DELETE FROM me_dependente 
WHERE nome_dependente = '$login_dep' AND id_usuario = (SELECT id_usuario FROM me_login WHERE login = '$login')";
$query_deletar_dep = mysqli_query($conexao, $deletar_dep);

if ($query_deletar_dep) {
    echo "<script>alert('Dependente excluído com sucesso.')</script>";
    echo "<script>window.location.href='login.php'</script>";
} else {
    echo "Erro ao excluir o dependente.";
}

?>
