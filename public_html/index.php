<?php
require_once '../app/vendor/autoload.php';


//Dependency injection container
$container = new \Slim\Container();
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
        ->setSearchLimit(5);
    return $client;
};

//Setup the application
$app = new \Slim\App($container);
$app->get('/', function(){
   echo "Home Page"; 
});

//Routes
require_once '../app/routes/Search.php';


$app->run();