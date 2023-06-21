<?php
// Inclui o arquivo de configuração da conexão com o banco de dados
require_once 'config.php';

// Função para atualizar o contador de mesas
function atualizarContadorMesas($conn, $quantidade) {
    $sql = "SELECT MAX(id) AS max_id FROM Mesas";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $maxId = $row['max_id'];

    $contadorMesas = $maxId ? $maxId + 1 : 1;

    $sql = "ALTER TABLE Mesas AUTO_INCREMENT = $contadorMesas";
    $conn->query($sql);
}

// Verifica se o formulário foi enviado para adicionar mesas
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_mesas'])) {
    // Obtém a quantidade de mesas enviada pelo formulário
    $quantidade = $_POST["quantidade"];

    // Insere as mesas no banco de dados
    for ($i = 1; $i <= $quantidade; $i++) {
        $numero = "Mesa " . $i;
        $status = "livre";

        $sql = "INSERT INTO Mesas (numero, status) VALUES ('$numero', '$status')";

        if ($conn->query($sql) !== TRUE) {
            echo "Erro ao cadastrar as mesas: " . $conn->error;
            break;
        }
    }

    // Atualiza o contador de mesas
    atualizarContadorMesas($conn, $quantidade);

    // Redireciona para a página atual para evitar o reenvio do formulário
    header("Location: {$_SERVER['REQUEST_URI']}");
    exit();
}

// Verifica se o formulário foi enviado para remover mesas
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remover_mesas'])) {
    // Obtém a quantidade de mesas a serem removidas
    $quantidade = $_POST["quantidade"];

    // Verifica se há mesas suficientes para remover
    $sqlCount = "SELECT COUNT(*) as total FROM Mesas";
    $resultCount = $conn->query($sqlCount);
    $row = $resultCount->fetch_assoc();
    $totalMesas = $row['total'];

    if ($quantidade > $totalMesas) {
        echo "Não há mesas suficientes para remover.";
    } else {
        // Remove as mesas do banco de dados
        $sql = "DELETE FROM Mesas ORDER BY id DESC LIMIT $quantidade";

        if ($conn->query($sql) !== TRUE) {
            echo "Erro ao remover as mesas: " . $conn->error;
        } else {
            // Atualiza o contador de mesas
            atualizarContadorMesas($conn, $quantidade * -1);

            // Redireciona para a página atual para evitar o reenvio do formulário
            header("Location: {$_SERVER['REQUEST_URI']}");
            exit();
        }
    }
}

// Consulta SQL para obter as mesas
$sql = "SELECT id, status FROM Mesas";
$result = $conn->query($sql);

// Número da mesa
$numeroMesa = 1;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro e Visualização de Mesas</title>
</head>
<body>
    <h2>Cadastro de Mesas</h2>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
        <label for="quantidade">Quantidade de Mesas a Adicionar:</label>
        <input type="number" name="quantidade" required><br><br>
        <input type="submit" name="add_mesas" value="Adicionar Mesas">
    </form>

    <h2>Remover Mesas</h2>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
        <label for="quantidade">Quantidade de Mesas a Remover:</label>
        <input type="number" name="quantidade" required><br><br>
        <input type="submit" name="remover_mesas" value="Remover Mesas">
    </form>

    <h2>Visualização de Mesas</h2>
    <?php
    if ($result->num_rows > 0) {
        // Exibe a lista de mesas
        while ($row = $result->fetch_assoc()) {
            echo "Mesa: " . $row['id'] . " - Status: " . $row['status'] . "<br>";
            $numeroMesa++;
        }
    } else {
        echo "Nenhuma mesa encontrada.";
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
    ?>
</body>
</html>
