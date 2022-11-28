<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class isGastronomico
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $parametros = $request->getParsedBody();

        $header = $request->getHeaderLine('Authorization');
        $response = new Response();
        try {
            if (!empty($header)) {
                $token = trim(explode("Bearer", $header)[1]);
                $data = AutentificadorJWT::ObtenerData($token);
                if ($data->rol == "cocinero" || $data->rol == "repostero" || $data->rol == "birrero" || $data->rol == "barman"){
                    $response = $handler->handle($request);
                }else{
                    $response->getBody()->write("Error, solo los gastronomicos pueden realizar esta accion");
                }
            }else{
                $response->getBody()->write("Error, Token vacio");
            }
        }catch (\Throwable $th) {
            echo $th->getMessage();
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}