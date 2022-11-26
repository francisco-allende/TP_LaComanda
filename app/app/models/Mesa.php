<?php

require_once './db/AccesoDatos.php';

class Mesa {
    public $id;
    public $estado; //libre / ocupada / cerrada

    public function __construct() {}

    public static function instanciarMesa($estado) {
        $mesa = new Mesa();
        $mesa->setEstado($estado);

        return $mesa;
    }

    //--- Getters ---//

    public function getId(){
        return $this->id;
    }

    public function getEstado(){
        return $this->estado;
    }

    //--- Setters ---//

    public function setId($id){
        $this->id = $id;
    }

    public function setEstado($estado){
        $this->estado = $estado;
    }        

    //--- Database Methods ---///

    public function CrearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("
        INSERT INTO mesas (estado) VALUES (:estado)");
        
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function ObtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }
}
?>