<?php
namespace controllers\roles;
use models\CheckModel;
use models\role\RoleModel;

class RoleController{
    private $check;

    public function __construct()
    {
        $role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $this->check = new CheckModel($role);
    }
    public function index(){
        $this->check->requiredPermissions();
        $roleModel = new RoleModel();
        $roles = $roleModel->getAllRoles();
        include'app/views/role/index.php';
    }

    public function create(){
        $this->check->requiredPermissions();
        include'app/views/role/create.php';
    }

    public function store(){
        $this->check->requiredPermissions();
        if(isset($_POST['role_name']) && isset($_POST['role_description'])){
            $roleModel = new RoleModel();
            if(empty($_POST['role_name']) && empty($_POST['role_description'])){
                echo 'Поля не должны быть пустыми!!';
                return;
            }
            
            $data = [
                'role_name' => trim(htmlspecialchars($_POST['role_name'])),
                'role_description' => trim(htmlspecialchars($_POST['role_description'])),
            ];
            $roleModel->store($data);

        }
        header("Location: /roles");
    }

    public function delete($params){
        $this->check->requiredPermissions();
        $roleModel = new RoleModel();
        $roleModel->delete($params['id']);
        header("Location: /roles");
    }

    public function edit($params){
        $this->check->requiredPermissions();
        $roleModel = new RoleModel();
        $role = $roleModel->getRoleById($params['id']);
        include'app/views/role/edit.php';
    }

    public function update($params){
        $this->check->requiredPermissions();
        $roleModel = new RoleModel();

        if(empty($_POST['role_name']) && empty($_POST['role_description'])){
            echo 'Поля не должны быть пустыми!!';
            return;
        }
        $_POST['role_name'] = trim(htmlspecialchars($_POST['role_name']));
        $_POST['role_description'] = trim(htmlspecialchars($_POST['role_description']));
        if($roleModel->update($params['id'],$_POST)){
            header("Location: /roles");
        }
    }

}