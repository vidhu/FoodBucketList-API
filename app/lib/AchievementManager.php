<?php

/**
 * Description of AchievementManager
 *
 * @author vidhu
 */
class AchievementManager {

    /**
     *
     * @var mysqli
     */
    protected $db;
    protected $userid;


    public function __construct($db, $userid) {
        $this->db = $db;
        $this->userid = $userid;
    }


    public function getAchievements(){
        /* @var $stmt mysqli_stmt */
        $stmt = $this->db->prepare("SELECT `count` FROM `achievements` WHERE `user_id` = ?");
        $stmt->bind_param('s', $this->userid);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows == 0){
            return 0;
        }
        $stmt->bind_result($count);
        while($stmt->fetch()){
            return $count;
        }
    }
    
    public function addAchievements(){
        /* @var $stmt mysqli_stmt */
        $stmt = $this->db->prepare(
            "INSERT INTO `achievements` (`user_id`, `count`) VALUES (?, 1)
            ON DUPLICATE KEY UPDATE `count` = `count` + 1"
        );
        $stmt->bind_param('s', $this->userid);
        $stmt->execute();
        

        return $this->getAchievements();
    }
}
