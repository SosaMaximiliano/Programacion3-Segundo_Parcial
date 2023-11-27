<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/./db/AccesoDatos.php';
require __DIR__ . '/./Controller/ReservaController.php';
require __DIR__ . '/./Controller/ClienteController.php';
require __DIR__ . '/./Controller/AuthJWTController.php';
require __DIR__ . '/./Middleware/CheckTokenMiddleware.php';
require __DIR__ . '/./Middleware/CheckAltaClienteMW.php';
require __DIR__ . '/./Middleware/CheckAltaReservaMW.php';


// Instantiate App
$app = AppFactory::create();

// Set base path
$app->setBasePath('/SEGUNDO_PARCIAL/app');

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes

$app->post('/login', \AuthJWTController::class . ':CrearTokenLogin');

$app->group('/cliente', function (RouteCollectorProxy $group)
{
    $group->post('/alta', \ClienteController::class . ':AltaCliente')->add(new CheckAltaClienteMW());
    $group->delete('/baja', \ClienteController::class . ':BajaCliente');
    $group->put('/modificar', \ClienteController::class . ':ModificarCliente');
    $group->post('/consultar', \ClienteController::class . ':ListarClientes');
    $group->post('/consultar/id', \ClienteController::class . ':ConsultarClientePorID');
})->add(new CheckTokenMiddleware());


$app->group('/reserva', function (RouteCollectorProxy $group)
{
    $group->post('/alta', \ReservaController::class . ':AltaReserva')->add(new CheckAltaReservaMW());
    $group->delete('/baja', \ReservaController::class . ':BajaReserva');
    $group->put('/modificar', \ReservaController::class . ':ModificarReserva');
    $group->get('/consultar', \ReservaController::class . ':ListarReservas');
    $group->get('/consultar/a2', \ReservaController::class . ':ListarReservasA2');
    $group->get('/consultar/b2', \ReservaController::class . ':ListarReservasB2');
    $group->get('/consultar/c2', \ReservaController::class . ':ListarReservasC2');
    $group->get('/consultar/d2', \ReservaController::class . ':ListarReservasD2');
    $group->get('/consultar/e', \ReservaController::class . ':ListarReservasE');
    $group->get('/consultar/f', \ReservaController::class . ':ListarReservasF');
    $group->get('/consultar/id', \ReservaController::class . ':ListarReserva');
    $group->get('/consultar/fecha', \ReservaController::class . ':ListarReservasA');
    $group->get('/consultar/idcliente', \ReservaController::class . ':ListarReservasB');
    $group->get('/consultar/desdehasta', \ReservaController::class . ':ListarReservasC');
    $group->get('/consultar/tipohabitacion', \ReservaController::class . ':ListarReservasD');
})->add(new CheckTokenMiddleware());


$app->get('[/]', function (Request $request, Response $response)
{
    $response->getBody()->write("PARCIAL II");
    return $response;
});

$app->run();
