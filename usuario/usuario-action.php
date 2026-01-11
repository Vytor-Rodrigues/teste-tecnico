<?php
session_start();
require '../connection.php';

if (isset($_POST['create_usuario'])) {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $selected_color_ids = $_POST['selected_color_ids'] ?? [];

    date_default_timezone_set('America/Sao_Paulo');
    $dataEnvio = date('Y-m-d, H:i:s');

    $connection = new Connection();
    $pdo = $connection->getConnection();
    $sql = "INSERT INTO users (name, email) VALUES (:nome, :email)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
    ]);

    if (!empty($selected_color_ids)) {
        $userId = $pdo->lastInsertId();
        $colorIdsString = implode(', ', $selected_color_ids);
        
        $sql_color = "INSERT INTO user_colors (user_id, color_id, created_at) VALUES (:user_id, :color_id, :created_at)";
        $stmt_color = $pdo->prepare($sql_color);
        
        $stmt_color->execute([
            ':user_id' => $userId,
            ':color_id' => $colorIdsString,
            ':created_at' => $dataEnvio,
        ]);
    }
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
    $selected_color_ids = $_POST['selected_color_ids'] ?? [];

    $connection = new Connection();
    $pdo = $connection->getConnection();
    $sql = "UPDATE users SET name = :nome, email = :email WHERE id = :usuario_id";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':usuario_id' => $usuario_id,
        ':nome' => $nome,
        ':email' => $email,
    ]);

    $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM user_colors WHERE user_id = :user_id");
    $stmt_check->execute([':user_id' => $usuario_id]);
    $exists = $stmt_check->fetchColumn();

    if (!empty($selected_color_ids)) {
        $colorIdsString = implode(', ', $selected_color_ids);
        
        if ($exists > 0) {
            $sql_color = "UPDATE user_colors SET color_id = :color_id WHERE user_id = :user_id";
            $stmt_color = $pdo->prepare($sql_color);
            $stmt_color->execute([
                ':user_id' => $usuario_id,
                ':color_id' => $colorIdsString,
            ]);
        } else {
            date_default_timezone_set('America/Sao_Paulo');
            $dataEnvio = date('Y-m-d, H:i:s');
            
            $sql_color = "INSERT INTO user_colors (user_id, color_id, created_at) VALUES (:user_id, :color_id, :created_at)";
            $stmt_color = $pdo->prepare($sql_color);
            $stmt_color->execute([
                ':user_id' => $usuario_id,
                ':color_id' => $colorIdsString,
                ':created_at' => $dataEnvio,
            ]);
        }
    } else {
        if ($exists > 0) {
            $sql_delete = "DELETE FROM user_colors WHERE user_id = :user_id";
            $stmt_delete = $pdo->prepare($sql_delete);
            $stmt_delete->execute([':user_id' => $usuario_id]);
        }
    }

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
    $cores_count = $_POST['cores_count'] ?? 0;

    $connection = new Connection();
    $pdo = $connection->getConnection();
    $sql = "DELETE FROM users WHERE id = :usuario_id";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':usuario_id' => $usuario_id,

    ]);
    if ($cores_count != 0) {
        $sql_colors = "DELETE FROM user_colors WHERE user_id = :usuario_id";
        $stmt_colors = $pdo->prepare($sql_colors);

        $stmt_colors->execute([
            ':usuario_id' => $usuario_id,
        ]);
    }

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