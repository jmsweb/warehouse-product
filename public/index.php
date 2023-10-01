<?php
use DI\Container;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;
use Symfony\Component\Dotenv\Dotenv;

require '../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

$container = new Container([
    'entity' => function() : EntityManager {
        $paths = [__DIR__ . '/../src/Domain'];
        $config = ORMSetup::createAttributeMetadataConfiguration($paths, true);
        $connection = DriverManager::getConnection([
            'driver'   => 'pdo_mysql',
            'user'     => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS'],
            'dbname'   => $_ENV['DB_NAME'],
            'host'     => $_ENV['DB_HOST']
        ], $config);
        return new EntityManager($connection, $config);
    }
]);

AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// CORS SETUP (PRE-FLIGHT)
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

// CORS SETUP (PRE-FLIGHT)
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', $_ENV['WAREHOUSE_API'])
        ->withHeader('Access-Control-Allow-Credentials', 'true')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->map(['GET'], '/health-check', App\Controller\HealthCheck::class);
$app->map(['GET'], '/all', App\Controller\ProductController::class);

//$app->map(['GET'], '[/[{slug}]]', App\Controller\Category\CategoryAction::class);
//$app->map(['GET'], '/{slug}/sub', App\Controller\Category\CategoryAction::class);

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
    throw new HttpNotFoundException($request);
});


$app->run();