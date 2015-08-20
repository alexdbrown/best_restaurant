<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Restaurant.php";
    require_once __DIR__."/../src/Cuisine.php";

    $app = new Silex\Application();

    $server = 'mysql:host=localhost;dbname=best_restaurant';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../views'));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->get("/", function() use($app) {
        return $app['twig']->render('index.html.twig', array('cuisines' => Cuisine::getAll()));
    });

    $app->post('/restaurants', function() use($app) {
        //takes the input values and builds a new restaurant and saves restaurant to database
        $restaurant_name = $_POST['restaurant_name'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $website = $_POST['website'];
        $cuisine_id = $_POST['cuisine_id'];
        $restaurant = new Restaurant($restaurant_name, $phone, $address, $website, $cuisine_id);
        $restaurant->save();

        //?
        $cuisine = Cuisine::find($cuisine_id);
        return $app['twig']->render('cuisines.html.twig', array('cuisine' => $cuisine, 'restaurants' => $cuisine->getRestaurants()));

    });

    $app->patch("/cuisines/{id}", function($id) use ($app) {
        $name = $_POST['name'];
        $cuisine = Cuisine::find($id);
        $cuisine->update($name);
        return $app['twig']->render('cuisines.html.twig', array('cuisine' => $cuisine, 'restaurants' => $cuisine->getRestaurants()));

    });

    $app->post('/cuisines', function() use($app) {
        $cuisine = new Cuisines($_POST['name']);
        $cuisine->save();
        return $app['twig']->render('index.html.twig', array('cuisine' => Cuisine::getAll()));
    });

    $app->get('/cuisines/{id}/edit', function($id) use ($app){
        $cuisine = Cuisine::find($id);
        return $app['twig']->render('cuisine_edit.html.twig', array('cuisine'=>$cuisine));
    });

    $app->post('/delete_cuisines', function() use($app) {
        Cuisine::deleteAll();
        return $app['twig']->render('index.html.twig', array('cuisine'=>Cuisine::getAll()));
    });

    $app->post('/delete_restaurants', function() use($app){
        Cuisine::deleteAll();
        return $app['twig']->render('index.html.twig', array('cuisines'=> Cuisine::getAll()));
    });

    $app->delete("/cuisines/{id}", function($id) use ($app) {
        $cuisine = Cuisine::find($id);
        $cuisine->delete();
        return $app['twig']->render('index.html.twig', array('cuisines' => Cuisine::getAll()));
    });

    $app->delete("/restaurants/{id}", function($id) use ($app) {
        $restaurant = Restaurant::find($id);
        $restaurant->delete();
        return $app['twig']->render('index.html.twig', array('restaurants' => Restaurant::getAll()));
    });

    return $app;
?>
