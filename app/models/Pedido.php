<?php

require_once './db/AccesoDatos.php';


class Pedido{

    public $id;
    public $id_mesa;
    public $status;
    public $nombre_cliente;
    public $foto;
    public $total_cuenta;

    public function __construct(){}

    public static function instanciarPedido($id_mesa, $status, $nombre_cliente, $foto, $total_cuenta = 0){
        $pedido = new Pedido();
        $pedido->setIdMesa($id_mesa);
        $pedido->setStatus($status);
        $pedido->setNombreCliente($nombre_cliente);
        $pedido->setFoto($foto);
        $pedido->setTotalCuenta($total_cuenta);

        return $pedido;
    }

    //--- Getters ---//

    public function getId(){
        return $this->id;
    }

    public function getIdMesa(){
        return $this->id_mesa;
    }

    public function getStatus(){
        return $this->status;
    }

    public function getNombreCliente(){
        return $this->nombre_cliente;
    }

    public function getFoto(){
        return $this->foto;
    }

    public function getTotalCuenta(){
        return $this->total_cuenta;
    }
    
    //--- Setters ---//

    public function setId($id){
        $this->id = $id;
    }

    public function setIdMesa($id_mesa){
        $this->id_mesa = $id_mesa;
    }

    public function setStatus($status){
        $this->status = $status;
    }

    public function setNombreCliente($nombre_cliente){
        $this->nombre_cliente = $nombre_cliente;
    }

    public function setFoto($foto){
        $this->foto = $foto;
    }

    public function setTotalCuenta($total_cuenta){
        $this->total_cuenta = $total_cuenta;
    }
}
?>