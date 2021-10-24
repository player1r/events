<?php
    include 'connect.php';    

    if(!empty($_POST)){
        $type_event = $_POST['type_event'];
        $search = $_POST['search'];

        if(!empty($search)){
            $search_request = " LIKE '%".$search."%'";
        }


        if ($type_event == '1') {

            if(!empty($search)){
                $search_request = "AND `name`".$search_request;
            }

            $all_events = $mysql->query("SELECT `*` FROM `events` WHERE `status` = '1' $search_request ORDER BY `date` DESC");

            $all = $mysql->query("SELECT COUNT(*) FROM `events` WHERE `status` = '1' $search_request");
            $a = $all->fetch_assoc();
            $all = $a['COUNT(*)'];

            //echo "111111 ".$search." > ".$search_request;
        }

        if ($type_event == '0') {

            if(!empty($search)){
                $search_request = "AND `name`".$search_request;
            }

            $all_events = $mysql->query("SELECT `*` FROM `events` WHERE `status` = '0' $search_request ORDER BY `date` DESC");

            $all = $mysql->query("SELECT COUNT(*) FROM `events` WHERE `status` = '0' $search_request");
            $a = $all->fetch_assoc();
            $all = $a['COUNT(*)'];

            //echo "000000".$search." >".$search_request."<";
        }

        if ($type_event == '2') {

            if(!empty($search)){
                $search_request = "AND `name`".$search_request;
            }

            $all_events = $mysql->query("SELECT `*` FROM `events` WHERE `status` = '2' $search_request ORDER BY `date` DESC");

            $all = $mysql->query("SELECT COUNT(*) FROM `events` WHERE `status` = '2' $search_request");
            $a = $all->fetch_assoc();
            $all = $a['COUNT(*)'];

            //echo "222222".$search." >".$search_request."<";
        }

        if ($type_event == '3') {

            if(!empty($search)){
                $search_request = "WHERE `name`".$search_request;
            }

            $all_events = $mysql->query("SELECT `*` FROM `events` $search_request ORDER BY `date` DESC");

            $all = $mysql->query("SELECT COUNT(*) FROM `events` $search_request");
            $a = $all->fetch_assoc();
            $all = $a['COUNT(*)'];

            //echo "333333".$search." >".$search_request."<";
        }
    } else {

        $all_events = $mysql->query("SELECT `*` FROM `events` ORDER BY `date` DESC");
        $type_event = '3';

        $all = $mysql->query("SELECT COUNT(*) FROM `events`");
        $a = $all->fetch_assoc();
        $all = $a['COUNT(*)'];     

        //echo $type_event,"< 333333 > без поиска";   
    }

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


    $email = $_COOKIE['user'];
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

        <title>Каталог мероприятий | Events</title>        

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
                <a href="#" class="selected_a">Каталог мероприятий</a>
            </div>
        </div>

        <!-- Active_events -->
        <div class="all_events_area">
            <div class="all_events">
                <h1>Каталог мероприятий</h1>
                <div class="events_types">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" target="_self">
                        <input type="hidden" name="search" value="<?=$search;?>">
                        <button type="submit" name="type_event" value="3" <?if ($type_event == '3') {?>class="selected_button"<?}?>>Все</button>
                        <button type="submit" name="type_event" value="1" <?if ($type_event == '1') {?>class="selected_button"<?}?>>Будущие</button>
                        <button type="submit" name="type_event" value="0" <?if ($type_event == '0') {?>class="selected_button"<?}?>>Прошедшие</button>
                        <button type="submit" name="type_event" value="2" <?if ($type_event == '2') {?>class="selected_button"<?}?>>Отменённые</button>
                    </form>
                </div>
                <h2>
                    <?if (!empty($search)) {?>
                        По запросу "<?=$search;?>" найдено записей: <span style="font-family: 'Open Sans';"><?=$all;?></span>
                    <?} else {?>
                        Найдено записей: <span style="font-family: 'Open Sans';"><?=$all;?></span>
                    <?}?>
                </h2>

                <?
                    while ($mas = mysqli_fetch_array($all_events)){
                ?>                
                
                <div class="event_item" <?if ($mas['status'] == '0') {?>style="border-color: grey;"<?} else if ($mas['status'] == '2') {?>style="color: grey; border-color: grey;"<?}?>>
                    <div class="name_and_status">

                        <div class="name"><?= $mas['name'];?></div>

                        <?if ($mas['status'] == '0') {?>
                            <div class="status">Проведено</div>
                        <?}?>
                        <?if ($mas['status'] == '2') {?>
                            <div class="status" style="color: grey;">Отменено</div>
                        <?}?>
                        <?if ($mas['status'] == '1') {?>
                            <div class="status">Активно</div>
                        <?}?>                        
                    </div>
                    <div class="date_and_button">
                        <div class="date">
                            <?echo date("d ",strtotime($mas['date'])),$rumonth[date('m',strtotime($mas['date']))],date(" Y",strtotime($mas['date']))." | ".date("H",$mas['time']).":".date("i",$mas['time']);?>
                        </div>
                        <?if ($mas['status'] != '2') {?>
                            <form method="post" action="event.php" target="_self">
                                <input type="hidden" name="from_page" value="all_events">
                                <button type="submit" name="id_conf" value="<?=$mas['id'];?>">Подробнее</button>
                            </form>
                        <?}?>
                    </div>
                </div>

                <?}?>

        <? $mysql->close();?>

            </div>
        </div>        
       
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
