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
        <link rel="stylesheet" href="css/subastas.css">

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

        <!-- Body -->
		<section class="home-section">
			<div class="text">Subastas</div>
			<p class="text-companion">Todas las subastas disponibles</p>

			<!-- Listado de subastas -->
			<div class="cards">
				<?php
					date_default_timezone_set('America/El_Salvador');
					$user_id = $_SESSION["id_usuario"];
					//Inicia consulta de subastas
					$res = $bd->select("SELECT * from subasta where estado=0 and subastador<>$user_id order by id_subasta desc");
					if($res->num_rows > 0){
						while($row = $res->fetch_assoc()){
							$id_subasta = $row["id_subasta"];
							$min = $row["min"];
							$max = $row["max"];
							$ini = $row["tiempo_ini"];
							$fin = $row["tiempo_fin"];
							$comprador = $row["comprador"];
							$id_producto = $row["id_producto"];

							$datetime_actual = date("Y-m-d H:i:s");
							$datetime1 = date_create($datetime_actual);
							$datetime2 = date_create($fin);
							$interval = $datetime1->diff($datetime2);

							//Inicia consulta de producto de las subastas
							$res2 = $bd->select("SELECT * from producto where id_producto=$id_producto");
							if($res2->num_rows > 0){
								while($row2 = $res2->fetch_assoc()){
									$nombre_p = $row2["nombre"];
									$imagen_p = $row2["imagen"];

									$res3 = $bd->select("SELECT * from oferta where id_subasta=$id_subasta order by id_oferta desc limit 1");
									if($res3->num_rows > 0){
										while($row3 = $res3->fetch_assoc()){
											$id_oferta = $row3["id_oferta"];
											$oferta = $row3["oferta"];

											/*Aqui se mostraran los productos que tienen una oferta ya*/
											?>
											<div class="card">
												<?php echo "<img class='card__image' src='images/productos/$imagen_p' style='height: 220px;'>";?>
												<div class="card__content">
													<h3><?php echo $nombre_p; ?></h3>
													<p><?php print $interval->format('%a días %H horas %I minutos'); ?></p>
													<p><?php echo "$$min.00 - $$max.00"; ?></p>
													<p>Oferta actual: <b class="text-danger"><?php echo "$$oferta.00"; ?></b></p>
												</div>
												<div class="card__info">
													<div>
														<?php echo "<p><a class='card__link' href='subasta.php?id=$id_subasta' class='btn btn-info btn-block' role='button'>Mejorar oferta</a></p>";?>
													</div>
												</div>
											</div>

											<?php
											/*Fin de los productos que tienen una oferta ya*/
										}
									}else{
										/*Aqui se mostraran los productos que aun no tienen oferta*/
										?>
										<div class="card">
											<?php echo "<img class='card__image' src='images/productos/$imagen_p' style='height: 220px;'>";?>
											<div class="card__content">
												<h3><?php echo $nombre_p; ?></h3>
												<p><?php print $interval->format('%a días %H horas %I minutos'); ?></p>
												<p><?php echo "$$min.00 - $$max.00"; ?></p>
												<p>Oferta actual: <b class="text-danger"><?php echo "$0.00"; ?></b></p>
											</div>
											<div class="card__info">
												<div>
													<?php echo "<p ><a class='card__link' href='subasta.php?id=$id_subasta' class='btn btn-info btn-block' role='button'>Primero en ofertar</a></p>";?>
												</div>
											</div>
										</div>

										<?php
										/*Fin de los productos que no tienen oferta*/
									}
								}
							}else{
								echo "<h4>Hubo un error al recuperar el producto</h4>";
							}
							//Termina consulta de producto de la subasta
						}
					}else{
						echo "<h3>Por el momento no existen subastas</h3>";
					}
					//Termina consulta de subastas
				?>
			</div>
		</section>
    </body>
</html>


