<?php
    include 'connect.php';
    
    $id_user = $_COOKIE['id_user'];

    $type_event = '1';

    $all_events = $mysql->query("SELECT `ev`.`id`,`ev`.`name`,`ev`.`date`, `ev`.`time`, `ev`.`place`,`p`.`type_participation`,`p`.`activity`,`ev`.`status`,`p`.`id_z`
                                FROM `participants` `p` 
                                JOIN `users` `u` ON (`u`.`id` = `p`.`id_user`) 
                                JOIN `events` `ev` ON (`ev`.`id` = `p`.`id_event`) 
                                WHERE `p`.`id_user` = '$id_user' AND `ev`.`status` = '1'
                                ORDER BY `ev`.`date` DESC");

    $all = $mysql->query("SELECT COUNT(*) 
                            FROM `participants` `p`
                            JOIN `users` `u` ON (`u`.`id` = `p`.`id_user`) 
                            JOIN `events` `ev` ON (`ev`.`id` = `p`.`id_event`) 
                            WHERE `p`.`id_user` = '$id_user' AND `ev`.`status` = '1'");

    $a = $all->fetch_assoc();
    $all = $a['COUNT(*)'];

    $org_status = $mysql->query("SELECT `org_status` FROM `users` WHERE `id` = '$id_user'");
    $o_s = $org_status->fetch_assoc();
    $org_status = $o_s['org_status'];

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

    if(!empty($_POST)){
        $type_event = $_POST['type_event'];

        if ($type_event == '1') {
            $all_events = $mysql->query("SELECT `ev`.`id`,`ev`.`name`,`ev`.`date`, `ev`.`time`, `ev`.`place`,`p`.`type_participation`,`p`.`activity`,`ev`.`status`,`p`.`id_z`
                                        FROM `participants` `p` 
                                        JOIN `users` `u` ON (`u`.`id` = `p`.`id_user`) 
                                        JOIN `events` `ev` ON (`ev`.`id` = `p`.`id_event`) 
                                        WHERE `p`.`id_user` = '$id_user' AND `ev`.`status` = '1'
                                        ORDER BY `ev`.`date` DESC");

            $all = $mysql->query("SELECT COUNT(*) 
                                FROM `participants` `p`  
                                JOIN `users` `u` ON (`u`.`id` = `p`.`id_user`) 
                                JOIN `events` `ev` ON (`ev`.`id` = `p`.`id_event`) 
                                WHERE `p`.`id_user` = '$id_user' AND `ev`.`status` = '1'");

            $a = $all->fetch_assoc();
            $all = $a['COUNT(*)'];
        }

        if ($type_event == '0') {
            $all_events = $mysql->query("SELECT `ev`.`id`,`ev`.`name`,`ev`.`date`, `ev`.`time`, `ev`.`place`,`p`.`type_participation`,`p`.`activity`,`ev`.`status`,`p`.`id_z`
                                        FROM `participants` `p` 
                                        JOIN `users` `u` ON (`u`.`id` = `p`.`id_user`) 
                                        JOIN `events` `ev` ON (`ev`.`id` = `p`.`id_event`) 
                                        WHERE `p`.`id_user` = '$id_user' AND `ev`.`status` = '0'
                                        ORDER BY `ev`.`date` DESC");

            $all = $mysql->query("SELECT COUNT(*) FROM `participants` `p` 
                                JOIN `users` `u` ON (`u`.`id` = `p`.`id_user`) 
                                JOIN `events` `ev` ON (`ev`.`id` = `p`.`id_event`) 
                                WHERE `p`.`id_user` = '$id_user' AND `ev`.`status` = '0'");

            $a = $all->fetch_assoc();
            $all = $a['COUNT(*)'];
        }

        if ($type_event == '2') {
            $all_events = $mysql->query("SELECT `ev`.`id`,`ev`.`name`,`ev`.`date`, `ev`.`time`, `ev`.`place`,`p`.`type_participation`,`p`.`activity`,`ev`.`status`,`p`.`id_z`
                                        FROM `participants` `p` 
                                        JOIN `users` `u` ON (`u`.`id` = `p`.`id_user`) 
                                        JOIN `events` `ev` ON (`ev`.`id` = `p`.`id_event`) 
                                        WHERE `p`.`id_user` = '$id_user' AND `ev`.`status` = '2'
                                        ORDER BY `ev`.`date` DESC");

            $all = $mysql->query("SELECT COUNT(*) 
                                FROM `participants` `p` 
                                JOIN `users` `u` ON (`u`.`id` = `p`.`id_user`) 
                                JOIN `events` `ev` ON (`ev`.`id` = `p`.`id_event`) 
                                WHERE `p`.`id_user` = '$id_user' AND `ev`.`status` = '2'");

            $a = $all->fetch_assoc();
            $all = $a['COUNT(*)'];
        }

    }
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

    <title>Мероприятия | Events</title>

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
                    <a href="exit.php">
                        <div class="logout_icon"></div>
                    </a>
                </div>
            </div>
    </div>
    
    <!-- Breadcrumbs -->
    <div class="breadcrumbs_area">
            <div class="breadcrumbs_block">
                <a href="../index.php">Главная</a><span class="image"></span>
                <a href="#" class="selected_a">Личный кабинет</a>
            </div>
    </div>

    <!-- Main_part -->
    <div class="profile_area">
            <div class="profile_block">
                <h1>Личный кабинет</h1>
                <div class="nav">
                    <a href="profile.php">Профиль</a>
                    <a href="my_events.php" class="selected_a">Мероприятия</a>
                    <a href="my_certificates.php">Сертификаты</a>
                    <a href="organization_profile.php">Кабинет организатора</a>
                </div>
            <div class="my_events">

                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" target="_self">
                    <button type="submit" name="type_event" value="1" <?if ($type_event == '1') {?>class="selected_button"<?} else {?>class="ev"<?}?>>Будущие</button>
                    <button type="submit" name="type_event" value="0" <?if ($type_event == '0') {?>class="selected_button"<?} else {?>class="ev"<?}?>>Прошедшие</button>
                    <button type="submit" name="type_event" value="2" <?if ($type_event == '2') {?>class="selected_button"<?} else {?>class="ev"<?}?>>Отменённые</button>
                </form>
                </br>
                
                <p>Найдено записей: <span style="font-family: 'Open Sans';"><?=$all;?></span></p>
                <?if ($all != 0) {?>
                <table>
                    <tr>
                        <th>Статус</th>
                        <th>Мероприятие</th>
                        <th>Тип участия</th>
                        <th>Действия</th>
                    </tr>

                    <?
                        while ($mas = mysqli_fetch_array($all_events)){
                    ?>

                    <tr onmouseover="this.style.background='rgba(0, 168, 247, 0.1)';" onmouseout="this.style.background='white';">
                        <td>
                            <?if ($mas['activity'] == '1') {
                                echo "Подтверждена";
                            } else if ($mas['activity'] == '0') {
                                echo "Отменена";
                            } else if ($mas['activity'] == '2') {
                                echo "Ожидает рассмотрения";
                            } else if ($mas['activity'] == '3') {
                                echo "Отказ";
                            }?>
                        </td>

                        <td>
                            <form method="post" action="event.php" target="_self">
                                <input type="hidden" name="from_page" value="my_events">
                                <button type="submit" name="id_conf" value="<?=$mas['id'];?>"><?echo $mas['name'], " от ",date("d ",strtotime($mas['date'])),$rumonth[date('m',strtotime($mas['date']))],date(" Y",strtotime($mas['date'])); echo " ",date('H:i',strtotime($mas['time']));?></button>
                            </form>
                        </td>
                                
                        <td><?if ($mas['type_participation'] == '0'){ 
                                echo "Слушатель";
                            } else if ($mas['type_participation'] == '1'){
                                echo "Докладчик";}?>
                        </td>
                        
                        <td>
                            <!-- Для будущих мероприятий -->
                                <?if ($type_event == 1){?>
                                    <!-- Отмена участия -->
                                    <form method="post" action="cancel_part.php" target="_self" onsubmit="return confirm('Вы дейcтвительно хотите отменить участие в выбранном мероприятии?');">
                                        <button class="td" type="submit" name="id" value="<?=$mas['id'];?>">Отменить</button> 
                                    </form>
                                <?}?>

                            <!-- Для отменённых мероприятий -->
                                <?if (($type_event == 2) && ($mas['status'] == 1) && ($mas['activity'] == 0)){?>
                                    <form method="post" action="active_part.php" target="_self">
                                        <button class="td" type="submit" name="id" value="<?=$mas['id'];?>">Участвовать</button> 
                                    </form>                         
                                <?}?>

                            <!-- Заявка  -->
                                    <form method="post" action="application.php" target="_self">
                                        <button class="td" type="submit" name="id_z" value="<?=$mas['id_z'];?>">Просмотр</button> 
                                    </form>  
                        </td>
                    </tr>
                    <?}?>
                </table>
                <?}?>
            </div>
        </div>
    </div>

  
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
