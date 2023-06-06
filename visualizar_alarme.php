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

$select_alarmes = "SELECT * FROM me_horario WHERE id_medicamento = $id_medicamento AND login = '$login'";
$query_alarmes = mysqli_query($conexao, $select_alarmes);

// Obtendo o tipo de usuário do banco de dados
$select_tipo_usuario = "SELECT id_tipo_usuario FROM me_usuario
 WHERE id_usuario IN (SELECT id_usuario FROM me_login WHERE login = '$login')";

$query_tipo_usuario = mysqli_query($conexao, $select_tipo_usuario);
$dado_tipo_usuario = mysqli_fetch_assoc($query_tipo_usuario);

$id_tipo_usuario = $dado_tipo_usuario['id_tipo_usuario'];

?>

<!DOCTYPE html>
<html>

<head>
  <title>Visualizar Alarmes</title>
  <link rel="stylesheet" href="style_visu_alarme.css">
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
          <li><a href="principal.php">Diário</a></li>
          <li><a href="remedios.php">Remédios</a></li>
          <?php
              if ($id_tipo_usuario == 1) {
          echo '<li><a href="addDependente.php">Depedentes</a></li>';
            } ?>
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
    <center>
      <div class="box-one">
        <h1>Detalhes do Alarme - <?php echo $nomeMedicamento; ?></h1>
        <table>
          <tr>
            <th>Horário</th>
            <th>Login</th>
            <th>Medicamento</th>
            <?php
            if ($id_tipo_usuario == 1) {
              echo '<th>Editar Medicação</th>';
              echo '<th>Excluir Medicamento</th>';
            }
            ?>
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
              <?php
              if ($id_tipo_usuario == 1) {
                echo '<td><a href="update_medicamento.php?id_horario=' . $alarme['id_horario'] . '&nome_medicamento=' . $nomeMedicamento . '">editar horário</a></td>';
                echo '<td><a href="excluir_medicamento.php?id_horario=' . $alarme['id_horario'] . '">excluir medicação</a></td>';
              }
              ?>



            </tr>
          <?php } ?>
        </table>

        <form action="remedios.php" style="text-align: center;">
          <button type="submit" class="voltar-button">
            Voltar
          </button>
        </form>

      </div>
    </center>
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

</body>

</html>