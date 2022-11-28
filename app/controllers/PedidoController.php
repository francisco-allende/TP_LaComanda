<?php
require_once './models/Pedido.php';
require_once './controllers/ArchivoController.php';

class PedidoController extends Pedido 
{

    //---   Insert Into   ---///

    public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $imgPath = "Pedido_de_{$params['nombre_cliente']}_mesa_Nro_{$params['id_mesa']}.jpg";

        $pedido = Pedido::instanciarPedido($params['id_mesa'], $params['status'], $params['nombre_cliente'], $imgPath);

        $pedido->CrearPedido();
        $retorno = ArchivoController::UploadPhoto($imgPath);
        if($retorno == 1 || $retorno){
          $payload = json_encode(array("mensaje" => "Pedido y foto creado con exito"));
        }else{
          $payload = json_encode(array("mensaje" => "Pedido creada con exito. No se pudo guardar la foto"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

      //---   Get  ---///

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::ObtenerTodos();
        $payload = json_encode(array("lista_pedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    

    //---   Delete  ---///

    public function BorrarUno($request, $response, $args)
    {
      $params = $request->getParsedBody();

      $fueBorrado = Pedido::BorrarPedido($params['id']);
      if($fueBorrado){
        $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));
      }else{
        $payload = json_encode(array("error" => "No se pudo borrar el pedido"));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    //--- Cuanto Falta, Calcular tiempos  ---///

    public function CuantoFalta($request, $response, $args)
    {
        $id_mesa = $args['id_mesa'];
        $id_pedido = $args['id_pedido'];

        $pedido = Pedido::ObtenerPedidoPorMesa($id_mesa);

        //cuando el cliente desocupa la mesa, se le cambia el id de mesa
        if($pedido != false && $pedido->id == $id_pedido)
        {
          $productos = Pedido::ObtenerProductosDelPedido($id_pedido);
          if(count($productos) > 0)
          {
            $demora = "";
            for($i = 0; $i < count($productos); $i++){
              $demora .= "Falta ";
              $demora .= $productos[$i]->calcularTiempoRestante($productos[$i]->getTiempoFin()); 
              $demora .= " para el ".$productos[$i]->getDescripcion().PHP_EOL;
            }
            $payload = $demora;

          }else{
            $payload = json_encode(array("Error" => "No hay productos asignados al pedido con ese id"));
          }
        }else{
          $payload = json_encode(array("Error" => "No existe pedido con ese id"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    
    public function CuantoFaltaPorPedido($request, $response, $args)
    {
        $id_pedido = $args['id_pedido'];

        $pedido = Pedido::ObtenerPedido($id_pedido);

        if($pedido != false && $pedido->id == $id_pedido)
        {
          $productos = Pedido::ObtenerProductosDelPedido($id_pedido);
          if(count($productos) > 0)
          {
            $demora = "";
            for($i = 0; $i < count($productos); $i++){
              $demora .= "Falta ";
              $demora .= $productos[$i]->calcularTiempoRestante($productos[$i]->getTiempoFin()); 
              $demora .= " para el ".$productos[$i]->getDescripcion().PHP_EOL;
            }
            $payload = $demora;

          }else{
            $payload = json_encode(array("Error" => "No hay productos asignados al pedido con ese id"));
          }
        }else{
          $payload = json_encode(array("Error" => "No existe pedido con ese id"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
