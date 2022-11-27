<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa
{
    public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $mesa = Mesa::instanciarMesa($params['estado']);

        $mesa->CrearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creada con exito"));

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

    public function ModificarStatus($request, $response, $args)
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
    
    
}
