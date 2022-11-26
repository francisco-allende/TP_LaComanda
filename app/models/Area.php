<?php

require_once './db/DataAccess.php';

 class Area {
        public $area_id;
        public $area_descripcion;

        public function __construct(){}

        public static function instanciarArea($area_id){
            $area = new Area();
            $producto->setAreaIdAndDescripcion($area_id);
            
            return $area;
        }
        //--- Getters ---//

        public function getAreaId(){
            return $this->area_id;
        }

        public function getAreaDescripcion(){
            return $this->area_descripcion;
        }

        //--- Setters ---//

        public function setAreaIdAndDescripcion($area_id){
            $this->area_id = $area_id;
            switch($area_id){
                case 1:
                    $this->area_descripcion = "Barra_Tragos";
                    break;
                 case 2:
                    $this->area_descripcion = "Barra_Cervezas";
                    break;
                 case 3:
                    $this->area_descripcion = "Cocina";
                    break;
                 case 4:
                    $this->area_descripcion = "Candy_Bar";
                    break;
                default:
                    $this->area_descriocion = "Id de area no valido. Elimine este area y mande un nro del 1 al 4";
            }
        }

        //--- Insert Area ---//

        public function insertArea(){
            $objDataAccess = DataAccess::getInstance();
            $sql = "INSERT INTO area (area_description) VALUES (:area_description);";
            $query = $objDataAccess->prepareQuery($sql);
            $query->bindValue(':area_description', $this->getAreaDescription());
            $query->execute();

            return $objDataAccess->getLastInsertedID();
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

        //--- Get All Areas ---//

        public static function getAllAreas(){
            $objDataAccess = DataAccess::getInstance();
            $sql = "SELECT * FROM area;";
            $query = $objDataAccess->prepareQuery($sql);
            $query->execute();
            $areas = $query->fetchAll(PDO::FETCH_CLASS, 'Area');
            return $areas;
        }
 }
?>