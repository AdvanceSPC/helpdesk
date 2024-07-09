<?php
    class User extends Conn {
        public function login(){
            $conectar = parent::conexion();
            parent::set_names();
            if(isset($_POST["enviar"])){
                $email = $_POST["us_correo"];
                $pass = $_POST["us_password"];
                if(empty($email) and empty($pass)){
                    header("Location:".Conn::ruta()."index.php?m=2");
                    exit();
                }else{
                    $sql = "SELECT * FROM tm_users WHERE us_correo=? AND us_password=? AND us_status=1";
                    $stmt = $conectar->prepare($sql);
                    $stmt->bindValue(1, $email);
                    $stmt->bindValue(2, $pass);
                    $stmt->execute();
                    $resultado = $stmt->fetch();
                    if(is_array($resultado) and count($resultado)>0){
                        $_SESSION["us_Id"]=$resultado["us_Id"];
                        $_SESSION["us_name"]=$resultado["us_name"];
                        $_SESSION["us_ape"]=$resultado["us_ape"];
                        header("Location: ".Conn::ruta()."view/home/");
                        exit();
                    }else{
                       header("Location: ".Conn::ruta()."index.php?m=1");
                       exit(); 
                    }
                }
            }
        }
    }
?>