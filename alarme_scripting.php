<?php
include('conexao.php');
session_start();
$login = $_SESSION['login'];
$nome_medicamento = $_POST['nome_medicamento'];
$opcao = $_POST['opcao'];
$inicio = $_POST['inicio'];
$duracao = $_POST['duracao'];
$frequencia = $_POST['frequencia'];

// Obter o ID do medicamento com base no nome
$consulta_medicamento = "SELECT id_medicamento FROM me_medicamento WHERE nome_medicamento = '$nome_medicamento'";
$resultado_medicamento = mysqli_query($conexao, $consulta_medicamento);

if (mysqli_num_rows($resultado_medicamento) > 0) {
  $row = mysqli_fetch_assoc($resultado_medicamento);
  $id_medicamento = $row['id_medicamento'];

  // Converter a data de início para o formato adequado do MySQL
  $mysql_inicio = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $inicio)));

  for ($i = 0; $i < $duracao; $i++) {
    $data_alarme = date('Y-m-d', strtotime($mysql_inicio . ' + ' . $i . ' days'));
    $hora_alarme = date('H:i:s', strtotime($mysql_inicio . ' + ' . ($i * 24 * $frequencia) . ' hours'));

    // Inserir os dados no banco de dados
    $incluir = "INSERT INTO me_horario (id_horario, id_medicamento, horario, login) 
    VALUES (null, $id_medicamento, '$data_alarme $hora_alarme', '$opcao')";
    mysqli_query($conexao, $incluir);
  }
} else {
  echo 'Medicamento não encontrado.';
}
?>
