<?php

// В реальном приложении, конечно, надо было бы создать класс

require_once ('DB.php');

switch ($_POST['requestType']){
    case ('uploadUrl'):
        $url = (string) $_POST['url'];
        placeUrl($url);
        break;
    case ('getUrl'):
        $hash = (string) $_POST['hash'];
        getUrl($hash);
        break;
    default:
        exit;
}

function placeUrl($url) {
    $db = DB::getInstance();
    // добавим ссылку, которую указал пользователь в базу данных
    try {
        $sql = 'INSERT INTO urls (url) VALUES (:url)';
        $result = $db->prepare($sql);
        $result->bindParam(':url', $url, PDO::PARAM_STR, 300);
        $result->execute();
    } catch (PDOException $e) {
        // тут можно залогировать ошибку
        exit;
    }

    $lastId = $db->lastInsertId();

    // ай ди соответствующую введенной пользователем ссылки мы преобразуем для простоты в 16-ичный формат
    // и будем использовать как код по которой можно будет получить эту ссылку
    $code = dechex($lastId);
    // соответственно вернем пользователю строку примерно такого вида http://localhost:8080/2a
    echo $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/' . $code;
}

function getUrl ($hash) {
    // получим оригинальное id
    $id = hexdec($hash);
    $db = DB::getInstance();

    // получим ссылку по id
    try {
        $sql = 'SELECT url FROM urls WHERE id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();
        $url = $result->fetchColumn();
        if ($url) {
            echo $url;
        } else {
            // код, говорящий о том, что ничего не найдено
            echo '0';
        }
    } catch (PDOException $e) {
        // тут можно залогировать ошибку
        echo '';
    }
}







