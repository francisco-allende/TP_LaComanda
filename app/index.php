<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
require_once './middlewares/isAdmin.php';
require_once './middlewares/CheckJWT.php';

require_once './controllers/UsuarioController.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Set base path
$app->setBasePath('/tp_laComanda/la_comanda/app');

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();


// Routes
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos'); 
    $group->post('/search_by_id', \UsuarioController::class . ':TraerUno'); 
    $group->post('/alta', \UsuarioController::class . ':CargarUno');
    $group->put('/modificar', \UsuarioController::class . ':ModificarUno');
    $group->delete('/borrar', \UsuarioController::class . ':BorrarUno')->add(new isAdmin());
  })->add(new CheckJWT());

$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("Slim Framework 4 PHP Francisco Allende");
    return $response;

});

$app->run();


