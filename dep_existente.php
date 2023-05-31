<?php
date_default_timezone_set('America/Sao_Paulo');
session_start(); // inicia a sessão
include('conexao.php');

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
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Adicionar Perfil de Dependente</title>
</head>
<body>
  <div class="container">
    <div class="box-one">
      <main>
        <h1>Adicionar Perfil de Dependente já cadastrado</h1>
       
        <form action="dep_existente_scripting.php" method="POST">
        
        <h3>Por favor preencha os campos com as mesmas informações fornecidas quando o usuário foi cadastrado</h3>
          <label for="nomeDependente">Confirme o nome do dependente:</label><br>
          <input type="text" name="nomeDependente" required><br>

          <label for="loginDependente">Confirme o login do dependente:</label><br>
          <input type="text" name="loginDependente" required><br>

          <label for="emailDependente">Confirme o email do dependente:</label><br>
          <input type="email" name="emailDependente" required><br>

          <label for="confSenha">Confirme a senha do perfil do seu dependente</label><br>
          <input type="password" name="confSenha" required><br>

          <br><button type="submit" name="adicionarDependente">Adicionar</button>
        </form>

        <form action="addDependente.php">
          <button type="submit">Voltar</button>
        </form>
      </main>
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