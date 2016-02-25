<?php
abstract class configuration {
 
    protected $datahost;
    protected $server;
    protected $user;
    protected $password;
    protected $database;
    
    protected function conectar(){
 		$this->server = "localhost";
 		$this->user = "root";
 		$this->password = "52190Lkwdic";
 		$this->database = "franc451_cr2013";
 
        try{
        	$pdostring = 'mysql:host='.$this->server.';dbname='.$this->database.';charset=utf8';
            $this->datahost = new PDO($pdostring, $this->user, $this->password);
            $this->datahost->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->datahost->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			return $this->datahost;
            
		}
        catch(PDOException $e){
                echo "Error en la conexión: ".$e->getMessage();
		}
    }
}
?>