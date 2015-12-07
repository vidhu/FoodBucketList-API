<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

require_once '../app/vendor/autoload.php';
require_once '../config.php';
require_once '../app/lib/AchievementManager.php';
require_once '../app/middleware/FBAuthMiddleWare.php';

//Dependency injection container
$container = new \Slim\Container();

//Yelp API
$container['yelp'] = function($container) {
    $client = new Stevenmaguire\Yelp\Client(array(
        'consumer_key' => $GLOBALS['yelp']['consumer_key'],
        'consumer_secret' => $GLOBALS['yelp']['consumer_secret'],
        'token' => $GLOBALS['yelp']['token'],
        'token_secret' => $GLOBALS['yelp']['token_secret'],
        'api_host' => $GLOBALS['yelp']['api_host']
    ));
    $client->setDefaultLocation('Boston, MA')
            ->setDefaultTerm('Sushi')
            ->setSearchLimit(10);
    return $client;
};

$container['fb'] = function($container) {
    $fb = new Facebook\Facebook([
        'app_id' => $GLOBALS['fb']['app_id'],
        'app_secret' => $GLOBALS['fb']['app_secret'],
        'default_graph_version' => 'v2.2',
    ]);
    return $fb;
};

//Database Instance
$container['DB'] = function($container) {
    //Create new database connection
    $db = new mysqli(
        $GLOBALS['mysql']['host'],
        $GLOBALS['mysql']['username'],
        $GLOBALS['mysql']['password'],
        $GLOBALS['mysql']['database']
    );

    //Die if error
    if ($db->connect_errno > 0) {
        die('Unable to connect to data [' . $db->connect_error . ']');
    }

    //Return instance of database
    return $db;
};

//Setup the application
$app = new \Slim\App($container);
$app->add(function($req, $res, $next){
    /* @var $res \Slim\Http\Response */
    $res = $next($req, $res);
    $res = $res->withHeader('Content-type', 'application/json');
    return $res;
});
$app->get('/', function() {
    echo "Home Page";
});

//Helpers
require_once '../app/Helpers.php';

//Routes
require_once '../app/routes/Search.php';
require_once '../app/routes/Bucket.php';
require_once '../app/routes/Achievement.php';

$app->run();
