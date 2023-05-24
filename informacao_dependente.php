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
</body>
</html>