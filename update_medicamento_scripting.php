<?php
include('conexao.php');
session_start();

    $login = $_SESSION['login'];
    $nome_medicamento = $_POST['nome_medicamento'];
    $id_horario = $_POST['id_horario'];
    $horarios = isset($_POST['horarios']) ? $_POST['horarios'] : '';

    if ($nome_medicamento == '' || $id_horario == '' || $horarios == '') {
        echo "<script>alert('Os campos são obrigatórios')</script>";
        echo "<script>window.location.href='update_medicamento.php'</script>";
    }else {
        foreach ($horarios as $horario) {
            if ($horario_valido = DateTime::createFromFormat('Y-m-d H:i', $horario)) {
                // Convertendo o objeto DateTime para um formato compatível com o MySQL
                $mysql_datetime = $horario_valido->format('Y-m-d H:i:s');

                $update = "UPDATE me_horario 
                SET horario = '$mysql_datetime' WHERE id_horario = '$id_horario'";
        
                $query_update = mysqli_query($conexao, $update);

                if ($query_update) {
                    header("location: perfil.php");
                }
                else {
                    echo "erro";
                }
             }//if com data formatada
        }//foreach
    }//else
?>