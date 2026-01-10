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
    <title>Cor - Editar</title>
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
                        <h4>Editar Cor
                            <a href="cores-screen.php" class="btn btn-danger float-end">VOLTAR</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($_GET['id'])) {
                            // Obter o ID do usuário da URL
                            $cor_id = $_GET['id'];
                            $sql = "SELECT * FROM colors WHERE id = :id";
                            $stmt = $connection->getConnection()->prepare($sql);
                            $stmt->execute(['id' => $cor_id]);

                            // Verificar se usuários existem
                            $pdo = $connection->getConnection();
                            $stmt2 = $pdo->prepare("SELECT COUNT(*) as total FROM colors");
                            $stmt2->execute();
                            $resultado = $stmt2->fetch(PDO::FETCH_ASSOC);
                            $total = $resultado['total'];

                            if ($total > 0) {
                                $cor = $stmt->fetch(PDO::FETCH_ASSOC);
    
                        ?>
                        <form action="cores-action.php" method="POST">
                            <input type="hidden" name="cor_id" value="<?=$cor['id']?>">
                            <div class="mb-3">
                                <label>Nome</label>
                                <input type="text" name="nome" value="<?=$cor['name']?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Hex</label>
                                <input type="text" name="hex" value="<?=$cor['hex']?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="update_cor" class="btn btn-primary">Salvar</button>
                            </div>

                        </form>
                        <?php
                        }else{
                            echo "<h5>Cor não encontrada.</h5>";
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