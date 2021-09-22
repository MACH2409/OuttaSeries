<div class="sidebar">
    <div class="logo-details">
        <div class="logo_name">Outta Series</div>
        <i class="fas fa-bars" id="btn"></i>
    </div>
    <ul class="nav-list">
        <li>
            <a href="index.php">
                <i class="fas fa-tachometer-alt"></i>
                <span class="links_name"> Dashboard </span>
            </a>
            <span class="tooltip"> Dashboard </span>
        </li>
        <li>
            <a href="cesta.php">
                <i class="fa fa-fw fa-shopping-cart"></i>
                <span class="links_name"> Mi cesta </span>
            </a>
            <span class="tooltip"> Mi cesta </span>
        </li>
        

        <li> <a href="alta_subasta.php">
                <i class="fa fa-fw fa-plus"></i>
                <span class="links_name">Nueva subasta</span>
            </a>
            <span class="tooltip">Nueva subasta </span>
        </li>
        <li>
            <a href="cuenta.php">
                <i class="fa fa-fw fa-th-list"></i>
                <span class="links_name">Mi cuenta</span>
            </a>
            <span class="tooltip">Mi cuenta</span>
        </li>
		<li>
			<a href="perfil.php">
				<i class="fas fa-user-circle"></i>
				<span class="links_name">Mi perfil</span>
			</a>
			<span class="tooltip">Mi perfil</span>
		</li>
        <li> <a href="logout.php">
                <i class="fas fa-power-off"></i>
                <span class="links_name">Cerrar Sesión</span>
            </a>
            <span class="tooltip">Cerrar Sesión</span>
        </li>
    </ul>
</div>

<script>
    let sidebar = document.querySelector(".sidebar");
    let closeBtn = document.querySelector("#btn");
    let searchBtn = document.querySelector(".bx-search");

    closeBtn.addEventListener("click", ()=>{
        sidebar.classList.toggle("open");
        menuBtnChange();
    });

    function menuBtnChange() {
        if(sidebar.classList.contains("open")){
            closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");
        }else {
            closeBtn.classList.replace("bx-menu-alt-right","bx-menu");
        }
    }
</script>

<?php
    date_default_timezone_set('America/El_Salvador');
    //Inicia consulta de subastas
    $res_1 = $bd->select("SELECT * from subasta where estado = 0");
    if($res_1->num_rows > 0){
        while($row = $res_1->fetch_assoc()){
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
            $signo = $interval->format("%R");

            //echo "[$status - $id_subasta]";

            if($signo == "-"){
                //echo "$id_subasta";

                if($comprador != null){//Si si tiene un ofertante se pasa a su cesta y cambia el estado de la subasta
                    //echo "$comprador";
                    $res_2 = $bd->query("INSERT into cesta(id_usuario, id_subasta) values($comprador,$id_subasta);");
                    if($res_2 == false){
                        echo "<script>Swal.fire({
                            title: '¡Error!',
                            text: 'Estamos manejando errores',
                            icon: 'error',
                            confirmButtonText: 'Intentar de nuevo'
                        })</script>";
                    }else{
                        $res_2_1 = $bd->query("UPDATE subasta set estado=1 where id_subasta=$id_subasta;");
                        if($res_2_1 == false){
                            echo "<script>Swal.fire({
                            title: '¡Error!',
                            text: 'Estamos manejando errores',
                            icon: 'error',
                            confirmButtonText: 'Intentar de nuevo'
                        })</script>";
                        }
                    }
                }else{//Si no tiene ofertante solo se cambia su estado y en comprador se queda con null
                    $res_3 = $bd->query("UPDATE subasta set estado=1 where id_subasta=$id_subasta;");
                    if($res_3 == false){
                        echo "<script>Swal.fire({
                            title: '¡Error!',
                            text: 'Estamos manejando errores',
                            icon: 'error',
                            confirmButtonText: 'Intentar de nuevo'
                        })</script>";
                    }
                }

            }
        }
    }
?>