function confirmarSaida() {
    var nome = document.getElementById('nome').value;
    var login = document.getElementById('login').value;
    var email = document.getElementById('email').value;

    // Comparar os valores com os dados originais do banco de dados
    if (nome !== '<?php echo $nome ?>' || login !== '<?php echo $login ?>' || email !== '<?php echo $email ?>') {
        return "Deseja sair sem salvar as alterações?";
    }
}

window.onbeforeunload = confirmarSaida;
