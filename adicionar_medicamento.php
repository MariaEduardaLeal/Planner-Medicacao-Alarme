<?php
    include('conexao.php'); 
    session_start();

    $nome_medicamento = $_POST['nome_medicamento'];
    $fabricante = $_POST['fabricante'];
    $link_bula = $_FILES['bula'];

    //Fazer um select para verificar se aquele medicamento já exisre no banco de dados
    $select = "SELECT id_medicamento, nome_medicamento, fabricante FROM me_medicamento WHERE nome_medicamento = '$nome_medicamento' AND fabricante = '$fabricante'";
    $verificar = mysqli_query($conexao, $select);
    $quant_med = mysqli_num_rows($verificar);

    //Se existir ele lhe manda direto para a página de alarme passando a variável $nome_medicamento com ela
    if ($quant_med > 0) {
        // Primeiro temos que formatar o nome do medicamento para aqueles medicamentos que tem caracteres
        // especiais não terem erros quando forem adicionar alarmes
        $nome_medicamento_encoded = urlencode($nome_medicamento);
        header("Location: alarme.php?nome_medicamento=$nome_medicamento_encoded");


    }else{
        //Caso não exista ele adciona no banco de dados 
        // salvar os dados no banco de dados
        $inserir = "INSERT INTO me_medicamento (nome_medicamento, fabricante, bula) 
        VALUES ('$nome_medicamento', '$fabricante', 'C:/Users/LENOVO/Downloads/bula/".$link_bula['bula']."')";
        
        $resultado = mysqli_query($conexao, $inserir);

        if($resultado){
            //E após isso lhe redireciona para a página de alarme passando a variável $nome_medicamento com ela
            $nome_medicamento_encoded = urlencode($nome_medicamento);
            header("Location: alarme.php?nome_medicamento=$nome_medicamento_encoded");

        } else {
            echo "<script>alert('Erro ao adicionar medicamento.')</script>";
        }
    }
?>


