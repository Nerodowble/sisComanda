<?php
require_once 'config.php';

// Verifica se o formulário de edição foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_item_id'])) {
    // Obtém os dados atualizados do item
    $itemId = $_POST['edit_item_id'];
    $nomeAtualizado = $_POST['edit_nome'];
    $descricaoAtualizada = $_POST['edit_descricao'];
    $valorAtualizado = $_POST['edit_valor'];

    // Atualiza o item no banco de dados
    $sql = "UPDATE itens SET nome = '$nomeAtualizado', descricao = '$descricaoAtualizada', valor = '$valorAtualizado' WHERE id = $itemId";
    if ($conn->query($sql) === TRUE) {
        echo "Item atualizado com sucesso.";
    } else {
        echo "Erro ao atualizar item: " . $conn->error;
    }
}

// Consulta os itens adicionados no banco de dados
$sql = "SELECT * FROM itens";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">

    <title>Adicionar Item</title>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function exibirEdicao(itemId) {
            // Obtém os valores atuais do item
            var nomeAtual = $('#nome_' + itemId).text();
            var descricaoAtual = $('#descricao_' + itemId).text();
            var valorAtual = $('#valor_' + itemId).text();

            // Preenche os campos do pop-up de edição com os valores atuais do item
            $('#edit_item_id').val(itemId);
            $('#edit_nome').val(nomeAtual);
            $('#edit_descricao').val(descricaoAtual);
            $('#edit_valor').val(valorAtual);

            // Exibe o pop-up de edição
            $('.modal').show();
        }

        function fecharEdicao() {
            // Oculta o pop-up de edição
            $('.modal').hide();
        }

        function salvarEdicao() {
            // Obtém os valores atualizados do item
            var itemId = $('#edit_item_id').val();
            var nomeAtualizado = $('#edit_nome').val();
            var descricaoAtualizada = $('#edit_descricao').val();
            var valorAtualizado = $('#edit_valor').val();

            // Atualiza os valores do item na página
            $('#nome_' + itemId).text(nomeAtualizado);
            $('#descricao_' + itemId).text(descricaoAtualizada);
            $('#valor_' + itemId).text(valorAtualizado);

            // Envia o formulário para atualizar o item no banco de dados
            $('#edit_form').submit();
        }
    </script>
</head>
<body>
    <h2>Adicionar Item</h2>
    <form method="POST" action="">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required><br><br>

        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao" id="descricao" required><br><br>

        <label for="valor">Valor:</label>
        <input type="text" name="valor" id="valor" required><br><br>

        <input type="submit" name="submit" value="Adicionar Item">
    </form>

    <h2>Lista de Itens Adicionados:</h2>
    <?php
    if ($result !== false && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $itemId = $row['id'];
            $itemNome = $row['nome'];
            $itemDescricao = $row['descricao'];
            $itemValor = $row['valor'];

            echo "<div id='item_$itemId'>";
            echo "Nome: <span id='nome_$itemId'>$itemNome</span><br>";
            echo "Descrição: <span id='descricao_$itemId'>$itemDescricao</span><br>";
            echo "Valor: <span id='valor_$itemId'>$itemValor</span><br>";

            // Botão de editar
            echo "<button onclick=\"exibirEdicao($itemId)\">Editar</button>";

            // Botão de remover
            echo "<button onclick=\"removerItem($itemId)\">Remover</button>";

            echo "<br><br></div>";
        }
    } else {
        echo "Nenhum item adicionado.";
    }
    ?>

    <!-- Pop-up de edição -->
    <div id="modalEditar" class="modal">
        <div class="modal-content">
            <span class="close" onclick="fecharEdicao()">&times;</span>
            <h2>Editar Item</h2>
            <form id="edit_form" method="POST" action="">
                <input type="hidden" name="edit_item_id" id="edit_item_id" value="">
                <label for="edit_nome">Nome:</label>
                <input type="text" name="edit_nome" id="edit_nome" required><br><br>

                <label for="edit_descricao">Descrição:</label>
                <input type="text" name="edit_descricao" id="edit_descricao" required><br><br>

                <label for="edit_valor">Valor:</label>
                <input type="text" name="edit_valor" id="edit_valor" required><br><br>

                <input type="button" name="salvar" value="Salvar" onclick="salvarEdicao()">
            </form>
        </div>
    </div>

    <script>
        // Fecha o pop-up de edição se o usuário clicar fora da janela modal
        window.onclick = function(event) {
            var modal = document.getElementById("modalEditar");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    </script>
</body>
</html>

<?php
$conn->close();
?>
