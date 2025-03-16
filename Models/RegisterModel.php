<?php

class Register {
    private $db;
 
    public function __construct(PDO $db) {
        $this->db = $db;
    }
 
    public function verifEmail($email) {
        $query = "SELECT COUNT(*) FROM user WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['email' => $email]);
        return $stmt->fetchColumn() > 0;
    }
 
    public function registerUser($email, $firstname, $lastname, $username, $birthdate, $city, $country, $password, $genre, $creation_date) {
    
        $birthDateObj = new DateTime($birthdate);
        $today = new DateTime();
        $age = $today->diff($birthDateObj)->y;

        if ($age < 13) {
            return ["error" => "Vous devez avoir au moins 13 ans pour vous inscrire."];
        }

        
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
        try {
            $this->db->beginTransaction();
    
            $query = "INSERT INTO user (email, firstname, lastname, username, birthdate, city, country, password, genre, creation_date)
                      VALUES (:email, :firstname, :lastname, :username, :birthdate, :city, :country, :password, :genre, :creation_date)";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'email' => $email,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'username' => $username,
                'birthdate' => $birthdate,
                'city' => $city,
                'country' => $country,
                'password' => $hashedPassword,
                'genre' => $genre,
                'creation_date' => $creation_date
            ]);
    
            $userId = $this->db->lastInsertId();
            $this->db->commit();
            return ["success" => "Inscription réussie.", "user_id" => $userId];
    
        } catch (Exception $e) {
            $this->db->rollBack();
            return ["error" => "Erreur SQL : " . $e->getMessage()];
        }
    }
}
