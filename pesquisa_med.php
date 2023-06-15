<?php

include('conexao.php');
session_start();
$login = $_SESSION['login'];

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
    <title>Tabela de Medicamentos</title>
    <link rel="stylesheet" href="style_pesquisa_med.css">
</head>

<body>
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
    <?php

    // obtém o nome do medicamento digitado pelo usuário
    $medicamento = $_GET['medicamento'];
    // faz a chamada à API para buscar as informações do medicamento
    $url = file_get_contents('https://bula.vercel.app/pesquisar?nome=' . $medicamento);

    $data = json_decode($url);
    if (!$data) {
        echo "<script>alert('O site está passando por instabilidades no momento, por favor tente novamente e se não conseguir tente mais tarde')</script>";
        echo "<script>window.location.href='principal2.php'</script>";
    }
    // verifica se o medicamento foi encontrado
    if (count($data->content) > 0) {
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Fabricante</th>";
        echo "<th>CNPJ</th>";
        echo "<th>Nome do Produto</th>";
        echo "<th>Número do Registro</th>";
        echo "<th>Baixar Bula</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        // verifica se o medicamento foi encontrado
        for ($i = 0; $i < count($data->content); $i++) {
            $atividade = $data->content[$i];

            $url_bula = file_get_contents('https://bula.vercel.app/bula?id=' . $atividade->idBulaPacienteProtegido);
            $data_bula = json_decode($url_bula);
            echo "<tr>";
            echo "<td>" . $atividade->razaoSocial . "</td>";
            echo "<td>" . $atividade->cnpj . "</td>";
            echo "<td>" . $atividade->nomeProduto . "</td>";
            echo "<td>" . $atividade->numeroRegistro . "</td>";
            echo "<td><a href='" . $data_bula->pdf . "'' onclick=\"downloadBula('" . $data_bula->pdf . "');\">Baixar Bula</a></td>";

            echo "<td>";
            if ($id_tipo_usuario == 1) {
                echo "<form method='POST' action='adicionar_medicamento.php'>
                    <input type='hidden' name='nome_medicamento' value='" . $atividade->nomeProduto . "'>
                    <input type='hidden' name='fabricante' value='" . $atividade->razaoSocial . "'>
                    <input type='hidden' name='bula' value='" . $data_bula->pdf . "'>
                    <button type='submit' name='submit'>Adicionar Alarme</button>
                </form>";
            }
            echo "</td>";


            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<script>alert('Nenhum medicamento encontrado com esse nome')</script>";
        echo "<script>window.location.href='principal2.php'</script>";
    }


    //A PARTIR DAQUI ESTÁ O CÓDIGO DO ALARME - NÃO MEXER

    date_default_timezone_set('America/Sao_Paulo');


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



    ?>
    <form action="principal2.php" style="text-align: center;">
        <br><button type="button" class="voltar-button" onclick="goBack()">
            Voltar
        </button>
    </form>


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