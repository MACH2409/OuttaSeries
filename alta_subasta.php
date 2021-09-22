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
			if(isset($_POST["agregar"])){

				//Variables que se guardaran en la tabla producto
				$nombre = $_POST["nombre"];
				$descripcion = $_POST["descripcion"];
				$categoria = $_POST["categoria"];
				$foto = $_FILES["foto"]["name"];//nombre de la imagen del producto
				$ruta = $_FILES["foto"]["tmp_name"];//ruta de la imagen del producto

				//Variables que se guardaran en la tabla subasta
				$p_minimo = $_POST["minimo"];
				$p_maximo = $_POST["maximo"];
				$fecha_hora_actual = date("Y-m-d H:i:s");
				$fecha_fin = $_POST["fecha_fin"];//Esto no se insertara en la tabla
				$hora_fin = $_POST["hora_fin"];//Esto no se insertara en la tabla
				$fecha_hora_fin = "$fecha_fin $hora_fin:00";
				$estado = 0;//1 = vendida && 0 = disponible
				$subastador = $_SESSION["id_usuario"];

				if($foto == null){

					$res = $bd->query("INSERT into producto(nombre, descripcion, imagen, id_categoria)
                            values('$nombre','$descripcion','default.jpg',$categoria);");

					if($res == true){
						echo "<script>Swal.fire({
                        title: '¡Proceso exitoso!',
                        text: 'Producto agregado correctamente',
                        icon: 'success'
                    })</script>";
						$id_producto = $bd->insert_id();

						$res2 = $bd->query("INSERT into subasta(min, max, tiempo_ini, tiempo_fin, estado, subastador, id_producto)
                              values($p_minimo,$p_maximo,'$fecha_hora_actual','$fecha_hora_fin',$estado,$subastador,$id_producto);");

						if($res2 == true){
							echo "<script>Swal.fire({
                            title: '¡Proceso exitoso!',
                            text: 'Subasta agregada correctamente',
                            icon: 'success'
                        })</script>";
						}else{
							echo "<script>Swal.fire({
                            title: '¡Error!',
                            text: 'No se pudo agregar la subasta',
                            icon: 'error',
                            confirmButtonText: 'Intentar de nuevo'
                        });</script>";
						}
					}else{
						echo "<script>Swal.fire({
                        title: '¡Error!',
                        text: 'No se pudo agregar el producto ni la subasta',
                        icon: 'error',
                        confirmButtonText: 'Intentar de nuevo'
                    });</script>";
					}

				}else{

					$dest = "images/productos/";
					copy($ruta, $dest . '' . $foto);

					$res = $bd->query("INSERT into producto(nombre, descripcion, imagen, id_categoria)
                            values('$nombre','$descripcion','$foto',$categoria);");

					if($res == true){
						echo "<script>Swal.fire({
                        title: '¡Proceso exitoso!',
                        text: 'Producto agregado correctamente',
                        icon: 'success'
                    })</script>";
						$id_producto = $bd->insert_id();

						$res2 = $bd->query("INSERT into subasta(min, max, tiempo_ini, tiempo_fin, estado, subastador, id_producto)
                              values($p_minimo,$p_maximo,'$fecha_hora_actual','$fecha_hora_fin',$estado,$subastador,$id_producto);");

						if($res2 == true){
							echo "<script>Swal.fire({
                        title: '¡Proceso exitoso!',
                        text: 'Subasta agregada correctamente',
                        icon: 'success'
                    })</script>";
						}else{
							echo "<script>Swal.fire({
                        title: '¡Error!',
                        text: 'No se pudo agregar la subasta',
                        icon: 'error',
                        confirmButtonText: 'Intentar de nuevo'
                    });</script>";
						}
					}else{
						echo "<script>Swal.fire({
                        title: '¡Error!',
                        text: 'No se pudo agregar el producto ni la subasta',
                        icon: 'error',
                        confirmButtonText: 'Intentar de nuevo'
                    });</script>";
					}
				}

			}
		?>

        <!-- Body -->
        <section class="home-section">
            <div class="text">Subastas</div>
            <p class="text-companion">Agregar nuevas subastas</p>

            <div>
                <form role="form" action="" class="login-form" method="post" enctype="multipart/form-data">
					<div>
						<h3>Detalles producto</h3>
						<div class="input-group">
							<label class="input-label">Nombre</label>
							<input type="text" class="input-text" name="nombre" placeholder="Nombre del producto" required>
						</div>
						<div class="input-group">
							<label class="input-label">Descripción</label>
							<textarea name="descripcion" class="input-text" placeholder="Descripción del producto" required></textarea>
						</div>
						<div class="input-group">
							<label class="input-label">Categoría</label>
							<select name="categoria" class="input-text" placeholder="Categoría" required>
								<?php
									$res = $bd->select("SELECT * from categoria");
									if($res->num_rows > 0){
										while($row = $res->fetch_assoc()){
											echo "<option class='input-text' value='".$row["id_categoria"]."'>".$row["categoria"]."</option>";
										}
									}else{
										echo "<option value='s/c'>No hay categorías disponibles</option>";
									}
								?>
							</select>
						</div>
						<div class="input-group">
							<label>Foto</label>
							<input type="file" name="foto" class="input-text">
						</div>
					</div>
					<div>
						<h3>Detalles subasta</h3>
						<div class="input-group">
							<label class="input-label">Precio mínimo</label>
							<input type="number" name="minimo" class="input-text" placeholder="$1.00" required>
						</div>
						<div class="input-group">
							<label class="input-label">Precio máximo</label>
							<input type="number" name="maximo" class="input-text" placeholder="$1000.00" required>
						</div>
						<div class="input-group">
							<label class="input-label">Fecha de cierre</label>
							<input type="date" name="fecha_fin" class="input-text" required>
						</div>
						<div class="input-group">
							<label>Hora de cierre</label>
							<input type="time" name="hora_fin" class="input-text" required>
						</div>
					</div>
                    <div class="input-group span-2">
                        <input type="submit" class="input-button input-button-primary" name="agregar" value="Subastar">
                    </div>
                </form>
            </div>
        </section>
    </body>
</html>