<?php

require_once '../Config/dbconnect.php';
require_once '../Models/LoginModel.php';

$loginModel = new Login($db);

Class Profile{
    private $db;
 
    public function __construct(PDO $db)   {
        $this->db = $db;
    }
   
 
    public function Followers($id){
        $query ="SELECT u.id, u.username, u.firstname, u.lastname, u.display_name
                FROM follow f
                JOIN user u ON f.id_user_follow = u.id
                WHERE f.id_user_followed = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);   
    }
 
 
    public function Following($id) {
        $query = "SELECT u.id, u.username, u.firstname, u.lastname, u.display_name
                  FROM follow f
                  JOIN user u ON f.id_user_followed = u.id
                  WHERE f.id_user_follow = :id";
    
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function CountFollowing($id) {
        $query = "SELECT COUNT(*) AS count FROM follow WHERE id_user_follow = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
    }
    

    public function CountFollowers($id) {
        $query = "SELECT COUNT(*) AS count FROM follow WHERE id_user_followed = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0; 
    }



    public function TweetsUser($userId) {
        try {
            $query = "
                (SELECT 
                    t.id AS tweet_id, 
                    t.id_user AS user_id, 
                    u.username,
                    u.firstname,
                    u.lastname,
                    u.display_name,
                    u.picture,
                    t.content, 
                    t.creation_date AS tweet_date, 
                    t.media1, t.media2, t.media3, t.media4,
                    NULL AS retweeted_by,
                    0 AS is_retweet,  
                    'tweet' AS type
                FROM tweet t
                JOIN user u ON t.id_user = u.id
                WHERE t.id_user = :id)
    
                UNION ALL
    
                (SELECT 
                    t.id AS tweet_id, 
                    r.id_user AS user_id,
                    u.username,
                    u.firstname,
                    u.lastname,
                    u.display_name,
                    u.picture,
                    t.content, 
                    r.creation_date AS tweet_date,  
                    t.media1, t.media2, t.media3, t.media4,
                    ru.username AS retweeted_by,
                    1 AS is_retweet,  
                    'retweet' AS type
                FROM retweet r
                JOIN tweet t ON r.id_tweet = t.id
                JOIN user u ON t.id_user = u.id
                JOIN user ru ON r.id_user = ru.id
                WHERE r.id_user = :id)
    
                ORDER BY tweet_date DESC
            ";
    
            $stmt = $this->db->prepare($query);
            $stmt->execute(['id' => $userId]);
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur SQL : " . $e->getMessage());
        }
    }
    
    public function getUserByUsername($username) {
        $query = "SELECT * FROM user WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function UpdateBio($id, $biography) {
        $query = "UPDATE user SET biography = :biography WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['biography' => $biography, 'id' => $id]);
    }
    
    public function UpdateDisplayName($id,$display_name) {
        $query = "UPDATE user SET display_name = :display_name WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['display_name' => $display_name, 'id' => $id]);
    }

    public function UpdateFirstName($id,$firstName) {
        $query = "UPDATE user SET firstname = :firstName WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['firstName' => $firstName, 'id' => $id]);
    }

    public function UpdateLastName($id,$lastName) {
        $query = "UPDATE user SET lastname = :lastName WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['lastName' => $lastName, 'id' => $id]);
    }

    public function UpdateUrl($id,$url) {
        $query = "UPDATE user SET url = :url WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['url' => $url, 'id' => $id]);
    }

    public function UpdateProfilePicture($id,$picture) {
        $query = "UPDATE user SET picture = :picture WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['picture' => $picture, 'id' => $id]);
    }

    public function UpdateHeader($id, $header) {
        $query = "UPDATE user SET header = :header WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['header' => $header, 'id' => $id]);
    }
    
}
