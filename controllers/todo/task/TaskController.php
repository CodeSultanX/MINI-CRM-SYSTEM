<?php
namespace controllers\todo\task;

use DateTime;
use models\CheckModel;
use models\todo\category\CategoryModel;
use models\todo\task\TagModel;
use models\todo\task\TaskModel;

class TaskController{
    private $check;
    private $tagsModel;
    private $user_id;

    public function __construct()
    {
        $role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $this->check = new CheckModel($role);
        $this->tagsModel = new TagModel();
        $this->user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    }
    public function index(){
        $this->check->requiredPermissions();
        $TaskModel = new TaskModel();
        $tasks = $TaskModel->getAllUrgenttasks($this->user_id);
        $CategoryModel = new CategoryModel();
        $tasks = array_map(function($task) use ($CategoryModel) {
            $task['category'] = $CategoryModel->getCategoryById($task['category_id']);
            $task['tags'] = $this->tagsModel->get_tags_by_task_id($task['id']);
            return $task;
        }, $tasks);

        include'app/views/todo/task/index.php';
    }

    public function expired(){
        $this->check->requiredPermissions();
        $TaskModel = new TaskModel();
        $ExpiredTasks = $TaskModel->getAllExpiredtasks($this->user_id);
        $CategoryModel = new CategoryModel();
        $ExpiredTasks = array_map(function($task) use ($CategoryModel) {
            $task['category'] = $CategoryModel->getCategoryById($task['category_id']);
            $task['tags'] = $this->tagsModel->get_tags_by_task_id($task['id']);
            return $task;
        }, $ExpiredTasks);
        include'app/views/todo/task/expired.php';
    }
    public function completed(){
        $this->check->requiredPermissions();
        $TaskModel = new TaskModel();
        $CompletedTasks = $TaskModel->getAllCompletedtasks($this->user_id);
        $CategoryModel = new CategoryModel();
        $CompletedTasks = array_map(function($task) use ($CategoryModel) {
            $task['category'] = $CategoryModel->getCategoryById($task['category_id']);
            $task['tags'] = $this->tagsModel->get_tags_by_task_id($task['id']);
            return $task;
        }, $CompletedTasks);
        include'app/views/todo/task/completed.php';
    }

    public function create(){
        $this->check->requiredPermissions();
        $CategoryModel = new CategoryModel();
        $categories = $CategoryModel->getAllCategoriesWithTasks();
        include'app/views/todo/task/create.php';
    }

    public function store(){
        $this->check->requiredPermissions();
        if(isset($_POST['title']) && isset($_POST['category_id']) && isset($_POST['finish_date'])){
            $TaskModel = new TaskModel();
            $_POST['user_id'] = $this->user_id;
           
            $data = [
                'title' => trim(htmlspecialchars($_POST['title'])),
                'user_id' => $_POST['user_id'],
                'category_id' => trim(htmlspecialchars($_POST['category_id'])),
                'finish_date' => $_POST['finish_date'],
                'status' => 'new',
                'priority' => 'low',
            ];

            $TaskModel->create($data);

        }
        header("Location: /todo/tasks");
    }

    public function delete($params){
        $this->check->requiredPermissions();
        $TaskModel = new TaskModel();
        $TaskModel->delete($params['id']);
        header("Location: /todo/task");
    }

    public function edit($params){
        $this->check->requiredPermissions();
        $TaskModel = new TaskModel();
        $CategoryModel = new CategoryModel();
        $task = $TaskModel->getOneTaskByUserId($params['id'],$this->user_id);
        if(!($task)){
            http_response_code(404);
            include'app/views/error/error.php';
            exit();
        }
        $categories = $CategoryModel->getAllCategoriesWithTasks();
        $tags = $this->tagsModel->get_tags_by_task_id($task['id']);
        if(!$task){
            echo "Task not found";
            return;
        }
        include'app/views/todo/task/edit.php';
    }

