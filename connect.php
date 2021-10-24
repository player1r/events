<?php
    include 'config.php';

    //Соединение с сервером
    $mysql = mysqli_connect($host, $user, $password, $database);

    if (!$mysql) {
        die(">Connection failed: " . mysqli_connect_error());
    }
?>