<?php 
date_default_timezone_set('America/Sao_Paulo');
include('conexao.php');
session_start();
$login = $_SESSION['login'];

$select_email = "SELECT email FROM me_usuario 
WHERE id_usuario = (SELECT id_usuario FROM me_login WHERE login = '$login')";
$query_email = mysqli_query($conexao, $select_email);
$dado_email = mysqli_fetch_assoc($query_email);
$email = $dado_email['email'];

$select_senha = "SELECT senha FROM me_login
WHERE id_usuario = (SELECT id_usuario FROM me_login WHERE login = '$login')";
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
    <title>Confirmar Dados</title>
    <link rel="stylesheet" href="style_confirmar_dados.css">
</head>

<body>
    <header>
        <nav>
            <a class="logo" href="login.php">Planner Medicamentos</a>            
        </nav>
    </header>
    <div class="container">
        <div class="box-one">
            <form action="mudar_senha_scripting.php" id="form_confirma_dados" method="post">
                <label for="email">Confirme seu email</label>
                <input type="email" name="email" placeholder="<?php echo $email ?>">

                <label for="login">Confirme Seu login</label>
                <input type="text" name="login" placeholder="<?php echo $login ?>">

                <label for="senha">Informe sua nova senha</label>
                <input type="text" name="senha">
                <button type="submit" class="enviar">Enviar</button>

            </form>
            <form action="perfil.php">
                <button type="submit" class="voltar">Voltar</button><br>
            </form>
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