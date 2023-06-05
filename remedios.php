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
  }
} else {
  // Se não houver alarmes no horário atual, agendamos a próxima verificação em 1 minuto
  echo "<script>setTimeout(function() { location.reload(); }, 60000);</script>";
}
//Vamos pegar as informações da tabela me_horario do nosso banco de dados
$select = "SELECT me_horario.*, me_medicamento.nome_medicamento
            FROM me_horario
            INNER JOIN me_medicamento ON me_horario.id_medicamento = me_medicamento.id_medicamento
            WHERE me_horario.login = '$login'";

$query_horario = mysqli_query($conexao, $select);


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
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
          <li><a href="principal.php">Diário</a></li>
          <li><a href="#Remédios" class="active">Remédios</a></li>
          <li><a href="addDependente.php">Depedentes</a></li>
          <li><a href="Sobre_nos.php">Sobre nós</a></li>
        </ul>
      </div>
      <div id="perfil">
        <img src="img/tentativa.png"><br><br>
        <label id="nome_perfil"><?php echo $login ?></label><br>
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
    <div id="main">
      <button class="meu-botao" onclick="window.location.href = 'principal2.php'">
        <div class="conteudo-botao">
          <img src="img/icon-button-adicionar-alarme.svg">
          <span>Adicionar medicação</span>
        </div>
      </button>
      <?php

      $medicamentos = array();

      while ($dado_horario = mysqli_fetch_assoc($query_horario)) {
        $nomeMedicamento = $dado_horario['nome_medicamento'];

        // Verifica se o medicamento já existe no array de medicamentos
        if (isset($medicamentos[$nomeMedicamento])) {
          // Atualiza a última data encontrada
          $ultima_data = $dado_horario['horario'];
        } else {
          // Cria um novo item no array de medicamentos
          $medicamentos[$nomeMedicamento] = array(
            'primeira_data' => $dado_horario['horario'],
            'ultima_data' => $dado_horario['horario'],
            'dados' => $dado_horario,
            'horarios' => array() // Adiciona um subarray para armazenar os horários dos alarmes
          );
        }

        // Atualiza a última data para o medicamento atual
        $medicamentos[$nomeMedicamento]['ultima_data'] = $dado_horario['horario'];

        // Adiciona o horário dos alarmes ao subarray de horários do medicamento
        $horario = date('H:i', strtotime($dado_horario['horario']));
        $medicamentos[$nomeMedicamento]['horarios'][] = $horario;
      }

      // Exibe apenas o último retângulo para cada nome de medicamento
      foreach ($medicamentos as $nomeMedicamento => $medicamento) {
        // Extrai as informações do medicamento
        $primeira_data = $medicamento['primeira_data'];
        $ultima_data = $medicamento['ultima_data'];
        $dados_medicamento = $medicamento['dados'];

        // Extrai as informações do medicamento
        $idHorario = $dados_medicamento['id_horario'];
        $horario = date('H:i', strtotime($dados_medicamento['horario']));
        $dosagem = $dados_medicamento['dosagem'];
        $concentracao = $dados_medicamento['concentracao'];

        // Cálculo da duração em dias
        $data_inicio = new DateTime($primeira_data);
        $data_fim = new DateTime($ultima_data);
        $intervalo = $data_inicio->diff($data_fim);
        $duracao_em_dias = $intervalo->days + 1; // +1 para incluir o último dia
      ?>

        <div class="retangulo">
          <div class="conteudo-retangulo">
            <img id="box_azul" src="img/icon-box-medicacao.svg">
            <label id="nome_remedio"><?php echo $nomeMedicamento ?></label>
            <img id="cubo_duplo" src="img/icon-dosagem.svg">
            <label id="dosagem"><?php echo $dosagem ?></label>
            <img id="bucket" src="img/icon-concentracao.svg">
            <label id="concentracao"><?php echo $concentracao ?></label>
            <img id="bandeira" src="img/icon-data-inicio.svg">
            <label id="inicio_medicacao"><?php echo date('d/m/Y', strtotime($primeira_data)); ?></label>
            <img id="quadriculada" src="img/icon-data-termino.svg">
            <label id="final_medicacao"><?php echo date('d/m/Y', strtotime($ultima_data)); ?></label>
            <img id="ampulheta" src="img/icon-duracao.svg">
            <label id="periodo_medicamento"><?php echo "Duração: $duracao_em_dias dias"; ?></label>

            <ul class="links-direita">
  <li><a href="visualizar_alarme.php?nome_med=<?php echo $nomeMedicamento ?>">Visualizar alarmes</a></li>
  <li><a href="pesquisa_med.php?medicamento=<?php echo $nomeMedicamento; ?>">Acessar bula</a></li>
  <li><a href="editar_medicamento.php?id_horario=<?php echo $dado_horario['id_horario'] ?>&nome_medicamento=<?php echo $dado_horario['nome_medicamento'] ?>">editar medicação</a></li>
</ul>



            <div class="linha">

              <div class="divisória"></div>

            </div>

          </div>

        </div>
      <?php   } ?>

      <div id="main2">
        <button class="meu-botao2" onclick="window.location.href = 'principal2.php'">
          <div class="conteudo-botao2">
            <img src="img/icon-button-adicionar-alarme.svg">
            <span>Adicionar medicação</span>
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
    <script src="script_principal2.js"></script>
</body>

</html>