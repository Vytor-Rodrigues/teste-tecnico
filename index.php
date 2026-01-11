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
    <?php include('navbar.php'); ?>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Listas Cores/Nomes
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 33.33%">Gerenciar Nomes</th>
                                    <th style="width: 33.33%">Gerenciar Cores</th>
                                    <th style="width: 33.33%">Relatorios(nomes/cores)</th>
                                </tr>
                            </thead>
                            <tbody>
                                        <tr>
                                            <td><a href="usuario/usuario-screen.php" class="btn btn-primary w-100">Crie/liste Nomes</a></td>
                                            <td><a href="cores/cores-screen.php" class="btn btn-primary w-100">Crie/liste Cores</a></td>
                                            <td><a href="ultimos/ultimos_vinculados.php" class="btn btn-primary w-100">Ultimos vinculados</a></td>
                                        </tr>
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