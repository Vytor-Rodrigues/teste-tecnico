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

                            $cor_id = $_GET['id'];
                            $sql = "SELECT * FROM colors WHERE id = :id";
                            $stmt = $connection->getConnection()->prepare($sql);
                            $stmt->execute(['id' => $cor_id]);

                            $pdo = $connection->getConnection();
                            $stmt2 = $pdo->prepare("SELECT COUNT(*) as total FROM colors");
                            $stmt2->execute();
                            $resultado = $stmt2->fetch(PDO::FETCH_ASSOC);
                            $total = $resultado['total'];

                            if ($total > 0) {
                                $cor = $stmt->fetch(PDO::FETCH_ASSOC);

                                $stmt_all_users = $pdo->prepare("SELECT * FROM users");
                                $stmt_all_users->execute();
                                $usuarios = $stmt_all_users->fetchAll(PDO::FETCH_ASSOC);
                                
                                $stmt_all_colors = $pdo->prepare("SELECT user_id, color_id FROM user_colors");
                                $stmt_all_colors->execute();
                                $all_user_colors = $stmt_all_colors->fetchAll(PDO::FETCH_ASSOC);
                                
                                $selected_users = [];
                                foreach ($all_user_colors as $user_color_record) {
                                    if (!empty($user_color_record['color_id'])) {
                                        $color_ids = array_filter(array_map('trim', explode(',', $user_color_record['color_id'])));
                                        if (in_array($cor_id, $color_ids)) {
                                            foreach ($usuarios as $usuario) {
                                                if ($usuario['id'] == $user_color_record['user_id']) {
                                                    $selected_users[] = [
                                                        'id' => $usuario['id'],
                                                        'name' => $usuario['name']
                                                    ];
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
    
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
                                <label>Vincular Usuários</label>

                                <select id="colorSelect" name="colorSelect" class="">
                                    <option value="">Nenhum usuário</option>
                                <?php 
                                    foreach ($usuarios as $usuario) {
                                ?>
                                <option value="<?=$usuario['id']?>" data-name="<?=$usuario['name']?>"><?=$usuario['name']?></option>
                                <?php }
                                 ?>
                                </select>
                                <button type="button" id="addColorBtn" class="btn btn-primary mt-2">Escolher</button>
                              
                                </div>
                                  <div id="tagsContainer" class="mt-3 d-flex flex-wrap gap-2">
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


<script>
    var selectEl = document.getElementById('colorSelect');
    var addBtn = document.getElementById('addColorBtn');
    var container = document.getElementById('tagsContainer');

    function temItem() {
        return container.querySelector('[data-color-id]') !== null;
    }

    function criarPlaceholder() {
        if (!temItem()) {
            var input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control';
            input.placeholder = 'Nenhum user selecionado';
            input.readOnly = true;
            input.setAttribute('data-placeholder', 'true');
            container.appendChild(input);
        }
    }

    function removerPlaceholder() {
        var ph = container.querySelector('[data-placeholder="true"]');
        if (ph) {
            ph.remove();
        }
    }

    function userJaAdicionada(id) {
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

        if (userJaAdicionada(id)) {
            return;
        }

        removerPlaceholder();

        var div = document.createElement('div');
        div.className = 'd-flex align-items-center';
        div.style.gap = '6px';
        div.setAttribute('data-color-id', id);

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

    <?php if (!empty($selected_users)): ?>
        var usersSelecionadas = <?= json_encode($selected_users) ?>;
        for (var i = 0; i < usersSelecionadas.length; i++) {
            var user = usersSelecionadas[i];

            removerPlaceholder();

            var div = document.createElement('div');
            div.className = 'd-flex align-items-center';
            div.style.gap = '6px';
            div.setAttribute('data-color-id', user.id);

            var input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control';
            input.value = user.name;
            input.readOnly = true;
            input.style.width = '150px';

            var hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'selected_users[]';
            hidden.value = user.id;

            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-danger btn-sm';
            btn.innerText = 'X';

            btn.addEventListener('click', function () {
                this.parentElement.remove();
                criarPlaceholder();
            });

            div.appendChild(input);
            div.appendChild(btn);
            div.appendChild(hidden);
            container.appendChild(div);
        }
    <?php else: ?>
        criarPlaceholder();
    <?php endif; ?>
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>