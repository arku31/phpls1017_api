<?php
require_once "db.php";

//GET /users - получить список всех юзеров //getUsers
//POST /users - создать юзера //storeUser //createUser //sozdatUser
//GET /users/1 - получить юзера №1 //getUserById
//PUT/PATCH /users/1 - обновить юзера №1 //updateUserById
//DELETE /users/1 - удалить юзера №1 //deleteUserByid
//DELETE /users - массовое удаление юзеров //deleteUsers

//if (empty($_COOKIE['authorized'])) {
//    http_response_code(401);
//    echo "auth first";
//}

//Запрос 1. Клиент сообщает нам свой идентификатор+кодовое слово. Мы возвращаем ему токен
//Запрос 2. Клиент сообщаем нам свой токен и запрашивает нужные данные.

$requestmethod = empty($_POST['_method']) ? strtoupper($_SERVER['REQUEST_METHOD']) : $_POST['_method'];


switch ($requestmethod) {
    case 'GET':
    default:
        responseGet();
        break;
    case 'POST':
        responsePost();
        break;
    case 'DELETE':
        responseDelete();
        break;
    case "PUT":
    case "PATCH":
        responseUpdate();
}

function responseUpdate()
{
    if (!empty($_REQUEST['id'])) {
        $user = User::find($_REQUEST['id']);
        if (empty($user)) {
            http_response_code(404);
            echo "Failed to update: 404 not found";
        } else {
            if (!empty($_REQUEST['name'])) {
                $user->name = strip_tags($_REQUEST['name']);
                $user->save();
                echo "user updated";
            } else {
                http_response_code(422);
                echo "Field 'name' required";
            }
        }
    } else {
        http_response_code(422);
        echo "Field 'id' required";
    }
}

function responseDelete()
{
    if (!empty($_REQUEST['id'])) {
        $user = User::find($_REQUEST['id']);
        if (empty($user)) {
            http_response_code(404);
            echo "Failed to delete: 404 not found";
        } else {
            $user->delete();
            echo "User deleted";
        }
    } else {
        http_response_code(422);
        echo "Field 'id' required";
    }
}

function responsePost()
{
    if (empty($_POST['name'])) {
        http_response_code(422);
        echo "Field 'name' required";
    } else {
        $user = new User();
        $user->name = $_POST['name'];
        $user->save();
        echo $user;
    }
}

function responseGet()
{
    if (!empty($_GET['id'])) {
        $user = User::find($_GET['id']);
        if (empty($user)) {
            http_response_code(404);
            echo "404 not found";
        } else {
            echo $user;
        }
    } else {
        echo User::all();
    }

}