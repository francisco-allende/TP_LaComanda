<?php
require_once './models/Trabajador.php';
require_once './controllers/ArchivoController.php';
require_once './interfaces/IApiUsable.php';

class TrabajadorController extends Trabajador implements IApiUsable
{

   //---    LOGIN   ---//

   public function Registrar($request, $response, $args)
   {
       $params = $request->getParsedBody();

       $trabajador = Trabajador::instanciarTrabajador($params['username'], $params['password'], $params['isAdmin'], $params['rol'],
       $params['fecha_inicio']);

       $trabajador->CrearTrabajador();

       $payload = json_encode(array("mensaje" => "Trabajador creado con exito"));

       $response->getBody()->write($payload);
       return $response
         ->withHeader('Content-Type', 'application/json');
   }

   public function Verificar($request, $response, $args)
   {
       $params = $request->getParsedBody();
       $payload = "";
       $username = $params['username'];
       $password = $params['password'];

       $trabajador = Trabajador::ObtenerTrabajadorPorMail($username);

       if(!is_null($trabajador) && $trabajador != false)
       {
         if(password_verify(trim($password), $trabajador->getPassword()))
         {
             $userData = array(
                 'id' => $trabajador->getId(),
                 'username' => $trabajador->getUsername(),
                 'password' => $trabajador->getPassword(),
                 'isAdmin' => $trabajador->getIsAdmin(),
                 'rol' => $trabajador->getRol(),
                 'fecha_inicio' => $trabajador->getFechaInicio(), 
                 'fecha_final' => $trabajador->getFechaFin());
             $payload = json_encode(array('Token' => AutentificadorJWT::CrearToken($userData), 'response' => 'OK', 'Tipo_Usuario' => $trabajador->getRol()));
         }else{
           $payload = json_encode(array('Login' => "Clave incorrecta"));
         }
       }else{
         $payload = json_encode(array('Login' => "No existe usuario $username"));
       }

       $response->getBody()->write($payload);
       return $response
         ->withHeader('Content-Type', 'application/json');
   }

   public function CrearCsv($request, $response, $args)
   {
      $lista = Trabajador::ObtenerTodos();
      if(ArchivoController::AltaCSV($lista)){
        $response->getBody()->write("trabajadores.csv creado con exito!");
      }else{
        $response->getBody()->write("Error: No se pudo guardar el archivo");
      }

      return $response
        ->withHeader('Content-Type', 'application/json');
   }

   public function LeerCsv($request, $response, $args)
   {
      $str = ArchivoController::LeerCsv();

      $response->getBody()->write($str);
      return $response
        ->withHeader('Content-Type', 'application/json');
   }

   public function DescargarCsv($request, $response, $args)
   {
      $lista = Trabajador::ObtenerTodos();
      
      header('Cache-Control: public');
      header('Content-Description: File Transfer');
      header('Content-Disposition: attachment; filename=trabajadores.csv');
      header('Content-type: application/csv');
      header("Content-Transfer-Encoding: UTF-8");

      $filePath = "./CSV/trabajadores.csv";
      readfile($filePath);
      exit;
      
      
      $response->getBody()->write("Copiar y pegar lo que retorna la app para crear el archivo");

      return $response;
   }

   //---    GETTERS   ---//

   public function TraerTodos($request, $response, $args)
   {
       $lista = Trabajador::ObtenerTodos();
       $payload = json_encode(array("lista_trabajadores" => $lista));

       $response->getBody()->write($payload);
       return $response
         ->withHeader('Content-Type', 'application/json');
   }

    public function TraerUno($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $trabajador = Trabajador::ObtenerTrabajador($args['id']);
        if($trabajador != false){
          $payload = json_encode($trabajador);
        }else{
          $payload = json_encode(array("Error" => "No existe trabajador con ese id"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

  //---    UPDATE   ---//

    public function ModificarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $fueModificado = Trabajador::ModificarTrabajador($params['username'], $params['password'], $params['id']);
        if($fueModificado){
          $payload = json_encode(array("mensaje" => "Trabajador modificado con exito"));
        }else{
          $payload = json_encode(array("error" => "No se pudo modificar el trabajador o no hubo ningun tipo de cambio"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    //---    DELETE   ---//

    public function BorrarUno($request, $response, $args)
    {
      $params = $request->getParsedBody();

      $fueBorrado = Trabajador::BorrarTrabajador($params['id']);
      if($fueBorrado){
        $payload = json_encode(array("mensaje" => "Trabajador borrado con exito"));
      }else{
        $payload = json_encode(array("error" => "No se pudo borrar el trabajador"));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

   
}
