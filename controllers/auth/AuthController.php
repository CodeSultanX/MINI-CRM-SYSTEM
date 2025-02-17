<?php
namespace controllers\auth;
use models\auth\AuthUserModel;

class AuthController{

    public function login(){
        include'app/views/auth/login.php';
    }

    public function register(){
        include'app/views/auth/register.php';
    }

    public function store(){
        if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_repeat'])){
            $userModel = new AuthUserModel();

            if(empty($_POST['username']) && empty($_POST['email']) && empty($_POST['password']) && empty($_POST['password_repeat'])){
                echo 'Поля не должны быть пустыми!!';
                return;
            }

            if($_POST['password'] !== $_POST['password_repeat']){
                echo 'Пароли должны совпадать!!';
                return;
            }
           
            
            $data = [
                'username' => trim(htmlspecialchars($_POST['username'])),
                'email' => trim(filter_var($_POST['email'],FILTER_SANITIZE_EMAIL)),
                'password' => password_hash(trim($_POST['password']),PASSWORD_DEFAULT),
                'role' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $userModel->register($data);

        }
        header("Location: /auth/login");
    }

    public function authontificate(){
        if(isset($_POST['email']) && isset($_POST['password'])){
            $userModel = new AuthUserModel();

            if(empty($_POST['email']) && empty($_POST['password'])){
                echo 'Поля не должны быть пустыми!!';
                return;
            }
            
            $remember = isset($_POST['remember']) ? $_POST['remember'] : '';
            $email = trim(htmlspecialchars($_POST['email']));
            $user = $userModel->findByEmail($email);
            if($user && password_verify($_POST['password'],$user['password'])){
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_name'] = $user['username'];

                if($remember == 'on'){
                    setcookie('user_email',$_POST['email'],time() + (7 * 24 * 60 * 60), '/');
                    setcookie('user_password',$_POST['password'],time() + (7 * 24 * 60 * 60), '/');
                }

                header("Location: /");
            }else{
                echo 'Email или пароль неверный';
                return; 
            }
        }
    }

    public function logout(){
        session_start();
        session_unset();
        session_destroy();
        header("Location: /auth/register");
    }

    

   

}