<?php
namespace controllers\pages;
use models\CheckModel;
use models\page\PageModel;
use models\role\RoleModel;


class PageController{

    private $check;

    public function __construct()
    {
        $role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $this->check = new CheckModel($role);
    }
    public function index(){
        $this->check->requiredPermissions();
        $pagesModel = new PageModel();
        $pages = $pagesModel->getAllPages();
        include'app/views/page/index.php';
    }

    public function create(){
        $this->check->requiredPermissions();
        $roleModel = new RoleModel();
        $roles = $roleModel->getAllRoles();
        include'app/views/page/create.php';
    }

    public function store(){
        $this->check->requiredPermissions();
        if(isset($_POST['title']) && isset($_POST['slug']) && isset($_POST['roles']) ){
            $pagesModel = new PageModel();

            if(empty($_POST['title']) || empty($_POST['slug']) || empty($_POST['roles'])){
                echo 'Поля не должны быть пустыми!!';
                return;
            }

            $roles = implode(',',$_POST['roles']);
            $roles = filter_var_array($_POST['roles'],FILTER_SANITIZE_NUMBER_INT);
            $roles = implode(',',$roles);
            
            $data = [
                'title' => trim(htmlspecialchars($_POST['title'])),
                'slug' => trim(htmlspecialchars($_POST['slug'])),
                'roles' => $roles
            ];
            $pagesModel->store($data);

        }

        header("Location: /pages");
    }

    public function delete($params){
        $this->check->requiredPermissions();
        $pagesModel = new PageModel();
        $pagesModel->delete($params['id']);
        header("Location: /pages");
    }

    public function edit($params){
        $this->check->requiredPermissions();
        $pagesModel = new PageModel();
        $roleModel = new RoleModel();
        $page = $pagesModel->getPageById($params['id']);
        $roles = $roleModel->getAllRoles();
        include'app/views/page/edit.php';
    }

    public function update($params){
        $this->check->requiredPermissions();
        $pagesModel = new PageModel();

        if(empty($_POST['title']) || empty($_POST['slug']) || empty($_POST['roles'])){
            echo 'Поля не должны быть пустыми!!';
            return;
        }
        if(isset($_POST['roles'])){
            $_POST['roles'] = implode(',',$_POST['roles']);
        }

        $_POST['title'] = trim(htmlspecialchars($_POST['title']));
        $_POST['slug'] = trim(htmlspecialchars($_POST['slug']));
        if($pagesModel->update($params['id'],$_POST)){
            header("Location: /pages");
        }
    }

}