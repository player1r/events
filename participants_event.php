<?php
    include 'connect.php';
    
    $id_user = $_COOKIE['id_user'];
    $id_event = $_POST['id_event'];
    if ($id_event == NULL) {
        $id_event = $_GET['id_event'];
    }

    $type = $_POST['type'];
    if ($type == NULL) {
        $type = $_GET['type'];
    }


    $all_events = $mysql->query("SELECT `u`.`name`, `u`.`surname`,`u`.`patronymic`,`p`.`type_participation`,`p`.`id_z`,`p`.`activity`
                                FROM `participants` `p`
                                JOIN `users` `u` ON (`u`.`id` = `p`.`id_user`)
                                WHERE `p`.`id_event` = '$id_event'");


    $all = $mysql->query("SELECT COUNT(*) 
                        FROM `participants`
                        WHERE `id_event` = '$id_event'");
    $a = $all->fetch_assoc();
    $all = $a['COUNT(*)'];

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
            $all_events = $mysql->query("SELECT `u`.`name`, `u`.`surname`,`u`.`patronymic`,`p`.`type_participation`,`p`.`id_z`,`p`.`activity`
                                FROM `participants` `p`
                                JOIN `users` `u` ON (`u`.`id` = `p`.`id_user`)
                                WHERE `p`.`activity` = '1' AND `p`.`id_event` = '$id_event'");


            $all = $mysql->query("SELECT COUNT(*) 
                                FROM `participants`
                                WHERE `activity` = '1' AND `id_event` = '$id_event'");
            $a = $all->fetch_assoc();
            $all = $a['COUNT(*)'];
        }

        if ($type_event == '3') {
            $all_events = $mysql->query("SELECT `u`.`name`, `u`.`surname`,`u`.`patronymic`,`p`.`type_participation`,`p`.`id_z`,`p`.`activity`
                                FROM `participants` `p`
                                JOIN `users` `u` ON (`u`.`id` = `p`.`id_user`)
                                WHERE `p`.`activity` = '3' AND `p`.`id_event` = '$id_event'");


            $all = $mysql->query("SELECT COUNT(*) 
                                FROM `participants`
                                WHERE `activity` = '3' AND `id_event` = '$id_event'");
            $a = $all->fetch_assoc();
            $all = $a['COUNT(*)'];
        }

        if ($type_event == '2') {
            $all_events = $mysql->query("SELECT `u`.`name`, `u`.`surname`,`u`.`patronymic`,`p`.`type_participation`,`p`.`id_z`,`p`.`activity`
                                FROM `participants` `p`
                                JOIN `users` `u` ON (`u`.`id` = `p`.`id_user`)
                                WHERE `p`.`activity` = '2' AND `p`.`id_event` = '$id_event'");


            $all = $mysql->query("SELECT COUNT(*) 
                                FROM `participants`
                                WHERE `activity` = '2' AND `id_event` = '$id_event'");
            $a = $all->fetch_assoc();
            $all = $a['COUNT(*)'];
        }

    }

    if($type_event == '') {
        
    $type_event = '4';
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

    <title>Кабинет организатора | Events</title>

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
                <a href="profile.php">Личный кабинет</a><span class="image"></span>
                <a href="organization_profile.php">Кабинет организатора</a><span class="image"></span>
                <a href="#" class="selected_a">Участники</a>
            </div>
    </div>
    
    <!-- Main_part -->
    <div class="profile_area">
            <div class="profile_block">
                <h1>Личный кабинет</h1>
                <div class="nav">
                    <a href="profile.php">Профиль</a>
                    <a href="my_events.php">Мероприятия</a>
                    <a href="my_certificates.php">Сертификаты</a>
                    <a href="organization_profile.php" class="selected_a">Кабинет организатора</a>
                </div>
            <div class="my_events">

                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" target="_self">
                    <input type="hidden" value="<?=$type;?>" name="type">
                    <input type="hidden" value="<?=$id_event;?>" name="id_event">   
                    <button type="submit" name="type_event" value="4" <?if ($type_event == '4') {?>class="selected_button"<?} else {?>class="ev"<?}?>>Все</button>
                    <button type="submit" name="type_event" value="2" <?if ($type_event == '2') {?>class="selected_button"<?} else {?>class="ev"<?}?>>Ожидают рассмотрения</button>
                    <button type="submit" name="type_event" value="1" <?if ($type_event == '1') {?>class="selected_button"<?} else {?>class="ev"<?}?>>Подтверждённые</button>
                    <button type="submit" name="type_event" value="3" <?if ($type_event == '3') {?>class="selected_button"<?} else {?>class="ev"<?}?>>Отказанные</button>            
                </form>
                </br>
                
                <p>Найдено записей: <span style="font-family: 'Open Sans';"><?=$all;?></span></p>
                <?if ($all != 0) {?>
                    <table>
            <tr>
                <?if ($type_event == '4') {?>
                    <th>Статус</th>
                <?}?>
                <th>ФИО</th>
                <th>Тип участия</th>
                <th>Действия</th>
            </tr>

            <?
                while ($mas = mysqli_fetch_array($all_events)){

            ?>

            <tr onmouseover="this.style.background='rgba(0, 168, 247, 0.1)';" onmouseout="this.style.background='white';">
                <?if ($type_event == '4') {?>
                    <td>
                        <?if ($mas['activity'] == '1') {?>
                            Подтверждена
                        <?}?>

                        <?if ($mas['activity'] == '2') {?>
                            Ожидает рассмотрения
                        <?}?>

                        <?if ($mas['activity'] == '3') {?>
                            Отказ
                        <?}?>
                    </td>
                <?}?>

                <td>
                    <?if ($mas['patronymic'] == "-") { $mas['patronymic'] = '';}
                    echo $mas['surname']," ",$mas['name'], " ", $mas['patronymic'];?>
                </td>
                
                <td>
                    <?if ($mas['type_participation'] == "1") {
                        echo "Докладчик";} 
                        else if ($mas['type_participation'] == "0") {
                            echo "Слушатель"; 
                        }?>
                </td>

                <td>
                    <form method="post" action="application.php" target="_blank" style="display: inline-block;">
                        <input type="hidden" name="id_event" value="<?=$id_event;?>">
                        <input type="hidden" name="type" value="<?=$type;?>">      
                        <button class="td" type="submit" name="id_z" value="<?=$mas['id_z'];?>">Просмотр заявки</button>
                    </form>    

                    <?if (($mas['activity'] == '1') && ($type == '1')) {?>
                        <form method="post" action="cancel_participation.php" target="_self" style="display: inline-block;" onsubmit="return confirm('Вы дейcтвительно хотите отказать в участии данному пользователю?');">                        
                            <input type="hidden" name="id_event" value="<?=$id_event;?>">
                            <input type="hidden" name="type" value="<?=$type;?>"> 
                            <button class="td" type="submit" name="id_z" value="<?=$mas['id_z'];?>">Отказать</button>
                        </form>
                    <?}?>

                    <?if (($mas['activity'] == '2') && ($type == '1')) {?>
                        <form method="post" action="activate_participation.php" target="_self" style="display: inline-block;" onsubmit="return confirm('Вы дейcтвительно хотите подтвердить участие данного пользователя?');">                        
                            <input type="hidden" name="id_event" value="<?=$id_event;?>"> 
                            <input type="hidden" name="type" value="<?=$type;?>">    
                            <button class="td" type="submit" name="id_z" value="<?=$mas['id_z'];?>">Подтвердить</button>
                        </form>
                    <?}?>

                    <?if (($mas['activity'] == '3') && ($type == '1')) {?>
                        <form method="post" action="return_participation.php" target="_self" style="display: inline-block;" onsubmit="return confirm('Вы дейcтвительно вернуть на рассмотрение заявку данного пользователя?');">                        
                            <input type="hidden" name="id_event" value="<?=$id_event;?>">
                            <input type="hidden" name="type" value="<?=$type;?>">  
                            <button class="td" type="submit" name="id_z" value="<?=$mas['id_z'];?>">Вернуть на рассмотрение</button>
                        </form>
                    <?}?>
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
