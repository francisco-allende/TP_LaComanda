<?php

require_once './db/AccesoDatos.php';

 class Producto{

    public $id;
    public $area;
    public $id_pedido;
    public $status;
    public $descripcion;
    public $precio;
    public $tiempo_inicio;
    public $tiempo_fin;
    public $tiempo_para_finalizar;

    public function __construct(){}

    public static function instanciarProducto($area, $id_pedido, $status, $descripcion, $precio, $tiempo_inicio){
        $producto = new Producto();
        $producto->setArea($area);
        $producto->setIdPedidoSegunProducto($id_pedido);
        $producto->setStatus($status);
        $producto->setDescripcion($descripcion);
        $producto->setPrecio($precio);
        $producto->setTiempoInicio($tiempo_inicio);
        $producto->setTiempoFin(null);
        $producto->setTiempoParaFinalizar(null);
        
        return $producto;
    }


    //--- Getters ---//
    public function getId(){
        return $this->id;
    }

    public function getArea(){
        return $this->area;
    }

    public function getStatus(){
        return $this->status;
    }

    public function getIdPedidoSegunProducto(){
        return $this->id_pedido;
    }

    public function getDescripcion(){
        return $this->descripcion;
    }

    public function getPrecio(){
        return $this->precio;
    }

    public function getTiempoInicio(){
        return $this->tiempo_inicio;
    }

    public function getTiempoFin(){
        return $this->tiempo_fin;
    }

    public function getTiempoParaFinalizar(){
        return $this->tiempo_para_finalizar;
    }

    //--- Setters ---//

    public function setId($id){
        $this->id = $id;
    }

    public function setArea($area){
        $this->area = $area;
    }

    public function setIdPedidoSegunProducto($id_pedido){
        $this->id_pedido = $id_pedido;
    }

    public function setStatus($status){
        $this->status = $status;
    }

    public function setDescripcion($descripcion){
        $this->descripcion = $dish_descripcion;
    }

    public function setPrecio($precio){
        $this->precio = $precio;
    }

    public function setTiempoInicio($tiempo_inicio){
        $this->tiempo_inicio = $tiempo_inicio;
    }

    public function setTiempoFin($tiempo_fin){
        $this->tiempo_fin = $tiempo_fin;
    }

    public function setTiempoParaFinalizar($tiempo_para_finalizar){
        $this->tiempo_para_finalizar = $tiempo_para_finalizar;
    }

    public function calcularTiempoRestante(){
        $newDate = new DateTime($this->getTiempoInicio());
        $newDate = $newDate->modify('+'.$this->getTiempoParaFinalizar().' minutes');
        $this->setTiempoFin($newDate->format('Y-m-d H:i:s'));
    }
}
?>