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

        $lista = Area::ObtenerStatusPorArea($desc, "pendiente");
        $payload = json_encode(array("lista_pendientes_area_{$desc}" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ListarEnPreparacion($request, $response, $args)
    {
        $area = Area::getAreaById($args['id']);
        $desc = $area->descripcion;

        $lista = Area::ObtenerStatusPorArea($desc, "en preparacion");
        $payload = json_encode(array("lista_en_preparacion_area_{$desc}" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ListarListos($request, $response, $args)
    {
        $area = Area::getAreaById($args['id']);
        $desc = $area->descripcion;

        $lista = Area::ObtenerStatusPorArea($desc, "listo");
        $payload = json_encode(array("lista_pendientes_area_{$desc}" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
