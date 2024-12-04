<?php
session_start();
ob_start();
include_once './conexao.php';

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if (empty($id)) {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado!</p>";
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Usuário</title>
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
            max-width: 500px;
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .link {
            margin-bottom: 20px;
        }
        .link a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            margin: 0 10px;
        }
        .link a:hover {
            text-decoration: underline;
        }
        .user-details {
            text-align: left;
            margin-top: 20px;
        }
        .user-details p {
            font-size: 16px;
            color: #555;
            margin: 8px 0;
        }
        .message {
            font-size: 14px;
            color: #f00;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Visualizar Usuário</h1>
        <div class="link">
            <a href="index.php">Listar</a>
            <a href="cadastrar.php">Cadastrar</a>
        </div>

        <?php
        $query_usuario = "SELECT id, nome, email FROM usuarios WHERE id = :id LIMIT 1";
        $result_usuario = $conn->prepare($query_usuario);
        $result_usuario->bindParam(':id', $id, PDO::PARAM_INT);
        $result_usuario->execute();

        if (($result_usuario) && ($result_usuario->rowCount() != 0)) {
            $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
            extract($row_usuario);
            echo "<div class='user-details'>";
            echo "<p><strong>ID:</strong> $id</p>";
            echo "<p><strong>Nome:</strong> $nome</p>";
            echo "<p><strong>E-mail:</strong> $email</p>";
            echo "</div>";
        } else {
            echo "<p class='message'>Erro: Usuário não encontrado!</p>";
        }
        ?>
    </div>
</body>

</html>
