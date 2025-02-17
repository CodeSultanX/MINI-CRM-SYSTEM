<?php
namespace controllers\home;

use models\page\PageModel;
use models\todo\task\TaskModel;
use models\user\UserModel;

class HomeController{
    public function __construct()
    {
        $userModel = new UserModel();
        $userModel->createRoleTable();
        $userModel->createUserTable();

        $pages = new PageModel();

        if($pages->createPageTable()){
            $pages->insertPages();
        }
    }

    public function index(){
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $TaskModel = new TaskModel();
        $tasks = $TaskModel->getAllUrgenttasks($user_id);
        $tasks = json_encode($tasks);
        include'app/views/home/index.php';
    }

    

}