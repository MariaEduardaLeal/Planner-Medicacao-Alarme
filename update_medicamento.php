<?php
date_default_timezone_set('America/Sao_Paulo');
session_start(); // inicia a sessão
include('conexao.php');
$id_horario = $_GET['id_horario'];
$nome_medicamento = $_GET['nome_medicamento'];

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alarme</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="style_update_medicamento.css">    
    <style>
        .horario-div {
            margin-bottom: 10px;
        }
    </style>
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
    <form action="update_medicamento_scripting.php" method="post">
        <input type="hidden" name="id_horario" value="<?php echo $id_horario ?>">
        <input type="hidden" name="nome_medicamento" value="<?php echo $nome_medicamento ?>">
        <label for="nome_medicamento">Nome do Medicamento:</label><br>
        <span><?php echo $nome_medicamento ?></span><br><br>

        <div id="horarios">
            <label for="horarios">Horários:</label><br>
            <div class="horario-div">
                <input type="text" name="horarios[]" class="flatpickr" data-enable-time=true data-date-format="Y-m-d H:i" multiple>
                
            </div>
        </div>

        
        <div style="height: 20%; display: block;"></div>

        
        <button type="submit">Atualizar</button>
        
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script>
 

flatpickr(".flatpickr", {
    enableTime: true,
    dateFormat: "Y-m-d H:i"
});



        </script>
    </form>
    <form action=".php">
                <button type="submit" class="voltar">Voltar</button>            
                </form>
    </center>
    <script src="mobile-navbar.js"></script>
    
    <script>
      // Supondo que o login esteja armazenado em uma variável chamada "login"
      const login = "<?php echo $_SESSION['login']; ?>";

      setInterval(function() {
        console.log(`Verificando alarmes às ${moment().format('YYYY-MM-DD HH:mm:ss')}`);
        tocarAlarmes(login);
      }, 60000); // Verificar a cada 1 minuto (60000 milissegundos)
    </script>
    </div>
    </div>    
</body>
</html>
