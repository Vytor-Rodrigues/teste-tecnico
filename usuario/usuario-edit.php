<?php
session_start();
require '../connection.php';
$connection = new Connection();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuario - Editar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <?php include '../navbar.php'; ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Editar Usuário
                            <a href="../index.php" class="btn btn-danger float-end">VOLTAR</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($_GET['id'])) {
                            // Obter o ID do usuário da URL
                            $usuario_id = $_GET['id'];
                            $sql = "SELECT * FROM users WHERE id = :id";
                            $stmt = $connection->getConnection()->prepare($sql);
                            $stmt->execute(['id' => $usuario_id]);

                            // Verificar se usuários existem
                            $pdo = $connection->getConnection();
                            $stmt2 = $pdo->prepare("SELECT COUNT(*) as total FROM users");
                            $stmt2->execute();
                            $resultado = $stmt2->fetch(PDO::FETCH_ASSOC);
                            $total = $resultado['total'];

                            if ($total > 0) {
                                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
                        ?>
                        <form action="usuario-action.php" method="POST">
                            <input type="hidden" name="usuario_id" value="<?=$usuario['id']?>">
                            <div class="mb-3">
                                <label>Nome</label>
                                <input type="text" name="nome" value="<?=$usuario['name']?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" value="<?=$usuario['email']?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="update_usuario" class="btn btn-primary">Salvar</button>
                            </div>

                        </form>
                        <?php
                        }else{
                            echo "<h5>Usuário não encontrado.</h5>";
                        }}
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>