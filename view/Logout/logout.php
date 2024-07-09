<?php
    require_once("../../config/connection.php");
    session_destroy();
    header("Location:".Conn::ruta()."index.php");
    exit();
?>