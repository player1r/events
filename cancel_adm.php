<?php
    include 'connect.php';

    $id_user_watch = $_POST['id'];
    $type = $_POST['type'];
    $id_user = $_COOKIE['id_user'];

    $mysql->query("UPDATE `users` SET `admin` = '0',`org_status` = '0' WHERE `id` = '$id_user_watch'");
    
    $mysql->close();      

    header("location: users_admin.php?type_event=".$type."");
?>