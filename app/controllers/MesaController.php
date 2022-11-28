<?php
require_once './models/Mesa.php';

class MesaController extends Mesa
{
    ///---    INSERT INTO    ---///

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

    ///---    GETTER    ---///

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::ObtenerTodos();
        $payload = json_encode(array("lista_Mesas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    ///---    UPDATE    ---///

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

    public function LevantarMesa($request, $response, $args)
    {
      $params = $request->getParsedBody();

      $mesa = Mesa::ObtenerMesa($params['id']);
      if($mesa != false && $mesa != null && $mesa->getEstado() == "con cliente pagando")
      {
        Mesa::ModificarStatusMesa("libre", $mesa->getId());
        
        $pedido = Pedido::ObtenerPedidoPorMesa($params['id']);
        if($pedido != false){
          Pedido::BorrarPedido($pedido->getId());
        }

        $payload = json_encode("Mesa Nro: {$params['id']} liberada");
      }else{
        $payload = json_encode("Error: No se pudo levantar la mesa");
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function CerrarMesa($request, $response, $args)
    {
      $params = $request->getParsedBody();

      $mesa = Mesa::ObtenerMesa($params['id']);
      if($mesa != false)
      {
        Mesa::ModificarStatusMesa("cerrada", $mesa->getId());
        
        $payload = json_encode("Mesa Nro: {$params['id']} cerrada");
      }else{
        $payload = json_encode("Error: No se pudo cerrar la mesa");
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function ClienteCambiaMesa($request, $response, $args)
    {
      $params = $request->getParsedBody();

      $pedido = Pedido::ObtenerPedido($params['id_pedido']);
      if($pedido != false)
      {
        $mesa = Mesa::ObtenerMesa($params['id_nueva_mesa']);
        if($mesa != false && $mesa->getEstado() == "libre")
        {
          Pedido::ModificarMesa($params['id_nueva_mesa'], $params['id_pedido']);
          $payload = json_encode("Mesa cambiada con exito");
        }else{
          $payload = json_encode("No puede cambiarse a una mesa ocupada o inexistente");
        }
      }else{
        $payload = json_encode("Error: No se pudo cambiar la mesa");
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
