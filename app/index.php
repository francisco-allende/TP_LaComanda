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
require_once './middlewares/EstaLogeado.php';

require_once './controllers/TrabajadorController.php';
require_once './controllers/MesaController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/AreaController.php';

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
$app->group('/trabajador', function (RouteCollectorProxy $group) {
  $group->post('/registrar', \TrabajadorController::class . ':Registrar');
  $group->post('/login', \TrabajadorController::class . ':Verificar');
});

//TRABAJADORES
$app->group('/trabajador', function (RouteCollectorProxy $group) {
    $group->get('[/]', \TrabajadorController::class . ':TraerTodos'); 
    $group->get('/search_by_id/{id}', \TrabajadorController::class . ':TraerUno');
    $group->put('/modificar', \TrabajadorController::class . ':ModificarUno');
    $group->delete('/borrar', \TrabajadorController::class . ':BorrarUno')->add(new isAdmin());
  })->add(new EstaLogeado());

//MESAS
$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerTodos'); 
    $group->get('/search_by_id/{id}', \MesaController::class . ':TraerUno'); 
    $group->post('/alta', \MesaController::class . ':CargarUno');
    $group->put('/modificar', \MesaController::class . ':ModificarUno');
    $group->delete('/borrar', \MesaController::class . ':BorrarUno')->add(new isAdmin());
  })->add(new EstaLogeado());

  //PRODUCTOS
  $app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerTodos'); 
    $group->get('/search_by_id/{id}', \ProductoController::class . ':TraerUno'); 
    $group->post('/alta', \ProductoController::class . ':CargarUno');
    $group->put('/modificar_status', \ProductoController::class . ':ModificarStatus');
    $group->delete('/borrar', \ProductoController::class . ':BorrarUno')->add(new isAdmin());
  })->add(new EstaLogeado());

  //PEDIDOS
  $app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class . ':TraerTodos'); 
    $group->get('/search_by_id/{id}', \PedidoController::class . ':TraerUno'); 
    $group->post('/alta', \PedidoController::class . ':CargarUno');
    $group->put('/modificar', \PedidoController::class . ':ModificarUno');
    $group->delete('/borrar', \PedidoController::class . ':BorrarUno')->add(new isAdmin());
  })->add(new EstaLogeado());

  //AREAS
  $app->group('/areas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \AreaController::class . ':TraerTodos'); 
    $group->get('/listar_pendientes/{id}', \AreaController::class . ':ListarPendientes');  
    $group->post('/alta', \AreaController::class . ':CargarUno')->add(new isAdmin());
  })->add(new EstaLogeado());






$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("Slim Framework 4 PHP Francisco Allende");
    return $response;

});

$app->run();


