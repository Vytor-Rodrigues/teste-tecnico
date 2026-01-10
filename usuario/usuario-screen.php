<?php
session_start();
require '../connection.php';

$connection = new Connection();
$pdo = $connection->getConnection();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prova PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <?php include('../navbar.php'); ?>
    <div class="container mt-4">
        <?php include('../message.php'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Lista de Usuários
                            <a href="usuario-create.php" class="btn btn-primary float-end">Adicionar Usuário</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Cores vinc.</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $stmt = $pdo->prepare("SELECT * FROM users");
                                $stmt->execute();

                                $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if ($usuarios > 0) {
                                    foreach ($usuarios as $users) {
                                        ?>
                                        <tr>
                                            <td><?= $users['id'] ?></td>
                                            <td><?= $users['name'] ?></td>
                                            <td><?= $users['email'] ?></td>
                                            <td>0</td>
                                            <td>
                                                <a href="usuario-view.php?id=<?= $users['id'] ?>"
                                                    class="btn btn-secondary btn-sm">Visualizar</a>
                                                <a href="usuario-edit.php?id=<?= $users['id'] ?>"
                                                    class="btn btn-success btn-sm">Editar</a>
                                                <form action="usuario-action.php" method="POST" class="d-inline">
                                                    <input type="hidden" name="usuario_id" value="<?= $users['id'] ?>">
                                                    <button type="submit" name="delete_usuario" class="btn btn-danger btn-sm">
                                                        Deletar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php }
                                } else {
                                    echo "<h5>Nenhum usuário encontrado.</h5>";
                                } ?>
                            </tbody>
                        </table>
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