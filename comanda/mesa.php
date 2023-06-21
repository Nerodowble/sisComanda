<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Mesa</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .container {
      position: relative;
    }

    .voltar-button {
      position: absolute;
      top: 10px;
      right: 10px;
    }
  </style>
</head>
<body class="container">

<?php
require_once 'config.php';

// Verifica se o parâmetro "mesa" está presente na URL
if (isset($_GET['mesa'])) {
    // Obtém o ID da mesa selecionada
    $mesaId = $_GET['mesa'];

		// Obtém o nome do cliente armazenado no banco de dados, se disponível
		$sqlNomeCliente = "SELECT nome FROM clientes WHERE mesa_id = $mesaId";
		$resultNomeCliente = $conn->query($sqlNomeCliente);

		if ($resultNomeCliente->num_rows > 0) {
			$rowNomeCliente = $resultNomeCliente->fetch_assoc();
			$cliente = $rowNomeCliente['nome'];
		} else {
			$cliente = "Nome do Cliente";
		}

    echo "<h1>Mesa $mesaId - $cliente</h1>"; 
    echo "<a href='index.php' class='voltar-button'>Voltar</a>"; 


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Verifica se o botão "Remover" foi clicado
        if (isset($_POST['remove'])) {
            $itemId = $_POST['item_id'];

            // Remover o item da mesa
            $sqlRemoverItem = "DELETE FROM mesa_itens WHERE mesa_id = $mesaId AND item_id = $itemId";
            if ($conn->query($sqlRemoverItem) === TRUE) {
                echo "Item removido da mesa com sucesso.<br>";
            } else {
                echo "Erro ao remover o item da mesa: " . $conn->error . "<br>";
            }

            // Redireciona para evitar o reenvio do formulário
            header("Location: mesa.php?mesa=$mesaId");
            exit();
        }

        // Verifica se o botão "Adicionar Itens" foi clicado
        if (isset($_POST['submit'])) {
            $itensSelecionados = $_POST['itens'];

            if (!empty($itensSelecionados)) {
                foreach ($itensSelecionados as $itemId) {
                    // Adicionar o item à mesa
                    $sqlAdicionarItem = "INSERT INTO mesa_itens (mesa_id, item_id) VALUES ('$mesaId', '$itemId')";
                    if ($conn->query($sqlAdicionarItem) === TRUE) {
                        echo "Item adicionado à mesa com sucesso.<br>";
                    } else {
                        echo "Erro ao adicionar o item à mesa: " . $conn->error . "<br>";
                    }
                }
            } else {
                echo "Nenhum item selecionado.";
            }

            // Redireciona para evitar o reenvio do formulário
            header("Location: mesa.php?mesa=$mesaId");
            exit();
        }
    }

    // Consulta os itens adicionados à mesa
    $sql = "SELECT i.id, i.nome, i.valor FROM itens i
            INNER JOIN mesa_itens mi ON mi.item_id = i.id
            WHERE mi.mesa_id = $mesaId";
    $result = $conn->query($sql);

    // Verifica se há itens adicionados
    if ($result->num_rows > 0) {
        echo "<h2>Itens adicionados à mesa:</h2>";
        $totalValor = 0; // Inicializa a variável para armazenar o valor total

        while ($row = $result->fetch_assoc()) {
            $itemId = $row['id'];
            $itemNome = $row['nome'];
            $itemValor = $row['valor'];

            echo "$itemNome (R$ $itemValor) ";
            echo "<form method='POST' action='mesa.php?mesa=$mesaId'>";
            echo "<input type='hidden' name='mesa_id' value='$mesaId'>";
            echo "<input type='hidden' name='item_id' value='$itemId'>";
            echo "<input type='submit' name='remove' value='Remover'>";
            echo "</form>";
            echo "<br>";

            // Soma o valor de cada item selecionado
            $totalValor += $itemValor;
        }

        echo "Valor total: R$ $totalValor<br>";
        echo "<button onclick='fecharComanda($mesaId)'>Fechar Comanda</button>";

    } else {
        echo "Nenhum item adicionado à mesa.";
    }
    
    echo "<script>
        function fecharComanda(mesaId) {
            if (confirm('Deseja realmente fechar a comanda?')) {
                // Atualiza o status da mesa para 'fechada'
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert('Comanda da mesa ' + mesaId + ' fechada com sucesso.');
                        window.location.href = 'index.php';
                    }
                };
                xhr.open('GET', 'mesa.php?fecharComanda=true&mesa=' + mesaId, true);
                xhr.send();
            }
        }
    </script>";

    // Verifica se o parâmetro "fecharComanda" está presente na URL
    if (isset($_GET['fecharComanda'])) {
        $mesaId = $_GET['mesa'];

        // Atualiza o status da mesa para 'fechada'
        $sqlFecharMesa = "UPDATE mesas SET status = 'fechada' WHERE id = $mesaId";
        if ($conn->query($sqlFecharMesa) === TRUE) {
            // Remove os itens associados à mesa
            $sqlRemoverItens = "DELETE FROM mesa_itens WHERE mesa_id = $mesaId";
            if ($conn->query($sqlRemoverItens) === TRUE) {
                // Redireciona para o index.php com a mensagem de sucesso e o status atualizado da mesa
                echo "<script>
                    alert('Comanda da mesa $mesaId fechada com sucesso.');
                    window.location.href = 'index.php';
                </script>";
                exit();
            } else {
                echo "Erro ao remover os itens da mesa: " . $conn->error;
            }
        } else {
            echo "Erro ao fechar a comanda: " . $conn->error;
        }
    }

    // Consulta os itens disponíveis para adicionar à mesa
    $sqlItensDisponiveis = "SELECT * FROM itens";
    $resultItensDisponiveis = $conn->query($sqlItensDisponiveis);

    // Verifica se há itens disponíveis
    if ($resultItensDisponiveis->num_rows > 0) {
        echo "<h2>Itens disponíveis para adicionar à mesa $mesaId:</h2>";
        echo "<form method='POST' action='mesa.php?mesa=$mesaId'>";
        echo "<input type='hidden' name='mesa_id' value='$mesaId'>";

        while ($rowItensDisponiveis = $resultItensDisponiveis->fetch_assoc()) {
            $itemId = $rowItensDisponiveis['id'];
            $itemNome = $rowItensDisponiveis['nome'];
            $itemValor = $rowItensDisponiveis['valor'];

            echo "<input type='checkbox' name='itens[]' value='$itemId'>$itemNome (R$ $itemValor)<br>";
        }

        echo "<br>";
        echo "<input type='submit' name='submit' value='Adicionar Itens'>";
        echo "</form>";
    } else {
        echo "Nenhum item disponível para adicionar à mesa.";
    }
} else {
    echo "Mesa não selecionada.";
}

$conn->close();
?>

</body>
</html>