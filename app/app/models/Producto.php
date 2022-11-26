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

    public static function instanciarProducto($area, $id_pedido, $status, $descripcion, $precio, $tiempo_inicio, $tiempo_fin = null, $tiempo_para_finalizar = null){
        $producto = new Producto();
        $producto->setArea($area);
        $producto->setIdPedidoSegunProducto($id_pedido);
        $producto->setStatus($status);
        $producto->setDescripcion($descripcion);
        $producto->setPrecio($precio);
        $producto->setTiempoInicio($tiempo_inicio);
        $producto->setTiempoFin($tiempo_fin);
        $producto->setTiempoParaFinalizar($tiempo_para_finalizar);
        
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
        $this->descripcion = $descripcion;
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

    //--- Database Methods ---///

    public function CrearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("
        INSERT INTO productos (area, id_pedido, status, descripcion, precio, tiempo_inicio, tiempo_fin, tiempo_para_finalizar) 
        VALUES (:area, :id_pedido, :status, :descripcion, :precio, :tiempo_inicio, :tiempo_fin, :tiempo_para_finalizar)");
        
        $consulta->bindValue(':area', $this->area, PDO::PARAM_STR);
        $consulta->bindValue(':id_pedido', $this->id_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':status', $this->status, PDO::PARAM_STR);
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':tiempo_inicio', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->bindValue(':tiempo_fin', $this->tiempo_fin);
       // $this->calcularTiempoRestante();
        $consulta->bindValue(':tiempo_para_finalizar', null);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function ObtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }
}
?>