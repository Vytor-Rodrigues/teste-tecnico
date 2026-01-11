<?php
session_start();
require '../connection.php';

if (isset($_POST['create_cor'])) {
    $nome = $_POST['nome'] ?? '';
    $hex = $_POST['hex'] ?? '';
    $selected_users = $_POST['selected_users'] ?? [];

    date_default_timezone_set('America/Sao_Paulo');
    $dataEnvio = date('Y-m-d, H:i:s');

    $connection = new Connection();
    $pdo = $connection->getConnection();
    $sql = "INSERT INTO colors (name, hex) VALUES (:nome, :hex)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nome' => $nome,
        ':hex' => $hex,
    ]);

    if (!empty($selected_users)) {
        $colorId = $pdo->lastInsertId();
        
        // Para cada usuário selecionado, inserir o vínculo
        foreach ($selected_users as $user_id) {
            $sql_user = "INSERT INTO user_colors (user_id, color_id, created_at) VALUES (:user_id, :color_id, :created_at)";
            $stmt_user = $pdo->prepare($sql_user);
            
            $stmt_user->execute([
                ':user_id' => $user_id,
                ':color_id' => $colorId,
                ':created_at' => $dataEnvio,
            ]);
        }
    }
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
    $selected_users = $_POST['selected_users'] ?? [];

    $connection = new Connection();
    $pdo = $connection->getConnection();
    
    $sql = "UPDATE colors SET name = :nome, hex = :hex WHERE id = :cor_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':cor_id' => $cor_id,
        ':nome' => $nome,
        ':hex' => $hex,
    ]);

    $selected_users = array_map('intval', $selected_users);
    $stmt_all = $pdo->prepare("SELECT user_id, color_id FROM user_colors");
    $stmt_all->execute();
    $rows = $stmt_all->fetchAll(PDO::FETCH_ASSOC);

    $existingUsers = [];
    foreach ($rows as $row) {
        $userId = (int)$row['user_id'];
        $existingUsers[] = $userId;
        $colorIds = array_filter(array_map('trim', explode(',', $row['color_id'])));

        $hasColor = in_array((string)$cor_id, $colorIds, true);

        if (in_array($userId, $selected_users, true)) {
   
            if (!$hasColor) {
                $colorIds[] = (string)$cor_id;
                $newColorString = implode(', ', $colorIds);
                $upd = $pdo->prepare("UPDATE user_colors SET color_id = :color_id WHERE user_id = :user_id");
                $upd->execute([':color_id' => $newColorString, ':user_id' => $userId]);
            }
        } else {
            if ($hasColor) {
                $colorIds = array_values(array_diff($colorIds, [(string)$cor_id]));
                if (empty($colorIds)) {
                    $del = $pdo->prepare("DELETE FROM user_colors WHERE user_id = :user_id");
                    $del->execute([':user_id' => $userId]);
                } else {
                    $newColorString = implode(', ', $colorIds);
                    $upd = $pdo->prepare("UPDATE user_colors SET color_id = :color_id WHERE user_id = :user_id");
                    $upd->execute([':color_id' => $newColorString, ':user_id' => $userId]);
                }
            }
        }
    }

    foreach ($selected_users as $userId) {
        if (!in_array($userId, $existingUsers, true)) {
            date_default_timezone_set('America/Sao_Paulo');
            $dataEnvio = date('Y-m-d, H:i:s');
            $ins = $pdo->prepare("INSERT INTO user_colors (user_id, color_id, created_at) VALUES (:user_id, :color_id, :created_at)");
            $ins->execute([':user_id' => $userId, ':color_id' => $cor_id, ':created_at' => $dataEnvio]);
        }
    }

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

    $connection = new Connection();
    $pdo = $connection->getConnection();
    
    $stmt_colors = $pdo->prepare("SELECT user_id, color_id FROM user_colors");
    $stmt_colors->execute();
    $all_user_colors = $stmt_colors->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($all_user_colors as $user_color) {
        if (!empty($user_color['color_id'])) {
            $color_ids = array_filter(array_map('trim', explode(',', $user_color['color_id'])));
            
            if (in_array($cor_id, $color_ids)) {
                $color_ids = array_diff($color_ids, [$cor_id]);
                
                if (empty($color_ids)) {
                    $sql_delete = "DELETE FROM user_colors WHERE user_id = :user_id";
                    $stmt_delete = $pdo->prepare($sql_delete);
                    $stmt_delete->execute([':user_id' => $user_color['user_id']]);
                } else {
                    $new_color_string = implode(', ', $color_ids);
                    $sql_update = "UPDATE user_colors SET color_id = :color_id WHERE user_id = :user_id";
                    $stmt_update = $pdo->prepare($sql_update);
                    $stmt_update->execute([
                        ':color_id' => $new_color_string,
                        ':user_id' => $user_color['user_id']
                    ]);
                }
            }
        }
    }
    
    $sql = "DELETE FROM colors WHERE id = :cor_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':cor_id' => $cor_id]);

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