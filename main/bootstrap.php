<?php

use Slim\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use App\Controllers\User as UserController;
use App\Utils\Auth as AppAuth;

require_once(__DIR__ . "/../vendor/autoload.php");
require_once(__DIR__ . "/../config/load_env_values.php");

AppAuth::setKey(getenv('JWT_KEY'));

$container = new Container();
AppFactory::setContainer($container);

$container->set('view', function() {
  return Twig::create(__DIR__ . "/../templates");
});

$app = AppFactory::create();

$app->add(TwigMiddleware::createFromContainer($app));
$app->get('/', UserController::class . ':home')->add(UserController::class . ':ipAuthenticate');
$app->get('/auth', UserController::class . ':auth');
$app->get('/check-db', UserController::class . ':check');
$app->post('/login', UserController::class . ':login');
$app->get('/profile', UserController::class . ':profile');
$app->post('/register', UserController::class . ':register');
// $app->get('/auth', UserController::class . ':handleAuth');

// $app->get('/', function (Request $request, Response $response, array $args) {
  // $response->getBody()->write("Hello world!");
  // return $response;
// });

// $app->

$app->run();
