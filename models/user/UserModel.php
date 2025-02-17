<?php
namespace models\user;
use models\database\DatabaseModel;

class UserModel{
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

    private function adminUserExists(){
        $query = "SELECT COUNT(*) FROM `users` WHERE `username` = 'Admin' AND `is_admin` = 1";
        $stmt = $this->db->query($query);
        return $stmt->fetchColumn() > 0;
    }
    private function roleExist(){
        $query = "SELECT COUNT(*) FROM `roles`";
        $stmt = $this->db->query($query);
        return $stmt->fetchColumn() > 0;
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
                if(!$this->adminUserExists()){
                    $insertAdminQuery = "INSERT INTO `users` (`username`, `email`, `password`, `is_admin`, `role`) VALUES
                    ('Admin', 'admin@gmail.com', '\$2y\$10\$dySccJEuCWDzywOgSoSU.eafBWHBXWp0/Nd7gohVz1z6mw1QzbEjW', 1, (SELECT `id` FROM `roles` WHERE `role_name` = 'Administrator'));";
                    $this->db->exec($insertAdminQuery);
                }
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
                if(!$this->roleExist()){
                    $insertRolesQuery = "INSERT INTO `roles` (`role_name`, `role_description`) VALUES
                    ('Subscriber', 'Может только читать статьи и оставлять комментарии, но не имеет права создавать свой контент или управлять сайтом.'),
                    ('Editor', 'Доступ к управлению и публикации статей, страниц и других контентных материалов на сайте. Редактор также может управлять комментариями и разрешать или запрещать их публикацию.'),
                    ('Author', 'Может создавать и публиковать собственные статьи, но не имеет возможности управлять контентом других пользователей.'),
                    ('Contributor', 'Может создавать свои собственные статьи, но они не могут быть опубликованы до одобрения администратором или редактором.'),
                    ('Administrator', 'Полный доступ ко всем функциям сайта, включая управление пользователями, плагинами а также создание и публикация статей.');";
                    $this->db->exec($insertRolesQuery);
                }
                return true;
            }catch(\PDOException $e){
                return false;
            }
        
    }

    public function readAll(){
        try{
            $result  = $this->db->query('SELECT * FROM users');
            return $result ? $result->fetchAll(\PDO::FETCH_ASSOC) : [];
        }catch(\PDOException $e){
            die('Ошибка при получение пользователей' .$e->getMessage());
        }
    }

    public function store($data){
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

    public function delete($id){
        try{
            $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
            $stmt->bindParam('id',$id);
            $stmt->execute();
            return true;
        }catch(\PDOException $e){
            return false;
        }
    }

    public function getUser($id){
        try{
            $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }catch(\PDOException $e){
            return false;
        }
    }

    public function update($id,$data){
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $data['is_admin'] = $data['role'] == 2 ? 1 : 0;
        try{
            $stmt = $this->db->prepare('UPDATE users SET username = :username , email = :email, is_admin = :is_admin, is_active = :is_active, role = :role WHERE id = :id');
            $stmt->bindParam(':id',$id);
            $stmt->bindParam(':username',$data['username']);
            $stmt->bindParam(':email',$data['email']);
            $stmt->bindParam(':role',$data['role']);
            $stmt->bindParam(':is_active',$data['is_active']);
            $stmt->bindParam(':is_admin',$data['is_admin']);
            $stmt->execute();
            return true;
        }catch(\PDOException $e){
            return false;
        }
    }
}
