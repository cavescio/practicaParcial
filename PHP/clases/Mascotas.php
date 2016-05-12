<?php
require_once"accesoDatos.php";
class Mascota
{
//--------------------------------------------------------------------------------//
//--ATRIBUTOS
	public $id;
	public $nombre;
 	public $edad;
  	public $fecha;
  	public $tipo;
  	public $sexo;
  	public $foto;

//--------------------------------------------------------------------------------//

//--------------------------------------------------------------------------------//
//--GETTERS Y SETTERS
  	public function GetId()
	{
		return $this->id;
	}
	public function GetApellido()
	{
		return $this->edad;
	}
	public function GetNombre()
	{
		return $this->nombre;
	}
	public function GetDni()
	{
		return $this->fecha;
	}
	public function GetFoto()
	{
		return $this->foto;
	}

	public function SetId($valor)
	{
		$this->id = $valor;
	}
	public function SetApellido($valor)
	{
		$this->edad = $valor;
	}
	public function SetNombre($valor)
	{
		$this->nombre = $valor;
	}
	public function SetDni($valor)
	{
		$this->fecha = $valor;
	}
	public function SetFoto($valor)
	{
		$this->foto = $valor;
	}
//--------------------------------------------------------------------------------//
//--CONSTRUCTOR
	public function __construct($nombre=NULL)
	{
		if($nombre != NULL){
			$obj = Mascota::TraerUnaMascota($nombre);
			
			$this->edad = $obj->edad;
			$this->nombre = $nombre;
			$this->fecha = $obj->fecha;
			$this->tipo = $obj->tipo;
			$this->sexo = $obj->sexo;
			$this->foto = $obj->foto;
		}
	}

//--------------------------------------------------------------------------------//
//--TOSTRING	
  	public function ToString()
	{
	  	return $this->nombre."-".$this->edad."-".$this->fecha."-".$this->tipo."-".$this->sexo."-".$this->foto;
	}
//--------------------------------------------------------------------------------//

//--------------------------------------------------------------------------------//
//--METODO DE CLASE
	public static function TraerUnaMascota($idParametro) 
	{	


		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from mascota where id =:id");
		//$consulta =$objetoAccesoDato->RetornarConsulta("CALL TraerUnaMascota(:id)");
		$consulta->bindValue(':id', $idParametro, PDO::PARAM_INT);
		$consulta->execute();
		$mascotaBuscada= $consulta->fetchObject('mascota');
		return $mascotaBuscada;	
					
	}
	
	public static function TraerTodasLasMascotas()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from mascota");
		//$consulta =$objetoAccesoDato->RetornarConsulta("CALL TraerTodasLasMascotas() ");
		$consulta->execute();			
		$arrMascotas= $consulta->fetchAll(PDO::FETCH_CLASS, "mascota");	
		return $arrMascotas;
	}
	
	public static function BorrarMascota($idParametro)
	{	
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("delete from mascota	WHERE id=:id");	
		//$consulta =$objetoAccesoDato->RetornarConsulta("CALL BorrarMascota(:id)");	
		$consulta->bindValue(':id',$idParametro, PDO::PARAM_INT);		
		$consulta->execute();
		return $consulta->rowCount();
		
	}
	
	public static function ModificarMascota($mascota)
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update mascota 
				set nombre=:nombre,
				edad=:edad,
				fecha=:fecha,
				tipo=:tipo,
				sexo=:sexo,
				foto=:foto
				WHERE id=:id");
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			//$consulta =$objetoAccesoDato->RetornarConsulta("CALL ModificarMascota(:id,:nombre,:edad,:foto)");
			$consulta->bindValue(':id',$mascota->id, PDO::PARAM_INT);
			$consulta->bindValue(':nombre',$mascota->nombre, PDO::PARAM_STR);
			$consulta->bindValue(':edad', $mascota->edad, PDO::PARAM_INT);
			$consulta->bindValue(':fecha', $mascota->fecha, PDO::PARAM_STR);
			$consulta->bindValue(':tipo', $mascota->tipo, PDO::PARAM_STR);
			$consulta->bindValue(':sexo', $mascota->sexo, PDO::PARAM_STR);
			$consulta->bindValue(':foto', $mascota->foto, PDO::PARAM_STR);
			return $consulta->execute();
	}

//--------------------------------------------------------------------------------//

//--------------------------------------------------------------------------------//

	public static function InsertarMascota($mascota)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into mascota (nombre,edad,fecha,tipo,sexo,foto)values(:nombre,:edad,:fecha,:tipo,:sexo,:foto)");
		//$consulta =$objetoAccesoDato->RetornarConsulta("CALL InsertarMascota (:nombre,:edad,:fecha,:foto)");
		$consulta->bindValue(':nombre',$mascota->nombre, PDO::PARAM_STR);
		$consulta->bindValue(':edad', $mascota->edad, PDO::PARAM_INT);
		$consulta->bindValue(':fecha', $mascota->fecha, PDO::PARAM_STR);
		$consulta->bindValue(':tipo', $mascota->tipo, PDO::PARAM_STR);
		$consulta->bindValue(':sexo', $mascota->sexo, PDO::PARAM_STR);
		$consulta->bindValue(':foto', $mascota->foto, PDO::PARAM_STR);
		$consulta->execute();		
		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	
				
	}	
//--------------------------------------------------------------------------------//



	public static function TraerMascotasTest()
	{
		$arrayDeMascotas=array();

		$mascota = new stdClass();
		$mascota->id = "4";
		$mascota->nombre = "rogelio";
		$mascota->edad = "agua";
		$mascota->fecha = "333333";
		$mascota->foto = "333333.jpg";

		//$objetJson = json_encode($mascota);
		//echo $objetJson;
		$mascota2 = new stdClass();
		$mascota2->id = "5";
		$mascota2->nombre = "Bañera";
		$mascota2->edad = "giratoria";
		$mascota2->fecha = "222222";
		$mascota2->foto = "222222.jpg";

		$mascota3 = new stdClass();
		$mascota3->id = "6";
		$mascota3->nombre = "Julieta";
		$mascota3->edad = "Roberto";
		$mascota3->fecha = "888888";
		$mascota3->foto = "888888.jpg";

		$arrayDeMascotas[]=$mascota;
		$arrayDeMascotas[]=$mascota2;
		$arrayDeMascotas[]=$mascota3;
		 
		

		return  $arrayDeMascotas;
				
	}	


}
