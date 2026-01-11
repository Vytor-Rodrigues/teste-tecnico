<?php
require '../connection.php';
$connection = new Connection();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cor - Visualizar</title>
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
                        <h4>Visualizar Cor
                            <a href="../index.php" class="btn btn-danger float-end">VOLTAR</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php

                        if (isset($_GET['id'])) {
                            // Obter o ID da cor da URL
                            $color_id = $_GET['id'];
                            $sql = "SELECT * FROM colors WHERE id = :id";
                            $stmt = $connection->getConnection()->prepare($sql);
                            $stmt->execute(['id' => $color_id]);

                            // Verificar se as cores existem
                            $pdo = $connection->getConnection();
                            $stmt2 = $pdo->prepare("SELECT COUNT(*) as total FROM colors");
                            $stmt2->execute();
                            $resultado = $stmt2->fetch(PDO::FETCH_ASSOC);
                            $total = $resultado['total'];

                            if ($total > 0) {
                                $cor = $stmt->fetch(PDO::FETCH_ASSOC);

                                // Buscar todos os usuários que têm essa cor vinculada
                                $stmt_all_colors = $pdo->prepare("SELECT user_id, color_id FROM user_colors");
                                $stmt_all_colors->execute();
                                $all_user_colors = $stmt_all_colors->fetchAll(PDO::FETCH_ASSOC);

                                $users_with_color = [];
                                foreach ($all_user_colors as $user_color_record) {
                                    if (!empty($user_color_record['color_id'])) {
                                        // Separar os IDs de cores
                                        $color_ids = array_filter(array_map('trim', explode(',', $user_color_record['color_id'])));
                                        // Verificar se a cor atual está presente
                                        if (in_array($color_id, $color_ids)) {
                                            // Buscar o nome do usuário
                                            $stmt_user = $pdo->prepare("SELECT name FROM users WHERE id = :user_id");
                                            $stmt_user->execute([':user_id' => $user_color_record['user_id']]);
                                            $user = $stmt_user->fetch(PDO::FETCH_ASSOC);
                                            if ($user) {
                                                $users_with_color[] = $user['name'];
                                            }
                                        }
                                    }
                                }

                                ?>

                                <div class="mb-3">
                                    <label>Nome</label>
                                    <p class="form-control">
                                        <?= $cor['name'] ?>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label>Hex</label>
                                    <p class="form-control">
                                        <?= $cor['hex'] ?>
                                    </p>
                                </div>
                                    <div class="mb-3">
                                        <label>Nomes Vinculados</label>
                                        <p class="form-control">
                                            <?= !empty($users_with_color) ? implode(', ', $users_with_color) : 'Nenhum usuário vinculado' ?>
                                        </p>
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