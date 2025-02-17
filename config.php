<?php
function tt($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}
function tte($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    exit();
}
CONST PERMISSION_MANAGMENT = true;
CONST DB_NAME = 'mini_crm_system';
CONST DB_HOST = 'localhost';
CONST DB_USER = 'root';
CONST DB_PASS = 'root';
CONST DB_PORT = 8899;
CONST DB_CHARSET = 'utf8mb4';