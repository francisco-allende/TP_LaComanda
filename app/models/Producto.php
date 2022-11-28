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

    public static function instanciarProducto($area, $id_pedido, $status, $descripcion, $precio, $tiempo_inicio = null, $tiempo_fin = null, $tiempo_para_finalizar = null){
        $producto = new Producto();
        $producto->setArea($area);
        $producto->setIdPedido($id_pedido);
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

    public function getIdPedido(){
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

    public function setIdPedido($id_pedido){
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

    public static function AsignarTiempoRestante($tiempo_fin)
    {
        $date = date_create("",new DateTimeZone('America/Argentina/Buenos_Aires'));
        
        $newDate = new DateTime(date_format($date,"Y/m/d H:i:s"));
        $newDate = $newDate->modify('+'.$tiempo_fin.'minutes');

        return $newDate;
    }

    public static function calcularTiempoRestante($tiempo_fin){
        $date_start = date_create("",new DateTimeZone('America/Argentina/Buenos_Aires'));
        $date_end = date_create($tiempo_fin,new DateTimeZone('America/Argentina/Buenos_Aires'));

        $newDate = new DateTime(date_format($date_end,"Y/m/d H:i:s"));
        $newDate = $newDate->modify('-'.$date_start->format("H").'hours');
        $newDate = $newDate->modify('-'.$date_start->format("i").'minutes');
        $newDate = $newDate->modify('-'.$date_start->format("s").'seconds');

        return $newDate->format('H:i:s');
    }

    //--- Metodos SQL  ---///

    //--- INSERT INTO ---///

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
        

        $date = date_create("",new DateTimeZone('America/Argentina/Buenos_Aires'));
        $newDate = new DateTime(date_format($date,"Y/m/d H:i:s"));
        $consulta->bindValue(':tiempo_inicio', $newDate->format("Y/m/d H:i:s"));

        $consulta->bindValue(':tiempo_fin', "No sabemos hasta que pase a en preparcion");
        $consulta->bindValue(':tiempo_para_finalizar', "No sabemos hasta que pase a en preparcion");
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

     //---      GETTERS      ---///

    public static function ObtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function ObtenerProducto($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos WHERE id = :id");
        $consulta->bindValue(':id', $id);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function ObtenerProductosPendientes()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos WHERE status = 'pendiente'");
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

     //---    UPDATE   ---///

    public static function ModificarStatusProducto($status, $id, $tiempo_fin = "")
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        if($status == "en preparacion")
        {
            $consulta = $objAccesoDato->prepararConsulta(
            "UPDATE productos SET status = :status, 
            tiempo_para_finalizar = :tiempo_para_finalizar,
            tiempo_fin = :tiempo_fin 
            WHERE id = :id");


            $tiempo_para_finalizar = Producto::AsignarTiempoRestante($tiempo_fin);
            $consulta->bindValue(':tiempo_para_finalizar', $tiempo_fin.=' minutes');
            $consulta->bindValue(':tiempo_fin', $tiempo_para_finalizar->format('H:i:s'));
        }else if($status == "listo")
        {
            $consulta = $objAccesoDato->prepararConsulta(
                "UPDATE productos SET status = :status, 
                tiempo_para_finalizar = '0 minutes'
                WHERE id = :id");
        }else{
            $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET status = :status WHERE id = :id");
        }
        $consulta->bindValue(':status', $status, PDO::PARAM_STR);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        if($consulta->rowCount() == 1){
            return true;
        }else{
            return false;
        }
    }
    
    //---      DELETE   ---///

    public static function BorrarProducto($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM productos WHERE id = :id;"); 
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        if($consulta->rowCount() == 1){
            return true;
        }else{
            return false;
        }
    }
}
?>