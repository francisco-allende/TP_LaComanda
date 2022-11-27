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
}
