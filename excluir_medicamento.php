<?php
include('conexao.php');
session_start();
$login = $_SESSION['login'];

$id_horario = $_GET['id_horario'];

$delete = "DELETE FROM me_horario WHERE id_horario = '$id_horario'";
$query_delete = mysqli_query($conexao, $delete);

if($query_delete) {
    echo "<script>alert('Dado excluido com sucesso')</script>";
    echo "<script>window.location.href='principal.php'</script>";
} else {
    echo "Erro ao excluir registro.";
}


?>