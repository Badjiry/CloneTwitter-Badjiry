<?php
 
class Login {
    private $db;
 
    public function __construct(PDO $db)   {
        $this->db = $db;
    }
 
        public function verifEmail ($email) {
    $query = "SELECT COUNT(*) FROM user WHERE email = :email";
    $stmt= $this->db ->prepare($query);
    $stmt->execute(['email' => $email]);
    return $stmt ->fetchColumn()>0;
    }
 
    public function verifConnexion($email, $password) {
        $query = "SELECT id, email, password FROM user WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
   
        if (!$user) {
            exit();
        }
   
    if (trim($password) == trim($user['password'])) {            
        return $user;
        }
    }
 
   
    public function InfoUser($id) {
        $query='SELECT u.* FROM user u
        WHERE u.id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
   
 
}
