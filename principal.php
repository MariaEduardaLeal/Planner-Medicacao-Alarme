<?php
date_default_timezone_set('America/Sao_Paulo');
session_start(); // inicia a sessão
include('conexao.php');

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
    echo "<audio autoplay><source src='audio/alarme_clock_audio_ringtone.mp3' type='audio/mpeg'></audio>";
  }
} else {
  // Se não houver alarmes no horário atual, agendamos a próxima verificação em 1 minuto
  echo "<script>setTimeout(function() { location.reload(); }, 60000);</script>";
}

//Pegando as informações dos horários
// Obtém a data atual no formato do banco de dados (ano-mês-dia)
$dataAtual = date('Y-m-d');

$select = "SELECT me_horario.*, me_medicamento.nome_medicamento
            FROM me_horario
            INNER JOIN me_medicamento ON me_horario.id_medicamento = me_medicamento.id_medicamento
            WHERE me_horario.login = '$login'
            AND DATE(me_horario.horario) = '$dataAtual'";

$query_horario = mysqli_query($conexao, $select);

// Obtendo o tipo de usuário do banco de dados
$select_tipo_usuario = "SELECT id_tipo_usuario FROM me_usuario
 WHERE id_usuario IN (SELECT id_usuario FROM me_login WHERE login = '$login')";

$query_tipo_usuario = mysqli_query($conexao, $select_tipo_usuario);
$dado_tipo_usuario = mysqli_fetch_assoc($query_tipo_usuario);

$id_tipo_usuario = $dado_tipo_usuario['id_tipo_usuario'];


?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="style_principal.css">
</head>

<body>
  <div class="container">
    <div id="nav">
      <div id="logo">
        <a href="login.php">
          <img src="img/logo_plannermed.png">
        </a>
      </div>

      <div id="menu">
          <ul>
            <li><a href="principal.php" class="active">Diário</a></li>
            <li><a href="remedios.php">Remédios</a></li>
            <li><a href="addDependente.php">Depedentes</a></li>
            <li><a href="sobre.php">Sobre nós</a></li>
          </ul>
        </div>

        <div id="perfil">
          <img src="img/icon-usuario-dependente-2.svg"><br><br>
          <label id="nome_perfil"><?php echo $login ?></label>
        </div>

        <div id="menuUser">
          <i id="burguer" class="material-icons" onclick="clickMenu()">menu</i>
          <menu id="itens">
            <ul>
              <li><a href="perfil.php">Dados do perfil</a></li>
              <li><a href="historico.php">Histórico</a></li>
              <li><a href="login.php">Sair</a></li>
            </ul>
          </menu>
        </div>
      </div>
    <div id="main">
      <button class="meu-botao" onclick="window.location.href ='principal2.php'">
      <div class="conteudo-botao">
          <img src="img/icon-button-adicionar-alarme.svg">
          <?php
          if ($id_tipo_usuario == 1) {
            echo '<span>Adicionar medicação</span>';
          } else {
            echo '<span>Pesquisar Medicação</span>';
          } ?>
        </div>
      </button>
      <?php
      while ($dado_horario = mysqli_fetch_assoc($query_horario)) {
        $idHorario = $dado_horario['id_horario'];
        $horario = date('H:i', strtotime($dado_horario['horario']));
        $nomeMedicamento = $dado_horario['nome_medicamento'];
        $dosagem = $dado_horario['dosagem'];
        $concentracao = $dado_horario['concentracao'];
      ?>
        <div class="retangulo">
          <div class="conteudo-retangulo">
            <img id="relogio" src="img/icon-box-horario.svg">

            <label id="horario"><?php echo $horario; ?></label>

            <label class="nome_medicamento"><?php echo $nomeMedicamento; ?></label>

            <div class="dosagem-concentracao">
              <img id="cubo" src="img/icon-dosagem.svg">
              <label class="dosagem"><?php echo $dosagem; ?></label>

              <img id="bucket" src="img/icon-concentracao.svg">
              <label class="concentracao"><?php echo $concentracao; ?></label>
            </div>

            <ul id="lista" style="list-style-type: none;">
              <li style="margin-bottom: 0.4cm; text-align: right;">
                <a href="pesquisa_med.php?medicamento=<?php echo $nomeMedicamento; ?>">Acessar bula</a>
              </li>
              <?php
              if ($id_tipo_usuario == 1) {
                echo '<li style="margin-bottom: 0.4cm; text-align: right;">
            <a href="update_medicamento.php?id_horario=' . $dado_horario['id_horario'] . '&nome_medicamento=' . $dado_horario['nome_medicamento'] . '">Editar medicação</a></li>';
                echo '<li style="margin-bottom: 0.4cm; text-align: right;"> <a href="excluir_medicamento.php?id_horario=' . $dado_horario['id_horario'] . '">Excluir horário</a></li>';
              } ?>
            </ul>

          </div>
        </div>
      <?php } ?>
    </div>
    <div id="footer">
      <div id="main2">
      <button class="meu-botao2" onclick="window.location.href = 'principal2.php'">
          <div class="conteudo-botao2">
            <img src="img/icon-button-adicionar-alarme.svg">
            <?php
            if ($id_tipo_usuario == 1) {
              echo '<span>Adicionar medicação</span>';
            } else {
              echo '<span>Pesquisar Medicação</span>';
            } ?>

          </div>
        </button>
      </div>
    </div>
    <script>
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
    <script>
        function clickMenu() {
          if (itens.style.display == 'block') {
            itens.style.display = 'none'; //se estiver visível, ao clicar oculta
          } else {
            itens.style.display = 'block'; //se não, revela
          }
        }
      </script>
</body>

</html>