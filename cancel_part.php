<?php
    include 'connect.php';

    $id_event = $_POST['id'];
    $id_user = $_COOKIE['id_user'];

    $mysql->query("UPDATE `participants` SET `activity` = '0' WHERE `id_event` = '$id_event' AND `id_user` ='$id_user'");
    
    $mysql->close();      

    header("location: my_events.php");
?>