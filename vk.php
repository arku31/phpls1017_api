<?php

namespace arku;

require_once "vkclass.php";
$vk = new Vk();

if ($_SERVER['REQUEST_METHOD'] == "GET" && empty($_GET['code'])) {
    echo "<a href='".$vk->authorizeUrl()."'>Я, такой-то такой-то, в полном сознании, разрешаю делать все что хочешь</a>";
    //file_get_contents('http://localhost:8888/vk.php?code=123');
}

if (isset($_GET['code'])) {
    $token = $vk->access_token($_GET['code']);
    $data=$vk->vkwithtoken($token);
    echo "<pre>";
    print_r($data);
    die();
}

