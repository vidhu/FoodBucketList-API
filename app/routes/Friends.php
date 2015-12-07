<?php

$app->get('/friends', function($req, $res, $args){
    $userID = $args['userid'];
    
    $fb = $this-get('FB');
    
    /* @var $db mysqli */
    $db = $this->get('DB');
    
    /* @var $stmt mysqli_stmt */
    $stmt = $db->prepare("SELECT `id`, `name`, `description` FROM `bucket` WHERE `user_id` = ?");
    $stmt->bind_param('s', $userID);
    $stmt->execute();
    $stmt->bind_result($id, $name, $description);
    
    $buckets = array();
    while($stmt->fetch()){
        $bucket['id'] = $id;
        $bucket['name'] = $name;
        $bucket['description'] = $description;
        array_push($buckets, $bucket);
    }
    
    echo makeResult(true, $buckets);
})->add(new FBAuthMiddleWare($app));

