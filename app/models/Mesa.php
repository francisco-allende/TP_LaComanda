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
}
?>