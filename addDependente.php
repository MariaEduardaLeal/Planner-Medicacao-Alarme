<?php
session_start(); // inicia a sessão
include('conexao.php');

$login = $_SESSION['login'];

// Processar o formulário de adicionar perfil ativo
if (isset($_POST['addPerfil'])) {
  // Redirecionar para a página desejada
  header('Location: dep_existente.php');
  exit();
}

// Processar o formulário de cadastrar novo perfil
if (isset($_POST['cadastrarPerfil'])) {
  // Redirecionar para a página desejada
  header('Location: cad_dependente.php');
  exit();
}
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
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Adicionar Dependente</title>
  <link rel="stylesheet" href="style_addDependente.css">
</head>

<body>
  <center>
    <nav>
      <a class="logo" href="login.php">Planner Medicamentos</a>
    </nav>
    <div class="container">
      <div class="box-one">


        <main>

          <h1>Escolha uma opção:</h1>

          <form action="" method="POST">
            <button type="submit" name="addPerfil">Adicionar Perfil de Dependente Ativo</button>
          </form>

          <form action="" method="POST">
            <button type="submit" name="cadastrarPerfil">Cadastrar Novo Perfil de Dependente</button>
          </form>

          <form action="escolher_dependente.php">
            <button type="submit">Visualizar Dependentes</button>
          </form>

          <form action="principal2.php">
            <button type="submit">Voltar</button>
          </form>
        </main>
        <script>
          // Supondo que o login esteja armazenado em uma variável chamada "login"
          const login = "<?php echo $_SESSION['login']; ?>";

          setInterval(function() {
            console.log(`Verificando alarmes às ${moment().format('YYYY-MM-DD HH:mm:ss')}`);
            tocarAlarmes(login);
          }, 60000); // Verificar a cada 1 minuto (60000 milissegundos)
        </script>
      </div>
    </div>
  </center>
</body>

</html>