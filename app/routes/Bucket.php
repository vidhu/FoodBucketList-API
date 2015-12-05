<?php

//Get all buckets
$app->get('/bucket', function($req, $res, $args){
    $userID = $args['userid'];
    
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

//Get items in bucket
$app->get('/bucket/{id}', function($req, $res, $args){
    $userID = $args['userid'];
    $bucketID = $args['id'];
    
    /* @var $db mysqli */
    $db = $this->get('DB');
    
    //Check if user owns bucket or that the bucket exists or not
    if(!isOwnerofBucket($db, $userID, $bucketID)){
        echo makeResult(false, "Bucket doesn't exist of not owner of bucket");
        return;
    }
    
    /* @var $stmt mysqli_stmt */
    $stmt = $db->prepare("SELECT `business_id` FROM `bucketlist` WHERE `bucket_id` = ?");
    $stmt->bind_param('s', $bucketID);
    $stmt->execute();
    $stmt->bind_result($businessID);
    
    $items = array();
    while($stmt->fetch()){
        array_push($items, $businessID);
    }
    echo makeResult(true, $items);
    
    
})->add(new FBAuthMiddleWare($app));


//Add a new bucket
$app->post('/bucket', function($req, $res, $args){
    if(empty($_POST['bucket-name'])){
        echo "bucket-name should be specified";
        return $res->withStatus(400);
    }
    
    $userID = $args['userid'];
    $bucketName = $_POST['bucket-name'];
    $description = (!empty($_POST['bucket-description'])) ? $_POST['bucket-description'] : "";
    
    /* @var $db mysqli */
    $db = $this->get('DB');
    /* @var $stmt mysqli_stmt */
    $stmt = $db->prepare("INSERT INTO `bucket`(`user_id`, `name`, `description`) VALUES (?, ?, ?);");
    $stmt->bind_param('sss', $userID, $bucketName, $bucketName);
    $stmt->execute();
    
    if($db->error){
        echo makeResult(false, "MySQL error: ".$db->errno);
    }else{
        echo makeResult(true, "Bucket successfully created");
    }
})->add(new FBAuthMiddleWare($app));

//Add item in bucket
$app->post('/bucket/{id}', function($req, $res, $args){
    if(empty($_POST['businessID'])){
        echo makeResult(false, "businessID should be specified");
        return;
    }
    
    $userID = $args['userid'];
    $bucketID = $args['id'];
    $businessID = $_POST['businessID'];
    
    /* @var $db mysqli */
    $db = $this->get('DB');
    
    //Check if user owns bucket or that the bucket exists or not
    if(!isOwnerofBucket($db, $userID, $bucketID)){
        echo makeResult(false, "Bucket doesn't exist of not owner of bucket");
        return;
    }
    
    //Add item in bucket
    $stmt = $db->prepare("INSERT INTO `bucketlist`(`bucket_id`, `business_id`) VALUES (?, ?)");
    $stmt->bind_param('ss', $bucketID, $businessID);
    $stmt->execute();
    
    
    //Send result
    if($db->error){
        echo makeResult(false, "MySQL error: ".$db->errno." Probably item already exists");
    }else{
        echo makeResult(true, "Added to bucket successfully created");
    }
    
})->add(new FBAuthMiddleWare($app));

//Add a new bucket
$app->put('/bucket/{id}', function($req, $res, $args){
    var_dump($_REQUEST);
    if(empty($_POST['bucket-name'])){
        echo "bucket-name should be specified";
        return $res->withStatus(400);
    }
    
    $userID = $args['userid'];
    $bucketID = $args['id'];
    $bucketName = $_POST['bucket-name'];
    $description = (!empty($_POST['bucket-description'])) ? $_POST['bucket-description'] : "";
    
    /* @var $db mysqli */
    $db = $this->get('DB');
    
    //Check if user owns bucket or that the bucket exists or not
    if(!isOwnerofBucket($db, $userID, $bucketID)){
        echo makeResult(false, "Bucket doesn't exist of not owner of bucket");
        return;
    }
    
    /* @var $stmt mysqli_stmt */
    $stmt = $db->prepare("UPDATE `bucket` SET `name` = ?, `description = ? WHERE `id` = ?;");
    $stmt->bind_param('sss', $bucketName, $description, $bucketID);
    $stmt->execute();
    
    if($db->error){
        echo makeResult(false, "MySQL error: ".$db->errno);
    }else{
        echo makeResult(true, "Bucket updated successfully");
    }
})->add(new FBAuthMiddleWare($app));

//Delete a bucket
$app->delete('/bucket/{id}', function($req, $res, $args){
    $userID = $args['userid'];
    $bucketID = $args['id'];
    
    /* @var $db mysqli */
    $db = $this->get('DB');
    
    //Check if user owns bucket or that the bucket exists or not
    if(!isOwnerofBucket($db, $userID, $bucketID)){
        echo makeResult(false, "Bucket doesn't exist of not owner of bucket");
        return;
    }
    
    //Delete item in bucket
    $stmt = $db->prepare("DELETE FROM `bucket` WHERE `id` = ?");
    $stmt->bind_param('s', $bucketID);
    $stmt->execute();
    
    //Send result
    if($db->error){
        echo makeResult(false, "MySQL error: ".$db->errno);
    }else{
        echo makeResult(true, "Deleted bucket successfully");
    }
})->add(new FBAuthMiddleWare($app));;

//Delete item in bucket
$app->delete('/bucket/{id}/{businessid}', function($req, $res, $args){
    $userID = $args['userid'];
    $bucketID = $args['id'];
    $businessID = $args['businessid'];
    
    /* @var $db mysqli */
    $db = $this->get('DB');
    
    //Check if user owns bucket or that the bucket exists or not
    if(!isOwnerofBucket($db, $userID, $bucketID)){
        echo makeResult(false, "Bucket doesn't exist of not owner of bucket");
        return;
    }
    
    //Delete item in bucket
    $stmt = $db->prepare("DELETE FROM `bucketlist` WHERE `bucket_id` = ? AND `business_id` = ?");
    $stmt->bind_param('ss', $bucketID, $businessID);
    $stmt->execute();
    
    //Send result
    if($db->error){
        echo makeResult(false, "MySQL error: ".$db->errno);
    }else{
        echo makeResult(true, "Deleted from bucket successfully");
    }
    
})->add(new FBAuthMiddleWare($app));