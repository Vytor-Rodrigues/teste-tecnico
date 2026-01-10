<?php
session_start();
require '../connection.php';

if (isset($_POST['create_cor'])) {
    $nome = $_POST['nome'] ?? '';
    $hex = $_POST['hex'] ?? '';

    $connection = new Connection();
    $pdo = $connection->getConnection();
    $sql = "INSERT INTO colors (name, hex) VALUES (:nome, :hex)";
    $stmt = $pdo->prepare($sql);

    // Executar com os dados
    $stmt->execute([
        ':nome' => $nome,
        ':hex' => $hex,
    ]);

    // Usar COUNT(*) para contar registros no SQLite
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM colors");
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = $resultado['total'];

    if ($total > 0) {
        $_SESSION['message'] = 'Cor criada com sucesso!';
        header('Location: cores-screen.php');
        exit();
    } else {
        $_SESSION['message'] = 'Erro ao criar cor.';
        header('Location: cores-screen.php');
        exit();
    }
}

if (isset($_POST['update_cor'])) {

    $cor_id = $_POST['cor_id'] ?? '';

    $nome = $_POST['nome'] ?? '';
    $hex = $_POST['hex'] ?? '';

    $connection = new Connection();
    $pdo = $connection->getConnection();
    $sql = "UPDATE colors SET name = :nome, hex = :hex WHERE id = :cor_id";
    $stmt = $pdo->prepare($sql);

    // Executar com os dados
    $stmt->execute([
        ':cor_id' => $cor_id,
        ':nome' => $nome,
        ':hex' => $hex,
    ]);

    // Usar COUNT(*) para contar registros no SQLite
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM colors");
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = $resultado['total'];
    
    if ($total > 0) {
        $_SESSION['message'] = 'Cor atualizada com sucesso!';
        header('Location: cores-screen.php');
        exit();
    } else {
        $_SESSION['message'] = 'Erro ao atualizar cor.';
        header('Location: cores-screen.php');
        exit();
    }
}

if (isset($_POST['delete_cor'])) {

    $cor_id = $_POST['cor_id'] ?? '';

    $nome = $_POST['nome'] ?? '';
    $hex = $_POST['hex'] ?? '';

    $connection = new Connection();
    $pdo = $connection->getConnection();
    $sql = "DELETE FROM colors WHERE id = :cor_id";
    $stmt = $pdo->prepare($sql);

    // Executar com os dados
    $stmt->execute([
        ':cor_id' => $cor_id,

    ]);

    // Usar COUNT(*) para contar registros no SQLite
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM colors");
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = $resultado['total'];
    
    if ($total > 0) {
        $_SESSION['message'] = 'Cor deletada com sucesso!';
        header('Location: cores-screen.php');
        exit();
    } else {
        $_SESSION['message'] = 'Cor deletada com sucesso.';
        header('Location: cores-screen.php');
        exit();
    }
}

?>