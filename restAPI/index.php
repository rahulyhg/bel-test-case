<?php

require_once ('DB.php');

$requestURIwGet = strtok($_SERVER['REQUEST_URI'], '/');
$requestURI = strtok($requestURIwGet, '?');

switch ($requestURI) {
    case 'generate':
        generate();
        break;
    case 'get':
        $id = $_GET['id'];
        if (!empty($id))
            retrieve($id);
        else
            exit;
        break;
    default:
        exit;
}

function generate() {

    $number = rand(0, 30000);
    $db= DB::getInstance();
    try {
        $sql = 'INSERT INTO numbers (number) VALUES (:number)';
        $result = $db->prepare($sql);
        $result->bindParam(':number', $number, PDO::PARAM_INT);
        $result->execute();
    } catch (PDOException $e) {
        // тут можно залогировать ошибку
        exit;
    }
    $lastId = $db->lastInsertId();
    header("Content-Type: text/plain");
    echo $lastId;
}

function retrieve($id) {
    $id = (int) $id;
    $db= DB::getInstance();
    try {
        $sql = 'SELECT number FROM numbers WHERE id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();
        $number = $result->fetchColumn();
        if ($number) {
            header("Content-Type: text/plain");
            echo $number;
        } else {
            header("Content-Type: text/plain");
            echo 'Nothing was found!';
        }
    } catch (PDOException $e) {
        // тут можно залогировать ошибку
        echo '';
    }
}

