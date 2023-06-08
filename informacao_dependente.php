<!DOCTYPE html>
<html>

<head>
  <title>Informações do Dependente</title>
  <link rel="stylesheet" href="style_informacao_dep.css">
</head>

<body>
  <?php
  include('conexao.php');
  session_start();
  $login = $_SESSION['login'];
  // Obtém os valores dos parâmetros
  $id_dependente = $_GET['id_dependente'];
  $nome_dependente = $_GET['nome_dependente'];

  // Executa a consulta para obter as informações do usuário
  $select_user = "SELECT me_usuario.*, me_login.*
                FROM me_usuario, me_login
                WHERE me_usuario.id_usuario = me_login.id_usuario
                AND me_login.login = '$nome_dependente'";

  $query_user = mysqli_query($conexao, $select_user);
  $dado_user = mysqli_fetch_row($query_user);

  $select_medicamento = "SELECT me_horario.*, me_medicamento.nome_medicamento
           FROM me_horario
           INNER JOIN me_medicamento ON me_horario.id_medicamento = me_medicamento.id_medicamento
           WHERE me_horario.login = '$nome_dependente'";
  $query_horario = mysqli_query($conexao, $select_medicamento);

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
  ?>
  <center>
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
            <li><a href="addDependente.php">Depedentes</a></li>
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
            <a href="historico.php">Histórico</a>
            <a href="login.php">Sair</a>
          </div>
        </div>
      </div>
      <div class="box-one">
        <h1>Informações do dependente</h1>
        <table>
          <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Login</th>
            <th>Excluir Dependente</th>
          </tr>
          <tr>
            <td><?php echo $dado_user[1] . "<br>"; ?></td>
            <td><?php echo $dado_user[2] . "<br>"; ?></td>
            <td><?php echo $dado_user[4] . "<br>" ?></td>
            <td><a href="excluir_usuario.php?id_usuario=<?php echo $dado_user[0]; ?>
                    &&login_dep=<?php echo $dado_user[4] ?>" id="link_excluir">Excluir Dependente</a></td>
          </tr>
        </table>
      </div>

      <div class="box-onde">
        <h3>Medicamentos e Horários Cadastrados:</h3>
        <?php
        if (mysqli_num_rows($query_horario) > 0) {
          echo "<table>";
          echo "<thead>";
          echo "<tr>";
          echo "<th>Data e Hora</th>";
          echo "<th>Login</th>";
          echo "<th>Medicamento</th>";
          echo "<th>Dosagem</th>";
          echo "<th>Concentração</th>";
          echo "<th>Excluir Alarmes</th>";
          echo "<th>Atualizar Alarmes</th>";
          echo "</tr>";
          echo "</thead>";
          echo "<tbody>";

          while ($dado_horario = mysqli_fetch_assoc($query_horario)) {
            echo "<tr>";
            echo "<td>" . date('d/m/Y H:i:s', strtotime($dado_horario['horario'])) . "</td>";
            echo "<td>" . $dado_horario['login'] . "</td>";
            echo "<td>" . $dado_horario['nome_medicamento'] . "</td>";
            echo "<td>" . $dado_horario['dosagem'] . "</td>";
            echo "<td>" . $dado_horario['concentracao'] . "</td>";
            //estou passando o dado de id_horario para poder excluir corretamente
            echo "<td><a href='excluir_medicamento.php?id_horario={$dado_horario['id_horario']}'>Excluir Medicamento</a></td>";
            echo "<td>
                    <a href='update_medicamento.php?id_horario={$dado_horario['id_horario']}&nome_medicamento={$dado_horario['nome_medicamento']}&dependente={$nome_dependente}'>
                    Atualizar Data e Hora</a></td>";

            echo "</tr>";
          }

          echo "</tbody>";
          echo "</table>";
        } else {
          echo "Nenhum medicamento cadastrado para este dependente.";
        }
        ?>
        <form action="principal2.php" style="text-align: center;">
          <button type="submit" class="voltar-button" onclick="goBack()">
            Voltar
          </button>
        </form>
      </div>
    </div>
  </center>
</body>
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

</html>