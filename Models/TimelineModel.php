<?php

class TimelineModel {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getTimelineTweets($userId) {
        try {
            $query = "SELECT 
                t.id AS tweet_id, 
                t.content, 
                t.creation_date AS tweet_date,
                t.media1,
                t.media2,
                t.media3,
                t.media4,
                u.id AS user_id, 
                u.firstname, 
                u.lastname, 
                u.username, 
                u.display_name, 
                u.picture,
                r.creation_date AS retweet_date,
                ru.username AS retweeted_by,
                CASE 
                    WHEN r.id_user IS NOT NULL THEN 1 
                    ELSE 0 
                END AS is_retweet
            FROM tweet t
            JOIN user u ON t.id_user = u.id
            LEFT JOIN follow f ON t.id_user = f.id_user_followed AND f.id_user_follow = :userId
            LEFT JOIN retweet r ON r.id_tweet = t.id 
            LEFT JOIN user ru ON r.id_user = ru.id
            WHERE 
                f.id_user_follow = :userId 
                OR t.id_user = :userId 
                OR r.id_user = :userId
            ORDER BY 
                CASE 
                    WHEN r.creation_date IS NOT NULL THEN r.creation_date 
                    ELSE t.creation_date 
                END DESC";
    
            $stmt = $this->db->prepare($query);
            $stmt->execute(['userId' => $userId]);
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur SQL : " . $e->getMessage());
        }
    }
        
    public function addTweet($id, $content, $media = []) {
        try {
            $query = "INSERT INTO tweet (id_user, content, media1, media2, media3, media4, creation_date)
                      VALUES (:id, :content, :media1, :media2, :media3, :media4, NOW())"; 
    
            $stmt = $this->db->prepare($query);
    
            $media1 = $media[0] ?? null;
            $media2 = $media[1] ?? null;
            $media3 = $media[2] ?? null;
            $media4 = $media[3] ?? null;
    
            $stmt->execute([
                'id' => $id,
                'content' => $content,
                'media1' => $media1,
                'media2' => $media2,
                'media3' => $media3,
                'media4' => $media4
            ]);
    
            return true;
        } catch (PDOException $e) { 
            die("Erreur SQL : " . $e->getMessage());
        }
    }


    public function deleteTweet($tweet_id, $user_id) {
        try {
            $query = "DELETE FROM tweet WHERE id = :tweet_id AND id_user = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'tweet_id' => $tweet_id,
                'user_id' => $user_id
            ]);
            return $stmt->rowCount() > 0; 
        } catch (PDOException $e) {
            error_log("Erreur SQL dans deleteTweet() : " . $e->getMessage());
            return false;
        }
    }  

    public function RetweetsUser($userId, $tweetId) {
        try {
            $checkQuery = "SELECT COUNT(*) FROM retweet WHERE id_user = :userId AND id_tweet = :tweetId";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->execute([
                'userId' => $userId,
                'tweetId' => $tweetId
            ]);
    
            if ($checkStmt->fetchColumn() > 0) {
                return false;
                        }
    
            $query = "INSERT INTO retweet (id_user, id_tweet, creation_date) 
                      VALUES (:userId, :tweetId, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'userId' => $userId,
                'tweetId' => $tweetId
            ]);
    
            return $stmt->rowCount() > 0;
    
        } catch (PDOException $e) {
            die("Erreur SQL : " . $e->getMessage());
        }
    }
    
    public function unRetweetUser($userId, $tweetId) {
        try {
            $query = "DELETE FROM retweet WHERE id_user = :userId AND id_tweet = :tweetId";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'userId' => $userId,
                'tweetId' => $tweetId
            ]);
    
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            die("Erreur SQL : " . $e->getMessage());
        }
    }

    public function subscribe($followerId, $followedId) {
        try {
            $query = "INSERT INTO follow (id_user_follow, id_user_followed) VALUES (:followerId, :followedId)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':followerId', $followerId, PDO::PARAM_INT);
            $stmt->bindParam(':followedId', $followedId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function unsubscribe($followerId, $followedId) {
        try {
            $query = "DELETE FROM follow WHERE id_user_follow = :followerId AND id_user_followed = :followedId";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':followerId', $followerId, PDO::PARAM_INT);
            $stmt->bindParam(':followedId', $followedId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}