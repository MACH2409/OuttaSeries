<?php
	date_default_timezone_set('America/El_Salvador');
	//Se incluye el archivo Conexion.php que contiene la clase usada para la conexion a la bd
	include("conexion/Conexion.php");
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
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">

		<!-- Custom Fonts -->
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

		<!-- CSS -->
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/fuentes.css">
		<link rel="stylesheet" href="css/sidebar.css">
		<link rel="stylesheet" href="css/cuenta.css">

		<!-- Favicon -->
		<link rel="icon" type="image/png" sizes="16x16" href="img/favicon.png">

		<!-- Sweet Alert 2 -->
		<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

		<title>Inicio</title>
	</head>
	<body>

		<!-- Sidebar -->
		<?php
			include("sidebar.php");
		?>

		<!-- Body -->
		<section class="home-section">
			<div class="text">Mi cuenta</div>
			<p class="text-companion">Productos subastados</p><br>

			<div class="grid-2">
				<div>
					<div class="text">Subastas activas</div>
					<!-- Tabla -->
					<div class="grid">

						<!-- Columnas -->
						<span><strong>Imagen</strong></span>
						<span><strong>Nombre</strong></span>
						<span><strong>Categoría</strong></span>
						<span><strong>Pagado</strong></span>


						<!-- Filas PHP -->
						<?php
							//Inicia consulta de subastas
							$iduser = $_SESSION['id_usuario'];
							//$iduser = $_SESSION['id_usuario'] ?? 'nothing' ;
							$res = $bd->select("SELECT * from subasta where subastador=$iduser and estado=0 order by id_subasta desc");
							if($res->num_rows > 0){
								while($row = $res->fetch_assoc()){
									$sub = $row["id_subasta"];
									$min = $row["min"];
									$max = $row["max"];
									$ini = $row["tiempo_ini"];
									$fin = $row["tiempo_fin"];
									$id_producto = $row["id_producto"];

									//Inicia consulta de producto de las subastas
									$res2 = $bd->select("SELECT * from producto where id_producto=$id_producto");
									if($res2->num_rows > 0){
										while($row2 = $res2->fetch_assoc()){
											$nombre_p = $row2["nombre"];
											$imagen_p = $row2["imagen"];
											$catego_p = $row2["id_categoria"];

											//Inicia consulta de categoria del producto
											$result = $bd->select("SELECT * from categoria where id_categoria=$catego_p");
											$categoria_arr = mysqli_fetch_array($result);
											$categoria = $categoria_arr["categoria"];

											//Inicia consulta de categoria del producto
											$result1 = $bd->select("SELECT * from oferta where id_subasta=$sub order by id_oferta desc limit 1");
											$oferta = mysqli_fetch_array($result1);
											//$of_final = $oferta["oferta"] ?? '0.00';
											//$of_final = $oferta["oferta"];
											$of_final = isset($oferta["oferta"]) ? $oferta["oferta"] : '0.00';
											?>

											<span><center><a href="<?php echo "subasta.php?id=$sub"; ?>"><img src="<?php echo "images/productos/$imagen_p";?>" style="height: 80px;"></a></center></span>
											<span><?php echo "<b class='text-success'>$nombre_p</b>";?></span>
											<span><?php echo $categoria;?></span>
											<span><?php echo "<b class='text-danger'>$"; if($of_final) echo"$of_final.00</b>";
												else echo "0.00"; ?></>

											<?php
										}
									}
								}
							}else{
								echo "<span><h3>No hay subastas activas</h3></span>";
								echo "<span><h3>No hay subastas activas</h3></span>";
								echo "<span><h3>No hay subastas activas</h3></span>";
								echo "<span><h3>No hay subastas activas</h3></span>";
							}
							//Termina consulta de subastas
						?>
					</div>
				</div>
				<div>
					<div class="text">Subastas cerradas</div>
					<!-- Tabla -->
					<div class="grid">
						<!-- Columnas -->
						<span><strong>Imagen</strong></span>
						<span><strong>Nombre</strong></span>
						<span><strong>Categoría</strong></span>
						<span><strong>Pagado</strong></span>

						<!-- Filas PHP -->
						<?php
							//Inicia consulta de subastas
							$iduser = $_SESSION['id_usuario'];
							//$iduser = $_SESSION['id_usuario'] ?? 'nothing' ;
							$res = $bd->select("SELECT * from subasta where subastador=$iduser and estado=1 order by id_subasta desc");
							if($res->num_rows > 0){
								while($row = $res->fetch_assoc()){
									$sub = $row["id_subasta"];
									$min = $row["min"];
									$max = $row["max"];
									$ini = $row["tiempo_ini"];
									$fin = $row["tiempo_fin"];
									$id_producto = $row["id_producto"];

									//Inicia consulta de producto de las subastas
									$res2 = $bd->select("SELECT * from producto where id_producto=$id_producto");
									if($res2->num_rows > 0){
										while($row2 = $res2->fetch_assoc()){
											$nombre_p = $row2["nombre"];
											$imagen_p = $row2["imagen"];
											$catego_p = $row2["id_categoria"];

											//Inicia consulta de categoria del producto
											$result = $bd->select("SELECT * from categoria where id_categoria=$catego_p");
											$categoria_arr = mysqli_fetch_array($result);
											$categoria = $categoria_arr["categoria"];

											//Inicia consulta de categoria del producto
											$result1 = $bd->select("SELECT * from oferta where id_subasta=$sub order by id_oferta desc limit 1");
											$oferta = mysqli_fetch_array($result1);
											//$of_final = $oferta["oferta"] ?? '0.00';
											//$of_final = $oferta["oferta"];
											$of_final = isset($oferta["oferta"]) ? $oferta["oferta"] : '0.00';
											?>
											<span><center><a href="<?php echo "subasta.php?id=$sub"; ?>"><img src="<?php echo"images/productos/$imagen_p";?>" style="height: 80px;"></a></center></span>
											<span><?php echo "<b class='text-success'>$nombre_p</b>";?></span>
											<span><?php echo $categoria;?></span>
											<span><?php echo "<b class='text-danger'>$"; if($of_final) echo"$of_final.00</b>";else echo "0.00"; ?></span>
											<?php
										}
									}
								}
							}else{
								echo "<span><h3>No hay subastas cerradas</h3></span>";
								echo "<span><h3>No hay subastas cerradas</h3></span>";
								echo "<span><h3>No hay subastas cerradas</h3></span>";
								echo "<span><h3>No hay subastas cerradas</h3></span>";
							}
							//Termina consulta de subastas
						?>
					</div>
				</div>
			</div>
		</section>
	</body>
</html>


