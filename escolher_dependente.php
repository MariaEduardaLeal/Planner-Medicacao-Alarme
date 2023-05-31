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
      <title>Escolher Dependente</title>
      <link rel="stylesheet" href="style_perfil.css">
    </head>
    <body>
      <center>
      <div class="container">
            <nav>
              <a class="logo" href="login.php">Planner Medicamentos</a>
            </nav>

        <div class="box-one">
          
            <main>

              <h1>Escolha o dependente:</h1>

              <form action="informacao_dependente.php" method="POST">
                <select name="dependente" required>
                  <option value="">Selecione o dependente</option>
                  <?php 
                // Loop através dos resultados e exibe cada dependente como uma opção no select
                    while ($row = mysqli_fetch_assoc($resultado)) {?>
                    <option value="<?php echo $row['nome_dependente']; ?>"><?php echo $row['nome_dependente']; ?></option>
                    <?php } ?>
                    </select>
                <button type="submit">Avançar</button>
              </form>

              <form action="<?php echo $_SERVER['HTTP_REFERER']; ?>">
                  <button type="submit">Voltar</button>
              </form>


            </main>
          </div>
        </div>
        </center>
    </body>
    </html>