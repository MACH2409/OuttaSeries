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

        <!-- Custom Fonts -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

        <!-- CSS -->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/fuentes.css">
        <link rel="stylesheet" href="css/login.css">

        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="16x16" href="img/favicon.png">

        <!-- Sweet Alert 2 -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <title>Registro de nuevos usuarios</title>
    </head>
    <body>
        <?php
			if(isset($_POST["registro"])){
				$correo = $_POST["correo"];
				$user = $_POST["user"];
				$pass = $_POST["pass"];
				$foto = 'default.jpg';
				$query = "INSERT into usuario(correo, user, pass, foto) values('$correo','$user','$pass', '$foto');";
				$result = $bd->query($query);

                if(empty($user)){
                    echo "<script>Swal.fire({
                                title: '¡Error!',
                                text: 'El usuario no fue registrado. Llena todos los campos',
                                icon: 'error',
                                confirmButtonText: 'Intentar de nuevo'
                            })</script>";
                }elseif(empty($pass)){
                    echo "<script>Swal.fire({
                                title: '¡Error!',
                                text: 'El usuario no fue registrado. Llena todos los campos',
                                icon: 'error',
                                confirmButtonText: 'Intentar de nuevo'
                            })</script>";
                }elseif(empty($correo)){
                    echo "<script>Swal.fire({
                                title: '¡Error!',
                                text: 'El usuario no fue registrado. Llena todos los campos',
                                icon: 'error',
                                confirmButtonText: 'Intentar de nuevo'
                            })</script>";
                }elseif(strlen($user)>15){
                    echo "<script>Swal.fire({
                                title: '¡Error!',
                                text: 'El usuario no fue registrado. Nombre de Usuario muy largo',
                                icon: 'error',
                                confirmButtonText: 'Intentar de nuevo'
                            })</script>";
                }elseif(strlen($pass)<25){
                    echo "<script>Swal.fire({
                                title: '¡Error!',
                                text: 'El usuario no fue registrado. La contraseña no es fuerte',
                                icon: 'error',
                                confirmButtonText: 'Intentar de nuevo'
                            })</script>";
                }elseif(!filter_var($correo, FILTER_VALIDATE_EMAIL)){
                    echo "<script>Swal.fire({
                                title: '¡Error!',
                                text: 'El usuario no fue registrado. El correo es incorrecto',
                                icon: 'error',
                                confirmButtonText: 'Intentar de nuevo'
                            })</script>";
                }
                
        }
        ?>

        <form class="login-form" method="post">
            <div class="input-group span-2">
                <label class="input-label">Correo Electrónico</label>
                <input type="email" class="input-text" name="correo" placeholder="tucorreo@electronico.com">
            </div>
            <div class="input-group span-2">
                <label class="input-label">Usuario</label>
                <input type="text" class="input-text" name="user" placeholder="Nombre de usuario">
            </div>
            <div class="input-group span-2">
                <label class="input-label">Contraseña</label>
                <input type="password" class="input-text" name="pass" placeholder="*****">
            </div>
            <div class="input-group span-2">
                <input type="submit" class="input-button input-button-primary" name="registro" value="Registrarse">
            </div>
        </form>
    </body>
</html>