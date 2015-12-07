<?php

/* @var $app \Slim\App */
$app->get('/achievement', function($req, $res, $args) {
    $userID = $args['userid'];

    $ach = new AchievementManager($this->get('DB'), $userID);
    $count = $ach->getAchievements();
    
    echo makeResult(true, $count);
})->add(new FBAuthMiddleWare($app));

$app->post('/achievement', function($req, $res, $args) {
    $userID = $args['userid'];

    $ach = new AchievementManager($this->get('DB'), $userID);
    $count = $ach->addAchievements();
    
    echo makeResult(true, $count);
})->add(new FBAuthMiddleWare($app));
