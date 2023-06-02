<?php
include('conexao.php');
session_start();
$dependente = $_POST['dependente'];

$select_user = "SELECT me_usuario.*, me_login.*
            FROM me_usuario, me_login
            WHERE me_usuario.id_usuario = me_login.id_usuario
            AND me_login.login = '$dependente'";

$query_user = mysqli_query($conexao, $select_user);
$dado_user = mysqli_fetch_row($query_user);


$select_medicamento = "SELECT me_horario.*, me_medicamento.nome_medicamento
           FROM me_horario
           INNER JOIN me_medicamento ON me_horario.id_medicamento = me_medicamento.id_medicamento
           WHERE me_horario.login = '$dependente'";
$query_horario = mysqli_query($conexao, $select_medicamento);

//Alarme
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

// Consulta SQL para obter os dependentes associados ao login
$consulta = "SELECT d.nome_dependente 
FROM me_dependente d
INNER JOIN me_login l ON l.id_usuario = d.id_usuario
WHERE l.login = '$login'";
$resultado = mysqli_query($conexao, $consulta);

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
    <title>Informações do dependente</title>
    <link rel="stylesheet" href="style_perfil.css">
</head>
<body>
<header>
        <nav>
            <a class="logo" href="login.php">Planner Medicamentos</a>                    
            <a href="escolher_dependente.php">Sair</a>      
        </nav>
    </header>
    <center>
    <div class="container">
        <div class="box-one">
        
            <table>
                <tr>
                    <th>nome</th>
                    <th>Email</th>
                    <th>Login</th>
                    <th>Excluir dependente</th>
                </tr>
                <tr>
                    <td><?php echo $dado_user[1]."<br>"; ?></td>
                    <td><?php echo $dado_user[2]."<br>"; ?></td>
                    <td><?php echo $dado_user[4]."<br>"?></td>
                    <td><a href="excluir_usuario.php?id_usuario=<?php echo $dado_user[0]; ?>&&login_dep=<?php echo $dado_user[4] ?>" id="link_excluir">Excluir dependente</a></td>
                </tr>
            </table>    
        </div> 

        <div class="box-onde">
            <h3>Medicamentos e horários cadastrados</h3>
            <?php
            if(mysqli_num_rows($query_horario) > 0){
                echo "<table>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Data e Hora</th>";
                echo "<th>Login</th>";
                echo "<th>Medicamento</th>";
                echo "<th>Exluir Alarmes</th>";
                echo "<th>Atualizar Alarmes</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
            
                while ($dado_horario = mysqli_fetch_assoc($query_horario)){
                    echo "<tr>";
                    echo "<td>" . date('d/m/Y H:i:s', strtotime($dado_horario['horario'])) . "</td>";
                    echo "<td>" . $dado_horario['login'] . "</td>";
                    echo "<td>" . $dado_horario['nome_medicamento'] . "</td>";
                    //estou passando o dado de id_horario para poder excluir corretamente
                    echo "<td><a href='excluir_medicamento.php?id_horario={$dado_horario['id_horario']}'>Excluir Medicamento</a></td>";
                    echo "<td>
                    <a href='update_medicamento.php?id_horario={$dado_horario['id_horario']}&nome_medicamento={$dado_horario['nome_medicamento']}&dependente={$dependente}'>
                    Atualizar Data e Hora</a></td>";
            
                    echo "</tr>";
                }
            
                echo "</tbody>";
                echo "</table>";
            } 
            ?>
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
    </script>
</body>
</html>