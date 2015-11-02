<?php
$app->get('/search/{term}', function($req, $res, $args){
    
    $yelp = $this->getContainer()->get('yelp');

    $options = array(
      'term' => $args['term'], 
      'location' => 'Boston, MA'
    );
   
    $results = $yelp->search($options);
    echo (isset($_GET['callback'])?$_GET['callback']:'')."([".json_encode($results)."])";
});