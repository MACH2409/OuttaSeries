<?php
	date_default_timezone_set('America/El_Salvador');
	//Se incluye el archivo Conexion.php que contiene la clase usada para la conexion a la bd
	include ("conexion/Conexion.php");
	//Se crea el objeto conexion
	$bd = new Conexion();
	//Se inicia la sesion o se propaga
	session_start();
	//Condicion que no deja entrar al index a menos que exista una variable de session
	if(!isset($_SESSION["id_usuario"])){
		//Redirecciona al login
		header("Location: login.php");
	}
	//Se verifica si existe una variable get id si no redirecciona
	if(!$_GET["id"]){
		header("Location: subastas.php");
	}

	//Si no redirecciona guardamos la variable get en una variable
	$id_sub = $_GET["id"];
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
		<link rel="stylesheet" href="css/subasta.css">

		<!-- Favicon -->
		<link rel="icon" type="image/png" sizes="16x16" href="img/favicon.png">

		<!-- Sweet Alert 2 -->
		<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

		<title>Inicio</title>
	</head>
	<body>
		<script>
			//Petición de JQuery a Vanilla Javascript
			function tiempo(){
				const http = new XMLHttpRequest();
				http.open("POST", "ajax/tiempo_regresivo.php", true);
				http.setRequestHeader("Content-type","application/x-www-form-urlencoded;");
				http.onreadystatechange = function() { document.getElementById("tiempo") ? document.getElementById("tiempo").innerHTML = http.responseText: null };
				http.send("tiempo_limite=" + encodeURI(document.getElementById("limite") ? document.getElementById("limite").value : null));
			}

			// Petición en JQuery
			/*
			 function tiempo(){
				$.post("ajax/tiempo_regresivo.php",{tiempo_limite:$("#limite").val()}, function(data){
					$("#tiempo").html(data);
				});
			}
			*/

			//Se le define el tiempo de ejecucion - al segundo
			conteoRegresivo = setInterval("tiempo()",1000);
		</script>
		<!-- PHP -->
		<?php
			if (isset($_POST["ofertar"])) {
				//Si el usuario quiere ofertar por un producto
				$oferta = $_POST["oferta"];
				$id_user_1 = $_POST["id_user"];
				$id_sub_1 = $_POST["id_sub"];
				$max = $_POST["max"];
				$fecha_hora_actual = date("Y-m-d H:i:s");

				if($oferta == $max){
					$res_1 = $bd->query("INSERT into oferta(oferta, estado, fecha, id_subasta, comprador) values($oferta, 1, '$fecha_hora_actual',$id_sub_1, $id_user_1);");
					if($res_1 == false){
						echo "<script>Swal.fire({
                        title: '¡Error!',
                        text: 'No se ha podido ofertar',
                        icon: 'error',
                        confirmButtonText: 'Intentar de nuevo'
                    })</script>";
					}else{
						$res_2 = $bd->query("INSERT into cesta(id_usuario, id_subasta) values($id_user_1,$id_sub_1);");
						if($res_2 == false){
							echo "<script>Swal.fire({
                        title: '¡Error!',
                        text: 'No se pudo agregar el producto a la cesta',
                        icon: 'error',
                        confirmButtonText: 'Intentar de nuevo'
                    })</script>";
						}else{
							$res_2_1 = $bd->query("UPDATE subasta set estado=1, comprador=$id_user_1 where id_subasta=$id_sub_1;");
							if($res_2_1 == false){
								echo "<script>Swal.fire({
                        title: '¡Error!',
                        text: 'No se pudo actualizar la subasta',
                        icon: 'error',
                        confirmButtonText: 'Intentar de nuevo'
                    })</script>";
							}else{
								echo "<script>Swal.fire({
                        title: '¡VENDIDO!',
                        text: '¡Este producto ahora es tuyo!',
                        icon: 'success'
                    })</script>";
							}
						}
					}
				}else{
					$res_1 = $bd->query("INSERT into oferta(oferta, estado, fecha, id_subasta, comprador) values($oferta, 0, '$fecha_hora_actual',$id_sub_1, $id_user_1);");
					if($res_1 == false){
						echo "<script>Swal.fire({
                        title: '¡Error!',
                        text: 'No se pudo realizar la oferta',
                        icon: 'error',
                        confirmButtonText: 'Intentar de nuevo'
                    })</script>";
					}else{
						$res_2_1 = $bd->query("UPDATE subasta set comprador=$id_user_1 where id_subasta=$id_sub_1;");
						if($res_2_1 == false){
							echo "<script>Swal.fire({
                        title: '¡Error!',
                        text: 'No se pudo actualizar la subasta',
                        icon: 'error',
                        confirmButtonText: 'Intentar de nuevo'
                    })</script>";
						}else{
							echo "<script>Swal.fire({
                        title: '¡Proceso exitoso!',
                        text: 'Oferta realizada con éxito',
                        icon: 'success'
                    })</script>";
						}
					}
				}
			}elseif(isset($_POST["comprar"])){
				//Si el usuario quiere comprar el producto pagando el monto maximo de la subasta
				$oferta = $_POST["max"];
				$id_user_1 = $_POST["id_user"];
				$id_sub_1 = $_POST["id_sub"];
				$max = $_POST["max"];
				$fecha_hora_actual = date("Y-m-d h:i:s");

				$res_1 = $bd->query("INSERT into oferta(oferta, estado, fecha, id_subasta, comprador) values($oferta, 1, '$fecha_hora_actual',$id_sub_1, $id_user_1);");
				if($res_1 == false){
					echo "<script>Swal.fire({
                        title: '¡Error!',
                        text: 'No se pudo realizar la oferta',
                        icon: 'error',
                        confirmButtonText: 'Intentar de nuevo'
                    })</script>";
				}else{
					$res_2 = $bd->query("INSERT into cesta(id_usuario, id_subasta) values($id_user_1,$id_sub_1);");
					if($res_2 == false){
						echo "<script>Swal.fire({
                        title: '¡Error!',
                        text: 'No se pudo agregar producto a la cesta',
                        icon: 'error',
                        confirmButtonText: 'Intentar de nuevo'
                    })</script>";
					}else{
						$res_2_1 = $bd->query("UPDATE subasta set estado=1, comprador=$id_user_1 where id_subasta=$id_sub_1;");
						if($res_2_1 == false){
							echo "<script>Swal.fire({
                        title: '¡Error!',
                        text: 'No se pudo actualizar la subasta',
                        icon: 'error',
                        confirmButtonText: 'Intentar de nuevo'
                    })</script>";
						}else{
							echo "<script>Swal.fire({
                        title: '¡VENDIDO!',
                        text: '¡Este producto ahora es tuyo!',
                        icon: 'success'
                    })</script>";
						}
					}
				}
			}
		?>

		<!-- Sidebar -->
		<?php
			include("sidebar.php");
		?>

		<!-- Body -->
		<section class="home-section">
			<div class="text">Mi cuenta</div>
			<p class="text-companion">Productos subastados</p><br>

			<div class="grid-2">

				<?php
					//Inicia consulta de subastas
					$res = $bd->select("SELECT * from subasta where id_subasta=$id_sub");
					if($res->num_rows > 0){
						while($row = $res->fetch_assoc()){
							$min = $row["min"];
							$max = $row["max"];
							$ini = $row["tiempo_ini"];
							$fin = $row["tiempo_fin"];
							$estado = $row["estado"];
							$comprador = $row["comprador"];
							$subastador = $row["subastador"];
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
									$descripcion_p = $row2["descripcion"];
									$id_categoria = $row2["id_categoria"];

									//Inicia consulta de categoria del producto
									$result = $bd->select("SELECT * from categoria where id_categoria=$id_categoria");
									$categoria_arr = mysqli_fetch_array($result);
									$categoria = $categoria_arr["categoria"];

									//echo "$id_subasta, $min, $max, $ini, $fin, $comprador, $id_producto, $nombre_p, $imagen_p<br>";

									$res_count=$bd->select("SELECT count(*) as total from oferta where id_subasta=$id_sub");
									$data=mysqli_fetch_array($res_count);
									$count_ofert = $data['total'];

									$res3 = $bd->select("SELECT * from oferta where id_subasta=$id_sub order by id_oferta desc limit 1");
									if($res3->num_rows > 0){
										while($row3 = $res3->fetch_assoc()){
											$id_oferta = $row3["id_oferta"];
											$oferta = $row3["oferta"];
											$ofertante_comp = $row3["comprador"];

											//echo "$id_subasta, $min, $max, $ini, $fin, $comprador, $id_producto, $nombre_p, $imagen_p, $id_oferta, $oferta<br>";

											/*Aqui se mostraran los productos que tienen una oferta ya*/
											?>
											<div>
												<?php
													//Aqui se mostrara la imagen del producto en grande
													echo "<img src='images/productos/$imagen_p' style='height: 450px; width: 80% ;'>";
												?>
											</div>
											<div>
												<div>
													<div>
														<?php
															if($estado == 1 && $ofertante_comp != null){
																echo "<h1 class='text-danger'>VENDIDO | SOLD</h1>";
																echo "<script>
                                                    clearTimeout(conteoRegresivo);
                                                    document.getElementById('limite').className = 'cuenta-regresiva';
                                                    </script>";
															}
														?>
														<h2><?php echo $nombre_p; ?></h2>
														<h4><?php echo $descripcion_p; ?></h4>
														<p><i class="fa fa-tag"></i> <?php echo $categoria; ?></p>
														<hr style="margin: 1px 1px 1px 1px;">

														<p>Producto publicado el <?php echo "<b>$ini</b>"; ?></p>
														<p><?php //print $interval->format('%R %a días %H horas %I minutos'); ?></p>

														<p id="tiempo"></p>
														<input type="hidden" id="limite" class="" value="<?php echo $fin; ?>">

														<p><?php echo "<b>Ofertantes:</b> $count_ofert";?></p>
														<p><?php echo "<b>Oferta minima:</b> $$min.00"; ?></p>
														<p><?php echo "<b>Oferta maxima:</b> $$max.00"; ?></p>
														<p><?php echo "<b>Oferta actual:</b> $$oferta.00"; ?></p>


														<form class="secondary-form" action="" method="post">

															<input type="hidden" name="id_user" value="<?php echo $_SESSION['id_usuario']; ?>">
															<input type="hidden" name="id_sub" value="<?php echo $id_sub; ?>">
															<input type="hidden" name="max" value="<?php echo $max; ?>">
															<input type="hidden" name="fin" value="<?php echo $fin; ?>">

															<?php
																if($estado == 1 || $_SESSION["id_usuario"] == $ofertante_comp || $_SESSION["id_usuario"] == $subastador){
																	?>

																	<div class="input-group">
																		<input type="number" disabled name="oferta" class="input-text" max="<?php echo $max;?>" min="<?php echo $min;?>" value="<?php echo $min;?>">
																	</div>
																	<div></div>
																	<div class="input-group">
																		<button type="submit" disabled class="input-button input-button-primary" name="ofertar">Mejorar oferta</button>
																	</div>
																	<div class="input-group">
																		<button type="submit" disabled class="input-button" name="comprar">Comprar ahora</button>
																	</div>
																	<?php
																}elseif($estado == 0){
																	?>
																	<div class="input-group">
																		<input type="number" name="oferta" class="input-text" max="<?php echo $max;?>" min="<?php echo $min;?>" value="<?php echo $min;?>">
																	</div>
																	<div></div>

																	<div class="input-group">
																		<button type="submit" class="input-button input-button-primary" name="ofertar">Mejorar oferta</button>
																	</div>
																	<div class="input-group">
																		<button type="submit" class="input-button" name="comprar">Comprar ahora</button>
																	</div>

																	<?php
																}
															?>
														</form>
													</div>
												</div>
											</div>
											<?php
											/*Fin de los productos que tienen una oferta ya*/
										}
									}else{
										/*Aqui se mostraran los productos que aun no tienen oferta*/
										?>
										<div>
											<?php
												//Aqui se mostrara la imagen del producto en grande
												echo "<img src='images/productos/$imagen_p' style='height: 450px; width: 80% ;'>";
											?>
										</div>
										<div>
											<div>
												<div>
													<h2><?php echo $nombre_p; ?></h2>
													<h4><?php echo $descripcion_p; ?></h4>
													<p><i class="fa fa-tag"></i> <?php echo $categoria; ?></p>
													<hr style="margin: 1px 1px 1px 1px;">
													<p>Producto publicado el <?php echo "<b>$ini</b>"; ?></p>
													<p><?php //print $interval->format('%R %a días %H horas %I minutos'); ?></p>

													<p id="tiempo"></p>
													<input type="hidden" id="limite" name="limite" value="<?php echo $fin; ?>">

													<p><?php echo "<b>Oferta minima:</b> $$min.00"; ?></p>
													<p><?php echo "<b>Oferta maxima:</b> $$max.00"; ?></p>
													<p><?php echo "<b>Oferta actual:</b> $0.00"; ?></p>

													<form class="secondary-form" action="" method="post">

														<input type="hidden" name="id_user" value="<?php echo $_SESSION['id_usuario']; ?>">
														<input type="hidden" name="id_sub" value="<?php echo $id_sub; ?>">
														<input type="hidden" name="max" value="<?php echo $max; ?>">
														<input type="hidden" name="fin" value="<?php echo $fin; ?>">

														<?php
															if($_SESSION["id_usuario"] == $subastador){
																?>
																<div class="input-group">
																	<input type="number" disabled name="oferta" class="input-text" max="<?php echo $max;?>" min="<?php echo $min;?>" value="<?php echo $min;?>">
																</div>
																<div></div>

																<div class="input-group">
																	<button type="submit" disabled class="input-button input-button-primary" name="ofertar">Mejorar oferta</button>
																</div>
																<div class="input-group">
																	<button type="submit" disabled class="input-button" name="comprar">Comprar ahora</button>
																</div>
																<?php
															}else{
																?>
																<div class="input-group">
																	<input type="number" name="oferta" class="input-text" max="<?php echo $max;?>" min="<?php echo $min;?>" value="<?php echo $min;?>">
																</div>
																<div></div>
																<div class="input-group">
																	<button type="submit" class="input-button input-button-primary" name="ofertar">Mejorar oferta</button>
																</div>
																<div class="input-group">
																	<button type="submit" class="input-button" name="comprar">Comprar ahora</button>
																</div>
																<?php
															}
														?>
													</form>
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


