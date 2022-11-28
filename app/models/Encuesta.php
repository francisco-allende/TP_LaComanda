<?php

require_once './db/AccesoDatos.php';

class Encuesta {
    public $id;
    public $id_pedido;
    public $id_mesa;
    public $puntos_mesa;
    public $puntos_restoran;
    public $puntos_mozo;
    public $puntos_cocinero;
    public $promedio;
    public $comentarios;

    public function __construct() {}

    public static function instanciarEncuesta($id_pedido, $id_mesa, $puntos_mesa, $puntos_restoran, $puntos_mozo, $puntos_cocinero, $comentarios) {
        $encuesta = new Encuesta();
        $encuesta->setIdPedido($id_pedido);
        $encuesta->setIdMesa($id_mesa);
        $encuesta->setPuntosMesa($puntos_mesa);
        $encuesta->setPuntosRestoran($puntos_restoran);
        $encuesta->setPuntosMozo($puntos_mozo);
        $encuesta->setPuntosCocinero($puntos_cocinero);
        $encuesta->setPromedio(0);
        $encuesta->setComentarios($comentarios);

        return $encuesta;
    }

    //--- Getters ---//

    public function getId(){
        return $this->id;
    }

    public function getIdPedido(){
        return $this->id_pedido;
    }

    public function getIdMesa(){
        return $this->id_mesa;
    }

    public function getPuntosMesa(){
        return $this->puntos_mesa;
    }

    public function getPuntosRestoran(){
        return $this->puntos_restoran;
    }

    public function getPuntosMozo(){
        return $this->puntos_mozo;
    }

    public function getPuntosCocinero(){
        return $this->puntos_cocinero;
    }

    public function getPromedio(){
        return $this->promedio;
    }

    public function getComentarios(){
        return $this->comentarios;
    }

   
    //--- Setters ---//

    public function setId($id){
        $this->id = $id;
    }

    public function setIdPedido($id_pedido){
        $this->id_pedido = $id_pedido;
    }

    public function setIdMesa($id_mesa){
        $this->id_mesa = $id_mesa;
    }

    public function setPuntosMesa($puntos_mesa){
        $this->puntos_mesa = $puntos_mesa;
    }

    public function setPuntosRestoran($puntos_restoran){
        $this->puntos_restoran = $puntos_restoran;
    }

    public function setPuntosMozo($puntos_mozo){
        $this->puntos_mozo = $puntos_mozo;
    }

    public function setPuntosCocinero($puntos_cocinero){
        $this->puntos_cocinero = $puntos_cocinero;
    }

    public function setPromedio($promedio){
        $this->promedio = $promedio;
    }

    public function setComentarios($comentarios){
        $this->comentarios = $comentarios;
    }

    //--- Metodos SQL  ---///
    
    //--- INSERT INTO  ---///

    public function CrearEncuesta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("
        INSERT INTO encuestas (id_pedido, id_mesa, puntos_mesa, puntos_restoran, puntos_mozo, puntos_cocinero, promedio, comentarios) VALUES (:id_pedido, :id_mesa, :puntos_mesa, :puntos_restoran, :puntos_mozo, :puntos_cocinero, :promedio, :comentarios )");
        
        $consulta->bindValue(':id_pedido', $this->id_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':puntos_mesa', $this->puntos_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':puntos_restoran', $this->puntos_restoran, PDO::PARAM_INT);
        $consulta->bindValue(':puntos_mozo', $this->puntos_mozo, PDO::PARAM_INT);
        $consulta->bindValue(':puntos_cocinero', $this->puntos_cocinero, PDO::PARAM_INT);

        $sumaTotal = (int)$this->puntos_mesa + (int) $this->puntos_restoran + (int) $this->puntos_mozo + (int) $this->puntos_cocinero;
        $promedio = $sumaTotal / 4;

        $consulta->bindValue(':promedio', $promedio, PDO::PARAM_INT);
        $consulta->bindValue(':comentarios', $this->comentarios, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    //--- GETTERS  ---///

    public static function ObtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuestas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

    public static function ObtenerMejoresComentarios()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuestas WHERE promedio > 7");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

    public static function ObtenerPeoresComentarios()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuestas WHERE promedio < 5");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

    public static function ObtenerEncuesta($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuestas WHERE id = :id");
        $consulta->bindValue(':id', $id);
        $consulta->execute();

        return $consulta->fetchObject('Encuesta');
    }

    //---   UPDATE  ---///

    public static function ModificarEncuesta($puntos_mesa, $puntos_restoran, $puntos_mozo, $puntos_cocinero, $id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(
            "UPDATE encuestas 
            SET puntos_mesa = :puntos_mesa, puntos_restoran = :puntos_restoran, puntos_mozo = :puntos_mozo, puntos_cocinero = :puntos_cocinero, promedio = :promedio
            WHERE id = :id");
        $consulta->bindValue(':puntos_mesa', $puntos_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':puntos_restoran', $puntos_restoran, PDO::PARAM_INT);
        $consulta->bindValue(':puntos_mozo', $puntos_mozo, PDO::PARAM_INT);
        $consulta->bindValue(':puntos_cocinero', $puntos_cocinero, PDO::PARAM_INT);

        $sumaTotal = (int)$puntos_mesa + (int) $puntos_restoran + (int) $puntos_mozo + (int) $puntos_cocinero;
        $promedio = $sumaTotal / 4;

        $consulta->bindValue(':promedio', $promedio, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        if($consulta->rowCount() == 1){
            return true;
        }else{
            return false;
        }
    }

    //---   DELETE  ---///

    public static function BorrarEncuesta($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM encuestas WHERE id = :id;"); 
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