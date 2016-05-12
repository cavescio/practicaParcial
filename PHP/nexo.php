<?php 

include "clases/Mascotas.php";
// $_GET['accion'];


if ( !empty( $_FILES ) ) {
    $tempPath = $_FILES[ 'file' ][ 'tmp_name' ];
    // $uploadPath = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $_FILES[ 'file' ][ 'name' ];
    $uploadPath = "../". DIRECTORY_SEPARATOR . 'fotos' . DIRECTORY_SEPARATOR . $_FILES[ 'file' ][ 'name' ];
    move_uploaded_file( $tempPath, $uploadPath );
    $answer = array( 'respuesta' => 'Archivo Cargado!' );
    $json = json_encode( $answer );
    echo $json;
}elseif(isset($_GET['accion']))
{
	$accion=$_GET['accion'];
	if($accion=="traer")
	{
		$respuesta= array();
		//$respuesta['listado']=Mascota::TraerMascotasTest();
		$respuesta['listado']=Mascota::TraerTodasLasMascotas();
		//var_dump(Mascota::TraerTodasLasMascotas());
		$arrayJson = json_encode($respuesta);
		echo  $arrayJson;
	}


	

}
else{

	$DatosPorPost = file_get_contents("php://input");
	$respuesta = json_decode($DatosPorPost);

	if(isset($respuesta->datos->accion)){

		switch($respuesta->datos->accion)
		{
			case "borrar":	
				if($respuesta->datos->mascota->foto!="pordefecto.png")
				{
					unlink("../fotos/".$respuesta->datos->mascota->foto);
				}
				Mascota::BorrarMascota($respuesta->datos->mascota->id);
			break;

			case "insertar":	
				if($respuesta->datos->mascota->foto!="pordefecto.png")
				{
					$rutaVieja="../fotos/".$respuesta->datos->mascota->foto;
					$rutaNueva=$respuesta->datos->mascota->nombre.".".PATHINFO($rutaVieja, PATHINFO_EXTENSION);
					copy($rutaVieja, "../fotos/".$rutaNueva);
					unlink($rutaVieja);
					$respuesta->datos->mascota->foto=$rutaNueva;
				}
				Mascota::InsertarMascota($respuesta->datos->mascota);
			break;

			case "buscar":
			
				echo json_encode(Mascota::TraerUnaMascota($respuesta->datos->id));
				break;
	
			case "modificar":
			
				if($respuesta->datos->mascota->foto!="pordefecto.png")
				{
					$rutaVieja="../fotos/".$respuesta->datos->mascota->foto;
					$rutaNueva=$respuesta->datos->mascota->id.".".PATHINFO($rutaVieja, PATHINFO_EXTENSION);
					copy($rutaVieja, "../fotos/".$rutaNueva);
					unlink($rutaVieja);
					$respuesta->datos->mascota->foto=$rutaNueva;
				}
				Mascota::ModificarMascota($respuesta->datos->mascota);
				break;
		}//switch($respuesta->datos->accion)
	}//if(isset($respuesta->datos->accion)){


}



 ?>