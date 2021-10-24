<?php
    include 'php/connect.php';

    $current_date = date("Y-m-d");
    $current_time = date("H:i:s");

    $past_events = $mysql->query("SELECT `*` FROM `events` WHERE ((`date` < '$current_date') OR (`date` = '$current_date' AND `time` < '$current_time')) AND (`status` = '1')");
    while ($mas = mysqli_fetch_array($past_events)){
        $id = $mas['id'];
        $mysql->query("UPDATE `events` SET `status` = '0' WHERE `id` = '$id'");
    }
?>