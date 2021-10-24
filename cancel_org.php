<?php
    include 'connect.php';

    $id_user_watch = $_POST['id'];
    $id_user = $_COOKIE['id_user'];
    $type = $_POST['type'];

    $mysql->query("UPDATE `users` SET `org_status` = '0' WHERE `id` = '$id_user_watch'");
    
    $mysql->close();      

    header("location: users_admin.php?type_event=".$type."");;
?>