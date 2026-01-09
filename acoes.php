<?php
session_start();
require 'connection.php';

if(isset($_POST['create_usuario'])){
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
//echo "Total de usuários: " . $total;

    
    
    if($total > 0 ){
        $_SESSION['message'] = 'Usuário criado com sucesso!';
        header('Location: index.php');
        exit();
    }else{
        $_SESSION['message'] = 'Erro ao criar usuário.';
        header('Location: index.php');
        exit();
    }
}

?>