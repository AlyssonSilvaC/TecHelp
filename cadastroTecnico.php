<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>TecHelp - acesse sua conta</title>
    <link rel="stylesheet" href="css/cadastroComum.css">
    <?php require "conexao.php";?>
    <?php
    function validaCPF($cpf) { 
        // Extrai somente os números
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf);
        // Números repetidos?
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
    
        // calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($soma = 0, $i = 0; $i < $t; $i++) {
                $soma += $cpf[$i] * (($t + 1) - $i);
            }
            $soma = ((10 * $soma) % 11) % 10;
            if ($cpf[$i] != $soma) {
                return false;
            }
        }
        return true;
    };
    // resetando variáveis
    $nome = $password = $confirm_senha = $email = $cpf = "";
    $erro_nome = $erro_senha = $confirm_password_err = $email_err = $erro_cpf = $erro_celular = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // verificando nome do usuário
        if(empty(trim($_POST["nome"]))){
            $erro_nome = "Por favor, preencha o campo";     
        } elseif(!preg_match('/^[a-zA-Z\\s]+$/', trim($_POST["nome"]))){
            $erro_nome = "O campo apenas pode conter letras e espaços";
        } else{
            $nome = trim($_POST["nome"]);
        }

    //validando email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Por favor, preencha o campo";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "E-mail inválido";
    }else {
        $sql = "SELECT id FROM users WHERE email = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // conectando o parâmetro e a variável
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // usando parâmetros
            $param_email = trim($_POST["email"]);
            
            // tentando executar o pedido sql
            if(mysqli_stmt_execute($stmt)){
                // guardando resultado
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "Este e-mail já está cadastrado";
                } else {$email = trim($_POST["email"]);}
            }
        }
    }
    // Validando senha
    if(empty(trim($_POST["password"]))){
        $erro_senha = "Por favor, preencha o campo";     
    } elseif(strlen(trim($_POST["password"])) < 8){
        $erro_senha = "A senha deve conter no mínimo 8 caracteres";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validando confirmação de senha
    if(empty(trim($_POST["confirm_senha"]))){
        $confirm_password_err = "Confirme a senha";     
    } else{
        $confirm_senha = trim($_POST["confirm_senha"]);
        if(empty($erro_senha) && ($password != $confirm_senha)){
            $confirm_password_err = "Senha e confirmação são diferentes";
        }
    }

    // validando cpf
    if(empty(trim($_POST["cpf"]))) {
        $erro_cpf = "Por favor, preencha o campo";
    } elseif(validaCPF(trim($_POST["cpf"])) == false) {
        $erro_cpf = "CPF Inválido";
    } else{
        $cpf = trim($_POST["cpf"]);
    }

    // validando celular
    if(empty(trim($_POST["celular"]))) {
        $erro_celular = "Por favor, preencha o campo";
    } else {
        $celular = trim($_POST["celular"]);
    }
    //se os erros estiverem vazios, enviar para o bdd
    if(empty($erro_nome) && empty($erro_senha) && empty($confirm_password_err) && empty($email_err) && empty($erro_cpf)){
        $sql = "INSERT INTO users (nome, password, email, tipo) VALUES (?, ?, ?, ?)";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sssi", $param_nome, $param_senha, $param_email, $tipo);
            $param_nome = $nome;
            $param_senha = password_hash($password, PASSWORD_DEFAULT); // Encripta a senha
            $param_email = $email;
            $tipo = 2;
            // Tentando executar
            if(mysqli_stmt_execute($stmt)){
                $sql2 = "INSERT INTO infotecnico (email, cpf, celular) VALUES (?, ?, ?)";
                if($stmt2 = mysqli_prepare($link, $sql2)) {
                    mysqli_stmt_bind_param($stmt2, "sss", $param_email, $param_cpf, $param_celular);
                    $param_celular = $celular;
                    $param_cpf = $cpf;

                    // Redireciona para a tela de login
                    header("location: index.php");
                }
            } else{
                echo "Algo deu errado! Tente novamente mais tarde";
            }

            // fechando pedido php
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
</head>

<body>
    <div class="cadastro">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <span class="grupo">
                <input type="text" placeholder="Email" name = "email">
                    <span class="mensagem_erro"><?php if (!empty($email_err)){echo "Erro: ". $email_err;}?></span>  
                <br>

                <input type="password" placeholder="Senha" name = "password">
                    <span class="mensagem_erro"><?php if (!empty($erro_senha)){echo "Erro: ". $erro_senha;}?></span>  
                <br>

                <input type="password" placeholder="Confirmar Senha" name="confirm_senha">
                <span class="mensagem_erro"><?php if (!empty($confirm_password_err)){echo "Erro: ". $confirm_password_err;}?></span>  
                <br><br>
            </span>
            <span class="grupo">
                <input type="text" placeholder="CPF" name = "cpf" pattern="\d{3}\.?\d{3}\.?\d{3}-?\d{2}" autofocus required title="XXX.XXX.XXX-XX ou XXXXXXXXXXX"/>
                <span class="mensagem_erro"><?php if (!empty($erro_cpf)){echo "Erro: ". $erro_cpf;}?></span>
                <input type="text" placeholder="Nome" name="nome">
                <span class="mensagem_erro"><?php if (!empty($erro_nome)){echo "Erro: ". $erro_nome;}?></span>
                <input type="text" placeholder="Celular" name = "celular" pattern="\d{2}\s?\d{8}" autofocus required title="XX XXXXXXXX ou XXXXXXXXXX"/>
            </span>
            
            <button>Cadastrar-se</button>
        </form>
        <img src="img/enfeite1.png" alt="alysson">  
        <div class="nome">
            <h6>É um técnico? <a href="cadastroTecnico.html">Cadastre-se aqui!</a></h6>
        </div>
    </div>
    
</body>
</html>
