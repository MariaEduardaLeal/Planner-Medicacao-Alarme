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
    <nav>
      <a class="logo" href="login.php">Planner Medicamentos</a>
    </nav>
    <div class="container">
      <div class="box-one">
<form action="addMedicamento_scripting.php" method="post">

    <label>Escolha seu usuário</label><br>
        <select name="opcao" required>
        <option value="">Selecione o usuário que deseja cadastrar o alarme</option>
        <?php 
            // Loop através dos resultados e exibe cada dependente como uma opção no select
            while ($row = mysqli_fetch_assoc($resultado)) {?>
                <option value="<?php echo $row['nome_dependente']; ?>"><?php echo $row['nome_dependente']; ?></option>
                <?php } ?>
    <option value="<?php echo $login ?>"><?php echo $login ?></option>
        </select><br>   

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

        
    </div>
</form>
    
<form action="visualizar_alarme.php">
    <button type="submit">Voltar</button>
</form>
      </div>
    </div>    
</center>
</body>
</html>
