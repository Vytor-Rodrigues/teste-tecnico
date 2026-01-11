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
    <title>Cor - Criar</title>
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
                        <h4>Adicionar Cor
                            <a href="cores-screen.php" class="btn btn-danger float-end">VOLTAR</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php
                        $stmt = $pdo->prepare("SELECT * FROM users");
                        $stmt->execute();

                        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <form action="cores-action.php" method="POST">
                            <div class="mb-3">
                                <label>Nome</label>
                                <input type="text" name="nome" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Hex</label>
                                <input type="text" name="hex" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Vincular Usuários</label>

                                <select id="colorSelect" name="colorSelect" class="">
                                    <option value="">Nenhum usuário</option>
                                    <?php
                                    foreach ($usuarios as $usuario) {
                                        ?>
                                        <option value="<?= $usuario['id'] ?>" data-name="<?= $usuario['name'] ?>">
                                            <?= $usuario['name'] ?></option>
                                    <?php }
                                    ?>
                                </select>
                                <button type="button" id="addColorBtn" class="btn btn-primary mt-2">Escolher</button>

                            </div>
                            <div id="tagsContainer" class="mt-3 d-flex flex-wrap gap-2">
                            </div>
                            <?php
                            if (empty($_SESSION['selected_users'] ?? [])) {
                                ?>
                                <div class="mb-3">
                                    <button type="submit" name="create_cor" class="btn btn-primary">Salvar</button>
                                </div>
                                <?php
                            }
                            ?>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var selectEl = document.getElementById('colorSelect');
        var addBtn = document.getElementById('addColorBtn');
        var container = document.getElementById('tagsContainer');

        function temUsuarioSelecionado() {
            return container.querySelector('[data-color-id]') !== null;
        }

        function criarPlaceholder() {
            if (!temUsuarioSelecionado()) {
                var input = document.createElement('input');
                input.type = 'text';
                input.className = 'form-control';
                input.placeholder = 'Nenhum usuário selecionado';
                input.readOnly = true;
                input.setAttribute('data-placeholder', 'true');
                container.appendChild(input);
            }
        }

        function removerPlaceholder() {
            var placeholder = container.querySelector('[data-placeholder="true"]');
            if (placeholder) {
                placeholder.remove();
            }
        }

        function usuarioJaAdicionado(id) {
            var itens = container.querySelectorAll('[data-color-id]');
            for (var i = 0; i < itens.length; i++) {
                if (itens[i].getAttribute('data-color-id') == id) {
                    return true;
                }
            }
            return false;
        }

        addBtn.addEventListener('click', function () {
            var option = selectEl.options[selectEl.selectedIndex];
            if (!option || option.value === '') {
                return;
            }

            var id = option.value;
            var name = option.text;

            if (usuarioJaAdicionado(id)) {
                return;
            }

            removerPlaceholder();

            var div = document.createElement('div');
            div.className = 'd-flex align-items-center';
            div.setAttribute('data-color-id', id);
            div.style.gap = '6px';

            var input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control';
            input.value = name;
            input.readOnly = true;
            input.style.width = '150px';

            var hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'selected_users[]';
            hidden.value = id;

            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-danger btn-sm';
            btn.innerText = 'X';

            btn.addEventListener('click', function () {
                div.remove();
                criarPlaceholder();
            });

            div.appendChild(input);
            div.appendChild(btn);
            div.appendChild(hidden);
            container.appendChild(div);
        });

        criarPlaceholder();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>