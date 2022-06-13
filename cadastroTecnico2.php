<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cadastro Técnico</title>
    <link rel="stylesheet" href="css/tecnicoCadastro2.css">
</head>
<body>

<form method="post" action="">
    <div class="center1">
    
        <div class="campo">

        <input type="text"placeholder="instituição onde você cursou">
        <br>
        <input type="text"placeholder="Nível de escolaridade">
        <br>
        Data de início
        <input name="datain" type="date"placeholder=""></input>
        Data de término
        <input name="datafim" type="date"placeholder=""></input> <label for="datafim"></label>
        <br>
        
        <textarea name="opiniao" cols="50" rows="5" style="width: 286px; height: 136px;" placeholder="Fale um pouco sobre você"></textarea>
        <br>
        <input type="file"name="dasd">
        <br>
        <button>Proximo</button>

        </div>
    </div>
</form>
<?php
    session_start();
    echo $_SESSION["email"];
?>

</body>
</html>