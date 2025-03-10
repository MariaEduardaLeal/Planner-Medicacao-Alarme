<?php
date_default_timezone_set('America/Sao_Paulo');
session_start(); // inicia a sessão
include('conexao.php');

$login = $_SESSION['login'];

$select_nome = "SELECT nome_usuario FROM me_usuario 
WHERE id_usuario IN (SELECT id_usuario FROM me_login WHERE login = '$login')";
$query_nome = mysqli_query($conexao, $select_nome);
$dado_nome = mysqli_fetch_assoc($query_nome);
$nome = $dado_nome['nome_usuario'];

$select_email = "SELECT email FROM me_usuario 
WHERE id_usuario IN (SELECT id_usuario FROM me_login WHERE login = '$login')";
$query_email = mysqli_query($conexao, $select_email);
$dado_email = mysqli_fetch_assoc($query_email);
$email = $dado_email['email'];

$select_senha = "SELECT senha FROM me_login
WHERE id_usuario IN (SELECT id_usuario FROM me_login WHERE login = '$login')";
$query_senha = mysqli_query($conexao, $select_senha);
$dado_senha = mysqli_fetch_assoc($query_senha);
$senha = $dado_senha['senha'];


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

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_mudar_dados.css">
    <title>Mudar Dados</title>
    <script src="verificar_dados.js"></script>
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
                <form action="mudar_dados_scripting.php" method="post">
                    <label for="nome">Digite seu nome :</label><br>
                    <input type="text" name="novo_nome" value="<?php echo $nome ?>" required><br>

                    <!--Cadastrar login-->
                    <label for="login">Digite seu login :</label><br>
                    <input type="text" name="novo_login" value="<?php echo $login ?>" required><br>

                    <!--Cadastrar email-->
                    <label for="email">Email :</label><br>
                    <input type="text" name="novo_email" value="<?php echo $email ?>" required><br>

                    <!--Cadastrar senha-->
                    <label for="senha">Digite sua senha :</label><br><!--<label></label> serve para que ao clicar no rótulo, o elemento de formulário associado a ele também recebe foco. Isso é útil para usuários que têm dificuldade em clicar em elementos pequenos, como caixas de seleção, ou que usam leitores de tela para acessar a página.-->
                    <input type="password" name="nova_senha" id="senha" value="<?php echo $senha ?>" required><br>

                    <input type="checkbox" onclick="mostrarOcultarSenha()">Mostar Senha <br>
                    <script type="text/javascript" src="verificar_senha.js"></script>

                    <button type="submit" class="enviar">Enviar</button>

                </form>
                <form action="perfil.php">
                    <button type="submit" class="voltar">Voltar</button>
                </form>
            </div>
        </div>
    </center>

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