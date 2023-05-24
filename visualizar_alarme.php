<?php 
include('conexao.php');
session_start();
$login = $_SESSION['login'];

// Obtendo o tipo de usuário do banco de dados
$select_tipo_usuario = "SELECT id_tipo_usuario FROM me_usuario
 WHERE id_usuario = (SELECT id_usuario FROM me_login WHERE login = '$login')";

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
    <title>Visualizar Alarme</title>
    <link rel="stylesheet" href="style_pesquisa_med.css">
</head>
<body>
<header>
        <nav>
            <a class="logo" href="login.php">Planner Medicamentos</a>

            <ul class="nav-list">
                <li><a href="perfil.php">Voltar</a></li>
                <?php 
                if ($id_tipo_usuario == 1) {
                    echo " <li><a href='addMedicamento.php'>Adicionar medicamento não encontrado</a></li>";
                }
                ?>
            </ul>

        </nav>
    </header>
    <div class="container">
        <div class="box-one">  
            <?php

                $select = "SELECT me_horario.*, me_medicamento.nome_medicamento
                        FROM me_horario
                        INNER JOIN me_medicamento ON me_horario.id_medicamento = me_medicamento.id_medicamento
                        WHERE me_horario.login = '$login'";
                $query_horario = mysqli_query($conexao, $select);

                if(mysqli_num_rows($query_horario) > 0){
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Data e Hora</th>";
                    echo "<th>Login</th>";
                    echo "<th>Medicamento</th>";
                    if ($id_tipo_usuario == 1) {
                        echo "<th>Exluir Alarmes</th>";
                        echo "<th>Atualizar Alarmes</th>";
                    }
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";

                    while ($dado_horario = mysqli_fetch_assoc($query_horario)){
                        echo "<tr>";
                        echo "<td>" . date('d/m/Y H:i:s', strtotime($dado_horario['horario'])) . "</td>";
                        echo "<td>" . $dado_horario['login'] . "</td>";
                        echo "<td>" . $dado_horario['nome_medicamento'] . "</td>";
                        //estou passando o dado de id_horario para poder excluir corretamente
                        if ($id_tipo_usuario == 1) {
                            echo "<td><a href='excluir_medicamento.php?id_horario={$dado_horario['id_horario']}'>Excluir Medicamento</a></td>";
                            echo "<td>
                            <a href='update_medicamento.php?id_horario={$dado_horario['id_horario']}&nome_medicamento={$dado_horario['nome_medicamento']}'>
                            Atualizar Data e Hora</a></td>";
                        }
                        echo "</tr>";
                    }

                    echo "</tbody>";
                    echo "</table>";
                }   

                //A PARTIR DAQUI ESTÁ O CÓDIGO DO ALARME - NÃO MEXER
                date_default_timezone_set('America/Sao_Paulo');
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
        </div>
       
</div>

<script>
      // Supondo que o login esteja armazenado em uma variável chamada "login"
      const login = "<?php echo $_SESSION['login']; ?>";

      setInterval(function() {
        console.log(`Verificando alarmes às ${moment().format('YYYY-MM-DD HH:mm:ss')}`);
        tocarAlarmes(login);
      }, 60000); // Verificar a cada 1 minuto (60000 milissegundos)
    </script>   

</body>
</html>

