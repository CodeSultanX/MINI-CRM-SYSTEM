<?php
namespace models\todo\category;
use models\database\DatabaseModel;


class CategoryModel{
    private $db;
    private $user_id;

    public function __construct()
    {   
        $this->db = DatabaseModel::getInstance()->getConnection();
        $this->user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
        try{
            $result = $this->db->query('SELECT 1 FROM todo_category LIMIT 1');
        }catch(\PDOException $e){
            $this->createCategoryTable();
        }
    }

   
    public function createCategoryTable(){
        $query = "CREATE TABLE IF NOT EXISTS `todo_category` (
            `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(255) NOT NULL,
            `description` TEXT,
            `usability` TINYINT DEFAULT 1,
            `user_id` INT(11) NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )";
            try{
                $this->db->exec($query);
                return true;
            }catch(\PDOException $e){
                return false;
            }
    }

    public function getAllCategories(){
        try{
            $result  = $this->db->prepare('SELECT * FROM todo_category WHERE user_id = :user_id');
            $result->bindParam('user_id',$this->user_id);
            $result->execute();
            return $result ? $result->fetchAll(\PDO::FETCH_ASSOC) : [];
        }catch(\PDOException $e){
            die('Ошибка при получение категорий' .$e->getMessage());
        }
    }
    public function getAllCategoriesWithTasks(){
        try{
            $result  = $this->db->prepare('SELECT * FROM todo_category WHERE user_id = :user_id AND usability = 1');
            $result->bindParam('user_id',$this->user_id);
            $result->execute();
            return $result ? $result->fetchAll(\PDO::FETCH_ASSOC) : [];
        }catch(\PDOException $e){
            die('Ошибка при получение категорий' .$e->getMessage());
        }
    }

    public function store($data){
            $sql = 'INSERT INTO todo_category (title,description,user_id) VALUES(:title, :description,:user_id)';
            try{
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':title',$data['title']);
                $stmt->bindParam(':description',$data['description']);
                $stmt->bindParam(':user_id',$data['user_id']);
                $stmt->execute();
                return true;
            }catch(\PDOException $e){
                return false;
            }
    }

    public function delete($id){
        try{
            $stmt = $this->db->prepare('DELETE FROM todo_category WHERE id = :id');
            $stmt->bindParam('id',$id);
            $stmt->execute();
            return true;
        }catch(\PDOException $e){
            return false;
        }
    }

    public function getCategoryById($id){
        try{
            $stmt = $this->db->prepare('SELECT * FROM todo_category WHERE id = :id');
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }catch(\PDOException $e){
            return false;
        }
    }

    public function update($id,$data){
        $data['usability'] = isset($data['usability']) ? $data['usability'] : 0;
        try{
            $stmt = $this->db->prepare('UPDATE todo_category SET title = :title , description = :description,usability = :usability WHERE id = :id');
            $stmt->bindParam(':id',$id);
            $stmt->bindParam(':title',$data['title']);
            $stmt->bindParam(':description',$data['description']);
            $stmt->bindParam(':usability',$data['usability']);
            $stmt->execute();
            return true;
        }catch(\PDOException $e){
            return false;
        }
    }
}
