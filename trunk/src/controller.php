<?php

/** Home Page
 * Pattern: /
 */
$app->get('/', function() use($app) {
    $conn = $app['doctrine.dbal.connection'];
    $categories = $conn->query('SELECT * FROM countries')->fetchAll();
    //var_dump($categories);        
    return $app['twig']->render('index.html.twig',array('c'=>$categories));
})->bind('homepage');


$app->get('/services.html', function() use($app) {
    return $app['twig']->render('services.html.twig');
})->bind('services');

$app->get('/testimonials.html', function() use($app) {
    return $app['twig']->render('testimonials.html.twig');
})->bind('testimonials');

$app->get('/gallery.html', function() use($app) {
    return $app['twig']->render('gallery.html.twig');
})->bind('gallery');

$app->get('/restaurant.html', function() use($app) {
    return $app['twig']->render('restaurant.html.twig');
})->bind('restaurant');

$app->get('/booking.html', function() use($app) {
    return $app['twig']->render('booking.html.twig');
})->bind('booking');






$app->get('/page/{slug}', function($slug) use($app) {

    return $app['twig']->render($slug.'.html.twig',array(
        'base_url' => $app['request']->getBaseUrl(),
        ));
});
