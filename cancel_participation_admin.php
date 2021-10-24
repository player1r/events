<?php
    include 'connect.php';

    $id_z = $_POST['id_z'];
    $id_event = $_POST['id_event'];
    $type = $_POST['type'];
    $id_user = $_COOKIE['id_user'];

    $mysql->query("UPDATE `participants` SET `activity` = '3' WHERE `id_z` = '$id_z'");
    
    $mysql->close();      

    header("location: participants_event_admin.php?id_event=".$id_event."&type=".$type."");
?>