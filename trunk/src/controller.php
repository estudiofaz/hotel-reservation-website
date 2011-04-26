<?php

/** Home Page
 * Pattern: /
 */
$app->get('/', function() use($app) {
    $conn = $app['doctrine.dbal.connection'];       
    
    $time = time();
    for($i=0;$i<3;$i++) 
    {
        $months[] = date("F Y",$time);
        $time = strtotime(" +1 month",$time);
    }                
    return $app['twig']->render('index.html.twig',array('months'=>$months));
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
    
    $time = time();
    for($i=0;$i<3;$i++) 
    {
        $months[] = date("F Y",$time);
        $time = strtotime(" +1 month",$time);
    }
    return $app['twig']->render('booking.html.twig',array('months'=>$months));
})->bind('booking');



$app->post('/booking.html', function() use($app) {
    
    $time = time();
    for($i=0;$i<3;$i++) 
    {
        $months[] = date("F Y",$time);
        $time = strtotime(" +1 month",$time);
    }
    
    $checkInDay = $app['request']->get('checkInDay');
    $checkInMonth = $app['request']->get('checkInMonth');
    $checkOutDay = $app['request']->get('checkOutDay');
    $checkOutMonth = $app['request']->get('checkOutMonth');
    $rooms = $app['request']->get('rooms');
    $room_type = $app['request']->get('room_type');
    
    if($room_type)
        $sql = "( select roomno from rooms where roomtypeid = $room_type) minus ";
    else $sql = "( select roomno from rooms) minus ";                  
                
    $in = $checkInDay.' '.$checkInMonth; 
    $from = date("d-M-y",strtotime($in));     
    $out = $checkOutDay.' '.$checkOutMonth;
    $to = date("d-M-y",strtotime($out));     
    
    $conn = $app['doctrine.dbal.connection'];    
    $sql .= " 
        
           ( select ROOMS.roomno from rooms,reservations 
                where ROOMS.ROOMNO = RESERVATIONS.ROOMNO
               AND
              (
               ( :fromdate >= reservations.checkindate AND :fromdate <= reservations.checkoutdate ) 
               OR
               ( :todate >= reservations.checkindate AND :todate <= reservations.checkoutdate )
               OR
               ( reservations.checkindate >= :fromdate AND reservations.checkindate <= :todate)
              )
            )";
                     
    $availableRooms = $conn->prepare($sql);
    $availableRooms->bindValue("fromdate",$from);    
    $availableRooms->bindValue("todate",$to);    
    $availableRooms->execute();
    $result = $availableRooms->fetchAll();                          
    
    return $app['twig']->render('available_rooms.html.twig',array('rooms'=>$result,'fromdate'=>$from,'todate'=>$to));
})->bind('booking_post');


$app->post('/reserved.html', function() use($app) {
    echo "form submitted to me";
    //return $app['twig']->render('restaurant.html.twig');
    
})->bind('reserved');


$app->get('/page/{slug}', function($slug) use($app) {

    return $app['twig']->render($slug.'.html.twig',array(
        'base_url' => $app['request']->getBaseUrl(),
        ));
});
