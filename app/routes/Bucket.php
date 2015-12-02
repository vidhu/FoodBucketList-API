<?php

$app->get('/bucket/{id}', function($req, $res, $args){
    
    $db = $this->getContainer()->get('DB');
    
    echo "hi!";
});

