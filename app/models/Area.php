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

        public function CrearArea()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
    
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO areas (descripcion) VALUES (:descripcion)");
            
            $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
            $consulta->execute();
    
            return $objAccesoDatos->obtenerUltimoId();
        }

        public static function ObtenerTodos(){
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM areas");
            $consulta->execute();
    
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Area');
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

        //--- Get Area ---//

        public static function getAreaById($area_id){
            $objDataAccess = DataAccess::getInstance();
            $query = $objDataAccess->prepareQuery("SELECT * FROM area WHERE area_id = :area_id;");
            $query->bindParam(':area_id', $area_id);
            $query->execute();
            $area = $query->fetchObject('Area');
            if(is_null($area)){
                throw new Exception("The area doesn't exist.");
            }
            
            return $area;
        }

        public static function getAreaByName($area_name){
            $objDataAccess = DataAccess::getInstance();
            $query = $objDataAccess->prepareQuery("SELECT area_id, area_description FROM area WHERE area_description = :area_description;");
            $query->bindParam(':area_description', $area_name);
            $query->execute();
            $area = $query->fetchObject('Area');
            
            return $area;
        }

 }
?>