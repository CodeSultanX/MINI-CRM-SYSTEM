<?php
namespace models\page;
use models\database\DatabaseModel;


class PageModel{
    private $db;

    public function __construct()
    {   
        $this->db = DatabaseModel::getInstance()->getConnection();

        try{
            $result = $this->db->query('SELECT 1 FROM pages LIMIT 1');
        }catch(\PDOException $e){
            $this->createPageTable();
        }
    }

   
    public function createPageTable(){
        $query = "
            CREATE TABLE IF NOT EXISTS `pages`(
            `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(255) NOT NULL,
            `slug` VARCHAR(255) NOT NULL,
            `role` VARCHAR(255) NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ";
            try{
                $this->db->exec($query);
                return true;
            }catch(\PDOException $e){
                return false;
            }
        
    }
    public function insertPages(){
        $insertPagesQuery = "INSERT INTO `pages` (`id`, `title`, `slug`, `role`, `created_at`, `updated_at`) VALUES
        (1, 'Home', '/', '1,2,3,4,5', NOW(), NOW()),
        (2, 'Users', 'users', '1,2,5', NOW(), NOW()),
        (3, 'Pages', 'pages', '5', NOW(), NOW()),
        (4, 'User edit', 'users/edit', '2,5', NOW(), NOW()),
        (5, 'User create', 'users/create', '3,4,5', NOW(), NOW()),
        (6, 'Users store', 'users/store', '3,4,5', NOW(), NOW()),
        (7, 'Users update', 'users/update', '5', NOW(), NOW()),
        (8, 'Roles', 'roles', '2,3,4,5', NOW(), NOW()),
        (9, 'Roles create', 'roles/create', '3,4,5', NOW(), NOW()),
        (10, 'Roles store', 'roles/store', '3,4,5', NOW(), NOW()),
        (11, 'Roles edit', 'roles/edit', '3,4,5', NOW(), NOW()),
        (12, 'Roles update', 'roles/update', '5', NOW(), NOW()),
        (13, 'Pages update', 'pages/update', '5', NOW(), NOW()),
        (14, 'Users delete', 'users/delete', '5', NOW(), NOW()),
        (15, 'Todo category create', 'todo/category/create', '3,4,5', NOW(), NOW()),
        (16, 'Todo category edit', 'todo/category/edit', '3,4,5', NOW(), NOW()),
        (17, 'Todo category', 'todo/category', '3,4,5', NOW(), NOW()),
        (18, 'Todo category store', 'todo/category/store', '3,4,5', NOW(), NOW()),
        (19, 'Todo category delete', 'todo/category/delete', '3,4,5', NOW(), NOW()),
        (20, 'Todo category update', 'todo/category/update', '3,4,5', NOW(), NOW()),
        (21, 'Tasks', 'todo/tasks', '3,4,5', NOW(), NOW()),
        (22, 'Task create', 'todo/tasks/create', '3,4,5', NOW(), NOW()),
        (23, 'Todo task store', 'todo/tasks/store', '3,4,5', NOW(), NOW()),
        (24, 'Tasks update', 'todo/tasks/update', '3,4,5', NOW(), NOW()),
        (25, 'Tasks delete', 'todo/taska/delete', '3,4,5', NOW(), NOW()),
        (26, 'Tasks edit', 'todo/tasks/edit', '3,4,5', NOW(), NOW()),
        (27, 'Tasks completed', 'todo/tasks/completed', '3,4,5', NOW(), NOW()),
        (28, 'Tasks update status', 'todo/tasks/update-status', '3,4,5', NOW(), NOW()),
        (29, 'Expired tasks', 'todo/tasks/expired', '3,4,5', NOW(), NOW()),
        (30, 'Pages create', 'pages/create', '5', NOW(), NOW()),
        (31, 'Pages edit', 'pages/edit', '5', NOW(), NOW()),
        (32, 'Pages delete', 'pages/delete', '5', NOW(), NOW()),
        (33, 'Pages store', 'pages/store', '5', NOW(), NOW()),
        (34, 'Roles delete', 'roles/delete', '5', NOW(), NOW()),
        (35, 'Todo tasks task', 'todo/tasks/task', '2,3,4,5', NOW(), NOW()),
        (36, 'Todo tasks  by tag', 'todo/tasks/by-tag', '2,3,4,5', NOW(), NOW());";
        try{
            $this->db->exec($insertPagesQuery);
            return true;
        } catch (\PDOException $e){
            return false;
        }
    }

    public function getAllPages(){
        try{
            $result  = $this->db->query('SELECT * FROM pages');
            return $result ? $result->fetchAll(\PDO::FETCH_ASSOC) : [];
        }catch(\PDOException $e){
            die('Ошибка при получение пользователей' .$e->getMessage());
        }
    }

    public function store($data){
            $sql = 'INSERT INTO pages (title,slug,role) VALUES(:title, :slug,:role)';
            try{
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':title',$data['title']);
                $stmt->bindParam(':slug',$data['slug']);
                $stmt->bindParam(':role',$data['roles']);
                $stmt->execute();
                return true;
            }catch(\PDOException $e){
                return false;
            }
           
    }

    public function delete($id){
        try{
            $stmt = $this->db->prepare('DELETE FROM pages WHERE id = :id');
            $stmt->bindParam('id',$id);
            $stmt->execute();
            return true;
        }catch(\PDOException $e){
            return false;
        }
    }

    public function getPageById($id){
        try{
            $stmt = $this->db->prepare('SELECT * FROM pages WHERE id = :id');
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }catch(\PDOException $e){
            return false;
        }
    }
    public function getPageBySlug($slug){
        try{
            $stmt = $this->db->prepare('SELECT * FROM pages WHERE slug = :slug');
            $stmt->bindParam(':slug',$slug);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }catch(\PDOException $e){
            return false;
        }
    }

    public function update($id,$data){
        try{
            $stmt = $this->db->prepare('UPDATE pages SET title = :title , slug = :slug , role = :role WHERE id = :id');
            $stmt->bindParam(':id',$id);
            $stmt->bindParam(':title',$data['title']);
            $stmt->bindParam(':slug',$data['slug']);
            $stmt->bindParam(':role',$data['roles']);
            $stmt->execute();
            return true;
        }catch(\PDOException $e){
            return false;
        }
    }
}
