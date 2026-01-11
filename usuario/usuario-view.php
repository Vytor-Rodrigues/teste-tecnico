<?php
require '../connection.php';
$connection = new Connection();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuario - Visualizar</title>
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
                        <h4>Visualizar Usuário
                            <a href="usuario-screen.php" class="btn btn-danger float-end">VOLTAR</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php

                        if (isset($_GET['id'])) {

                            $usuario_id = $_GET['id'];
                            $sql = "SELECT * FROM users WHERE id = :id";
                            $stmt = $connection->getConnection()->prepare($sql);
                            $stmt->execute(['id' => $usuario_id]);

                            $pdo = $connection->getConnection();
                            $stmt2 = $pdo->prepare("SELECT COUNT(*) as total FROM users");
                            $stmt2->execute();
                            $resultado = $stmt2->fetch(PDO::FETCH_ASSOC);
                            $total = $resultado['total'];

                            if ($total > 0) {
                                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                                $stmt_colors = $pdo->prepare("SELECT color_id FROM user_colors WHERE user_id = :user_id");
                                $stmt_colors->execute([':user_id' => $usuario_id]);
                                $user_colors = $stmt_colors->fetch(PDO::FETCH_ASSOC);

                                $cores_nomes = [];
                                if ($user_colors && !empty($user_colors['color_id'])) {

                                    $color_ids = explode(',', $user_colors['color_id']);
                                    $color_ids = array_map('trim', $color_ids);

                                    $placeholders = implode(',', array_fill(0, count($color_ids), '?'));
                                    $stmt_names = $pdo->prepare("SELECT name FROM colors WHERE id IN ($placeholders)");
                                    $stmt_names->execute($color_ids);
                                    $cores_nomes = $stmt_names->fetchAll(PDO::FETCH_COLUMN);
                                }
                                ?>

                                <div class="mb-3">
                                    <label>Nome</label>
                                    <p class="form-control">
                                        <?= $usuario['name'] ?>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label>Email</label>
                                    <p class="form-control">
                                        <?= $usuario['email'] ?>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label>Cores Vinculadas</label>
                                    <p class="form-control">
                                        <?= $cores_nomes !== null ? implode(', ', $cores_nomes) : 'Nenhuma cor vinculada' ?>
                                    </p>
                                </div>
                                <?php
                            } else {
                                echo "<h5>ID de usuário não encontrado.</h5>";
                            }
                        } else {
                            echo "<h5>ID não fornecido na URL.</h5>";
                        }
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