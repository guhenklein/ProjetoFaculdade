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

$query_usuario = "SELECT id FROM usuarios WHERE id = :id LIMIT 1";
$result_usuario = $conn->prepare($query_usuario);
$result_usuario->bindParam(':id', $id, PDO::PARAM_INT);
$result_usuario->execute();

if (($result_usuario) && ($result_usuario->rowCount() != 0)) {
    $usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Confirmar Exclusão</title>
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
                max-width: 400px;
                text-align: center;
            }
            h1 {
                color: #333;
                margin-bottom: 20px;
            }
            p {
                color: #555;
                margin-bottom: 20px;
            }
            form button {
                background-color: #007bff;
                color: #fff;
                padding: 10px 15px;
                border: none;
                border-radius: 5px;
                font-size: 16px;
                cursor: pointer;
                transition: background-color 0.3s ease;
                margin: 5px;
            }
            form button:hover {
                background-color: #0056b3;
            }
            form button[type="button"] {
                background-color: #ccc;
                color: #333;
            }
            form button[type="button"]:hover {
                background-color: #aaa;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Confirmar Exclusão</h1>
            <p>Você realmente deseja excluir o usuário com ID <?= htmlspecialchars($usuario['id']); ?>?</p>
            <form method="post" action="excluir_usuario.php">
                <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']); ?>">
                <button type="submit" name="confirmar" value="sim">Sim</button>
                <button type="button" onclick="window.location.href='index.php'">Não</button>
            </form>
        </div>
    </body>
    </html>
    <?php
} else {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado!</p>";
    header("Location: index.php");
    exit();
}
?>
