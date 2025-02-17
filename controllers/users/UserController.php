<?php
namespace controllers\users;

use models\CheckModel;
use models\role\RoleModel;
use models\user\UserModel;
class UserController{

    private $check;

    public function __construct()
    {
        $role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $this->check = new CheckModel($role,$user_id);
    }

    public function index(){
        $this->check->requiredPermissions();
        $userModel = new UserModel();
        $users = $userModel->readAll();
        include'app/views/user/index.php';
    }

    public function create(){
        $this->check->requiredPermissions();
        include'app/views/user/create.php';
    }

    public function store(){
        $this->check->requiredPermissions();
        if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_repeat'])){
            $userModel = new UserModel();
            if($_POST['password'] !== $_POST['password_repeat']){
                echo 'Пароли должны совпадать!!';
                return;
            }

            if(empty($_POST['username']) && empty($_POST['email']) && empty($_POST['password']) && empty($_POST['password_repeat'])){
                echo 'Поля не должны быть пустыми!!';
                return;
            }
            
            $data = [
                'username' => trim(htmlspecialchars($_POST['username'])),
                'email' => trim(htmlspecialchars($_POST['email'])),
                'password' => password_hash($_POST['password'],PASSWORD_DEFAULT),
                'role' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $userModel->store($data);

        }
        header("Location: /users");
    }

    public function delete($params){
        $this->check->requiredPermissions();
        $userModel = new UserModel();
        $userModel->delete($params['id']);
        header("Location: /users");
    }

    public function edit($params){
        $this->check->requiredPermissions();
        $userModel = new UserModel();
        $roleModle = new RoleModel();
        $user = $userModel->getUser($params['id']);
        $roles = $roleModle->getAllRoles();
        include'app/views/user/edit.php';
    }

    public function update($params){
        $this->check->requiredPermissions();
        $userModel = new UserModel();

        if(empty($_POST['username']) && empty($_POST['email']) && empty($_POST['role'])){
            echo 'Поля не должны быть пустыми!!';
            return;
        }

        $_POST['username'] = trim(htmlspecialchars($_POST['username']));
        $_POST['email'] = trim(htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'));
        $_POST['role'] = trim(htmlspecialchars($_POST['role']));

        $userModel->update($params['id'],$_POST);
        if($this->check->getCurrentUser($params['id'])){

            if(isset($_POST['role'])){
                $_SESSION['user_role'] = $_POST['role'];
            }
            header("Location: /auth/logout");
            exit();
        }
        header("Location: /users");
       
    }

}