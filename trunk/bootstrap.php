<?php

require_once __DIR__.'/vendor/silex.phar';

use ExtraExtensions\DoctrineExtension;
//use Silex\Extension\DoctrineExtension //[Excluding ORM]
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app = new Silex\Application;
$app['autoloader']->registerNamespace('ExtraExtensions', __DIR__.'/vendor/ext');
$app->register(new DoctrineExtension, array(
    'doctrine.dbal.connection_options' => array(
        'driver' => 'pdo_mysql',
        'dbname' => 'jellydog',
        'host' => 'localhost',
        'user' => 'root',
        'password' => null
    ),
    'doctrine.orm' => true,
    'doctrine.orm.entities' => array(
        array('type' => 'annotation', 'path' => __DIR__.'/Entity', 'namespace' => 'Entity'),
    ),
    'doctrine.common.class_path'    => __DIR__.'/vendor/doctrine-common/lib',
    'doctrine.dbal.class_path'    => __DIR__.'/vendor/doctrine-dbal/lib',
    'doctrine.orm.class_path'    => __DIR__.'/vendor/doctrine/lib',
));

$app['autoloader']->registerNamespace('Entity', __DIR__);



$app->register(new Silex\Extension\UrlGeneratorExtension());

$app->register(new Silex\Extension\SymfonyBridgesExtension(), array(
    'symfony_bridges.class_path' => __DIR__.'/vendor/symfony/src'
));

$app->register(new Silex\Extension\TwigExtension(), array(
    'twig.path' => __DIR__.'/templates',
    'twig.class_path' => __DIR__.'/vendor/twig/lib',
    'twig.options' => array('cache' => __DIR__.'/cache','debug'=>true),
));
/*
$app->error(function(\Exception $e) {
    if ($e instanceof NotFoundHttpException) {
        return new Response('The requested page could not be found.', 404);
    }

    $code = ($e instanceof HttpException) ? $e->getStatusCode() : 500;
    return new Response('We are sorry, but something went terribly wrong.', $code);
});
*/

include __DIR__."/src/controller.php";
return $app;
