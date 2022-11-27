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

    //--- Database Methods ---///

    public function CrearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("
        INSERT INTO pedidos (id_mesa, status, nombre_cliente, foto, total_cuenta) 
        VALUES (:id_mesa, :status, :nombre_cliente, :foto, :total_cuenta)");
        
        $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':status', $this->status, PDO::PARAM_STR);
        $consulta->bindValue(':nombre_cliente', $this->nombre_cliente, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(':total_cuenta', $this->total_cuenta, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function ObtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    
    public static function ObtenerPedido($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE id = :id");
        $consulta->bindValue(':id', $id);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function ObtenerPedidoPorMesa($id_mesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT pedidos.id, pedidos.id_mesa, pedidos.status, pedidos.nombre_cliente 
            FROM pedidos
            WHERE pedidos.id_mesa = :id_mesa");
        $consulta->bindValue(':id_mesa', $id_mesa);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function ObtenerProductosDelPedido($id_pedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * 
            FROM productos 
            WHERE id_pedido = :id_pedido");
        $consulta->bindValue(':id_pedido', $id_pedido);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function BorrarPedido($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM pedidos WHERE id = :id;"); 
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