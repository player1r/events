<?php
    include 'connect.php';
    
    $id_user = $_COOKIE['id_user'];

    $admin = $mysql->query("SELECT `admin` FROM `users` WHERE `id` = '$id_user'");
    $adm = $admin->fetch_assoc();
    $admin = $adm['admin'];  
    
    //Дата рождения
        //Наибольшая дата
        $max_date = new DateTime();
        $max_date->modify('-3 years');

        //Наименьшая дата
        $min_date = new DateTime();
        $min_date->modify('-130 years');

    $active_events = $mysql->query("SELECT `*` FROM `events` WHERE `status` = '1' LIMIT 0,7");
    $past_events = $mysql->query("SELECT `*` FROM `events` WHERE `status` = '0' LIMIT 0,5");

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


    $info = $mysql->query("SELECT `*` FROM `users` WHERE `id` = '$id_user'");
    $degree_res = $mysql->query("SELECT * FROM `degree_user`");
    $title_res = $mysql->query("SELECT * FROM `title_user`");

    $user_info = mysqli_fetch_array($info);

    if(!empty($_POST)){
        $cancel = $_POST['cancel'];

        if ($cancel != 1) {
            $create_mode = $_POST['create'];
            if($create_mode == "0"){
                $create = 0;

                $email = filter_var(trim($_POST['email']),FILTER_SANITIZE_STRING);

                //текст запроса
                $mysql->query(
                    "UPDATE `users` SET
                    `email` = '$email'
                    WHERE `id` = '$id_user';");

                header("Refresh: 0;"); 

            }
            if($create_mode == "1"){
                $create = 1;
            }
        }
    } else {
        $create = 0;
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

    <title>Панель администратора | Events</title>

</head>

<body>

    
    

    <?if ($admin == '1') {?>
  
    
    

    <!-- Main -->
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
        <div class="profile_area" style="margin-bottom: 220px;">
            <div class="profile_block">
                <h1>Панель администратора</h1>
                <div class="nav">
                    <a href="admin_panel.php" class="selected_a">Профиль</a>
                    <a href="organization_profile_admin.php">Мероприятия</a>
                    <a href="users_admin.php">Пользователи</a>
                </div>
                <div class="info">

                <form action="<? echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        
            <!-- Электронная почта -->
            <div class="input_style">
                <div class="labels">
                    Логин
                </div>
                <div class="inputs">
                    <input type="email" name="email" placeholder="Электронная почта" value="<?=$user_info['email']?>" <?if($create == 0){?>disabled<?}?>>
                </div>
            </div>

            <div class="input_style">
                <div class="right">
                    <button type="submit" name="create" value="<?if($create == 0){?>1<?} else {?>0<?}?>"><?if($create == 0){?>Изменить<?} else {?>Сохранить<?}?></button>
                    </form> 
                </div>
                <div class="left">
                    <?if($create == 1){?>
                    <form action="<? echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                        <button type="submit" name="cancel" value="1">Отмена</button>
                    </form>
                    <?}?>
                </div>
            </div>
            

            
            

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
    <?} else {?>
        Запрашиваемый ресур не найден
    <?}?>
</body>
</html>
