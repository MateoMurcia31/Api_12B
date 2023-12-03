<?php
require_once("config.php");
class Connection{
    public $hola= "Hellowda";
    static public function connecction(){
        $con = false;
        try {
            $data = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
            $con = new PDO($data, DB_USERNAME, DB_PASSWORD);
            return $con;
        } catch (PDOException $e) {
            $mensaje = array(
                "COD" => "000",
                "MENSAJE" => ("ERROR EN BD".$e)
            );
        }
        return $con;
    }
}
?>