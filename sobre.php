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

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style_sobre.css" />
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
          <li><a href="#sobre" class="active">Sobre nós</a></li>
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

    <div class="boxQuemSomos">
      <div class="quem-somos-e-objetivos">
        <img src="img/icon-nosso-objetivo.svg" class="imagem-icon" id="imagemTopico">
        <h2 id="nossoObjetivo">Quem somos</h2>
        <p>Por meio dessa aplicação web, objetivamos auxiliar as pessoas à gerenciar a administração de suas medicações de forma mais fácil, eficiente
          e organizada. O aplicativo possui um design e usabilidade intuitivos e práticos tanto para usuários comuns como para usuários com menos
          experiência e/ou maior dificuldade no uso de tecnologias. Além disso, tivemos como meta a concepção de um aplicativo que permita também o
          gerenciamento da administração de medicações para dependentes (crianças, idosos etc.) e que automatize o cálculo dos intervalos de uso
          dessas medicações.</p>

        <img src="img/icon-quem-somos.svg" id="imagemTopico">
        <h2 id="quemSomos">Objetivos</h2>
        <p>Nossa equipe é composta por estudantes de Ciência da Computação da FPB. No entanto, mais do que isso, somos pessoas que buscam, por
          meio de seus conhecimentos, trazer retorno à sociedade na forma de solução de problemas através da tecnologia.</p>
      </div>

      <img src="img/icon-contatos.svg" id="imagemTopico">
      <h2 id="contatos">Contatos</h2>

    </div>
  </div>

  <div class="boxContatos">
    <div class="boxContatos1">
      <ul class="lista-contato">

        <li id="iconePerfil" class="nome">JéssicaRaissa</li>
        <li><a id="linkContato" href="mailto:jessicaraissapessoa@gmail.com?subject=Jéssica você está muito bonita hoje&jessicaraissapessoa@gmail.com"><img src="img/icon-gmail.svg"> Gmail</a></li>
        <li><a id="linkContato" href="https://www.linkedin.com/in/jessicaraissapessoa/" target="_blank"><img src="img/icon-linkedin.svg"> Linkedin</a></li>
        <li><a id="linkContato" href="https://github.com/jessicaraissapessoa" target="_blank"><img src="img/icon-github.svg"> GitHub</a></li>
      </ul>
    </div>

    <div class="boxContatos2">
      <ul class="lista-contato">
        <li id="iconePerfil" class="nome"> MariaEduarda</li>
        <li><a id="linkContato" href="mailto:eduardaleal753@gmail.com?subject=Jéssica você está muito bonita hoje&eduardaleal753@gmail.com"><img src="img/icon-gmail.svg"> Gmail</a></li>
        <li><a id="linkContato" href="https://www.linkedin.com/in/maria-eduarda-de-medeiros-leal-716601235/" target="_blank"><img src="img/icon-linkedin.svg"> Linkedin</a></li>
        <li><a id="linkContato" href="https://github.com/MariaEduardaLeal" target="_blank"><img src="img/icon-github.svg"> GitHub</a></li>
      </ul>
    </div>

    <div class="boxContatos3">
      <ul class="lista-contato">
        <li id="iconePerfil" class="nome"> PatrezeLeal</li>
        <li><a id="linkContato" href="mailto:patrezeleal@gmail.com?subject=Jéssica você está muito bonita hoje&patrezeleal@gmail.com"><img src="img/icon-gmail.svg"> Gmail</a></li>
        <li><a id="linkContato" href="https://www.linkedin.com/in/patreze-leal-medeiros-64b656235/" target="_blank"><img src="img/icon-linkedin.svg"> Linkedin</a></li>
        <li><a id="linkContato" href="https://github.com/PatrezeLeal" target="_blank"><img src="img/icon-github.svg"> GitHub</a></li>
      </ul>
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