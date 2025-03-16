<?php

class MessageModel {
    private $db;
 
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function sendMessage($id_sender, $id_receiver, $content, $media = null) {
        $query = "INSERT INTO message (id_sender, id_receiver, content, media, date) VALUES (:id_sender, :id_receiver, :content, :media, NOW())";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'id_sender' => $id_sender, 
            'id_receiver' => $id_receiver, 
            'content' => $content, 
            'media' => $media]);
    }

    public function getMessages($id_sender, $id_receiver) {
        $query = "SELECT * FROM message 
                  WHERE (id_sender = ? AND id_receiver = ?) 
                  OR (id_sender = ? AND id_receiver = ?) 
                  ORDER BY date ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id_sender, $id_receiver, $id_receiver, $id_sender]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function markAsViewed($message_id) {
        $query = "UPDATE message SET is_viewed = 1 WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$message_id]);
    }
}
?>
