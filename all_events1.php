<?php
    include 'connect.php';    

    if(!empty($_POST)){
        $type_event = $_POST['type_event'];
        $search = $_POST['search'];

        if(!empty($search)){
            $search_request = " LIKE '%".$search."%'";
        }

        if ($type_event == '1') {
            $all_events = $mysql->query("SELECT `*` FROM `events` WHERE `status` = '1' AND `name`$search_request ORDER BY `events`.`date` DESC");

            $all = $mysql->query("SELECT COUNT(*) FROM `events` WHERE `status` = '1' AND `name`$search_request");
            $a = $all->fetch_assoc();
            $all = $a['COUNT(*)'];

            echo "111111 ".$search." > ".$search_request;
        }

        if ($type_event == '0') {
            $all_events = $mysql->query("SELECT `*` FROM `events` WHERE `status` = '0' AND `name`$search_request ORDER BY `events`.`date` DESC");

            $all = $mysql->query("SELECT COUNT(*) FROM `events` WHERE `status` = '0' AND `name`$search_request");
            $a = $all->fetch_assoc();
            $all = $a['COUNT(*)'];

            echo "000000".$search." > ".$search_request;
        }

        if ($type_event == '2') {
            $all_events = $mysql->query("SELECT `*` FROM `events` WHERE `status` = '2' AND `name`$search_request ORDER BY `events`.`date` DESC");

            $all = $mysql->query("SELECT COUNT(*) FROM `events` WHERE `status` = '2' AND `name`$search_request");
            $a = $all->fetch_assoc();
            $all = $a['COUNT(*)'];

            echo "222222".$search." > ".$search_request;
        }

        if ($type_event == '3') {
            $all_events = $mysql->query("SELECT `*` FROM `events` WHERE `name`$search_request ORDER BY `events`.`date` DESC");

            $all = $mysql->query("SELECT COUNT(*) FROM `events` WHERE `name`$search_request");
            $a = $all->fetch_assoc();
            $all = $a['COUNT(*)'];

            echo "333333".$search." > ".$search_request;
        }
    } else {

        $all_events = $mysql->query("SELECT `*` FROM `events` ORDER BY `events`.`date` DESC");
        $type_event == '3';

        $all = $mysql->query("SELECT COUNT(*) FROM `events`");
        $a = $all->fetch_assoc();
        $all = $a['COUNT(*)'];     

        echo "333333 > без поиска";   
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

    <title>Каталог мероприятий | Events</title>

</head>

<body>

    <!-- Вход/ЛК -->
    <?if ($_COOKIE['id_user'] == null) {?>
        <a href="signin.php">Войти</a>
    <?} else {?>
        <a href="profile.php">Личный кабинет</a>
    <?}?>
    </br>
    <p><a href="../index.php">Главная</a> / Все мероприятия</p>

    <!-- Main -->

        <h1>Все мероприятия</h1>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" target="_self">
            <input type="text" name="search" placeholder="Поиск по названию" value="<?=$search;?>">
            <button type="submit" name="type_event" value="<?=$type_event;?>">Найти</button>
        </form>

        <?if (!empty($search)) {?>
            <p>По запросу "<?=$search;?>" найдено записей: <?=$all;?></p>
        <?} else {?>
            <p>Найдено записей: <?=$all;?></p>
        <?}?>





        <?if ($type_event == '1') {?>
            <p>Будущие</p>
        <?}?>

        <?if ($type_event == '0') {?>
            <p>Прошедшие</p>
        <?}?>

        <?if ($type_event == '2') {?>
            <p>Отменённые</p>
        <?}?>

        <?if ($type_event == '3') {?>
            <p>Все</p>
        <?}?>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" target="_self">
            <input type="hidden" name="search" value="<?=$search;?>">
            <button type="submit" name="type_event" value="3">Все</button>
            <button type="submit" name="type_event" value="1">Будущие</button>
            <button type="submit" name="type_event" value="0">Прошедшие</button>
            <button type="submit" name="type_event" value="2">Отменённые</button>
        </form>


        <?
            while ($mas = mysqli_fetch_array($all_events)){
        ?>
            <div>
                <p><?= $mas['name'];?>

                <?if ($mas['status'] == '0') {?>
                     (проведено)</p><?}?>
                <?if ($mas['status'] == '2') {?>
                     (отменено)</p><?}?>

                <p><?echo date("d ",strtotime($mas['date'])),$rumonth[date('m',strtotime($mas['date']))],date(" Y",strtotime($mas['date']));?><br/><?echo $mas['place'];?></p>

                <form method="post" action="event.php" target="_self">
                    <input type="hidden" name="from_page" value="all_events">
                    <button type="submit" name="id_conf" value="<?=$mas['id'];?>">Подробнее</button>
                </form>
            </div>
            <?}?>

        <? $mysql->close();?>

    <!-- Footer -->
    <div>
        ©2021 Events
    </div>

</body>
</html>
