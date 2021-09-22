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

        <!-- Iconos -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

        <!-- CSS -->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/fuentes.css">
        <link rel="stylesheet" href="css/sidebar.css">
        <link rel="stylesheet" href="css/index.css">

        <!-- Icono -->
        <link rel="icon" type="image/png" sizes="16x16" href="img/favicon.png">

        <!-- Alertas -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- GOOGLE FONTS-->
        <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500&display=swap" rel="stylesheet">

        <title>Inicio</title>
    </head>
    <body>

        <!-- Sidebar -->
        <?php
        include ("sidebar.php");
        ?>

        <!-- PHP -->
        <?php
            //Se hacen los count que se mostraran en la pantalla principal
            //Count para las subastas disponibles
            $user_id = $_SESSION["id_usuario"];
            $res_count=$bd->select("SELECT count(*) as total from subasta where estado=0 and subastador<>$user_id");
            $data=mysqli_fetch_array($res_count);
            $count_sub = $data['total'];//En esta variable se guardan el total

            //Count para productos en mi cesta
            $res_count=$bd->select("SELECT count(*) as total from cesta where id_usuario=".$_SESSION["id_usuario"]);
            $data=mysqli_fetch_array($res_count);
            $count_cesta = $data['total'];//En esta variable se guardan el total

            //Count para las subastas propias activas
            $res_count=$bd->select("SELECT count(*) as total from subasta where estado=0 and subastador=".$_SESSION["id_usuario"]);
            $data=mysqli_fetch_array($res_count);
            $count_sub_act = $data['total'];//En esta variable se guardan el total

            //Count para las subastas propias cerradas
            $res_count=$bd->select("SELECT count(*) as total from subasta where estado=1 and subastador=".$_SESSION["id_usuario"]);
            $data=mysqli_fetch_array($res_count);
            $count_sub_cerr = $data['total'];//En esta variable se guardan el total
        ?>

        <!-- Body -->
        <section class="home-section">
            <div class="text">Dashboard</div>
            <p class="text-companion">Supervisar mis subastas</p>

            <div class="cards">
                <div class="card card1">
                    <div class="container">
                        <img src="img/subastas.jpg" alt="Subastas disponibles">
                    </div>
                    <div class="details">
                        <h4><?php echo $count_sub;?></h4>
                        <h3>Subastas disponibles</h3>
                    </div>
                    <div class="link-side">
                        <a href="subastas.php">
                            <span class="pull-left">Ver detalles</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        </a>
                    </div>
                </div>
                <div class="card card3">
                    <div class="container">
                        <img src="img/shopping.jpg" alt="Mi cesta">
                    </div>
                    <div class="details">
                        <h4><?php echo $count_cesta;?></h4>
                        <h3>Mi cesta</h3>
                    </div>
                    <div class="link-side">
                        <a href="cesta.php">
                            <span class="pull-left">Ver detalles</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        </a>
                    </div>
                </div>
                <div class="card card2">
                    <div class="container">
                        <img src="img/unlocked.png" alt="Subastas activas">
                    </div>
                    <div class="details">
                        <h4><?php echo $count_sub_act;?></h4>
                        <h3>Subastas activas</h3>
                    </div>
                    <div class="link-side">
                        <a href="cuenta.php">
                            <span class="pull-left">Ver detalles</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        </a>
                    </div>
                </div>
                <div class="card card4">
                    <div class="container">
                        <img src="img/locked.png" alt="Subastas cerradas">
                    </div>
                    <div class="details">
                        <h4><?php echo $count_sub_cerr;?></h4>
                        <h3>Subastas cerradas</h3>
                    </div>
                    <div class="link-side">
                        <a href="cuenta.php">
                            <span class="pull-left">Ver detalles</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>


