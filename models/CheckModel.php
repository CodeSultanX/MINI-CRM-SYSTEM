<?php
namespace models;

use models\page\PageModel;


class CheckModel{
    private $user_id;
    private $user_role;

    public function __construct($role,$user_id = null)
    {
        $this->user_id = $user_id;
        $this->user_role = $role;
    }  

    public function getCurrentSlugUrl(){
        $uri = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $parseUrl = parse_url($uri);
        $path = $parseUrl['path'];
        $segments = explode('/',ltrim($path,"/"));
        if(count($segments) > 2){
            //Если последний элем id шник то обрезаем его из slug
            $length = $segments[2] == (int) $segments[2] ? 2 : 3;
        }else{
            $length = 2;
        }
        $firstTwosegments = array_slice($segments,0,$length);
        $slug = implode('/',$firstTwosegments);
        return $slug;
    }

    public function getCurrentUser($user_id){
        return $this->user_id == $user_id;
    }

    public function requiredPermissions(){
        if(!PERMISSION_MANAGMENT){
            return;
        }
        $slug = $this->getCurrentSlugUrl();
        $pageModel = new PageModel();
        $page = $pageModel->getPageBySlug($slug);
        if(!$page){
            header("Location: /");
            exit();
        }
        $roles = explode(',',$page['role']);
        if(!in_array($this->user_role,$roles)){
            header("Location: /");
        }
    }


}