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
                        <h4>Relatorios
                            <a href="../index.php" class="btn btn-danger float-end" style="margin-right: 5px;">VOLTAR</a>
                            <a href="ultimos_vinculados.php?filtro=todos&ordem=DESC" class="btn btn-primary float" style="margin-right: 5px;">Mais novos</a>
                            <a href="ultimos_vinculados.php?filtro=todos&ordem=ASC" class="btn btn-primary float" style="margin-right: 5px;">Mais antigos</a>
                            <a href="ultimos_vinculados.php?filtro=um_dia&ordem=DESC" class="btn btn-primary float" style="margin-right: 5px;">Somente hoje</a>
                            <a href="ultimos_vinculados.php?filtro=cinco_dias&ordem=DESC" class="btn btn-primary float" style="margin-right: 5px;">Ultimos 5 dias</a>
                            <a href="ultimos_vinculados.php?filtro=todos&ordem=DESC" class="btn btn-primary float" style="margin-right: 5px;">Todos</a>

                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Usuarios</th>
                                    <th>Cores</th>
                                    <th>Data/Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ordem = $_GET['ordem'] ?? 'DESC';
                                $filtro = $_GET['filtro'] ?? 'todos';
                                
                                if ($ordem !== 'ASC' && $ordem !== 'DESC') {
                                    $ordem = 'DESC';
                                }
                                
                                $sql = "SELECT * FROM user_colors WHERE 1=1";
                                
                                if ($filtro === 'um_dia') {
                                    $hoje = date('Y-m-d');
                                    $sql .= " AND created_at LIKE '$hoje%'";
                                } elseif ($filtro === 'cinco_dias') {
                                    $cinco_dias_atras = date('Y-m-d', strtotime('-5 days'));
                                    $sql .= " AND created_at >= '$cinco_dias_atras'";
                                }
                                
                                $sql .= " ORDER BY created_at $ordem";
                                
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();

                                $vinculos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if (count($vinculos) > 0) {
                                    foreach ($vinculos as $vinculo) {
                                        $stmt_user = $pdo->prepare("SELECT name FROM users WHERE id = :user_id");
                                        $stmt_user->execute([':user_id' => $vinculo['user_id']]);
                                        $usuario = $stmt_user->fetch(PDO::FETCH_ASSOC);
                                        $nome_usuario = $usuario ? $usuario['name'] : 'Usuário não encontrado';
                                        
                                        $color_ids = array_filter(array_map('trim', explode(',', $vinculo['color_id'])));
                                        $cores_nomes = [];
                                        
                                        foreach ($color_ids as $color_id) {
                                            $stmt_color = $pdo->prepare("SELECT name FROM colors WHERE id = :color_id");
                                            $stmt_color->execute([':color_id' => $color_id]);
                                            $cor = $stmt_color->fetch(PDO::FETCH_ASSOC);
                                            if ($cor) {
                                                $cores_nomes[] = $cor['name'];
                                            }
                                        }
                                        
                                        $cores_texto = !empty($cores_nomes) ? implode(', ', $cores_nomes) : 'Nenhuma cor';
                                        ?>
                                        <tr>
                                            <td><?= $nome_usuario ?></td>
                                            <td><?= $cores_texto ?></td>
                                            <td><?= $vinculo['created_at'] ?></td>
                                        </tr>
                                    <?php }
                                } else {
                                    echo "<h5>Nenhuma vinculação encontrada.</h5>";
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