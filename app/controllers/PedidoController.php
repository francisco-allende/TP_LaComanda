<?php
require_once './models/Pedido.php';
require_once './controllers/ArchivoController.php';

class PedidoController extends Pedido 
{
    public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $imgPath = "Pedido_de_{$params['nombre_cliente']}_mesa_Nro_{$params['id_mesa']}.jpg";

        $pedido = Pedido::instanciarPedido($params['id_mesa'], $params['status'], $params['nombre_cliente'], $imgPath,
        $params['total_cuenta']);

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

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::ObtenerTodos();
        $payload = json_encode(array("lista_pedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
