<?php
include('conexao.php');
session_start();
$login = $_SESSION['login'];

$novo_nome = $_POST['novo_nome'];
$novo_login = $_POST['novo_login'];
$novo_email= $_POST['novo_email'];
$nova_senha = $_POST['nova_senha'];

if ($novo_nome == '' || $novo_login == '' || $novo_email == '' || $nova_senha == '') {
    // Se as informações forem nulas, o código volta para a página de inserir dados
    echo "<script>window.alert('Os campos são obrigatórios')</script>";
    echo "<script>window.location.href='mudar_dados.php'</script>";
} else {
    // Verificar se o login já existe
    $verificar_login = "SELECT login FROM me_login WHERE login = '$novo_login'";
    $query_verificar_login = mysqli_query($conexao, $verificar_login);
    $quant_login = mysqli_num_rows($query_verificar_login);

    if ($quant_login > 0 && $novo_login != $login) {
        // Se o login já existir e for diferente do login antigo, exibir mensagem de erro
        echo "<script>alert('O login já existe, por favor escolha outro')</script>";
        echo "<script>window.location.href='mudar_dados.php'</script>";
    } else {
        // Código de atualização dos dados do usuário
        $renovar_usuario = "UPDATE me_usuario
            SET nome_usuario = '$novo_nome', 
                email = '$novo_email'
            WHERE id_usuario = (SELECT id_usuario FROM me_login WHERE login = '$login')";

        $query_update_usuario = mysqli_query($conexao, $renovar_usuario);

        if ($query_update_usuario) {
            // Criação de um novo registro na tabela me_login com o novo login e senha
            $novo_registro_login = "INSERT INTO me_login (login, senha, id_usuario)
                                    SELECT '$novo_login', '$nova_senha', id_usuario
                                    FROM me_login
                                    WHERE login = '$login'";

            $query_novo_registro_login = mysqli_query($conexao, $novo_registro_login);

            if ($query_novo_registro_login) {
                // Atualização do campo login na tabela me_horario para refletir o novo login
                $atualizar_horario = "UPDATE me_horario
                                      SET login = '$novo_login'
                                      WHERE login = '$login'";

                $query_atualizar_horario = mysqli_query($conexao, $atualizar_horario);

                if ($query_atualizar_horario) {
                    // Remoção do registro antigo da tabela me_login
                    $remover_registro_antigo = "DELETE FROM me_login
                                                WHERE login = '$login'";

                    $query_remover_registro_antigo = mysqli_query($conexao, $remover_registro_antigo);

                    if ($query_remover_registro_antigo) {
                        echo "<script>alert('Atualização feita com sucesso')</script>";
                        echo "<script>window.location.href='login.php'</script>";
                    } else {
                        echo "<script>alert('Erro ao fazer Update')</script>";
                    }
                } else {
                    echo "<script>alert('Erro ao fazer Update')</script>";
                }
            } else {
                echo "<script>alert('Erro ao fazer Update')</script>";
            }
        } else {
            echo "<script>alert('Erro ao fazer Update')</script>";
        }
    }
}
?>
