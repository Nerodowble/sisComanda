<?php
// Inclua o arquivo de conexão com o banco de dados
include 'config.php';

// Função para gerar uma senha aleatória
function gerarSenhaAleatoria($tamanho = 8) {
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $senha = '';
    for ($i = 0; $i < $tamanho; $i++) {
        $indice = rand(0, strlen($caracteres) - 1);
        $senha .= $caracteres[$indice];
    }
    return $senha;
}

// Verifica se o formulário foi enviado para cadastro
if (isset($_POST['cadastrar'])) {
    // Obtém os dados do formulário
    $username = $_POST['username'];
    $password = $_POST['password'];
    $cargo = $_POST['cargo'];

    // Verifica se todos os campos foram preenchidos
    if (!empty($username) && !empty($password) && !empty($cargo)) {
        // Hash da senha
        $hashSenha = password_hash($password, PASSWORD_DEFAULT);

        // Insere o novo funcionário no banco de dados
        $sql = "INSERT INTO funcionarios (username, password, cargo) VALUES ('$username', '$hashSenha', '$cargo')";
        $conn->query($sql);

        // Redireciona de volta para a página de funcionários
        header('Location: cadastro_funcionario.php');
        exit();
    }
}

// Verifica se o formulário foi enviado para reset de senha
if (isset($_POST['resetarSenha'])) {
    // Obtém o ID do funcionário
    $id = $_POST['id'];

    // Gera uma nova senha aleatória
    $novaSenha = gerarSenhaAleatoria();

    // Hash da nova senha
    $hashSenha = password_hash($novaSenha, PASSWORD_DEFAULT);

    // Atualiza a senha do funcionário no banco de dados
    $sql = "UPDATE funcionarios SET password = '$hashSenha' WHERE id = '$id'";
    $conn->query($sql);

    // Exibe a nova senha em um popup
    echo '<script type="text/javascript">';
    echo 'alert("A senha foi redefinida com sucesso. Nova senha: ' . $novaSenha . '");';
    echo '</script>';
    
    // Redireciona de volta para a página de funcionários
    header('Location: cadastro_funcionario.php');
    exit();
}

// Verifica se o formulário foi enviado para edição
if (isset($_POST['editar'])) {
    // Obtém os dados do formulário
    $id = $_POST['id'];
    $novoNome = $_POST['nome'];
    $novoCargo = $_POST['cargo'];

    // Atualiza os dados do funcionário no banco de dados
    $sql = "UPDATE funcionarios SET username = '$novoNome', cargo = '$novoCargo' WHERE id = '$id'";
    $conn->query($sql);

    // Redireciona de volta para a página de funcionários
    header('Location: cadastro_funcionario.php');
    exit();
}

// Verifica se o formulário foi enviado para exclusão
if (isset($_POST['excluir'])) {
    // Obtém o ID do funcionário
    $id = $_POST['id'];

    // Exclui o funcionário do banco de dados
    $sql = "DELETE FROM funcionarios WHERE id = '$id'";
    $conn->query($sql);

    // Redireciona de volta para a página de funcionários
    header('Location: cadastro_funcionario.php');
    exit();
}

// Consulta os funcionários cadastrados no banco de dados
$sql = "SELECT * FROM funcionarios";
$result = $conn->query($sql);
?>

<!-- Tabela de funcionários -->
<table>
    <tr>
        <th>ID</th>
        <th>Nome de usuário</th>
        <th>Cargo</th>
        <th>Ações</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) : ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['cargo']; ?></td>
            <td>
                <!-- Formulário de edição -->
                <form action="cadastro_funcionario.php" method="POST" style="display: inline-block;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="text" name="nome" placeholder="Novo nome">
                    <select name="cargo">
                        <option value="">Selecionar cargo</option>
                        <option value="Gerente">Gerente</option>
                        <option value="Supervisor">Supervisor</option>
                        <option value="Funcionário">Funcionário</option>
                    </select>
                    <button type="submit" name="editar">Editar</button>
                </form>

                <!-- Formulário de reset de senha -->
                <form action="cadastro_funcionario.php" method="POST" style="display: inline-block;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="resetarSenha">Resetar Senha</button>
                </form>

                <!-- Formulário de exclusão -->
                <form action="cadastro_funcionario.php" method="POST" style="display: inline-block;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="excluir">Excluir</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table><br>

<!-- Formulário de cadastro de funcionário -->
<form action="cadastro_funcionario.php" method="POST">
    <label for="username">Nome de usuário:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Senha:</label>
    <input type="password" id="password" name="password" required>

    <label for="cargo">Cargo:</label>
    <select id="cargo" name="cargo" required>
        <option value="">Selecionar cargo</option>
        <option value="Gerente">Gerente</option>
        <option value="Supervisor">Supervisor</option>
        <option value="Funcionário">Funcionário</option>
    </select>

    <button type="submit" name="cadastrar">Cadastrar</button>
</form>

<?php
// Verifica se há uma mensagem a ser exibida
if (isset($_POST['mensagem'])) {
    $mensagem = $_POST['mensagem'];
    echo "<p>$mensagem</p>";
}
?>
