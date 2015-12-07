<?php

require_once '../app/vendor/autoload.php';
require_once '../app/middleware/FBAuthMiddleWare.php';

//Dependency injection container
$container = new \Slim\Container();

//Yelp API
$container['yelp'] = function($container) {
    $client = new Stevenmaguire\Yelp\Client(array(
        'consumer_key' => 'OLM18X5Gse5bdiTcTDe3SQ',
        'consumer_secret' => 'ryd5BeZBQxDtYRU98OLSKkzsM-4',
        'token' => 'RwUFD2h4Ty760lmwHLJ8BRQGGk_DkDc5',
        'token_secret' => 'Yuzt1JSEyLyiLRMBYQqKIn_ek0Q',
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
    $res = $res->withHeader('Access-Control-Allow-Origin', '*');
    $res = $res->withHeader('Access-Control-Max-Age', '1000');
    $res = $res->withHeader('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, OPTIONS');
    
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
