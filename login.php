<?php
	date_default_timezone_set('America/El_Salvador');
	include ("conexion/Conexion.php");
	//include ("Encryptar.php");
	$bd = new Conexion();
	//$enc = new Encryptar();
	session_start();
	if(isset($_SESSION["id_usuario"])){
		header("Location: index.php");
	}
?>


<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <!-- Iconos -->
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- CSS -->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/fuentes.css">
        <link rel="stylesheet" href="css/login.css">

        <!-- Icono -->
        <link rel="icon" type="image/png" sizes="16x16" href="img/favicon.png">

        <!-- Alertas -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <title>Iniciar sesión</title>
    </head>
    <body>
        <?php
			if(isset($_POST["entrar"])){

				$user = $_POST["user"];
				$pass = $_POST["pass"];

				$query = "SELECT * from usuario where user='$user' and pass='$pass';";

				$result = $bd->select($query);

				if($result->num_rows > 0){

					while($row = $result->fetch_assoc()){
						$id_us = $row["id_usuario"];
						$nombre = $row["nombre"];
						$paterno = $row["paterno"];
					}

					$_SESSION["id_usuario"] = $id_us;
					$_SESSION["nomb_comp"] = $nombre . " " . $paterno;
					header("Location: index.php");
				}else{
					echo "<script>
                            Swal.fire({
                                title: '¡Error!',
                                text: 'Datos incorrectos',
                                icon: 'error',
                                confirmButtonText: 'Intentar de nuevo'
                            })
                        </script>";
				}
			}
        ?>

        <form class="login-form" method="post">
            <div class="input-group span-2">
                <label class="input-label">Usuario</label>
                <input type="text" class="input-text" name="user" placeholder="Nombre de usuario">
            </div>
            <div class="input-group span-2">
                <label class="input-label">Contraseña</label>
                <input type="password" class="input-text" name="pass" placeholder="*****">
            </div>
            <div class="input-group">
                <input type="button" value="Registrarse" class="input-button" onclick="goToRegister()">
            </div>
            <div class="input-group">
                <input type="submit" class="input-button input-button-primary" name="entrar" value="Iniciar Sesión">
            </div>
        </form>
    </body>
    <script>
        function goToRegister(){
            location.href="registro.php";
        }
    </script>
</html>