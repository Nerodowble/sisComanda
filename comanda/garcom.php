<?php
// Inclua o arquivo de configuração da conexão com o banco de dados
require_once 'config.php';

// Verifica se o formulário foi submetido para adicionar um novo garçom
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome_garcom'])) {
    $nomeGarcom = $_POST['nome_garcom'];

    // Insere o novo garçom no banco de dados
    $sqlInserirGarcom = "INSERT INTO garcons (nome, ativo) VALUES ('$nomeGarcom', 'NÃO')";
    if ($conn->query($sqlInserirGarcom) === TRUE) {
        // Redireciona para evitar o envio repetido do formulário
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Erro ao adicionar o garçom: " . $conn->error;
    }
}

// Consulta os garçons no banco de dados
$sqlGarcons = "SELECT * FROM garcons";
$resultGarcons = $conn->query($sqlGarcons);

// Verifica se o formulário foi submetido para editar o garçom
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['garcom_id']) && isset($_POST['novo_nome'])) {
    $garcomId = $_POST['garcom_id'];
    $novoNome = $_POST['novo_nome'];

    // Atualiza o nome do garçom no banco de dados
    $sqlAtualizarGarcom = "UPDATE garcons SET nome = '$novoNome' WHERE id = $garcomId";
    if ($conn->query($sqlAtualizarGarcom) === TRUE) {
        // Redireciona para evitar o envio repetido do formulário
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Erro ao atualizar o nome do garçom: " . $conn->error;
    }
}

// Verifica se o formulário foi submetido para excluir o garçom
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_garcom_id'])) {
    $garcomId = $_POST['excluir_garcom_id'];

    // Exclui o garçom do banco de dados
    $sqlExcluirGarcom = "DELETE FROM garcons WHERE id = $garcomId";
    if ($conn->query($sqlExcluirGarcom) === TRUE) {
        // Redireciona para evitar o envio repetido do formulário
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Erro ao excluir o garçom: " . $conn->error;
    }
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lista de Garçons</title>
    <!-- Estilos CSS -->
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Adicionar Garçom</h1>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="text" name="nome_garcom" placeholder="Nome do garçom" required>
        <button type="submit">Adicionar</button>
    </form>

    <h1>Lista de Garçons</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Ativo</th>
            <th>Ações</th>
        </tr>
        <?php
        if ($resultGarcons->num_rows > 0) {
            while ($rowGarcom = $resultGarcons->fetch_assoc()) {
                $garcomId = $rowGarcom['id'];
                $garcomNome = $rowGarcom['nome'];
                $garcomAtivo = $rowGarcom['ativo'];
                ?>
                <tr>
                    <td><?php echo $garcomId; ?></td>
                    <td><?php echo $garcomNome; ?></td>
                    <td><?php echo ($garcomAtivo == 'SIM') ? 'Ativo' : 'Não ativo'; ?></td>
                    <td>
                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <input type="hidden" name="garcom_id" value="<?php echo $garcomId; ?>">
                            <input type="text" name="novo_nome" placeholder="Novo nome" required>
                            <button type="submit">Editar</button>
                        </form>
                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return confirm('Tem certeza que deseja excluir o garçom?');">
                            <input type="hidden" name="excluir_garcom_id" value="<?php echo $garcomId; ?>">
                            <button type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='4'>Nenhum garçom encontrado.</td></tr>";
        }
        ?>
    </table>
</body>
</html>
