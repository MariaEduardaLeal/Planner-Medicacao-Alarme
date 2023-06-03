<?php
date_default_timezone_set('America/Sao_Paulo');
include('conexao.php');
session_start();
$login = $_SESSION['login'];

$nomeMedicamento = $_GET['nome_med'];

$select_id_medicamento = "SELECT id_medicamento FROM me_medicamento WHERE nome_medicamento = '$nomeMedicamento'";
$query_id_medicamento = mysqli_query($conexao, $select_id_medicamento);
$dado_id_medicamento = mysqli_fetch_assoc($query_id_medicamento);
$id_medicamento = $dado_id_medicamento['id_medicamento'];

$select_alarmes = "SELECT * FROM me_horario WHERE id_medicamento = $id_medicamento";
$query_alarmes = mysqli_query($conexao, $select_alarmes);



?>

<!DOCTYPE html>
<html>

<head>
  <title>Visualizar Alarmes</title>
  <link rel="stylesheet" href="style_visu_alarme.css">
</head>

<body>
  <div id="menu">
    <ul>
      <li><a href="principal.php">Diário</a></li>
      <li><a href="remedios.php" class="active">Remédios</a></li>
      <li><a href="dependentes.php">Depedentes</a></li>
      <li><a href="Sobre_nos.php">Sobre nós</a></li>
    </ul>
  </div>
  <h1>Detalhes do Alarme - <?php echo $nomeMedicamento; ?></h1>
  <table>
    <tr>
      <th>Horário</th>
      <th>Login</th>
      <th>Medicamento</th>
      <th>Editar Medicação</th>
      <th>Excluir Medicamento</th>
    </tr>
    <?php
    while ($alarme = mysqli_fetch_assoc($query_alarmes)) {
      $horario = $alarme['horario'];
      $login = $alarme['login'];
    ?>
      <tr>
        <td><?php echo $horario; ?></td>
        <td><?php echo $login; ?></td>
        <td><?php echo $nomeMedicamento; ?></td>
        <td> <a href="update_medicamento.php?id_horario=<?php echo $alarme['id_horario'] ?>&nome_medicamento=<?php echo $nomeMedicamento ?>">editar horário</a></td>
        <td> <a href="excluir_medicamento.php?id_horario=<?php echo $alarme['id_horario'] ?>">excluir medicação</a></td>
      </tr>
    <?php } ?>
  </table>

  <script>
    // Supondo que o login esteja armazenado em uma variável chamada "login"
    const login = "<?php echo $_SESSION['login']; ?>";

    setInterval(function() {
      console.log(`Verificando alarmes às ${moment().format('YYYY-MM-DD HH:mm:ss')}`);
      tocarAlarmes(login);
    }, 60000); // Verificar a cada 1 minuto (60000 milissegundos)
  </script>

</body>

</html>