<?php

require_once './db/AccesoDatos.php';

class Trabajador{
    
    //--- Atributos ---//
    public $id;
    public $username;
    public $password;
    public $isAdmin;
    public $rol;
    public $fecha_inicio;
    public $fecha_fin;

    //--- Constructor ---//
    public function __construct(){}

    public static function instanciarTrabajador($username, $password, $isAdmin, $rol, $fecha_inicio, $fecha_fin=null){
        $trabajador = new trabajador();
        $trabajador->setUsername($username);
        $trabajador->setPassword($password);
        $trabajador->setIsAdmin($isAdmin);
        $trabajador->setRol($rol);
        $trabajador->setFechaInicio($fecha_inicio);
        $trabajador->setFechaFin($fecha_fin);

        return $trabajador;
    }

    //--- Getters ---//
    public function getId(){
        return $this->id;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getPassword(){
        return $this->password;
    }

    public function getIsAdmin(){
        return $this->isAdmin;
    }

    public function getRol(){
        return $this->rol;
    }

    public function getFechaInicio(){
        return $this->fecha_inicio;
    }

    public function getFechaFin(){
        return $this->fecha_fin;
    }

    //--- Setters ---//

    public function setId($id){
        $this->id = $id;
    }

    public function setUsername($username){
        $this->username = $username;
    }

    public function setPassword($password){
        $this->password = $password;
    }

    public function setIsAdmin($isAdmin){
        $this->isAdmin = $isAdmin;
    }

    public function setRol($rol){
        $this->rol = $rol;
    }
    
    public function setFechaInicio($fecha_inicio){
        $this->fecha_inicio = $fecha_inicio;
    }

    public function setFechaFin($fecha_fin){
        $this->fecha_fin = $fecha_fin;
    }

    //---  Metodos SQL  ---///
    
    //--- INSERT INTO ---///

    public function CrearTrabajador()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("
        INSERT INTO trabajadores (username, password, isAdmin, rol, fecha_inicio, fecha_fin) 
        VALUES (:username, :password, :isAdmin, :rol, :fecha_inicio, :fecha_fin)");
        
        $consulta->bindValue(':username', $this->username, PDO::PARAM_STR);
        $claveHash = password_hash($this->password, PASSWORD_DEFAULT);
        $consulta->bindValue(':password', $claveHash);
        $consulta->bindValue(':isAdmin', $this->isAdmin, PDO::PARAM_STR);
        $consulta->bindValue(':rol', $this->rol, PDO::PARAM_STR);
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':fecha_inicio', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->bindValue(':fecha_fin', null);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    //---   GETTERS   ---//

    public static function ObtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM trabajadores");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Trabajador');
    }

    public static function ObtenerTrabajador($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM trabajadores WHERE id = :id");
        $consulta->bindValue(':id', $id);
        $consulta->execute();

        return $consulta->fetchObject('Trabajador');
    }

    public static function ObtenerTrabajadorPorMail($username)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM trabajadores WHERE username = :username");
        $consulta->bindValue(':username', $username);
        $consulta->execute();
 
        $myObj = $consulta->fetchObject('Trabajador');
        if (is_null($myObj)) {
            return null;
        }
 
        return $myObj;
    }

    //---   UPDATE   ---//

    public static function ModificarTrabajador($username, $password, $id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE trabajadores SET username = :username, password = :password WHERE id = :id");
        $consulta->bindValue(':username', $username, PDO::PARAM_STR);
        $consulta->bindValue(':password', $password, PDO::PARAM_STR);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        if($consulta->rowCount() == 1){
            return true;
        }else{
            return false;
        }
    }

    //---    DELETE   ---//

    public static function BorrarTrabajador($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        //$consulta = $objAccesoDato->prepararConsulta("DELETE FROM trabajadores WHERE id = :id;"); Descomentar para borrar enserio
        $consulta = $objAccesoDato->prepararConsulta("UPDATE trabajadores SET fecha_fin = :fecha_fin WHERE id = :id;");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_fin', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();

        if($consulta->rowCount() == 1){
            return true;
        }else{
            return false;
        }
    }

   
}
?>