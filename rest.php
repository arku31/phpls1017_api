<?php
$data = [
    0 => 'hi',
    1 => 'hello'
];
echo "method: " . $_SERVER['REQUEST_METHOD'];
// http://mysupersite.ru/api/v1/
//GET /users - получить всех юзеров //GET users/all (everyone)
//GET /users/1 - получить юзера с ид = 1 //GET /users/1
//PUT/PATCH /users/1 - обновить пользователя с ид = 1 //POST users/update/1 (edit, ...)
//POST /users/ - создать нового пользователя //POST /users (create)
//DELETE /users/1 - удалить юзера №1 //POST /users/delete/1 (destroy)
//
// ... users/1 ...
//+ красивый URL
//- отсутствие поддержки браузера, умеет только GET и POST. Все остальное эмулируется.
//PUT&PATCH - PUT - полностью меняет объект (включая идентификатор), PATCH - частично меняет (только title)
$user = "неавторизован";
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    if ($user !== "авторизован") {
        http_response_code(403);
        die("Нет авторизции");
    }
    $data[]=$_GET['data'];
    echo json_encode($data);
}
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    echo json_encode($data);
}
?>

<!--<form action="" method="post">-->
<!--    <input type="hidden" name="_method" value="PUT">-->
<!--</form>-->