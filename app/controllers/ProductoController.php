<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto 
{
    public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $producto = Producto::instanciarProducto($params['area'], $params['id_pedido'], $params['status'], $params['descripcion'],
        $params['precio'], $params['tiempo_inicio'], $params['tiempo_fin']);

        $producto->CrearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

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

        $trabajador = Producto::ObtenerProducto($args['id']);
        if($trabajador != false){
          $payload = json_encode($trabajador);
        }else{
          $payload = json_encode(array("Error" => "No existe producto con ese id"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarStatus($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $fueModificado = Producto::ModificarStatusProducto($params['status'], $params['id']);
        if($fueModificado){
          $payload = json_encode(array("mensaje" => "Status de producto modificado con exito"));
        }else{
          $payload = json_encode(array("error" => "No se pudo modificar el status de producto o no hubo ningun tipo de cambio"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

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
