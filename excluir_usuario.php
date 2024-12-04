<?php
session_start();
ob_start();
include_once './conexao.php';

$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
$confirmar = filter_input(INPUT_POST, "confirmar", FILTER_SANITIZE_STRING);

if ($confirmar === "sim" && !empty($id)) {
    $query_del_usuario = "DELETE FROM usuarios WHERE id = :id";
    $apagar_usuario = $conn->prepare($query_del_usuario);
    $apagar_usuario->bindParam(':id', $id, PDO::PARAM_INT);

    if ($apagar_usuario->execute()) {
        $_SESSION['msg'] = "<p style='color: green;'>Usuário apagado com sucesso!</p>";
    } else {
        $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Não foi possível excluir o usuário.</p>";
    }
} else {
    $_SESSION['msg'] = "<p style='color: #f00;'>Ação cancelada.</p>";
}

header("Location: index.php");
exit();
?>
