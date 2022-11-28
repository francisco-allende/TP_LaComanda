<?php
require_once './models/Encuesta.php';
require_once './controllers/ArchivoController.php';

class EncuestaController extends Mesa
{
    ///---    INSERT INTO    ---///

    public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $filePath = $_FILES["file"]['name'];
        $retorno = ArchivoController::UploadFile($filePath);


      /*
        $encuesta = Encuesta::instanciarEncuesta($params['estado']);

        $encuesta->CrearEncuesta();

        $payload = json_encode(array("mensaje" => "Encuesta creada con exito"));
    */
        $payload = "";
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    ///---    GETTER    ---///

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::ObtenerTodos();
        $payload = json_encode(array("lista_encuestas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    ///---    UPDATE    ---///

    public function Modificar($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $fueModificado = Mesa::ModificarStatusMesa($params['status'], $params['id']);
        
        if($fueModificado){
          $payload = json_encode(array("mensaje" => "Status de la mesa modificado con exito"));
        }else{
          $payload = json_encode(array("error" => "No se pudo modificar el status de la mesa o no hubo ningun tipo de cambio"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    //---   DELETE    ---///
    public function BorrarUno($request, $response, $args)
    {
      $params = $request->getParsedBody();

      $fueBorrado = Mesa::BorrarMesa($params['id']);
      if($fueBorrado){
        $payload = json_encode(array("mensaje" => "Mesa borrada con exito"));
      }else{
        $payload = json_encode(array("error" => "No se pudo borrar la mesa"));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
    
    
}
