<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="Cadastro-tecnico3.css">
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

    $cpf = $celular = "";
    $erro_cpf = $erro_celular = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
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
        if(empty($erro_celular) && empty($erro_cpf)){
            $sql = "INSERT INTO users (nome, password, email, tipo) VALUES (?, ?, ?, ?)";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "sssi", $param_nome, $param_senha, $param_email, $tipo);
                $param_nome = $nome;
                $param_senha = password_hash($password, PASSWORD_DEFAULT); // Encripta a senha
                $param_email = $email;
                $tipo = 2;
</head>
<body>

<form method="post" action="">
    
    <div id="area">

        <div id="titulo">
            
            <h2>Cadastro tecnico</h2>

            <div id="dados">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <input type="text"placeholder="Celular">
                    <br>
                    <input type="text"placeholder="CPF">
                    <br>
                    <input type="text"placeholder="nivel de escolaridade">
                    <input type="text"placeholder="instituição onde você cursou">
                    <h6>Data de inicio</h6>
                    <input type="date"placeholder=""></input>
                    <br>
                    <h6>Data de Finalização</h6>
                    <input type="date"placeholder=""></input>
                    <br>
                    
                    <textarea name="opiniao" cols="50" rows="5" style="width: 286px; height: 136px;" placeholder="Fale um pouco sobre você"></textarea>
                    <br>
                    <input type="file"name="dasd">
                    <br>
                    <button>Proximo</button>
                </form>
            </div>
        </div>
    </div>
</form>
<?php
    session_start();
    echo $_SESSION["email"];
    echo $_SESSION["nome"];
    echo $_SESSION["password"];
    echo $_SESSION["tipo"];
?>

</body>
</html>