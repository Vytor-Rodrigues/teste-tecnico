<?php
session_start();
require '../connection.php';

if (isset($_POST['create_usuario'])) {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';

    $connection = new Connection();
    $pdo = $connection->getConnection();
    $sql = "INSERT INTO users (name, email) VALUES (:nome, :email)";
    $stmt = $pdo->prepare($sql);

    // Executar com os dados
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
    ]);

    // Usar COUNT(*) para contar registros no SQLite
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM users");
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = $resultado['total'];

    if ($total > 0) {
        $_SESSION['message'] = 'Usuário criado com sucesso!';
        header('Location: usuario-screen.php');
        exit();
    } else {
        $_SESSION['message'] = 'Erro ao criar usuário.';
        header('Location: usuario-screen.php');
        exit();
    }
}

if (isset($_POST['update_usuario'])) {

    $usuario_id = $_POST['usuario_id'] ?? '';

    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';

    $connection = new Connection();
    $pdo = $connection->getConnection();
    $sql = "UPDATE users SET name = :nome, email = :email WHERE id = :usuario_id";
    $stmt = $pdo->prepare($sql);

    // Executar com os dados
    $stmt->execute([
        ':usuario_id' => $usuario_id,
        ':nome' => $nome,
        ':email' => $email,
    ]);

    // Usar COUNT(*) para contar registros no SQLite
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM users");
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = $resultado['total'];
    
    if ($total > 0) {
        $_SESSION['message'] = 'Usuário atualizado com sucesso!';
        header('Location: usuario-screen.php');
        exit();
    } else {
        $_SESSION['message'] = 'Erro ao atualizar usuário.';
        header('Location: usuario-screen.php');
        exit();
    }
}

if (isset($_POST['delete_usuario'])) {

    $usuario_id = $_POST['usuario_id'] ?? '';

    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';

    $connection = new Connection();
    $pdo = $connection->getConnection();
    $sql = "DELETE FROM users WHERE id = :usuario_id";
    $stmt = $pdo->prepare($sql);

    // Executar com os dados
    $stmt->execute([
        ':usuario_id' => $usuario_id,

    ]);

    // Usar COUNT(*) para contar registros no SQLite
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM users");
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = $resultado['total'];
    
    if ($total > 0) {
        $_SESSION['message'] = 'Usuário deletado com sucesso!';
        header('Location: usuario-screen.php');
        exit();
    } else {
        $_SESSION['message'] = 'Usuário deletado com sucesso.';
        header('Location: usuario-screen.php');
        exit();
    }
}

?>