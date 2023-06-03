<?php
include('conexao.php');
session_start();

$nome_medicamento = $_POST['nome_medicamento'];
$id_horario = $_POST['id_horario'];
$novo_horario = $_POST['novo_horario'];
$dosagem = $_POST['dosagem'];
$concentracao = $_POST['concentracao'];

    if ($horario_valido = DateTime::createFromFormat('Y-m-d\TH:i', $novo_horario)) {
        // Convertendo o objeto DateTime para um formato compatível com o MySQL
        $mysql_datetime = $horario_valido->format('Y-m-d H:i:s');

        $update = "UPDATE me_horario 
            SET horario = '$mysql_datetime', dosagem = '$dosagem', concentracao = '$concentracao' 
            WHERE id_horario = '$id_horario'";

        $query_update = mysqli_query($conexao, $update);

        if ($query_update) {
            header("location: principal.php");
        } else {
            echo "Erro ao atualizar o medicamento";
        }
    } else {
        echo "Formato de horário inválido";
    }

?>
