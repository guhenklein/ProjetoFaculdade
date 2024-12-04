<?php
include_once 'conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
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
        .link {
            margin-bottom: 20px;
            display: none;
        }
        .link a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        .link a:hover {
            text-decoration: underline;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        form label {
            text-align: left;
            width: 100%;
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
        }
        form input[type="text"],
        form input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            color: #333;
        }
        form input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .success {
            color: green;
            margin-bottom: 15px;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Interessado em entrar para nossa lista de espera?</h1>
        <h4>Preencha as informações abaixo </h4>
        <div class="link">
            <a href="index.php">Listar Usuários</a>
        </div>
        <?php
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (!empty($dados['CadUsuario'])) {
            $empty_input = false;
            $dados = array_map('trim', $dados);

            if (in_array("", $dados)) {
                $empty_input = true;
                echo "<p class='error'>Erro: Necessário preencher todos os campos!</p>";
            } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
                $empty_input = true;
                echo "<p class='error'>Erro: Necessário preencher com um e-mail válido!</p>";
            } else {
                // Verificar se o e-mail já existe no banco de dados
                $query_verifica_email = "SELECT id FROM usuarios WHERE email = :email LIMIT 1";
                $verifica_email = $conn->prepare($query_verifica_email);
                $verifica_email->bindParam(':email', $dados['email'], PDO::PARAM_STR);
                $verifica_email->execute();

                if ($verifica_email->rowCount() > 0) {
                    $empty_input = true;
                    echo "<p class='error'>Erro: O e-mail informado já está cadastrado!</p>";
                }
            }

            if (!$empty_input) {
                $query_usuario = "INSERT INTO usuarios (nome, email) VALUES (:nome, :email)";
                $cad_usuario = $conn->prepare($query_usuario);
                $cad_usuario->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
                $cad_usuario->bindParam(':email', $dados['email'], PDO::PARAM_STR);
                $cad_usuario->execute();

                if ($cad_usuario->rowCount()) {
                    echo "<p class='success'>Usuário cadastrado com sucesso!</p>";
                    unset($dados);
                } else {
                    echo "<p class='error'>Erro: Usuário não foi cadastrado!</p>";
                }
            }
        }
        ?>
        <form name="cad-usuario" method="post" action="">
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" placeholder="Insira o nome" 
                   value="<?php echo isset($dados['nome']) ? $dados['nome'] : ''; ?>">

            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Insira o email"
                   value="<?php echo isset($dados['email']) ? $dados['email'] : ''; ?>">
             
            <input type="submit" value="Cadastrar" name="CadUsuario">
        </form>
    </div>

    <script>
        document.addEventListener('keydown', function(event) {
            if (event.key === 'F4') {
                event.preventDefault(); 
                const linkDiv = document.querySelector('.link');
                linkDiv.style.display = 'block'; 
            }
        });
    </script>
</body>

</html>
