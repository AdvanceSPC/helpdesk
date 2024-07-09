<?php

class Ticket extends Conn {
    public function listar($us_Id){
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT
                tickets.ticket_id AS Ticket,
                tickets.categoria AS Categoria,
                tickets.subject AS Titulo,
                tickets.campaign_name AS Cliente,
                tickets.user_id,
                tm_users.us_name AS Nombre,
                tm_users.us_ape AS Apellido
                FROM tickets
                INNER JOIN tm_users ON tickets.user_id = tm_users.us_Id
                WHERE
                tickets.user_id=1";

        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $us_Id);
        $sql->execute();
        return $resultado=$sql->fetchAll();
    }
}

?>