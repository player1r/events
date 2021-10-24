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

        //Background
        $background = $mysql->query("SELECT `background_image` FROM `events` WHERE `id` = '$id_conf'");
        $b = $background->fetch_assoc();
        $background = $b['background_image'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>        

    <link rel="stylesheet" href="../assets/theme.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <title><?=$name_conf?> | Events</title>

</head>

<body>
    
    <!-- Header -->
    <div class="header_area_all">
            <div class="header_line">
                <div class="logo_and_name_and_search">
                    <!-- <div class="logo"></div> -->
                    <div class="name">
                        Events
                    </div>
                    <div class="search">
                        <form method="post" action="all_events.php" target="_self">
                            <input type="text" name="search" placeholder="Поиск мероприятий по названию...">
                            <input type="hidden" name="type_event" value="3">
                            <i class="fa fa-search"></i>
                          </form>
                    </div>
                </div>
                <div class="menu">
                    <a href="../index.php" class="item">Главная</a>
                    <a href="all_events.php" class="item">Каталог</a>
                    <?if ($_COOKIE['id_user'] == null) {?>
                        <a href="signin.php">
                            <div class="enter"></div>
                        </a>
                    <?} else {?>
                        <a href="profile.php">
                            <div class="profile"></div>
                        </a>
                    <?}?>
                </div>
            </div>
    </div>

    <!-- Breadcrumbs -->
    <div class="breadcrumbs_area">
        <div class="breadcrumbs_block">
            <a href="../index.php">Главная</a><span class="image"></span>
            
            <?if($from_page == "index"){?>
                <a href="#" class="selected_a"><?=$name_conf;?></a>
            <?} else
            if($from_page == "all_events") {?>
                <a href="all_events.php">Каталог мероприятий</a><span class="image"></span>
                <a href="#" class="selected_a"><?=$name_conf;?></a>
            <?} else                
            if($from_page == "my_events") {?>
                <a href="profile.php">Личный кабинет</a><span class="image"></span>
                <a href="my_events.php">Мои мероприятия</a>
                <a href="#" class="selected_a"><?=$name_conf;?></a>
            <?} else if ($activity != 2){?> 
                <a href="choose_part.php?id_event=<?=$id_conf;?>">Регистрация на мероприятие</a>
            <?}?>
            
        </div>
    </div>    
    
    <!-- Event -->
    <div class="event_page_area">
            <div class="name_button_image">
                <div class="name_and_button">                    
                    <h1><?=$name_conf;?></h1>
                    <div class="place_and_date">
                        <p class="info"><span class="place"></span><?=$organization;?></p>
                        <p class="info"><span class="date"></span><?echo date("d ",strtotime($date)),$rumonth[date('m',strtotime($date))],date(" Y",strtotime($date))." в ".date('H:i',strtotime($time));?></p>
                    </div>

                    <?if ($activity == 2){?>      
                        <form>
                            <button class="button" disabled>Заявка отправлена</button>
                        </form>
                    <?} else if ($activity == 1) {?>
                        <form>
                            <button class="button" disabled>Вы участвуете</button>
                        </form>
                    <?} else if ($status_conf == '1' ) {?>
                        <form method="post" action="choose_part.php" target="_self">
                            <button class="button" type="submit" name="id_event" value="<?=$id_conf;?>" <?if ($_COOKIE['id_user'] == null) {?>disabled<?}?>>Принять участие</button>
                            <?if ($_COOKIE['id_user'] == null) {?>Авторизируйтесь на портале<?}?>
                        </form>
                    <?}?>
                </div>

                <div class="image" style="background-image: url(/static/assets/images/<? if (($background == "-") || ($background == "")) {?>defult.jpg<?} else { echo $background;}?>);"></div>
            </div>

            <div class="about">
                <h1>О мероприятии</h1>
                <p><?=$about;?></p>
            </div>

            <div class="contacts">
                <h1>Контакты</h1>
                <div class="address_organizer">
                    <div class="item">
                        <h2>Место проведения</h2>
                        <p style="font-family: 'Open Sans';"><?=$address;?></p>
                    </div>
                    <div class="item">
                        <h2>Организатор</h2>
                        <p><?=$surname_otvet;?> <?=$name_otvet; echo " ";?><?=$patronymic_otvet;?></p>
                        <p><?=$email_otvet;?></p>
                        <p style="font-family: 'Open Sans';"><?=$phone_otvet;?></p>
                    </div>
                </div>
            </div>
        </div>



        
    <!-- Конец PHP -->
    <? $mysql->close();?>

    <!-- Footer -->
    <div class="footer_area">
            <div class="footer_line">
                <div class="logo">
                    Events
                </div>
                <div class="info">
                    © 2021 Все права защищены
                </div>
            </div>
    </div>

</body>
</html>