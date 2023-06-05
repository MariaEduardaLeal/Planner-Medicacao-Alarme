<?php
include('conexao.php');
session_start();
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

// Consulta SQL para obter os dependentes associados ao login
$consulta = "SELECT me_dependente.nome_dependente 
FROM me_dependente
INNER JOIN me_login ON me_login.id_usuario = me_dependente.id_usuario
WHERE me_login.login = '$login'";

$resultado = mysqli_query($conexao, $consulta);



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Medicamento</title>
    <link rel="stylesheet" href="style_addMedicamento.css">
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
                        <a href="#">Histórico</a>
                        <a href="login.php">Sair</a>
                    </div>
                </div>
            </div>
            <div class="box-one">
                <form action="addMedicamento_scripting.php" method="post">

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
                    <label>Unidade de dosagem</label>
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
                    <label>Concentração</label>
                    <select name="concentracao" required>
                        <option>Concentração</option>
                        <option value="mcg">mcg</option>
                        <option value="mg">mg</option>
                        <option value="g">g</option>
                        <option value="mL">mL</option>
                        <option value="L">L</option>
                    </select>

                    <label for="nome_medicamento">Nome do medicamento: </label><br>
                    <input type="text" name="nome_medicamento" required><br>

                    <label for="fabricante">Fabricante: </label><br>
                    <input type="text" name="fabricante" required><br>

                    <label for="inicio">Horário de início:</label><br>
                    <input type="datetime-local" name="inicio" required><br>

                    <label for="duracao">Duração do tratamento (em dias):</label><br>
                    <input type="number" name="duracao" required><br>

                    <label for="frequencia">Frequência (em horas):</label><br>
                    <input type="number" name="frequencia" required><br>


                    <button type="submit">Enviar</button><br>
                </form>
                <form action="principal2.php">
                    <button type="submit">Voltar</button>
                </form>

            </div>



        </div>
    </center>
</body>
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

</html>