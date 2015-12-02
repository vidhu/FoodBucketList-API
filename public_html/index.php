<?php
require_once '../app/vendor/autoload.php';


//Dependency injection container
$container = new \Slim\Container();

//Yelp API
$container['yelp'] = function($container){
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

//Database Instance
$container['DB'] = function($container){
    //Create new database connection
    $db = new mysqli('nom.crxozhxstjvk.us-east-1.rds.amazonaws.com:3306', 'nom', 'nomnom', 'nom_db');
    
    //Die if error
    if($db->connect_errno > 0){
        die('Unable to connect to data [' . $db->connect_error . ']');
    }
    
    //Return instance of database
    return $db;
};

//Setup the application
$app = new \Slim\App($container);
$app->get('/', function(){
   echo "Home Page"; 
});

//Routes
require_once '../app/routes/Search.php';


$app->run();