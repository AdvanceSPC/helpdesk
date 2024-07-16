<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
        
    class Conn {
        protected $dbh;
        public function Conexion(){
            try{
                $conectar = $this->dbh = new PDO("mysql:local=localhost;dbname=userticket","root","");
                return $conectar;
            }catch(Exception $e){
                print "Error en conexión!!: " . $e->getMessage(). "<br/>";
                die();
            }
        }
        
        public function set_names(){
            return $this->dbh->query("SET NAMES 'utf8'");
        }

        public static function ruta(){
            return "http://ec2-34-228-83-52.compute-1.amazonaws.com/";
        }
    }

    $conn = new Conn();
    $conexion = $conn->Conexion();
    $conn->set_names();
?>