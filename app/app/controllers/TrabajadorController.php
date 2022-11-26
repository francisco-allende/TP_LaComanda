<?php
require_once './models/Trabajador.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $usuario = Usuario::instanciarUsuario($params['username'], $params['password'], $params['isAdmin'], $params['rol'],
        $params['fecha_inicio'], $params['fecha_fin']);

        $usuario->CrearUsuario();

        $payload = json_encode(array("mensaje" => "Trabajador creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por id
        $params = $request->getParsedBody();

        $usuario = Usuario::ObtenerUsuario($params['id']);
        if($usuario != false){
          $payload = json_encode($usuario);
        }else{
          $payload = json_encode(array("Error" => "No existe trabajador con ese id"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::ObtenerTodos();
        $payload = json_encode(array("lista_trabajadores" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $fueModificado = Usuario::ModificarUsuario($params['username'], $params['password'], $params['id']);
        if($fueModificado){
          $payload = json_encode(array("mensaje" => "Trabajador modificado con exito"));
        }else{
          $payload = json_encode(array("error" => "No se pudo modificar el trabajador o no hubo ningun tipo de cambio"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    //Comente lo anterior porque no da de baja sino que hace baja logica, agrega una fecha de baja
    public function BorrarUno($request, $response, $args)
    {
      $params = $request->getParsedBody();

      $fueBorrado = Usuario::BorrarUsuario($params['id']);
      if($fueBorrado){
        $payload = json_encode(array("mensaje" => "Trabajador borrado con exito"));
      }else{
        $payload = json_encode(array("error" => "No se pudo borrar el trabajador"));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function Verificar($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $payload = "";
        $username = $params['username'];
        $password = $params['password'];

        $usuario = Usuario::ObtenerUsuarioPorMail($username);

        if(!is_null($usuario) && $usuario != false)
        {
          if(password_verify(trim($password), $usuario->getPassword()))
          {
              $userData = array(
                  'id' => $usuario->getId(),
                  'username' => $usuario->getMail(),
                  'password' => $usuario->getClave(),
                  'isAdmin' => $usuario->getIsAdmin(),
                  'user_type' => $usuario->getUserType(),
                  'fecha_inicio' => $usuario->getFechaInicio(), 
                  'fecha_final' => $usuario->getFechaFinal());
              $payload = json_encode(array('Token' => AutentificadorJWT::CrearToken($userData), 'response' => 'OK', 'Tipo_Usuario' => $usuario->getTipo()));
          }else{
            $payload = json_encode(array('Login' => "Clave incorrecta"));
          }
        }else{
          $payload = json_encode(array('Login' => "No existe usuario $username"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
