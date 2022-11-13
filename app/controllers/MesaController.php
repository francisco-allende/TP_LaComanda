<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $mesa = Mesa::instanciarMesa($params['id_mozo'], $params['estado']);

        $mesa->CrearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creada con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $usuario = Mesa::ObtenerMesa($params['id']);
        if($usuario != false){
          $payload = json_encode($usuario);
        }else{
          $payload = json_encode(array("Error" => "No existe mesa con ese id"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::ObtenerTodos();
        $payload = json_encode(array("lista_Mesas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $fueModificado = Mesa::ModificarMesa($params['username'], $params['password'], $params['id']);
        if($fueModificado){
          $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
        }else{
          $payload = json_encode(array("error" => "No se pudo modificar el usuario o no hubo ningun tipo de cambio"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    //Comente lo anterior porque no da de baja sino que hace baja logica, agrega una fecha de baja
    public function BorrarUno($request, $response, $args)
    {
      $params = $request->getParsedBody();

      $fueBorrado = Usuario::BorrarUsuario($params['id']);
      if($fueBorrado){
        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));
      }else{
        $payload = json_encode(array("error" => "No se pudo borrar el usuario"));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
}
