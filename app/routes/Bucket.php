<?php

$app->get('/bucket/{id}', function($req, $res, $args){
    //$db = $this->getContainer()->get('DB');
    var_dump($this->get('DB'));
    
    echo "Return bucket list";
});

$app->post('/bucket', function($req, $res, $args){
    if(empty($_POST['bucket-name'])){
        echo "back-name should be specified";
        return $res->withStatus(400);
    }
    $db = $this->getContainer()->get('DB');
    $bucketName = $_POST['bucket-name'];
    
    echo "Create new bucket list with name: $bucketName";
});
