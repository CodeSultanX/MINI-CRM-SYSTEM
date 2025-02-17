<?php

function is_active($uri){
    $current_uri = $_SERVER['REQUEST_URI'];
    return $current_uri == $uri ? 'active' : '';
}