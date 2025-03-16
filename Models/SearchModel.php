<?php

class Search {
    private $db;
 
    public function __construct(PDO $db) {
        $this->db = $db;
    }
    
    public function search($search) {
        try {
            $query = "SELECT tweet.*, user.username, user.picture 
                      FROM tweet 
                      INNER JOIN user ON tweet.id_user = user.id 
                      WHERE tweet.content LIKE :search OR user.username LIKE :search
                      ORDER BY tweet.creation_date DESC";
    
            $stmt = $this->db->prepare($query);
            $searchTerm = '%' . $search . '%';
            $stmt->execute(['search' => $searchTerm]);
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur SQL dans search() : " . $e->getMessage());
            return [];
        }
    }
    
    public function searchUsers($search) {
        try {
            $query = "SELECT id, username, display_name, firstname, lastname, picture 
                      FROM user 
                      WHERE username LIKE :search OR firstname LIKE :search OR lastname LIKE :search
                      ORDER BY username ASC";
    
            $stmt = $this->db->prepare($query);
            $searchTerm = '%' . $search . '%';
            $stmt->execute(['search' => $searchTerm]);
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur SQL dans searchUsers() : " . $e->getMessage());
            return [];
        }
    }

    public function isFollowing($currentUserId, $targetUserId) {
        $query = "SELECT COUNT(*) FROM follow WHERE id_user_follow = :currentUser AND id_user_followed = :targetUser";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'currentUser' => $currentUserId,
            'targetUser' => $targetUserId
        ]);
        return $stmt->fetchColumn() > 0;
    }
}