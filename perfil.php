<?php
    date_default_timezone_set('America/El_Salvador');
    //Se incluye el archivo Conexion.php que contiene la clase usada para la conexion a la bd
    include ("conexion/Conexion.php");
    //Se crea el objeto conexion
    $bd = new Conexion();
    //Se inicia la sesion o se propaga
    session_start();
    if(!isset($_SESSION["id_usuario"])){
        header("Location: login.php");
    }
?>


<!doctype html>
<html>
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
        <link rel="stylesheet" href="css/sidebar.css">
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="css/alta_subasta.css">

        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="16x16" href="img/favicon.png">

        <!-- Sweet Alert 2 -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <title>Inicio</title>
    </head>
    <body>

        <!-- Sidebar -->
        <?php
        include ("sidebar.php");
        ?>

        <!-- PHP -->
		<?php

			if(isset($_POST["guardar"])){

				$id_usuario = $_POST["id_usuario"];
				$nombre = $_POST["nombre"];
				$paterno = $_POST["paterno"];
				$materno = $_POST["materno"];
				$edad = $_POST["edad"];
				$correo = $_POST["correo"];
				$user = $_POST["user"];
				$pass = $_POST["pass"];

				$foto = $_FILES["foto"]["name"];
				$ruta = $_FILES["foto"]["tmp_name"];

				if($foto == null){
					echo "<script>Swal.fire({
                        title: '¡Advertencia!',
                        text: 'Foto vacía, continuarás con la misma foto',
                        icon: 'warning',
                        confirmButtonText: 'Intentar de nuevo'
                    })</script>";

					$res = $bd->query("UPDATE usuario set nombre='$nombre', paterno='$paterno', materno='$materno', edad='$edad',
                          correo='$correo', user='$user', pass='$pass' where id_usuario=$id_usuario;");

					if($res==true){
						echo "<script>Swal.fire({
                        title: '¡Proceso exitoso!',
                        text: 'Datos modificados correctamente',
                        icon: 'success'
                    })
                </script>";
						$_SESSION["nomb_comp"] = $nombre." ".$paterno;
					}else{
						echo "<script>Swal.fire({
                        title: '¡Error!',
                        text: 'No se modificaron los datos',
                        icon: 'error',
                        confirmButtonText: 'Intentar de nuevo'
                    })</script>";
					}

				}else{
					echo "<script>Swal.fire({
                        title: '¡Proceso exitoso!',
                        text: 'Tu nueva foto será agregada',
                        icon: 'success'
                    })</script>";

					$dest = "images/usuarios/";
					copy($ruta,$dest.''.$foto);

					$res = $bd->query("UPDATE usuario set nombre='$nombre', paterno='$paterno', materno='$materno', edad='$edad',
                          foto='$foto', correo='$correo', user='$user', pass='$pass' where id_usuario=$id_usuario;");

					if($res==true){
						echo "<script>Swal.fire({
                        title: '¡Proceso exitoso!',
                        text: 'Datos modificados correctamente',
                        icon: 'success'
                    })</script>";
						$_SESSION["nomb_comp"] = $nombre." ".$paterno;
					}else{
						echo "<script>Swal.fire({
                        title: '¡Error!',
                        text: 'No se modificaron los datos',
                        icon: 'error',
                        confirmButtonText: 'Intentar de nuevo'
                    })</script>";
					}
				}
			}
		?>

        <!-- Body -->
        <section class="home-section">
            <div class="text">Datos personales</div>
            <p class="text-companion">Datos personales</p>

			<?php
				$id_user = $_SESSION["id_usuario"];
				$res = $bd->select("SELECT * from usuario where id_usuario=$id_user");

				if($res->num_rows == 1){
					while($row = $res->fetch_assoc()){
						$id_usuario = $row["id_usuario"];
						$nombre = $row["nombre"];
						$paterno = $row["paterno"];
						$materno = $row["materno"];
						$edad = $row["edad"];
						$foto = $row["foto"];
						$correo = $row["correo"];
						$user = $row["user"];
						$pass = $row["pass"];
						?>

						<div>

							<form role="form" action="" class="login-form" method="post" enctype="multipart/form-data">

								<div>

									<div class="input-group">
										<label>Id</label>
										<input type="text" name="id_usuario" class="input-text" readonly value="<?php echo $id_usuario; ?>">
									</div>

									<div class="input-group">
										<label>Nombre</label>
										<input type="text" name="nombre" class="input-text" value="<?php echo $nombre; ?>">
									</div>

									<div class="input-group">
										<label>Apellido Paterno</label>
										<input type="text" name="paterno" class="input-text" value="<?php echo $paterno; ?>">
									</div>

									<div class="input-group">
										<label>Apellido Materno</label>
										<input type="text" name="materno" class="input-text" value="<?php echo $materno; ?>">
									</div>

									<div class="input-group">
										<label>Edad</label>
										<input type="number" name="edad" class="input-text" value="<?php echo $edad; ?>">
									</div>

								</div>
								<div>

									<div class="input-group">
										<label>Correo</label>
										<input type="email" name="correo" class="input-text" value="<?php echo $correo; ?>" required>
									</div>

									<div class="input-group">
										<label>Usuario</label>
										<input type="text" name="user" class="input-text" value="<?php echo $user; ?>" required>
									</div>

									<div class="input-group">
										<label>Contraseña</label>
										<input type="text" name="pass" class="input-text" value="<?php echo $pass; ?>" required>
									</div>

									<div class="input-group">
										<label>Foto</label>
										<img style="margin-bottom:10px; width:250px;height:100px;" src="images/usuarios/<?php if($foto) echo $foto;
										else echo "sin-foto.jpg";
										?>" width="100" height="100" >
										<input type="file" name="foto">
									</div>

								</div>
								<button name="guardar" type="submit" class="span-2 input-text input-button-primary">Guardar</button>
							</form>

						</div>
						<?php
					}
				}
			?>
        </section>
    </body>
</html>