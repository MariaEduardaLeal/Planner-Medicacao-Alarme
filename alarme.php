<?php
include('conexao.php');
session_start();
$login = $_SESSION['login'];
$nome_medicamento = $_GET['nome_medicamento'];



// Consulta SQL para obter os dependentes associados ao login
$consulta = "SELECT d.nome_dependente 
FROM me_dependente d
INNER JOIN me_login l ON l.id_usuario = d.id_usuario
WHERE l.login = '$login'";
$resultado = mysqli_query($conexao, $consulta);

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
    <title>Alarme</title>

    <link rel="stylesheet" href="style_alarme.css">

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
                <form action="alarme_scripting.php" method="post" onsubmit="redirecionar()">
                    <input type="hidden" name="nome_medicamento" value="<?php echo $nome_medicamento ?>">

                    <label>Escolha seu usuário</label><br>
                    <select name="opcao" required>
                        <option value="">Selecione o usuário que deseja cadastrar o alarme</option>
                        <?php
                        // Loop através dos resultados e exibe cada dependente como uma opção no select
                        while ($row = mysqli_fetch_assoc($resultado)) { ?>
                            <option value="<?php echo $row['nome_dependente']; ?>"><?php echo $row['nome_dependente']; ?></option>
                        <?php } ?>
                        <option value="<?php echo $login ?>"><?php echo $login ?></option>
                    </select><br>

                    <label>Dosagem</label>
                    <input type="num" name="num_dosagem" required><br>
                    <label>Unidade de Dosagem</label>
                    <select name="dosagem" required>
                        <option>Selecione</option>
                        <option value="comprimido">comprimido</option>
                        <option value="capsula">cápsula</option>
                        <option value="gota">gota</option>
                        <option value="colher">colher</option>
                        <option value="unidade">unidade</option>
                    </select>

                    <label>Concentração</label>
                    <input type="num" name="num_concentracao"><br>
                    <label>Unidade de Concentração</label>
                    <select name="concentracao" required>
                        <option>Concentração</option>
                        <option value="mcg">mcg</option>
                        <option value="mg">mg</option>
                        <option value="g">g</option>
                        <option value="mL">mL</option>
                        <option value="L">L</option>
                    </select>

                    <label for="inicio">Horário de início:</label>
                    <input type="datetime-local" name="inicio" required>

                    <label for="duracao">Duração do tratamento (em dias):</label>
                    <input type="number" name="duracao" required>

                    <label for="frequencia">Frequência (em horas):</label>
                    <input type="number" name="frequencia" required>

                    <button type="submit">Enviar</button>


                </form>
                <!--TEM QUE VOLTAR PARA A PÁGINA PRINCIPAL, SE VOLTAR PARA pesquisa_med 
    VAI DAR MERDA PQ ELE NÃO VOLTA COM O CACHÊ DO NOME-->

                <!--NÃO TENTE MUDAR-->
                <form action="principal2.php">
                    <button type="submit">Voltar</button>
                </form>

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

    </center>


</body>

</html>