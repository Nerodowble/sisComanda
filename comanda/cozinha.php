<?php
// Estabelecer conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "siscomanda";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar se a conexão foi estabelecida com sucesso
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Verificar se foi enviado o ID do pedido a ser confirmado
if (isset($_GET['confirmar_pedido'])) {
    $pedido_id = $_GET['confirmar_pedido'];

    // Atualizar o status do pedido para "em preparo"
    $sql = "UPDATE mesa_itens SET status = 'em preparo' WHERE id = $pedido_id";
    $conn->query($sql);
}

// Verificar se foi enviado o ID do pedido a ser finalizado
if (isset($_GET['finalizar_pedido'])) {
    $pedido_id = $_GET['finalizar_pedido'];

    // Atualizar o status do pedido para "finalizado"
    $sql = "UPDATE mesa_itens SET status = 'finalizado' WHERE id = $pedido_id";
    $conn->query($sql);
}

// Consultar todas as mesas ocupadas e seus pedidos pendentes
$sql = "SELECT m.id AS mesa_id, m.numero AS mesa_numero, g.nome AS garcom_nome, i.nome AS item_nome, mi.id AS item_id, mi.status
        FROM mesas m
        INNER JOIN mesa_itens mi ON m.id = mi.mesa_id
        INNER JOIN itens i ON mi.item_id = i.id
        LEFT JOIN garcons g ON m.garcom_id = g.id
        WHERE mi.status = 'pendente' OR mi.status = 'em preparo'";

$result = $conn->query($sql);

// Verificar se foram encontradas mesas ocupadas com pedidos pendentes ou em preparo
if ($result->num_rows > 0) {
    $mesas = array();

    // Agrupar os itens pendentes e em preparo por mesa
    while ($row = $result->fetch_assoc()) {
        $mesa_id = $row["mesa_id"];
        $mesa_numero = $row["mesa_numero"];
        $garcom_nome = $row["garcom_nome"];
        $item_nome = $row["item_nome"];
        $item_id = $row["item_id"];
        $status = $row["status"];

        // Verificar se a mesa já existe no array
        if (!isset($mesas[$mesa_id])) {
            $mesas[$mesa_id] = array(
                "mesa_id" => $mesa_id,
                "garcom_nome" => $garcom_nome,
                "itens_pendentes" => array(),
                "itens_em_preparo" => array()
            );
        }

        // Adicionar o item à lista correspondente (pendente ou em preparo)
        if ($status == "pendente") {
            $mesas[$mesa_id]["itens_pendentes"][] = array(
                "item_id" => $item_id,
                "item_nome" => $item_nome
            );
        } elseif ($status == "em preparo") {
            $mesas[$mesa_id]["itens_em_preparo"][] = array(
                "item_id" => $item_id,
                "item_nome" => $item_nome
            );
        }
    }

    // Exibir as comandas
    foreach ($mesas as $mesa_id => $mesa) {
        $garcom_nome = $mesa["garcom_nome"];
        $itens_pendentes = $mesa["itens_pendentes"];
        $itens_em_preparo = $mesa["itens_em_preparo"];

        echo "<a href='mesa.php?mesa=$mesa_id'>Mesa $mesa_id:</a><br>";

        // Exibir os itens pendentes
        foreach ($itens_pendentes as $item) {
            $item_id = $item["item_id"];
            $item_nome = $item["item_nome"];

            echo "- $garcom_nome: $item_nome<br>";
        }

        // Exibir o botão de "Confirmar Pedido" para a comanda pendente
        if (!empty($itens_pendentes)) {
            echo "<a href='cozinha.php?confirmar_pedido={$itens_pendentes[0]['item_id']}'>Confirmar Pedido</a><br>";
        }

        // Exibir o botão de "Finalizado" para cada comanda em preparo
        foreach ($itens_em_preparo as $item) {
            $item_id = $item["item_id"];
            $item_nome = $item["item_nome"];

            echo "- $garcom_nome: $item_nome ";
            echo "<a href='cozinha.php?finalizar_pedido=$item_id'>Finalizado</a><br>";
        }
    }
} else {
    echo "Não há pedidos pendentes na cozinha.";
}

// Fechar a conexão com o banco de dados
$conn->close();
?>


