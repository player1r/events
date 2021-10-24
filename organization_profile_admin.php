<?php
    include 'connect.php';
    
    $id_user = $_COOKIE['id_user'];

    $admin = $mysql->query("SELECT `admin`
                        FROM `users`
                        WHERE `id` = '$id_user'");
    $adm = $admin->fetch_assoc();
    $admin = $adm['admin'];



    $type_event = '4';

    $all_events = $mysql->query("SELECT `*`
                                FROM `events`
                                ORDER BY `date` DESC");

    $all = $mysql->query("SELECT COUNT(*) 
                        FROM `events`");
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
            $all_events = $mysql->query("SELECT `*`
                                        FROM `events`
                                        WHERE `status` = '1'
                                        ORDER BY `date` DESC");

            $all = $mysql->query("SELECT COUNT(*) 
                                FROM `events`
                                WHERE `status` = '1'");

            $a = $all->fetch_assoc();
            $all = $a['COUNT(*)'];
        }

        if ($type_event == '0') {
            $all_events = $mysql->query("SELECT `*`
                                        FROM `events`
                                        WHERE `status` = '0'
                                        ORDER BY `date` DESC");

            $all = $mysql->query("SELECT COUNT(*)
                                FROM `events`
                                WHERE `status` = '0'");

            $a = $all->fetch_assoc();
            $all = $a['COUNT(*)'];
        }

        if ($type_event == '2') {
            $all_events = $mysql->query("SELECT `*`
                                        FROM `events`
                                        WHERE `status` = '2'
                                        ORDER BY `date` DESC");

            $all = $mysql->query("SELECT COUNT(*) 
                                FROM `events`
                                WHERE `status` = '2'");

            $a = $all->fetch_assoc();
            $all = $a['COUNT(*)'];
        }

        if ($type_event == '4') {
            $all_events = $mysql->query("SELECT `*`
                                        FROM `events`
                                        ORDER BY `date` DESC");

            $all = $mysql->query("SELECT COUNT(*) 
                                FROM `events`");

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

    <title>Администрирование мероприятий | Events</title>

</head>

<body>

    <?if ($admin == '1') {?>

    
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
                <a href="#" class="selected_a">Панель администратора</a>
            </div>
        </div>

        <!-- Main_part -->
        <div class="profile_area" >
            <div class="profile_block">
                <h1>Панель администратора</h1>
                <div class="nav">
                    <a href="admin_panel.php">Профиль</a>
                    <a href="organization_profile_admin.php" class="selected_a">Мероприятия</a>
                    <a href="users_admin.php">Пользователи</a>
                </div>
            <div class="my_events">

                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" target="_self">
                    <button type="submit" name="type_event" value="4" <?if ($type_event == '4') {?>class="selected_button"<?} else {?>class="ev"<?}?>>Все</button>
                    <button type="submit" name="type_event" value="1" <?if ($type_event == '1') {?>class="selected_button"<?} else {?>class="ev"<?}?>>Будущие</button>
                    <button type="submit" name="type_event" value="0" <?if ($type_event == '0') {?>class="selected_button"<?} else {?>class="ev"<?}?>>Прошедшие</button>
                    <button type="submit" name="type_event" value="2" <?if ($type_event == '2') {?>class="selected_button"<?} else {?>class="ev"<?}?>>Отменённые</button>
                    <a href="create_event_admin.php">Новое мероприятие</a> <a style="margin-left: 5px;" href="report_admin.php">Отчёт</a>
                </form>
                </br>
                
                <p>Найдено записей: <span style="font-family: 'Open Sans';"><?=$all;?></span></p>
                <?if ($all != 0) {?>
                    <table>
            <tr>
                <th>Мероприятие</th>
                <th>Действия</th>
            </tr>

            <?
                while ($mas = mysqli_fetch_array($all_events)){

                    $id_p_n_a_e = $mas['id'];
                    $p_n_a = $mysql->query("SELECT COUNT(*) 
                        FROM `participants`                        
                        WHERE `id_event` = '$id_p_n_a_e' AND `activity` = '2'");

                    $p = $p_n_a->fetch_assoc();
                    $p_n_a = $p['COUNT(*)'];

                    if ($p_n_a == "0") {
                        $p_n_a = "";
                    } else {
                        $p_n_a = " (".$p_n_a.")";
                    }
            ?>

            <tr onmouseover="this.style.background='rgba(0, 168, 247, 0.1)';" onmouseout="this.style.background='white';">
                <td>
                    <?echo $mas['name']," от ",date("d ",strtotime($mas['date'])),$rumonth[date('m',strtotime($mas['date']))],date(" Y",strtotime($mas['date'])); echo " ",date('H:i',strtotime($mas['time']));?>
                </td>
                
                <td>
                    <form method="post" action="participants_event_admin.php" target="_self" style="display: inline-block;">  
                        <input type="hidden" value="<?=$type_event;?>" name="type">                    
                        <button class="td" type="submit" name="id_event" value="<?=$mas['id'];?>">Участники<?=$p_n_a;?></button>
                    </form>    

                    <form method="post" action="manage_event_admin.php" target="_self" style="display: inline-block;">                        
                        <button class="td" type="submit" name="id_event" value="<?=$mas['id'];?>">О мероприятии</button>
                    </form>

                    <?if ($mas['status'] == '1') {?>
                        <form method="post" action="cancel_event_admin.php" target="_self" style="display: inline-block;" onsubmit="return confirm('Вы дейcтвительно хотите отменить данное мероприятие?');">                        
                            <button class="td" type="submit" name="id_event" value="<?=$mas['id'];?>">Отменить</button>
                        </form>
                    <?}?>

                    <?if ($mas['status'] == '2') {?>
                        <form method="post" action="activate_event_admin.php" target="_self" style="display: inline-block;" onsubmit="return confirm('Вы дейcтвительно хотите активировать данное мероприятие?');">                        
                            <button class="td" type="submit" name="id_event" value="<?=$mas['id'];?>">Активировать</button>
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
    
    <?} else { echo "Запрашиваемый ресурс не найден";}?>
</body>
</html>
