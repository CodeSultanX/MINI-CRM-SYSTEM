<?php
namespace controllers\todo\category;
use models\CheckModel;
use models\todo\category\CategoryModel;


class CategoryController{
    private $check;

    public function __construct()
    {
        $role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $this->check = new CheckModel($role);
    }
    public function index(){
        $this->check->requiredPermissions();
        $CategoryModel = new CategoryModel();
        $categories = $CategoryModel->getAllCategories();
        include'app/views/todo/category/index.php';
    }

    public function create(){
        $this->check->requiredPermissions();
        include'app/views/todo/category/create.php';
    }

    public function store(){
        $this->check->requiredPermissions();
        if(isset($_POST['title']) && isset($_POST['description'])){
            $CategoryModel = new CategoryModel();
            if(empty($_POST['title']) && empty($_POST['description'])){
                echo 'Поля не должны быть пустыми!!';
                return;
            }
            $_POST['user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
            $data = [
                'title' => trim(htmlspecialchars($_POST['title'])),
                'description' => trim(htmlspecialchars($_POST['description'])),
                'user_id' => $_POST['user_id'],
            ];
            $CategoryModel->store($data);

        }
        header("Location: /todo/category");
    }

    public function delete($params){
        $this->check->requiredPermissions();
        $CategoryModel = new CategoryModel();
        $CategoryModel->delete($params['id']);
        header("Location: /todo/category");
    }

    public function edit($params){
        $this->check->requiredPermissions();
        $CategoryModel = new CategoryModel();
        $category = $CategoryModel->getCategoryById($params['id']);
        include'app/views/todo/category/edit.php';
    }

    public function update($params){
        $this->check->requiredPermissions();
        $CategoryModel = new CategoryModel();

        if(empty($_POST['title']) && empty($_POST['description'])){
            echo 'Поля не должны быть пустыми!!';
            return;
        }

        $_POST['title'] = trim(htmlspecialchars($_POST['title']));
        $_POST['description'] = trim(htmlspecialchars($_POST['description']));

        if($CategoryModel->update($params['id'],$_POST)){
            header("Location: /todo/category");
        }
    }

}