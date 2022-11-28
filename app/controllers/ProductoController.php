<?php
require_once './models/Producto.php';
require_once './models/Pedido.php';
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto 
{

    ///---    INSERT INTO    ---///

    public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();

        //valido que el pedido exista
        $pedido = Pedido::ObtenerPedido($params['id_pedido']);
        if($pedido != false){
          $producto = Producto::instanciarProducto($params['area'], $params['id_pedido'], "pendiente", $params['descripcion'],
          $params['precio']);
  
          $producto->CrearProducto();
  
          $payload = json_encode(array("mensaje" => "Producto creado con exito"));
        }else{
          $payload = json_encode(array("mensaje" => "No puede crearse un producto de un pedido inexistente"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    ///---    GETTERS    ---///

    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::ObtenerTodos();
        $payload = json_encode(array("lista_productos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $producto = Producto::ObtenerProducto($args['id']);
        if($producto != false){
          $payload = json_encode($producto);
        }else{
          $payload = json_encode(array("Error" => "No existe producto con ese id"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    ///---    UPDATE    ---///

    public function ModificarStatus($request, $response, $args)
    {
        $params = $request->getParsedBody();

        if(isset($params['tiempo_fin']))
        {
          $fueModificado = Producto::ModificarStatusProducto($params['status'], $params['id'], $params['tiempo_fin']);
          if($fueModificado){
            $producto = Producto::ObtenerProducto($params['id']);
            if($producto != false){
              $pedido = Pedido::ObtenerPedido($producto->getIdPedido());
              if($pedido != false){
                Pedido::ModificarStatusPedido("en preparacion", $pedido->getId());
              }
            }
          }
        }else{
          $fueModificado = Producto::ModificarStatusProducto($params['status'], $params['id']);
        }
        
        if($fueModificado){
          $payload = json_encode(array("mensaje" => "Status de producto modificado con exito"));
        }else{
          $payload = json_encode(array("error" => "No se pudo modificar el status de producto o no hubo ningun tipo de cambio"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function Servir($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $producto = Producto::ObtenerProducto($params['id']);
        if($producto != false)
        {
          if($producto->getStatus() == "listo")
          {
            $id_pedido = $producto->getIdPedido();
            $pedido = Pedido::ObtenerPedido($id_pedido);

            //cambio el estado de la mesa
            Mesa::ModificarStatusMesa("con cliente comiendo", $pedido->getIdMesa());

            //chequeo si hay otro producto para el pedido. Sino, lo cambio a servido
            $productos = Pedido::ObtenerProductosDelPedido($id_pedido);

            $statusProductos = "";
            $todoListo = true; 
         
            for($i = 0; $i < count($productos); $i++)
            {
              $statusProductos = $productos[$i]->getStatus();
              
              if($statusProductos != "listo" && $statusProductos != "servido")
              {
                $todoListo = false;
                break;
              } 
            }

            //sirvo el producto
            Producto::ModificarStatusProducto("servido", $producto->getId());

            //dependiendo de si estan todos listos o no cambio el status de pedido
            if(!$todoListo){
              Pedido::ModificarStatusPedido("con producto servido pero no todos", $pedido->getId());
              $payload = json_encode("servido el producto, aun el pedido no esta totalmente servido");
            }else{
              Pedido::ModificarStatusPedido("todo servido", $pedido->getId());
              $payload = json_encode("todo servido para este pedido");
            }            
          }else{
            $payload = json_encode(array("Error" => "El producto no se encuentra listo para servir"));
          }
        }else{
          $payload = json_encode(array("Error" => "No existe producto con ese id"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    ///---    DELETE    ---///

    public function BorrarUno($request, $response, $args)
    {
      $params = $request->getParsedBody();

      $fueBorrado = Producto::BorrarProducto($params['id']);
      if($fueBorrado){
        $payload = json_encode(array("mensaje" => "Producto borrado con exito"));
      }else{
        $payload = json_encode(array("error" => "No se pudo borrar el producto"));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }


}
