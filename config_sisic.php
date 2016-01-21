<?php
require("configuration.php");

class bd extends configuration{
	private $conexion;
	
	public function __construct(){
		$this->conexion = parent::conectar();
		return $this->conexion;
	}
	
	function query($sql, $arr){
		//echo "Haciendo consulta ".$sql."<br />";
		try {
			if($statement = $this->conexion->prepare($sql)){
				if(count($arr) > 0){
					if (!$statement->execute($arr)) { //si no se ejecuta la consulta...
			            print_r($statement->errorInfo()); //imprimir errores
			        }	
				}else{
					if (!$statement->execute()) { //si no se ejecuta la consulta...
		                print_r($statement->errorInfo()); //imprimir errores
		            }
				}
	            
	            $resultado = $statement->fetchAll(PDO::FETCH_ASSOC);
	            return $resultado;
			}
        } catch(PDOException $ex) {
            echo "An Error occured!"; //handle me.
        }
	}		
}
?>