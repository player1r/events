<?php
    include 'connect.php';   

    $rumonth['01'] = 'Января';
    $rumonth['02'] = 'Февраля';
    $rumonth['03'] = 'Марта';
    $rumonth['04'] = 'Апреля';
    $rumonth['05'] = 'Мая';
    $rumonth['06'] = 'Июня';
    $rumonth['07'] = 'Июля';
    $rumonth['08'] = 'Августа';
    $rumonth['09'] = 'Сентября';
    $rumonth['10'] = 'Октября';
    $rumonth['11'] = 'Ноября';
    $rumonth['12'] = 'Декабря';
    
    $id_user = $_COOKIE['id_user'];

    $id_conf = $_POST['id_conf'];
    if ($id_conf == NULL) {
        $id_conf = $_GET['id_conf'];
    }
    $from_page = $_POST['from_page'];

    //Название мероприятия
    $name_conf = $mysql->query("SELECT `name` FROM `events` WHERE `id` = '$id_conf'");
    $n = $name_conf->fetch_assoc();
    $name_conf = $n['name'];

    //Статус мероприятия
    $status_conf = $mysql->query("SELECT `status` FROM `events` WHERE `id` = '$id_conf'");
    $st = $status_conf->fetch_assoc();
    $status_conf = $st['status'];

    //Дата и время проведения мероприятия
        //Дата проведения
        $date = $mysql->query("SELECT `date` FROM `events` WHERE `id` = '$id_conf'");
        $d = $date->fetch_assoc();
        $date = $d['date'];

        //Время мероприятия
        $time = $mysql->query("SELECT `time` FROM `events` WHERE `id` = '$id_conf'");
        $t = $time->fetch_assoc();
        $time = $t['time'];

    //Место проведения мероприятия
        //Название места
        $organization = $mysql->query("SELECT `place` FROM `events` WHERE `id` = '$id_conf'");
        $o = $organization->fetch_assoc();
        $organization = $o['place'];

        //Адрес места проведения мероприятия
        $address = $mysql->query("SELECT `address` FROM `events` WHERE `id` = '$id_conf'");
        $a = $address->fetch_assoc();
        $address = $a['address'];

    //О конференции
    $about = $mysql->query("SELECT `about` FROM `events` WHERE `id` = '$id_conf'");
    $a = $about->fetch_assoc();
    $about = $a['about'];

    //Информация об организаторе (Контактное лицо)
    $id_organization = $mysql->query("SELECT `id_organization` FROM `events` WHERE `id` = '$id_conf'");
    $i_o = $id_organization->fetch_assoc();
    $id_organization = $i_o['id_organization'];

        //ФИО
            //Фамилия
            $surname_otvet = $mysql->query("SELECT `surname` FROM `users` WHERE `id` = '$id_organization'");
            $s_o = $surname_otvet->fetch_assoc();
            $surname_otvet = $s_o['surname'];
            //Имя
            $name_otvet = $mysql->query("SELECT `name` FROM `users` WHERE `id` = '$id_organization'");
            $n_o = $name_otvet->fetch_assoc();
            $name_otvet = $n_o['name'];
            //Отчество
            $patronymic_otvet = $mysql->query("SELECT `patronymic` FROM `users` WHERE `id` = '$id_organization'");
            $p_o = $patronymic_otvet->fetch_assoc();
            $patronymic_otvet = $p_o['patronymic'];

        //Контактный телефон
        $phone_otvet = $mysql->query("SELECT `phone_number` FROM `users` WHERE `id` = '$id_organization'");
        $ph_o = $phone_otvet->fetch_assoc();
        $phone_otvet = $ph_o['phone_number'];

        //Электронная почта
        $email_otvet = $mysql->query("SELECT `email` FROM `users` WHERE `id` = '$id_organization'");
        $e_o = $email_otvet->fetch_assoc();
        $email_otvet = $e_o['email'];


        //Статус участия
        $activity = $mysql->query("SELECT `activity` FROM `participants` WHERE `id_event` = '$id_conf' AND `id_user`='$id_user'");
        $act = $activity->fetch_assoc();
        $activity = $act['activity'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">

    <title><?=$name_conf?> | Events</title>

</head>

<body>
    <!-- Вход/ЛК -->
    <?if ($_COOKIE['id_user'] == null) {?>
        <a href="signin.php">Войти</a>
    <?} else {?>
        <a href="profile.php">Личный кабинет</a>
    <?}?>
    </br>

    <p><a href="../index.php">Главная</a> 
    <?if($from_page == "index"){?>
        / <?=$name_conf;?></p>
    <?} else

    if($from_page == "all_events") {?>
         / <a href="all_events.php">Все мероприятия</a> / <?=$name_conf;?></p>
    <?} else
    
    if($from_page == "my_events") {?>
        / <a href="profile.php">Личный кабинет</a> / <a href="my_events.php">Мои мероприятия</a></p>
    <?} else if ($activity != 2){?> 
        / <a href="choose_part.php?id_event=<?=$id_conf;?>">Регистрация на мероприятие</a>
        </p> 
    <?}?>
    
    
    <!-- Основная часть -->
    
    <h1><?=$name_conf;?></h1>
    <?  if ($status_conf == '0') {?>
        <p> Проведено</p>
    <?}?>
    <?  if ($status_conf == '2') {?>
        <p> Отменено</p>
    <?}?>


    <h2><?=date("d ",strtotime($date)),$rumonth[date('m',strtotime($date))],date(" Y",strtotime($date));?>
    <br/><?=$place;?></h2></br><?=$address;?>

    <?if ($activity == 2){?>        
        <p>Заявка отправлена</p>
    <?} else if ($activity == 1) {?>
        <p>Вы участвуете</p>
    <?} else if ($status_conf == '1' ) {?>
    <form method="post" action="choose_part.php" target="_self">
        <button type="submit" name="id_event" value="<?=$id_conf;?>" <?if ($_COOKIE['id_user'] == null) {?>disabled<?}?>>Принять участие</button>
        <?if ($_COOKIE['id_user'] == null) {?>Авторизируйтесь на портале<?}?>
    </form>
    <?}?>
                
    <h1>О мероприятии</h1>
    <p>Время начала: <?=date('H:i',strtotime($time));?></p>
    <p></br><?=$about;?></p>

    <h1>Контактное лицо</h1>
        <p></br><?=$surname_otvet;?></br><?=$name_otvet; echo " ";?><?=$patronymic_otvet;?><br/><?=$email_otvet;?><br/><?=$phone_otvet;?></p>
        
        
    <!-- Конец PHP -->
    <? $mysql->close();?>

    <!-- Футер -->
    <div>©2021 Events</div>

</body>
</html>