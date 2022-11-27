<?php
require_once './models/Area.php';

class AreaController extends Area
{
    public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $area = Area::instanciarArea($params['descripcion']);

        $area->CrearArea();

        $payload = json_encode(array("mensaje" => "Area creada con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Area::ObtenerTodos();
        $payload = json_encode(array("lista_areas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ListarPendientes($request, $response, $args)
    {
        $area = Area::getAreaById($args['id']);
        $desc = $area->descripcion;

        $lista = Area::ObtenerPendientesPorArea( $desc);
        $payload = json_encode(array("lista_pendientes_area_{$desc}" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
