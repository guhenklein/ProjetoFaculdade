<?php
session_start();
include_once './conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Usuários</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        .link {
            text-align: center;
            margin-bottom: 20px;
        }
        .link a {
            text-decoration: none;
            margin: 0 10px;
            color: #007bff;
            font-weight: bold;
        }
        .link a:hover {
            text-decoration: underline;
        }
        .user-card {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 10px;
            background: #f9f9f9;
        }
        .user-card h2 {
            margin: 0;
            font-size: 18px;
            color: #555;
        }
        .user-card p {
            margin: 5px 0;
            color: #777;
        }
        .user-card a {
            text-decoration: none;
            color: #007bff;
            margin-right: 10px;
        }
        .user-card a:hover {
            text-decoration: underline;
        }
        .pagination {
            text-align: center;
            margin-top: 20px;
        }
        .pagination a {
            text-decoration: none;
            color: #007bff;
            margin: 0 5px;
            font-weight: bold;
        }
        .pagination a:hover {
            text-decoration: underline;
        }
        .message {
            text-align: center;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .message.success {
            color: green;
        }
        .message.error {
            color: #f00;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Listar Usuários</h1>
        <div class="link">
            <a href="cadastro.php">Cadastrar</a>
        </div>

        <?php
        if (isset($_SESSION['msg'])) {
            echo "<div class='message'>" . $_SESSION['msg'] . "</div>";
            unset($_SESSION['msg']);
        }

        // Receber o número da página
        $pagina_atual = filter_input(INPUT_GET, "page", FILTER_SANITIZE_NUMBER_INT);
        $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;

        // Setar a quantidade de registros por página
        $limite_resultado = 40;

        // Calcular o início da visualização
        $inicio = ($limite_resultado * $pagina) - $limite_resultado;

        $query_usuarios = "SELECT id, nome, email FROM usuarios ORDER BY id DESC LIMIT $inicio, $limite_resultado";
        $result_usuarios = $conn->prepare($query_usuarios);
        $result_usuarios->execute();

        if (($result_usuarios) AND ($result_usuarios->rowCount() != 0)) {
            while ($row_usuario = $result_usuarios->fetch(PDO::FETCH_ASSOC)) {
                extract($row_usuario);
                echo "<div class='user-card'>";
                echo "<h2>ID: $id</h2>";
                echo "<p>Nome: $nome</p>";
                echo "<p>E-mail: $email</p>";
                echo "<a href='visualizar.php?id=$id'>Visualizar</a>";
                echo "<a href='editar.php?id=$id'>Editar</a>";
                echo "<a href='apagar.php?id=$id'>Apagar</a>";
                echo "</div>";
            }

            // Contar a quantidade de registros no BD
            $query_qnt_registros = "SELECT COUNT(id) AS num_result FROM usuarios";
            $result_qnt_registros = $conn->prepare($query_qnt_registros);
            $result_qnt_registros->execute();
            $row_qnt_registros = $result_qnt_registros->fetch(PDO::FETCH_ASSOC);

            // Quantidade de páginas
            $qnt_pagina = ceil($row_qnt_registros['num_result'] / $limite_resultado);

            // Máximo de links
            $maximo_link = 2;

            echo "<div class='pagination'>";
            echo "<a href='index.php?page=1'>Primeira</a>";

            for ($pagina_anterior = $pagina - $maximo_link; $pagina_anterior <= $pagina - 1; $pagina_anterior++) {
                if ($pagina_anterior >= 1) {
                    echo "<a href='index.php?page=$pagina_anterior'>$pagina_anterior</a>";
                }
            }

            echo "<strong>$pagina</strong>";

            for ($proxima_pagina = $pagina + 1; $proxima_pagina <= $pagina + $maximo_link; $proxima_pagina++) {
                if ($proxima_pagina <= $qnt_pagina) {
                    echo "<a href='index.php?page=$proxima_pagina'>$proxima_pagina</a>";
                }
            }

            echo "<a href='index.php?page=$qnt_pagina'>Última</a>";
            echo "</div>";
        } else {
            echo "<div class='message error'>Erro: Nenhum usuário encontrado!</div>";
        }
        ?>
    </div>
</body>

</html>
