<?php

require_once './db/AccesoDatos.php';

class Mesa {
    public $id;
    public $id_mozo;
    public $estado; //libre / ocupada / cerrada

    public function __construct() {}

    public static function instanciarMesa($id_mozo, $estado) {
        $mesa = new Mesa();
        $mesa->setIdMozo($id_mozo);
        $mesa->setEstado($estado);

        return $mesa;
    }

    //--- Getters ---//

    public function getId(){
        return $this->id;
    }

    public function getIdMozo(){
        return $this->id_mozo;
    }

    public function getEstado(){
        return $this->estado;
    }

    //--- Setters ---//

    public function setId($id){
        $this->id = $id;
    }

    public function setIdMozo($id_mozo){
        $this->id_mozo = $id_mozo;
    }

    public function setEstado($estado){
        $this->estado = $estado;
    }        

    //--- Database Methods ---///

    public function CrearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("
        INSERT INTO mesas (id_mozo, estado) VALUES (:id_mozo, :estado)");
        
        $consulta->bindValue(':id_mozo', $this->id_mozo, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function ObtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, id_mozo, estado FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }
}
?>