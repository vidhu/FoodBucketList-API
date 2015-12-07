<?php
$app->get('/search/{term}', function($req, $res, $args){
    
    $yelp = $this->get('yelp');

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
        echo $_GET['callback']."(".json_encode($trimmed_results).")";
    }else{
        echo json_encode($trimmed_results);
    }
    
});

$app->get('/search/id/{id}', function($req, $res, $args){
   
    //Get yelp API Object
    $yelp = $this->get('yelp');
    
    //Get json data of business with specified id
    try{
        $results = $yelp->getBusiness(urldecode($args['id']));
    }catch(Exection $e){
        echo makeResult(false, "Business doesn't exist");
    }
    
    //Return json/jsonp content
    if(isset($_GET['callback'])){
        echo $_GET['callback']."(".json_encode($results).")";
    }else{
        echo json_encode($results);
    }
});