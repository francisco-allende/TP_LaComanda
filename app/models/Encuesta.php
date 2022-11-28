<?php

require_once './db/AccesoDatos.php';

class Encuesta {
    public $id;

    public function __construct() {}

    public static function instanciarEncuesta($estado) {
        $encuesta = new Encuesta();
        $encuesta->setEstado($estado);

        return $encuesta;
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

    //--- Metodos SQL  ---///

    public function CrearEncuesta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("
        INSERT INTO encuestas ( ) VALUES (: )");
        
        $consulta->bindValue(': ', $this->estado, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function ObtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuestas");
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

    public static function ModificarStatusEncuesta($estado, $id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE encuestas SET  = : WHERE id = :id");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        if($consulta->rowCount() == 1){
            return true;
        }else{
            return false;
        }
    }

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