    public function update(){
        $this->check->requiredPermissions();
        $TaskModel = new TaskModel();

        if(isset($_POST['title']) && isset($_POST['category_id']) && isset($_POST['finish_date'])){

            if(empty($_POST['title']) && empty($_POST['description'])){
                echo 'Найменование и описание не должны быть пустыми';
                return;
            }

            $data['id'] = $_POST['id'];
            $data['category_id'] = trim(htmlspecialchars($_POST['category_id']));
            $data['status'] = trim(htmlspecialchars($_POST['status']));
            $data['priority'] = trim(htmlspecialchars($_POST['priority']));
            $data['finish_date'] = $_POST['finish_date'];
            $data['title'] = trim(htmlspecialchars($_POST['title']));
            $data['description'] = trim(htmlspecialchars($_POST['description']));

            $remidered_at_option = $_POST['reminder_at'];
            $finish_date_value = $_POST['finish_date'];
            $finish_date = new \DateTime($finish_date_value);
            switch($remidered_at_option){
                case '30_minutes':
                    $interval = new \DateInterval('PT30M');
                    break;
                case '1_hour':
                    $interval = new \DateInterval('PT1H');
                    break;
                case '2_hours':
                    $interval = new \DateInterval('PT2H');
                    break;
                case '12_hours':
                    $interval = new \DateInterval('PT12H');
                    break;
                case '24_hours':
                    $interval = new \DateInterval('P1D');
                    break;
                case '7_days':
                    $interval = new \DateInterval('P7D');
                    break;
            }

            $remindered_at = $finish_date->sub($interval);
            $data['reminder_at'] = $remindered_at->format('Y-m-d\TH:i');
            //Обновление задачи в базе
            $TaskModel->update($data);

            //Работа с тегами

            $tags = explode(',',$_POST['tags']);
            $tags = array_map('trim',$tags);

            $oldTags = $this->tagsModel->get_tags_by_task_id($data['id']);
            $this->tagsModel->removeAllTaskTags($data['id']);

            foreach($tags as $tag_name){
                $tag = $this->tagsModel->get_tag_by_name_user_id($tag_name,$this->user_id);
                //Проверяем если этот тег нет у этого юзера то добавляем в таблицу 
                if(!$tag){
                    $tag_id = $this->tagsModel->createTag($this->user_id,$tag_name);
                }else{
                    $tag_id = $tag['id'];
                }

                $this->tagsModel->create_task_tags($data['id'],$tag_id);
            }

            //Дропаем старые теги
            foreach($oldTags as $oldTag){
                $this->tagsModel->remove_unused_tags($oldTag['id']);
            }

        }
        header("Location: /todo/tasks/task/{$data['id']}");
    }


    public function tasksBytag($params){
        $this->check->requiredPermissions();
        $TaskModel = new TaskModel();

        $tasks = $TaskModel->getTasksByTagId($params['id'],$this->user_id);
        $CategoryModel = new CategoryModel();
        $tasks = array_map(function($task) use ($CategoryModel) {
            $task['category'] = $CategoryModel->getCategoryById($task['category_id']);
            $task['tags'] = $this->tagsModel->get_tags_by_task_id($task['task_id']);
            return $task;
        }, $tasks);

        $tagname = $this->tagsModel->get_tag_name_by_id($params['id']);

        include'app/views/todo/task/by-tag.php';
    }

    public function updateStatus($params){
        $this->check->requiredPermissions();
        $id = $params['id'];
        $status = trim(htmlspecialchars($_POST['status']));

        $datetime = null;
        if($status == 'completed'){
            $datetime = date('Y-m-d H:i:s');
        }

        $TaskModel = new TaskModel();
        if($TaskModel->updateStatusTask($id,$status,$datetime)){
            header("Location: /todo/tasks");
        }

    }

    public function task($params){
        $this->check->requiredPermissions();
        $TaskModel = new TaskModel();

        $task = $TaskModel->getOneTaskByUserId($params['id'],$this->user_id);
        if(!($task)){
            http_response_code(404);
            include'app/views/error/error.php';
            exit();
        }
        $CategoryModel = new CategoryModel();
        $category = $CategoryModel->getCategoryById($task['category_id']);
        $tags = $this->tagsModel->get_tags_by_task_id($params['id']);

        include'app/views/todo/task/task.php';
    }

  

}