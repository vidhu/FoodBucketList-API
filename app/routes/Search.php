<?php
$app->get('/search/{term}', function($req, $res, $args){
    
    $yelp = $this->getContainer()->get('yelp');

    $options = array(
      'term' => $args['term'], 
      'location' => 'Boston, MA'
    );
   
    $results = $yelp->search($options);
    $trimmed_results = array();
    foreach ($results->businesses as $business){
        $trimmed_results[] = array(
            'id' => $business->id,
            'name' => $business->name
        );
    }

    
    if(isset($_GET['callback'])){
        echo $_GET['callback']."([".json_encode($trimmed_results)."])";
    }else{
        echo json_encode($trimmed_results);
    }
    
});