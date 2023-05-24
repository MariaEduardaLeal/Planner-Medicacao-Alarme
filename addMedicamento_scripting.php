<?php
include('conexao.php');

session_start();
$login = $_SESSION['login'];

$nome_medicamento = $_POST['nome_medicamento'];
$fabricante = $_POST['fabricante'];
$horarios = isset($_POST['horarios']) ? $_POST['horarios'] : '';
$opcao = $_POST['opcao'];

if ($nome_medicamento == '' || $horarios == '' || $login == '' || $fabricante == '' || $opcao == '') {
    echo "Os campos são obrigatórios";
}//if ($nome_medicamento == '' || $horarios == '' || $login == '' || $fabricante == '' || $opcao == '') {
  else{
    $inserir = "INSERT INTO me_medicamento (nome_medicamento, fabricante, bula)
    VALUES ('$nome_medicamento', '$fabricante', 'C:/Users/User/Downloads/bula/')";
    $query_incluir = mysqli_query($conexao, $inserir);

    if (!$query_incluir) {
        echo "<script>Erro ao inserir medicamento: '</script>";
        echo "<script>window.location.href='alarme.php'</script>";
    }else {
        // Consultar o banco de dados para obter o id_medicamento
        $consulta_medicamento = "SELECT id_medicamento FROM me_medicamento WHERE nome_medicamento = '$nome_medicamento' AND fabricante = '$fabricante'";
        $resultado_consulta = mysqli_query($conexao, $consulta_medicamento);

        if ($resultado_consulta && mysqli_num_rows($resultado_consulta) > 0) {
        $row = mysqli_fetch_assoc($resultado_consulta);
        $id_medicamento = $row['id_medicamento'];

        foreach ($horarios as $horario) {
            //Vamos formatar A data e hora para um jeito que o MySql entenda
            //O jeito que chega para a gente é dia/mês/ano e o mysql entende ano/mês/dia
            if ($horario_valido = DateTime::createFromFormat('Y-m-d H:i', $horario)) {

                // Convertendo o objeto DateTime para um formato compatível com o MySQL
                $mysql_datetime = $horario_valido->format('Y-m-d H:i:s');

                //Já com a hora formatada vamos fazer o insert no nosso banco de dados
                $incluir = "INSERT INTO me_horario(id_horario, id_medicamento, horario, login)
                VALUES (null, $id_medicamento, '$mysql_datetime', '$opcao')";

                $query = mysqli_query($conexao, $incluir);
                //Se for bem sucedido ele lhe envia para a página principal2.php
                if ($query) {
                    header("location: visualizar_alarme.php");
                } else {
                    //Se algo der errado ele lhe envia de volta para alarme.php
                    echo "<script>Erro ao inserir horário: '</script>";
                    echo "<script>window.location.href='alarme.php'</script>";
                    break;
                }

            } //if ($horario_valido = DateTime::createFromFormat('Y-m-d H:i', $horario)) {
            else {
                echo 'Erro ao inserir horário: formato inválido';
                break; // Interrompe o loop caso ocorra um erro

            } //else { echo 'Erro ao inserir horário: formato inválido';
                
        }

        
    }  
 }//else para pegar o id_medicamento
}//Primeiro else
?>