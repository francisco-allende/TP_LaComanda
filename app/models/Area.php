<?php

require_once './db/AccesoDatos.php';

 class Area {
        public $id;
        public $descripcion;

        public function __construct(){}

        public static function instanciarArea($descripcion){
            $area = new Area();
            $area->setDescripcion($descripcion);
            
            return $area;
        }
        //--- Getters ---//

        public function getAreaId(){
            return $this->id;
        }

        public function getAreaDescripcion(){
            return $this->descripcion;
        }

        //--- Setters ---//

        public function setDescripcion($descripcion){
            $this->descripcion = $descripcion;
        }

        //---   Metodos SQL   ---//

        //---   INSERT INTO   ---//

        public function CrearArea()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO areas (descripcion) VALUES (:descripcion)");
            $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
            $consulta->execute();
    
            return $objAccesoDatos->obtenerUltimoId();
        }

        //---   GETTERS   ---//

        public static function ObtenerTodos()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM areas");
            $consulta->execute();
    
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Area');
        }

        public static function ObtenerStatusPorArea($descripcion, $status)
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos WHERE status = :status && area = :descripcion;");
            $consulta->bindValue(':status', $status, PDO::PARAM_STR);
            $consulta->bindValue(':descripcion', $descripcion, PDO::PARAM_STR);
            $consulta->execute();
    
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Area');
        }

        public static function getAreaById($id)
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM areas WHERE id = :id");
            $consulta->bindValue(':id', $id);
            $consulta->execute();
    
            $area = $consulta->fetchObject('Area');
            if(is_null($area)){
                throw new Exception("El area no existe");
            }
            
            return $area;
        }

        //--- Update Area ---//

        public static function updateArea($area){
            $objDataAccess = DataAccess::getInstance();
            $sql = "UPDATE area SET area_description = ':area_description' WHERE area_id = :area_id;";
            $query = $objDataAccess->prepareQuery($sql);
            $query->bindValue(':area_id', $area->getAreaId());
            $query->bindValue(':area_description', $area->getAreaDescription());
            return $query->execute();
        }

        //--- Delete Area ---//

        public static function deleteArea($area){
            $objDataAccess = DataAccess::getInstance();
            $sql = "DELETE FROM area WHERE area_id = :area_id";
            $query = $objDataAccess->prepareQuery($sql);
            $query->bindValue(':area_id', $area->getAreaId());
            return $query->execute();
        }
 }
?>