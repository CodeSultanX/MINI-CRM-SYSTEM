<?php
namespace models\auth;
use models\database\DatabaseModel;


class AuthUserModel{
    private $db;

    public function __construct()
    {   
        $this->db = DatabaseModel::getInstance()->getConnection();

        try{
            $result = $this->db->query('SELECT 1 FROM users LIMIT 1');
        }catch(\PDOException $e){
            $this->createRoleTable();
            $this->createUserTable();
        }
    }

    public function createUserTable(){
        $query = "
            CREATE TABLE IF NOT EXISTS `users`(
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `username` VARCHAR(255) NOT NULL,
            `email` VARCHAR(255) NOT NULL,
            `email_verification` TINYINT(1) NULL DEFAULT 0,
            `password` VARCHAR(255) NOT NULL,
            `is_admin` TINYINT(1)  NULL DEFAULT 0,
            `role` INT(11) NOT NULL DEFAULT 0,
            `is_active` TINYINT(1) NULL DEFAULT 1,
            `last_login` TIMESTAMP NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`role`) REFERENCES `roles`(`id`)
            )";
            try{
                $this->db->exec($query);
                return true;
            }catch(\PDOException $e){
                return false;
            }
        
    }
    public function createRoleTable(){
        $query = "
            CREATE TABLE IF NOT EXISTS `roles`(
            `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `role_name` VARCHAR(255) NOT NULL,
            `role_description` TEXT
            )";
            try{
                $this->db->exec($query);
                return true;
            }catch(\PDOException $e){
                return false;
            }
        
    }


    public function register($data){
            $sql = 'INSERT INTO users (username,email,role,password,created_at) VALUES(:username, :email, :role, :password, :created_at)';
            try{
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':username',$data['username']);
                $stmt->bindParam(':email',$data['email']);
                $stmt->bindParam(':role',$data['role']);
                $stmt->bindParam(':password',$data['password']);
                $stmt->bindParam(':created_at',$data['created_at']);
                $stmt->execute();
                return true;
            }catch(\PDOException $e){
                return false;
            }
           
    }


    public function findByEmail($email){
        try{
            $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
            $stmt->bindParam(':email',$email);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }catch(\PDOException $e){
            return false;
        }
    }

   
}
