<?php
namespace models\todo\task;
use models\database\DatabaseModel;


class TaskModel{
    private $db;

    public function __construct()
    {   
        $this->db = DatabaseModel::getInstance()->getConnection();

        try{
            $result = $this->db->query('SELECT 1 FROM todo_list LIMIT 1');
        }catch(\PDOException $e){
            $this->createTasksTable();
        }
    }

   
    public function createTasksTable(){
        $query = "CREATE TABLE IF NOT EXISTS `todo_list` (
            `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT(11) NOT NULL,
            `title` VARCHAR(255) NOT NULL,
            `description` TEXT,
            `category_id` INT(11) NOT NULL,
            `status` ENUM('new', 'in_progress', 'completed', 'on_hold', 'cancelled') NOT NULL,
            `priority` ENUM('low', 'medium', 'high', 'urgent') NOT NULL,
            `assigned_to` INT,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `finish_date` DATETIME,
            `completed_at` DATETIME,
            `reminder_at` DATETIME,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )";
            try{
                $this->db->exec($query);
                return true;
            }catch(\PDOException $e){
                return false;
            }
    }

    public function getAllUrgenttasks($user_id){
        try{
            $result  = $this->db->prepare("SELECT * FROM todo_list WHERE user_id = :user_id
             AND status != 'completed' 
             AND finish_date > NOW()
             ORDER BY ABS(TIMESTAMPDIFF(SECOND,NOW(),finish_date))
             ");
            $result->bindParam(':user_id',$user_id);
            $result->execute();
            return $result ? $result->fetchAll(\PDO::FETCH_ASSOC) : [];
        }catch(\PDOException $e){
            die('Ошибка при получение категорий' .$e->getMessage());
        }
    }
    public function getAllCompletedtasks($user_id){
        try{
            $result  = $this->db->prepare("SELECT * FROM todo_list WHERE user_id = :user_id
             AND status = 'completed' 
             ORDER BY ABS(TIMESTAMPDIFF(SECOND,NOW(),finish_date))
             ");
            $result->bindParam(':user_id',$user_id);
            $result->execute();
            return $result ? $result->fetchAll(\PDO::FETCH_ASSOC) : [];
        }catch(\PDOException $e){
            die('Ошибка при получение категорий' .$e->getMessage());
        }
    }
    public function getAllExpiredtasks($user_id){
        try{
            $result  = $this->db->prepare("SELECT * FROM todo_list WHERE user_id = :user_id
             AND finish_date < NOW()
             ORDER BY ABS(TIMESTAMPDIFF(SECOND,NOW(),finish_date))
             ");
            $result->bindParam(':user_id',$user_id);
            $result->execute();
            return $result ? $result->fetchAll(\PDO::FETCH_ASSOC) : [];
        }catch(\PDOException $e){
            die('Ошибка при получение категорий' .$e->getMessage());
        }
    }

    public function create($data){
            $sql = 'INSERT INTO todo_list (title,user_id,category_id,status, priority, finish_date) 
            VALUES(:title,:user_id,:category_id,:status, :priority, :finish_date)';
            try{
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':title',$data['title']);
                $stmt->bindParam(':user_id',$data['user_id']);
                $stmt->bindParam(':category_id',$data['category_id']);
                $stmt->bindParam(':status',$data['status']);
                $stmt->bindParam(':priority',$data['priority']);
                $stmt->bindParam(':finish_date',$data['finish_date']);
                $stmt->execute();
                return true;
            }catch(\PDOException $e){
                return false;
            }
    }

    public function delete($id){
        try{
            $stmt = $this->db->prepare('DELETE FROM todo_list WHERE id = :id');
            $stmt->bindParam('id',$id);
            $stmt->execute();
            return true;
        }catch(\PDOException $e){
            return false;
        }
    }

    
    public function getOneTaskByUserId($id,$user_id){
        try{
            $stmt = $this->db->prepare('SELECT * FROM todo_list WHERE id = :id AND user_id = :user_id');
            $stmt->bindParam(':id',$id);
            $stmt->bindParam(':user_id',$user_id);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }catch(\PDOException $e){
            return false;
        }
    }
    public function getTasksByTagId($tag_id,$user_id){
        try{
            $stmt = $this->db->prepare('SELECT * FROM todo_list 
            JOIN task_tags ON task_id = todo_list.id
            WHERE user_id = :user_id AND tag_id = :tag_id');
            $stmt->bindParam(':user_id',$user_id);
            $stmt->bindParam(':tag_id',$tag_id);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }catch(\PDOException $e){
            return false;
        }
    }

    public function update($data){
        try{
            $stmt = $this->db->prepare(
                'UPDATE todo_list SET title = :title , description = :description,category_id = :category_id,
                                      status = :status,priority = :priority,finish_date = :finish_date,
                                      reminder_at = :reminder_at WHERE id = :id');
            $stmt->bindParam(':id',$data['id']);
            $stmt->bindParam(':title',$data['title']);
            $stmt->bindParam(':description',$data['description']);
            $stmt->bindParam(':category_id',$data['category_id']);
            $stmt->bindParam(':status',$data['status']);
            $stmt->bindParam(':priority',$data['priority']);
            $stmt->bindParam(':finish_date',$data['finish_date']);
            $stmt->bindParam(':reminder_at',$data['reminder_at']);
            $stmt->execute();
            return true;
        }catch(\PDOException $e){
            return false;
        }
    }

    public function updateStatusTask($id,$status,$datetime){
        try{
            if($datetime == null){
                $stmt = $this->db->prepare(
                    'UPDATE todo_list SET status = :status WHERE id = :id');
                $stmt->bindParam(':id',$id);
                $stmt->bindParam(':status',$status);
            }else{
                $stmt = $this->db->prepare(
                    'UPDATE todo_list SET status = :status,completed_at = :completed_at,finish_date = :finish_date WHERE id = :id');
                $stmt->bindParam(':id',$id);
                $stmt->bindParam(':status',$status);
                $stmt->bindParam(':completed_at',$datetime);
                $stmt->bindParam(':finish_date',$datetime);
            }
          
            $stmt->execute();
            return true;
        }catch(\PDOException $e){
            return "Ошибка обновление статуса задачи";
        }
    }
}
