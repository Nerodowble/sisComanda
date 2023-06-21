<?php
session_start();

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta o banco de dados para encontrar o funcionário com as credenciais fornecidas
    $sql = "SELECT * FROM funcionarios WHERE username = '$username'";
    // Execute a consulta SQL e verifique se há correspondência
    // Se houver uma correspondência, verifique se a senha está correta
    // Armazene o cargo do funcionário em uma variável

    // Verifique se as credenciais são válidas
    if ($username === $row['username'] && password_verify($password, $row['password'])) {
        // Autenticação bem-sucedida, armazene o cargo do funcionário na sessão
        $_SESSION['cargo'] = $row['cargo'];

        // Redirecionar para a página apropriada com base no cargo do funcionário
        if ($row['cargo'] === 'garcom') {
            header('Location: index.php');
            exit();
        } elseif ($row['cargo'] === 'cozinheiro') {
            header('Location: cozinha.php');
            exit();
        } elseif ($row['cargo'] === 'gerente') {
            header('Location: gerente.php');
            exit();
        }
    } else {
        // Credenciais inválidas, exibir mensagem de erro
        $error = 'Credenciais inválidas, tente novamente.';
    }
}
?>

<!-- Formulário de login na página de login -->
<form action="login.php" method="POST">
    <label for="username">Nome de usuário:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Senha:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Entrar</button>
</form>
