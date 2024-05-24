<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require get_lang_file();
function get_lang_file(){

    $_SESSION['lang'] = $_SESSION['lang'] ?? 'en';
    $_SESSION['lang'] = $_GET['lang'] ?? $_SESSION['lang'];

    return 'lang/' . $_SESSION['lang'] . '.php';
}

function lang($str){
    global $lang;
    if(!empty($lang[$str])){
        return $lang[$str];
    }
    return $str;
};