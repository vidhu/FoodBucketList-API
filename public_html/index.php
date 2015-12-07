<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

require_once '../app/vendor/autoload.php';
require_once '../app/middleware/FBAuthMiddleWare.php';

//Dependency injection container
$container = new \Slim\Container();

//Yelp API
$container['yelp'] = function($container) {
    $client = new Stevenmaguire\Yelp\Client(array(
        'consumer_key' => 'Kvn1dhfh4V1t5D_B0hj1yw',
        'consumer_secret' => '7TQE7UFvF0eypix1huTr3KBIN8o',
        'token' => 'BkeIzCexlPmW5jKWvb4xgvqp4ABpDCmU',
        'token_secret' => 'fIjoMWwwxgyNWDfVwC_rEYbdIzU',
        'api_host' => 'api.yelp.com'
    ));
    $client->setDefaultLocation('Boston, MA')
            ->setDefaultTerm('Sushi')
            ->setSearchLimit(10);
    return $client;
};

$container['fb'] = function($container) {
    $fb = new Facebook\Facebook([
        'app_id' => '1206232049392333',
        'app_secret' => '61e82c053df81c514fe890ad42678eb1',
        'default_graph_version' => 'v2.2',
    ]);
    return $fb;
};

//Database Instance
$container['DB'] = function($container) {
    //Create new database connection
    $db = new mysqli('nom.crxozhxstjvk.us-east-1.rds.amazonaws.com', 'nom', 'nomnom', 'nom_db');

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

$app->run();
