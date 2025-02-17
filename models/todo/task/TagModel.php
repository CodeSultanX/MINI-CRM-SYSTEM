<?php
namespace models\todo\task;
use models\database\DatabaseModel;


class TagModel{
    private $db;

    public function __construct()
    {   
        $this->db = DatabaseModel::getInstance()->getConnection();

        try{
            $result = $this->db->query('SELECT 1 FROM tags LIMIT 1');
        }catch(\PDOException $e){
            $this->createTables();
        }
    }

   
    public function createTables(){
        $query = "CREATE TABLE IF NOT EXISTS `tags` (
            `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT(11),
            `name` VARCHAR(255) NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id));
            CREATE TABLE IF NOT EXISTS `task_tags` (
            `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `task_id` INT NOT NULL,
            `tag_id` INT NOT NULL,
            FOREIGN KEY (task_id) REFERENCES todo_list(id),
            FOREIGN KEY (tag_id) REFERENCES tags(id)
        );";
            try{
                $this->db->exec($query);
                return true;
            }catch(\PDOException $e){
                return false;
            }
    }

   
    public function createTag($user_id,$tagName){
            $sql = 'INSERT INTO tags (name,user_id) 
            VALUES(:name,:user_id)';
            try{
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':name',$tagName);
                $stmt->bindParam(':user_id',$user_id);
                $stmt->execute();
                return $this->db->lastInsertId();
            }catch(\PDOException $e){
                return false;
            }
    }
    public function create_task_tags($task_id,$tag_id){
            $sql = 'INSERT INTO task_tags (task_id,tag_id) 
            VALUES(:task_tags,:tag_id)';
            try{
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':task_tags',$task_id);
                $stmt->bindParam(':tag_id',$tag_id);
                $stmt->execute();
                return true;
            }catch(\PDOException $e){
                return false;
            }
    }

    

    public function remove_unused_tags($tag_id){
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM task_tags WHERE tag_id = :tag_id ');
        $stmt->bindParam(':tag_id',$tag_id);
        $stmt->execute();
        $count = $stmt->fetch(\PDO::FETCH_ASSOC);
        try{
            if($count == 0){
                $stmt = $this->db->prepare('DELETE FROM tags WHERE id = :id');
                $stmt->bindParam(':id',$tag_id);
                $stmt->execute();
                return true;
            }
        }catch(\PDOException $e){
            return false;
        }
    }
    public function removeAllTaskTags($task_id){
        try{
            $stmt = $this->db->prepare('DELETE FROM task_tags WHERE task_id = :task_id');
            $stmt->bindParam(':task_id',$task_id);
            $stmt->execute();
            return true;
        }catch(\PDOException $e){
            return false;
        }
    }

    public function get_tag_by_name_user_id($tagName,$user_id){
        try{
            $stmt = $this->db->prepare('SELECT * FROM tags WHERE name = :name AND user_id = :user_id');
            $stmt->bindParam(':name',$tagName);
            $stmt->bindParam(':user_id',$user_id);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }catch(\PDOException $e){
            return false;
        }
    }
    public function get_tag_name_by_id($id){
        try{
            $stmt = $this->db->prepare('SELECT name FROM tags WHERE id = :id');
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            $tag = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $tag ? $tag['name'] : '';
        }catch(\PDOException $e){
            return false;
        }
    }
   
    public function get_tags_by_task_id($task_id) {
        $query = "SELECT tags.* FROM tags
        JOIN task_tags ON tags.id = task_tags.tag_id
        WHERE task_tags.task_id = :task_id";
        
        try{
            $stmt = $this->db->prepare($query);
            $stmt->execute(['task_id' => $task_id]);
    
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch(\PDOException $e){
            return false;
        }
    }


   
}
