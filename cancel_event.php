<?php
    include 'connect.php';

    $id_event = $_POST['id_event'];

    $mysql->query("UPDATE `events` SET `status` = '2' WHERE `id` = '$id_event'");
    
    $mysql->close();      

    header("location: organization_profile.php");
?>