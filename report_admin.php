<?php
    include 'connect.php';
    
    $id_user = $_COOKIE['id_user'];

    $admin = $mysql->query("SELECT `admin`
                        FROM `users`
                        WHERE `id` = '$id_user'");
    $adm = $admin->fetch_assoc();
    $admin = $adm['admin'];

    $type_res = $mysql->query("SELECT * FROM `type_event`");
    $form_res = $mysql->query("SELECT * FROM `form_event`");
    $scale_res = $mysql->query("SELECT * FROM `scale_event`");

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
        $parameter = filter_var(trim($_POST['parameter']),FILTER_SANITIZE_STRING);
        $date_min = filter_var(trim($_POST['date_min']),FILTER_SANITIZE_STRING);
        $date_max = filter_var(trim($_POST['date_max']),FILTER_SANITIZE_STRING);
        $submit = filter_var(trim($_POST['submit']),FILTER_SANITIZE_STRING);


        if((empty($parameter))&&(empty($date_min))&&(empty($date_max))) {
            $error = 1;
        } else {

            if(!empty($date_min)){
                $date_min_request = ">= '".$date_min."'";
            }

            if(!empty($date_max)){
                $date_max_request = "<= '".$date_max."'";
            }

            // echo ">", $parameter, "</br>";
            // echo ">", $date_min, "</br>";
            // echo ">", $date_max, "</br>";

            //по типу
            if ($parameter == "1") {
                $types = $mysql->query(
                    "SELECT `*`
                    FROM `type_event`");
            }

            if ($parameter == "2") {
                $types = $mysql->query(
                    "SELECT `*`
                    FROM `form_event`");
            }

            if ($parameter == "3") {
                $types = $mysql->query(
                    "SELECT `*`
                    FROM `scale_event`");
            }
                // while ($mas = mysqli_fetch_array($types)){
                //     echo " | ".$mas['name_rus'];
                // }

            $types_count = $mysql->query(
                "SELECT 
                    COUNT(*)
                FROM `type_event`");

            $t_c = $types_count->fetch_assoc();
            $types_count = $t_c['COUNT(*)'];

            $i = 0;                                
            while ($mas = mysqli_fetch_array($types)){
                $id = $mas['id'];
                $type[$i] = $mas['name_rus'];
                $num = $mysql->query(
                                "SELECT COUNT(*)
                                FROM `events`
                                WHERE `type` = '$id' 
                                AND 
                                `date`$date_min_request
                                AND
                                `date`$date_max_request;");

                $n = $num->fetch_assoc();
                $response[$i] = $n['COUNT(*)'];
                $i++;                    
            }            
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

    <title>Отчёт по мероприятиям | Events</title>

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
                <a href="admin_panel.php">Панель администратора</a><span class="image"></span>
                <a href="organization_profile_admin.php">Мероприятия</a><span class="image"></span>
                <a href="#" class="selected_a">Формирование отчёта</a>
            </div>
        </div>

        <div class="profile_area" >
            <div class="profile_block">
                <h1>Панель администратора</h1>
                <div class="nav">
                    <a href="admin_panel.php">Профиль</a>
                    <a href="organization_profile_admin.php"  class="selected_a">Мероприятия</a>
                    <a href="users_admin.php">Пользователи</a>
                </div>
            <div class="report">

            <h3>Формирование отчёта</h3>

            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" target="_self">

            <div class="input_style">
                <div class="labels">
                    Параметр формирования
                </div>
                <div class="inputs">
                    <select name="parameter">
                        <option value="" disabled selected hidden></option>          
                        <option value="1" <?if($parameter == "1"){?>selected<?}?>>по типу</option>
                        <option value="2" <?if($parameter == "2"){?>selected<?}?>>по форме</option>
                        <option value="3" <?if($parameter == "3"){?>selected<?}?>>по масштабу</option>
                    </select>
                </div>
            </div>

            <div class="input_style">
                <div class="labels">
                    Период проведения
                </div>
            </div>

            <div class="input_style">
                <div class="calendar">
                    от<input type="date" name="date_min" value="<?=$date_min;?>">
                </div>
                <div class="calendar">
                    <p style="margin-left: 10px;">до</p><input type="date" name="date_max" value="<?=$date_max;?>">
                </div>
            </div>


            <div class="input_style">
                    <button type="submit" name="submit" value="1">Сформировать</button>                    
                    </form>
            </div>

            <?if ($submit == '1') {
                if ($error == 1) {?>            
                    
                <?} else {?>

                    <?if ($types_count != 0) {?>
                        <form method="post" action="response_excel_admin.php" target="_blank">
                            <input type="hidden" name="parameter" value="<?=$parameter;?>">
                            <input type="hidden" name="date_min" value="<?=$date_min;?>">
                            <input type="hidden" name="date_max" value="<?=$date_max;?>">
                            <div class="input_style">
                            <button class="download" type="submit">Выгрузить отчёт</button>
                    </div>
                        </form>
                    <?}?>
                
                    <table>
                        <tr>
                            <th>
                                <?if ($parameter == "1"){?>Тип<?}?>
                                <?if ($parameter == "2"){?>Форма<?}?>
                                <?if ($parameter == "3"){?>Масштаб<?}?>
                            </th>
                            <th>Количество</th>
                        </tr>

                        <?
                            
                            for($ind = 0; $ind < $i; $ind++){
                        ?>

                        <tr onmouseover="this.style.background='rgba(0, 168, 247, 0.1)';" onmouseout="this.style.background='white';">
                            
                            <td>
                                <?=$type[$ind];?>
                            </td>
                            <td>
                                <?=$response[$ind];?>
                            </td>  
                            
                        </tr>
                        <?}?>

                    </table>
                <?}?>
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
