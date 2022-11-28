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
require_once './middlewares/isMozo.php';
require_once './middlewares/EstaLogeado.php';

require_once './controllers/TrabajadorController.php';
require_once './controllers/MesaController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/AreaController.php';
require_once './controllers/EncuestaController.php';

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
    $group->get('/leer/csv', \TrabajadorController::class . ':LeerCsv');
    $group->put('/modificar', \TrabajadorController::class . ':ModificarUno');
    $group->post('/upload/csv', \TrabajadorController::class . ':CrearCsv');
    $group->post('/download/csv', \TrabajadorController::class . ':DescargarCsv');
    $group->delete('/borrar', \TrabajadorController::class . ':BorrarUno')->add(new isAdmin());
  })->add(new EstaLogeado());

  //PRODUCTOS
  $app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerTodos'); 
    $group->get('/search_by_id/{id}', \ProductoController::class . ':TraerUno'); 
    $group->post('/alta', \ProductoController::class . ':CargarUno');
    $group->put('/preparar', \ProductoController::class . ':ModificarStatus');
    $group->put('/listo', \ProductoController::class . ':ModificarStatus');
    $group->put('/servir', \ProductoController::class . ':Servir')->add(new isMozo());
    $group->delete('/borrar', \ProductoController::class . ':BorrarUno')->add(new isAdmin());
  })->add(new EstaLogeado());

  //PEDIDOS
  $app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class . ':TraerTodos'); 
    $group->get('/search_by_id/{id}', \PedidoController::class . ':TraerUno'); 
    $group->get('/cuanto_falta/{id_mesa}/{id_pedido}', \PedidoController::class . ':CuantoFalta');
    $group->get('/cuanto_falta_por_pedido/{id_pedido}', \PedidoController::class . ':CuantoFaltaPorPedido');
    $group->get('/mostrar_productos/{id}', \PedidoController::class . ':TraerProductos'); 
    $group->post('/alta', \PedidoController::class . ':CargarUno')->add(new isMozo());
    $group->put('/modificar', \PedidoController::class . ':ModificarUno')->add(new isMozo());
    $group->put('/cobrar', \PedidoController::class . ':Cobrar')->add(new isMozo());
    $group->delete('/borrar', \PedidoController::class . ':BorrarUno')->add(new isMozo());
  })->add(new EstaLogeado());

  //MESAS
  $app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerTodos'); 
    $group->get('/search_by_id/{id}', \MesaController::class . ':TraerUno'); 
    $group->post('/alta', \MesaController::class . ':CargarUno')->add(new isAdmin());
    $group->put('/modificar_status', \MesaController::class . ':ModificarStatus')->add(new isMozo());
    $group->put('/levantar', \MesaController::class . ':LevantarMesa')->add(new isMozo());
    $group->put('/cerrar', \MesaController::class . ':CerrarMesa')->add(new isAdmin());
    $group->put('/cliente_cambia_mesa', \MesaController::class . ':ClienteCambiaMesa')->add(new isMozo());
    $group->delete('/borrar', \MesaController::class . ':BorrarUno')->add(new isAdmin());
  })->add(new EstaLogeado());


  //AREAS
  $app->group('/areas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \AreaController::class . ':TraerTodos'); 
    $group->get('/listar_pendientes/{id}', \AreaController::class . ':ListarPendientes');  
    $group->get('/listar_en_preparacion/{id}', \AreaController::class . ':ListarEnPreparacion');  
    $group->get('/listar_listos/{id}', \AreaController::class . ':ListarListos');  
    $group->post('/alta', \AreaController::class . ':CargarUno')->add(new isAdmin());
  })->add(new EstaLogeado());

  //Encuestas
  $app->group('/encuestas', function (RouteCollectorProxy $group) {
    $group->get('/mostrar', \EncuestaController::class . ':TraerTodos'); 
    $group->post('/alta', \EncuestaController::class . ':CargarUno');
    $group->put('/modificar', \EncuestaController::class . ':Modificar');
    $group->delete('/borrar', \EncuestaController::class . ':BorrarUno');//->add(new isAdmin());
  })->add(new EstaLogeado());



$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("Slim Framework 4 PHP Francisco Allende");
    return $response;

});

$app->run();


