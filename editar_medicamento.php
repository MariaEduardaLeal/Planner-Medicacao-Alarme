<?php
date_default_timezone_set('America/Sao_Paulo');
include('conexao.php');
session_start();

// Recupera os parâmetros da URL
$id_horario = $_GET['id_horario'];
$nome_medicamento = $_GET['nome_medicamento'];

// Consulta o horário atual, dosagem e concentração do medicamento
// Consulta o horário atual, dosagem e concentração do medicamento
$query = "SELECT h.horario, h.dosagem, h.concentracao
          FROM me_horario h
          WHERE h.id_horario = $id_horario";

$resultado = mysqli_query($conexao, $query);
$medicamento = mysqli_fetch_assoc($resultado);

$horario_atual = $medicamento['horario'];
$dosagem = $medicamento['dosagem'];
$concentracao = $medicamento['concentracao'];

$login = $_SESSION['login'];

// Obtém a data e hora atual no formato do banco de dados (ano-mês-dia hora:minuto:segundo)
$agora = date('Y-m-d H:i:s');

// Calcula o intervalo de tempo de 1 minuto antes e depois do horário atual
$intervalo_inicio = date('Y-m-d H:i:s', strtotime($agora) - 60);
$intervalo_fim = date('Y-m-d H:i:s', strtotime($agora) + 60);
// Isso é feito para considerar uma margem de segurança na verificação dos alarmes
// e garantir que nenhum alarme relevante seja perdido devido a pequenas variações de tempo.

// Consulta SQL para buscar os alarmes cujo horário esteja dentro do intervalo de tempo
$select = "SELECT me_horario.*, me_medicamento.nome_medicamento
           FROM me_horario
           INNER JOIN me_medicamento ON me_horario.id_medicamento = me_medicamento.id_medicamento
           WHERE me_horario.login = '$login'
           AND me_horario.horario >= '$intervalo_inicio'
           AND me_horario.horario <= '$intervalo_fim'";

$query_alarmes = mysqli_query($conexao, $select);

if (mysqli_num_rows($query_alarmes) > 0) {
  // Exibe o alerta para o usuário com os medicamentos cujo horário esteja dentro do intervalo
  while ($dado_alarme = mysqli_fetch_assoc($query_alarmes)) {
    $nomeMedicamento = $dado_alarme['nome_medicamento'];
    echo "<script>alert('Hora de tomar o remédio: $nomeMedicamento');</script>";
  }
} else {
  // Se não houver alarmes no horário atual, agendamos a próxima verificação em 1 minuto
  echo "<script>setTimeout(function() { location.reload(); }, 60000);</script>";
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Atualizar Medicamento</title>
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
  <h1>Atualizar Medicamento - <?php echo $nome_medicamento; ?></h1>

  <form method="POST" action="update_medicamento_scripting.php">
    <label>Dosagem: </label><br>
    <input type="text" name="dosagem" value="<?php echo $dosagem ?>" required><br>
    <label>concentracao: </label><br>
    <input type="text" name="concentracao" value="<?php echo $concentracao ?>" required><br>
    <input type="hidden" name="id_horario" value="<?php echo $id_horario; ?>">
    <label>Horário Atual: <?php echo $horario_atual; ?></label><br>
    <label>Novo Horário:</label>
    <input type="datetime-local" name="novo_horario" required><br>
    <input type="submit" value="Atualizar">
  </form>

  <form action="remedios.php">
    <button>
      voltar
    </button>
  </form>
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