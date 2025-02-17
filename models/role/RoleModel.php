<?php
namespace models\role;
use models\database\DatabaseModel;


class RoleModel{
    private $db;

    public function __construct()
    {   
        $this->db = DatabaseModel::getInstance()->getConnection();

        try{
            $result = $this->db->query('SELECT 1 FROM users LIMIT 1');
        }catch(\PDOException $e){
            $this->createRoleTable();
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

    public function getAllRoles(){
        try{
            $result  = $this->db->query('SELECT * FROM roles');
            return $result ? $result->fetchAll(\PDO::FETCH_ASSOC) : [];
        }catch(\PDOException $e){
            die('Ошибка при получение пользователей' .$e->getMessage());
        }
    }

    public function store($data){
            $sql = 'INSERT INTO roles (role_name,role_description) VALUES(:role_name, :role_description)';
            try{
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':role_name',$data['role_name']);
                $stmt->bindParam(':role_description',$data['role_description']);
                $stmt->execute();
                return true;
            }catch(\PDOException $e){
                return false;
            }
    }

    public function delete($id){
        try{
            $stmt = $this->db->prepare('DELETE FROM roles WHERE id = :id');
            $stmt->bindParam('id',$id);
            $stmt->execute();
            return true;
        }catch(\PDOException $e){
            return false;
        }
    }

    public function getRoleById($id){
        try{
            $stmt = $this->db->prepare('SELECT * FROM roles WHERE id = :id');
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }catch(\PDOException $e){
            return false;
        }
    }

    public function update($id,$data){
        try{
            $stmt = $this->db->prepare('UPDATE roles SET role_name = :role_name , role_description = :role_description WHERE id = :id');
            $stmt->bindParam(':id',$id);
            $stmt->bindParam(':role_name',$data['role_name']);
            $stmt->bindParam(':role_description',$data['role_description']);
            $stmt->execute();
            return true;
        }catch(\PDOException $e){
            return false;
        }
    }
}
