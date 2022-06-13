<!DOCTYPE html>
<html >
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
    <?php require_once "conexao.php" ?>
    <?php
        // iniciar a sessão
        session_start();
        // se já houver uma sessão, redirecionar para a tela de perfil
        if(isset($_SESSION["logado"]) && $_SESSION["logado"] == true) {
            if ($_SESSION["tipo"] == 1) {
                header("location: localizar.html");
            } elseif($_SESSION["tipo"] == 2) {
                header("location: foto.html");
            }
            exit;
        }
        $login = $senha = $tipo = "";
        $erro_senha = $erro_login = "";

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty((trim($_POST["login"])))) {
                $erro_login = "Por favor, preencha o campo";
            } else {
                $login = trim($_POST["login"]);
            }

            if (empty((trim($_POST["senha"])))) {
                $erro_senha = "Por favor, preencha o campo";
            } else {
                $senha = trim($_POST["senha"]);
            }
            // verificando se está correto
            if(empty($erro_login) && empty($erro_senha)) {
                $sql = "SELECT id, email, password, tipo FROM users WHERE email = ?";

                if($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "s", $param_login);
                    $param_login = $login;

                    // tenta executar o pedido sql
                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_store_result($stmt); 
                        if (mysqli_stmt_num_rows($stmt) == 1) {
                            mysqli_stmt_bind_result($stmt, $id, $login, $senha_crypt, $tipo);
                            if (mysqli_stmt_fetch($stmt)) {
                                if (password_verify($senha, $senha_crypt)) {
                                    session_start();
                                    
                                    $_SESSION["logado"] = true;
                                    $_SESSION["id"] = $id;
                                    $_SESSION["login"] = $login;
                                    if ($tipo == 1) {
                                        $_SESSION["tipo"] = 1;
                                        header("location: localizar.html");
                                    } elseif($tipo == 2) {
                                        $_SESSION["tipo"] = 2;
                                        header("location: foto.html");
                                    }
                                    
                                }else{
                                    // senha incorreta
                                    $erro_login = "Login e/ou Senha inválido";
                                }

                                
                            }
                        } else {
                            // usuário não existe
                            $erro_login = "Login e/ou Senha inválido";
                        }
                    } else {
                        echo "Algo deu errado! Tente novamente mais tarde";
                    }
                    // fechando pedido sql
                    mysqli_stmt_close($stmt);
                }
            }
            // fechando conexão
            mysqli_close($link);
        }
    ?>
</head>

<body>
    <div class = "center">
        <img src="img/logo.png" alt="">
        <div class="login">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="text" placeholder="Email" name="login">
                <span class="mensagem_erro"><?php if (!empty($erro_login)){echo "Erro: ". $erro_login;}?></span>
                <br>
                <input type="password" placeholder="Senha" name="senha">
                <span class="mensagem_erro"><?php if (!empty($erro_login)){echo "Erro: ". $erro_senha;}?></span>
                <br>
                <div class = "botao">
                    <button  type = "submit">Entrar</button>
                    <a href="cadastroComum.php"><button type = "button">Registrar</button></a>    
                </div>
                <a href="recuperarSenha.html"><h5>Recuperar Senha</h5></a>
            </form>
            
        </div>
    </div>

</body>
</html>