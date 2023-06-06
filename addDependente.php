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
    echo "<audio autoplay><source src='audio/alarme_clock_audio_ringtone.mp3' type='audio/mpeg'></audio>";
  }
} else {
  // Se não houver alarmes no horário atual, agendamos a próxima verificação em 1 minuto
  echo "<script>setTimeout(function() { location.reload(); }, 60000);</script>";
}

// Consulta SQL para obter os dependentes associados ao login
$consulta = "SELECT d.nome_dependente, d.id_dependente 
FROM me_dependente d
INNER JOIN me_login l ON l.id_usuario = d.id_usuario
WHERE l.login = '$login'";
$resultado = mysqli_query($conexao, $consulta);
$dependentes = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
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

    <div class="container">
      <div id="nav">
        <div id="logo">
          <a href="login.php">
            <img src="img/logo_plannermed.png">
          </a>
        </div>
        <div class="mobile-menu">
          <div class="line1"></div>
          <div class="line2"></div>
          <div class="line3"></div>
        </div>
        <div id="menu">
          <ul>
            <li><a href="principal.php">Diário</a></li>
            <li><a href="remedios.php">Remédios</a></li>
            <li><a href="addDependente.php" class="active">Depedentes</a></li>
            <li><a href="sobre.php">Sobre nós</a></li>
          </ul>
        </div>
        <div id="perfil">
          <img src="img/tentativa.png"><br><br>
          <label id="nome_perfil"><?php echo $login ?></label>
          <div class="seta">
            <img id="seta-img" src="img/seta-perfil.svg" alt="Seta para baixo">
          </div>

          <div id="menu-dropdown" style="display: none;">
            <!-- Conteúdo do menu dropdown -->
            <a href="perfil.php">Dados do perfil</a>
            <a href="#">Histórico</a>
            <a href="login.php">Sair</a>
          </div>
        </div>
      </div>

      <div class="box-one">

        <h2>Dependentes Cadastrados:</h2><br>
        <?php foreach ($dependentes as $dependente) : ?>
          <div class="dependente">
            <img src="img/tentativa.png" alt="Foto do dependente">
            <div>
              <span><?php echo $dependente['nome_dependente']; ?></span>
              <a class="editar_dep" href="informacao_dependente.php?id_dependente=<?php echo $dependente['id_dependente']; ?>
          &nome_dependente=<?php echo $dependente['nome_dependente']; ?>">Acessar Dependente</a>
            </div>
          </div>
        <?php endforeach; ?>

        <div class="actions">
          <form action="" method="POST">
            <button type="submit" name="addPerfil">Adicionar Perfil de Dependente Ativo</button>
          </form>

          <form action="" method="POST">
            <button type="submit" name="cadastrarPerfil">Cadastrar Novo Perfil de Dependente</button>
          </form>
        </div>
       
          <button type="button" class="voltar-button" onclick="goBack()">
            Voltar
          </button>
        

        <script>
          //Voltar para a última página acessada
          function goBack() {
            window.history.back();
          }
          // Seleciona a imagem de seta pelo ID
          const setaImg = document.getElementById('seta-img');

          // Seleciona o menu-dropdown pelo ID
          const menuDropdown = document.getElementById('menu-dropdown');

          // Adiciona um evento de clique à imagem de seta
          setaImg.addEventListener('click', function() {
            // Verifica se o menu-dropdown está visível
            const isMenuVisible = menuDropdown.style.display === 'block';

            // Alterna a visibilidade do menu-dropdown
            if (isMenuVisible) {
              menuDropdown.style.display = 'none'; // Oculta o menu-dropdown
            } else {
              menuDropdown.style.display = 'block'; // Exibe o menu-dropdown
            }
          });
          // Supondo que o login esteja armazenado em uma variável chamada "login"
          const login = "<?php echo $_SESSION['login']; ?>";

          setInterval(function() {
            console.log(`Verificando alarmes às ${moment().format('YYYY-MM-DD HH:mm:ss')}`);
            tocarAlarmes(login);
          }, 60000); // Verificar a cada 1 minuto (60000 milissegundos)
        </script>
        <script src="script_principal.js"></script>
        <script src="script_addDependente.js"></script>
      </div>
    </div>
  </center>
</body>

</html>