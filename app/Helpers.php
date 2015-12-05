<?php

/**
 * Checks to see if the specified bucket is 
 * owned by the specified user
 * 
 * @param mysqli $db
 * @param type $userID
 * @param type $bucketID
 * 
 * retrun True if user is owner
 */
function isOwnerofBucket($db, $userID, $bucketID){
    
    /* @var $stmt mysqli_stmt */
    $stmt = $db->prepare(
        "SELECT COUNT(*) FROM `bucket`
        WHERE `bucket`.`user_id` = ? AND `bucket`.`id` = ?"
    );
    $stmt->bind_param('ss', $userID, $bucketID);
    $stmt->execute();
    $stmt->bind_result($count);
    while($stmt->fetch()){
        return $count > 0;
    }
    
    return false;
}

function makeResult($success, $msg){
    $results['success'] = $success;
    $results['result'] = $msg;
    
    if(isset($_GET['callback'])){
        return $_GET['callback']."(".json_encode($results).")";
    }else{
        return json_encode($results);
    }
}

