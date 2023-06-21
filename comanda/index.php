<?php
// Inclua o arquivo de configuração da conexão com o banco de dados
require_once 'config.php';

// Consulta as mesas do banco de dados
$sql = "SELECT m.id, m.numero, COUNT(mi.id) AS total_itens FROM mesas m LEFT JOIN mesa_itens mi ON m.id = mi.mesa_id GROUP BY m.id";
$result = $conn->query($sql);

// Verifica se há mesas disponíveis
if ($result->num_rows > 0) {
    echo '<!DOCTYPE html>';
    echo '<html>';
    echo '<head>';
    echo '<title>Mesas - Quiosque do Borogodó</title>';
    echo '<link rel="stylesheet" href="style.css">';
    echo '<style>';
    echo '.container {';
    echo 'background-color: #f2f2f2;';
    echo 'border: 1px solid #ddd;';
    echo 'border-radius: 8px;';
    echo 'padding: 20px;';
    echo 'margin: 20px auto;';
    echo 'max-width: 600px;';
    echo 'box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);';
    echo '}';
    echo '.mesa-container {';
    echo 'display: flex;';
    echo 'flex-wrap: wrap;';
    echo 'justify-content: center;';
    echo '}';
    echo '</style>';
    echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
    echo '<script>';
    echo '$(document).ready(function() {';
    echo "$('.abrir-popup').click(function(e) {";
    echo 'e.preventDefault();';
    echo 'var mesaId = $(this).attr("href").split("=")[1];';
    echo 'var mesaStatus = $(this).data("status");';
    echo 'if (mesaStatus === "Livre") {';
    echo 'var nomeCliente = prompt("Digite o nome do cliente:");';
    echo 'if (nomeCliente) {';
    echo '$.post("", {nome_cliente: nomeCliente, mesa_id: mesaId})';
    echo '.done(function(data) {';
    echo 'alert(data);';
    echo 'window.location.href = "mesa.php?mesa=" + mesaId;';
    echo '})';
    echo '.fail(function() {';
    echo 'alert("Erro ao salvar o nome do cliente.");';
    echo '});';
    echo '}';
    echo '} else {';
    echo 'window.location.href = "mesa.php?mesa=" + mesaId;';
    echo '}';
    echo '});';
    echo '});';
    echo '</script>';
    echo '</head>';
    echo '<body class="container">';
    echo '<h1>Quiosque do Borogodó</h1>';
    echo '<h2>Mesas</h2>';
    echo '<div class="mesa-container">';

    $counter = 0;
    while ($row = $result->fetch_assoc()) {
        $mesaId = $row['id'];
        $mesaNumero = $row['numero'];
        $totalItens = $row['total_itens'];
        $mesaStatus = ($totalItens > 0) ? 'Ocupada' : 'Livre';

        // Verifica se o nome do cliente está armazenado no banco de dados
        $nomeCliente = '';
        if ($mesaStatus === 'Ocupada') {
            $sqlNomeCliente = "SELECT nome FROM clientes WHERE mesa_id = $mesaId";
            $resultNomeCliente = $conn->query($sqlNomeCliente);
            if ($resultNomeCliente->num_rows > 0) {
                $rowNomeCliente = $resultNomeCliente->fetch_assoc();
                $nomeCliente = $rowNomeCliente['nome'];
            }
        }

        if ($counter % 4 === 0) {
            echo '<div class="mesa-group">';
        }

        echo "<a href='mesa.php?mesa=$mesaId' class='abrir-popup' data-status='$mesaStatus'>";
        echo "<div class='mesa'>";
        echo "<div class='numero'>Mesa $mesaId</div>";
        echo "<div class='status'>$mesaStatus</div>";

        if ($mesaStatus === 'Ocupada') {
            echo "<div class='cliente'>Cliente: $nomeCliente</div>";
        }

        echo "</div>";
        echo "</a>";

        if ($counter % 4 === 3) {
            echo '</div>';
        }

        $counter++;
    }

    if ($counter % 4 !== 0) {
        echo '</div>';
    }

    echo '</div>';
    echo '</body>';
    echo '</html>';
} else {
    echo "Nenhuma mesa encontrada.";
}

// Verifica se o formulário do popup foi enviado
if (isset($_POST['nome_cliente']) && isset($_POST['mesa_id'])) {
    $nomeCliente = $_POST['nome_cliente'];
    $mesaId = $_POST['mesa_id'];

    // Verifica se a mesa está livre antes de inserir o novo nome
    $sqlVerificarMesa = "SELECT COUNT(*) AS total_mesas FROM mesas WHERE id = $mesaId AND (SELECT COUNT(*) FROM mesa_itens WHERE mesa_id = $mesaId) = 0";
    $resultVerificarMesa = $conn->query($sqlVerificarMesa);
    if ($resultVerificarMesa->num_rows > 0) {
        $rowVerificarMesa = $resultVerificarMesa->fetch_assoc();
        $totalMesas = $rowVerificarMesa['total_mesas'];
        if ($totalMesas > 0) {
            // A mesa está livre, remover o nome do cliente anterior (se existir)
            $sqlRemoverCliente = "DELETE FROM clientes WHERE mesa_id = $mesaId";
            if ($conn->query($sqlRemoverCliente) === TRUE) {
                // Inserir o novo nome do cliente no banco de dados
                $sqlInserirCliente = "INSERT INTO clientes (nome, mesa_id) VALUES ('$nomeCliente', $mesaId)";
                if ($conn->query($sqlInserirCliente) === TRUE) {
                    echo "Nome do cliente salvo com sucesso.";
                } else {
                    echo "Erro ao salvar o nome do cliente: " . $conn->error;
                }
            } else {
                echo "Erro ao remover o nome do cliente anterior: " . $conn->error;
            }
        } else {
            echo "A mesa está ocupada.";
        }
    } else {
        echo "Erro ao verificar a mesa: " . $conn->error;
    }

    // Redirecionar para a página da mesa
    header("Location: mesa.php?mesa=$mesaId");
    exit;
}

// Verifica se o parâmetro "fecharComanda" está presente na URL
if (isset($_GET['fecharComanda']) && $_GET['fecharComanda'] === 'true') {
    $mesaId = $_GET['mesa'];

    // Verificar se a mesa está ocupada
    $sqlVerificarMesa = "SELECT COUNT(*) AS total_mesas FROM mesas WHERE id = $mesaId AND (SELECT COUNT(*) FROM mesa_itens WHERE mesa_id = $mesaId) > 0";
    $resultVerificarMesa = $conn->query($sqlVerificarMesa);
    if ($resultVerificarMesa->num_rows > 0) {
        $rowVerificarMesa = $resultVerificarMesa->fetch_assoc();
        $totalMesas = $rowVerificarMesa['total_mesas'];
        if ($totalMesas > 0) {
            // Remover itens da mesa
            $sqlRemoverItens = "DELETE FROM mesa_itens WHERE mesa_id = $mesaId";
            if ($conn->query($sqlRemoverItens) === TRUE) {
                // Remover nome do cliente
                $sqlRemoverCliente = "DELETE FROM clientes WHERE mesa_id = $mesaId";
                if ($conn->query($sqlRemoverCliente) === TRUE) {
                    echo "Comanda da mesa $mesaId fechada com sucesso.";
                } else {
                    echo "Erro ao remover o nome do cliente: " . $conn->error;
                }
            } else {
                echo "Erro ao remover os itens da mesa: " . $conn->error;
            }
        } else {
            echo "A mesa está livre.";
        }
    } else {
        echo "Erro ao verificar a mesa: " . $conn->error;
    }
}
?>
