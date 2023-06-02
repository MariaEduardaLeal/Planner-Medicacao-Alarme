<?php
include('conexao.php');
session_start();
$login = $_SESSION['login'];

//Pega as informações do usuário logado
$select = "SELECT me_usuario.*, me_login.*
FROM me_usuario, me_login
WHERE me_usuario.id_usuario = me_login.id_usuario
AND me_login.login = '$login'";

$query_user = mysqli_query($conexao, $select);
$dado_user = mysqli_fetch_row($query_user);

// Obtendo o tipo de usuário do banco de dados
$select_tipo_usuario = "SELECT id_tipo_usuario FROM me_usuario
 WHERE id_usuario = (SELECT id_usuario FROM me_login WHERE login = '$login')";

$query_tipo_usuario = mysqli_query($conexao, $select_tipo_usuario);
$dado_tipo_usuario = mysqli_fetch_assoc($query_tipo_usuario);

$id_tipo_usuario = $dado_tipo_usuario['id_tipo_usuario'];


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
    <link rel="stylesheet" href="style_perfil.css">
    <title>Perfil</title>
</head>
<body>
<header>
        <nav>
        <a class="logo" href="login.php">Planner Medicamentos</a>
        </nav>
    </header>    
    <center>
    <div class="container"> 
    <div class="box-one">
    <h1>Aqui estão suas informações pessoais</h1>
    <table>
        <tr>
            <th>nome</th>
            <th>Email</th>
            <th>Login</th>
        </tr>
        <tr>
            <td><span></span><?php echo $dado_user[1]."<br>"; ?></td>
            <td><span></span><?php echo $dado_user[2]."<br>"; ?></td>
            <td><span></span><?php echo $dado_user[4]."<br>"?></td>
        </tr>
    </table>    
    
    <?php
    if ($id_tipo_usuario == 1) {
       echo "<form action='mudar_senha_user.php'>";
       echo "<button type='submit'>Mudar Senha</button>";
       echo "</form>";

       echo"
       <form action='mudar_dados.php'>
        <button type='submit'>Mudar dados</button>
       </form>
       ";
    }
    ?>
    <form action="visualizar_alarme.php">
    <button type="submit">Visualizar Alames</button>
    </form>

    <?php 
    if ($id_tipo_usuario == 1) {
        echo "
        <form action='escolher_dependente.php'>
            <button type='submit'>Visualizar Dependentes</button>
        </form>
        ";
    }
    ?>
    
    <form action="principal2.php">
    <button type="submit">Voltar</button>
    </form>
    
</div>
</div>
</center>

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
