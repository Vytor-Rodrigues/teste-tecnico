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
                            <a href="usuario-screen.php" class="btn btn-danger float-end">VOLTAR</a>
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
                                
                                // Buscar todas as cores disponíveis
                                $stmt_all_colors = $pdo->prepare("SELECT * FROM colors");
                                $stmt_all_colors->execute();
                                $cores = $stmt_all_colors->fetchAll(PDO::FETCH_ASSOC);
                                
                                // Buscar cores já vinculadas ao usuário
                                $stmt_user_colors = $pdo->prepare("SELECT color_id FROM user_colors WHERE user_id = :user_id");
                                $stmt_user_colors->execute([':user_id' => $usuario_id]);
                                $user_colors_data = $stmt_user_colors->fetch(PDO::FETCH_ASSOC);
                                
                                // Preparar array de cores selecionadas com seus nomes
                                $selected_colors = [];
                                if ($user_colors_data && !empty($user_colors_data['color_id'])) {
                                    $color_ids = array_filter(array_map('trim', explode(',', $user_colors_data['color_id'])));
                                    
                                    foreach ($color_ids as $color_id) {
                                        foreach ($cores as $cor) {
                                            if ($cor['id'] == $color_id) {
                                                $selected_colors[] = [
                                                    'id' => $cor['id'],
                                                    'name' => $cor['name']
                                                ];
                                                break;
                                            }
                                        }
                                    }
                                }
    
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
                                <label>Vincular cores</label>

                                <select id="colorSelect" name="colorSelect" class="">
                                    <option value="">Nenhuma cor</option>
                                <?php 
                                    foreach ($cores as $colors) {
                                ?>
                                <option value="<?=$colors['id']?>" data-name="<?=$colors['name']?>"><?=$colors['name']?></option>
                                <?php }
                                 ?>
                                </select>
                                <button type="button" id="addColorBtn" class="btn btn-primary mt-2">Escolher</button>
                              
                                </div>
                                  <div id="tagsContainer" class="mt-3 d-flex flex-wrap gap-2">
                                </div>
                                <?php
                               if (empty($_SESSION['selected_colors'] ?? [])) { 
                                    ?>
                                     <div class="mb-3">
                                <button type="submit" name="update_usuario" class="btn btn-primary">Salvar</button>
                            </div> 
                                    <?php
                                }
                                    ?>
                      
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
            input.placeholder = 'Nenhuma cor selecionada';
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

    function corJaAdicionada(id) {
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

        if (corJaAdicionada(id)) {
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
        hidden.name = 'selected_color_ids[]';
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

    <?php if (!empty($selected_colors)): ?>
        var coresSelecionadas = <?= json_encode($selected_colors) ?>;
        for (var i = 0; i < coresSelecionadas.length; i++) {
            var cor = coresSelecionadas[i];

            removerPlaceholder();

            var div = document.createElement('div');
            div.className = 'd-flex align-items-center';
            div.style.gap = '6px';
            div.setAttribute('data-color-id', cor.id);

            var input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control';
            input.value = cor.name;
            input.readOnly = true;
            input.style.width = '150px';

            var hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'selected_color_ids[]';
            hidden.value = cor.id;

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