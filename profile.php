<?php
    include 'connect.php';
    
    $id_user = $_COOKIE['id_user'];
    
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

                $surname = filter_var(trim($_POST['surname']),FILTER_SANITIZE_STRING);
                $name = filter_var(trim($_POST['name']),FILTER_SANITIZE_STRING);
                $patronymic = filter_var(trim($_POST['patronymic']),FILTER_SANITIZE_STRING);
                $gender = filter_var(trim($_POST['gender']),FILTER_SANITIZE_STRING);
                $birthday = filter_var(trim($_POST['birthday']),FILTER_SANITIZE_STRING);
                $country = filter_var(trim($_POST['country']),FILTER_SANITIZE_STRING);
                $city = filter_var(trim($_POST['city']),FILTER_SANITIZE_STRING);
                $work = filter_var(trim($_POST['work']),FILTER_SANITIZE_STRING);
                $degree = filter_var(trim($_POST['degree']),FILTER_SANITIZE_STRING);
                $title = filter_var(trim($_POST['title']),FILTER_SANITIZE_STRING);
                $email = filter_var(trim($_POST['email']),FILTER_SANITIZE_STRING);
                $phone_number = filter_var(trim($_POST['phone_number']),FILTER_SANITIZE_STRING);
                $address = filter_var(trim($_POST['address']),FILTER_SANITIZE_STRING);

                //текст запроса
                $mysql->query(
                    "UPDATE `users` SET
                    `email` = '$email',
                    `phone_number` = '$phone_number',
                    `surname` = '$surname',
                    `name` = '$name',
                    `patronymic` = '$patronymic',
                    `country` = '$country',
                    `city` = '$city',
                    `work` = '$work',
                    `post_address` = '$address',
                    `degree` = '$degree',
                    `title` = '$title',
                    `gender` = '$gender',
                    `birthday` = '$birthday'
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

    <title>Профиль | Events</title>

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
    
    

    <!-- Main -->
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
                    <a href="profile.php" class="selected_a">Профиль</a>
                    <a href="my_events.php">Мероприятия</a>
                    <a href="my_certificates.php">Сертификаты</a>
                    <?if ($org_status == 1) {?>
                        <a href="organization_profile.php">Кабинет организатора</a>
                    <?}?>
                </div>
                <div class="info">

                    <form action="<? echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                    <!-- Фамилия -->
                    <div class="input_style">
                        <div class="labels">
                            Фамилия
                        </div>
                        <div class="inputs">
                            <input type="text" name="surname" placeholder="-" maxlength="50" value="<?=$user_info['surname']?>" <?if($create == 0){?>disabled<?}?>>
                            <?=$surname_error;?>
                        </div>
                    </div>

                    <!-- Имя -->
                    <div class="input_style">
                        <div class="labels">
                            Имя
                        </div>
                        <div class="inputs">
                            <input type="text" name="name" placeholder="-" maxlength="50" value="<?=$user_info['name']?>" <?if($create == 0){?>disabled<?}?>>
                            <?=$name_error;?>
                        </div>
                    </div>

                    <!-- Отчество -->
                    <div class="input_style">
                        <div class="labels">
                            Отчество
                        </div>
                        <div class="inputs">
                            <input type="text" name="patronymic" placeholder="-" maxlength="50" value="<?=$user_info['patronymic']?>" <?if($create == 0){?>disabled<?}?>><br/>
                        </div>
                    </div>

                     <!-- Пол -->
                     <div class="input_style">
                        <div class="labels">
                            Пол
                        </div>
                        <div class="inputs">
                            <select name="gender" value="<?php echo $gender;?>" <?if($create == 0){?>disabled<?}?>>
                            <?if ($user_info['gender'] == '1') {?>
                                <option value="0">Женский</option>
                                <option selected value="1">Мужской</option>
                            <?}?>

                            <?if ($user_info['gender'] == '0') {?>
                                <option selected value="0">Женский</option>
                                <option value="1">Мужской</option>
                            <?}?>
                            </select>
                            <?=$gender_error;?>
                        </div>
                    </div>

                    <!-- Дата рождения -->
                    <div class="input_style">
                        <div class="labels">
                            Дата рождения
                        </div>
                        <div class="inputs">
                            <input type="date" name="birthday" placeholder="-" value="<?=$user_info['birthday'];?>" min="<?=$min_date->format('Y-m-d');?>" max="<?=$max_date->format('Y-m-d');?>" <?if($create == 0){?>disabled<?}?>>
                            <?=$birthday_error;?>
                        </div>
                    </div>

                    <!-- Страна -->
                    <div class="input_style">
                        <div class="labels">
                            Страна
                        </div>
                        <div class="inputs">
                            <input type="text" name="country" placeholder="-" value="<?=$user_info['country']?>" <?if($create == 0){?>disabled<?}?>>
                            <?=$country_error;?>
                        </div>
                    </div>

                    <!-- Город -->
                    <div class="input_style">
                        <div class="labels">
                            Город
                        </div>
                        <div class="inputs">
                            <input type="text" name="city" placeholder="-" value="<?=$user_info['city']?>" <?if($create == 0){?>disabled<?}?>>
                            <?=$city_error;?>
                        </div>
                    </div>

                    <!-- Место работы -->
                    <div class="input_style">
                        <div class="labels">
                            Место работы
                        </div>
                        <div class="inputs">
                            <input type="text" name="work" placeholder="-" value="<?=htmlspecialchars($user_info['work']);?>" <?if($create == 0){?>disabled<?}?>><br/>
                        </div>
                    </div>

                    <!-- Учёная степень -->
                    <div class="input_style">
                        <div class="labels">
                            Учёная степень
                        </div>
                        <div class="inputs">
                            <select name="degree" <?if($create == 0){?>disabled<?}?>>
                            <option value="" disabled selected hidden></option>
                            <?php while($mas = mysqli_fetch_array($degree_res))
                            {?>
                                <option value="<?=$mas['id']?>" <?if($user_info['degree'] == $mas['id']){?>selected<?}?>><?echo $mas['name_rus'];?></option>
                            <?}?>
                            </select>
                            <?=$degree_error;?>
                        </div>
                    </div>

                    <!-- Учёное звание -->
                    <div class="input_style">
                        <div class="labels">
                            Учёное звание
                        </div>
                        <div class="inputs">
                            <select name="title" value="<?php echo $title;?>" <?if($create == 0){?>disabled<?}?>>
                            <option value="" disabled selected hidden></option>
                            <?php while($mas = mysqli_fetch_array($title_res))
                            {?>
                                <option value="<?=$mas['id']?>" <?if($user_info['title'] == $mas['id']){?>selected<?}?>><?echo $mas['name_rus'];?></option>
                            <?}?>
                            </select>
                            <?=$title_error;?>
                        </div>
                    </div>

                    <!-- Электронная почта -->
                    <div class="input_style">
                        <div class="labels">
                            E-mail
                        </div>
                        <div class="inputs">
                            <input type="email" name="email" placeholder="-" value="<?=$user_info['email']?>" <?if($create == 0){?>disabled<?}?>>
                            <?=$email_error;?>
                        </div>
                    </div>

                    <!-- Телефон -->
                    <div class="input_style">
                        <div class="labels">
                            Телефон
                        </div>
                        <div class="inputs">
                            <input type="text" name="phone_number" placeholder="-" value="<?=$user_info['phone_number']?>" <?if($create == 0){?>disabled<?}?>>
                            <?=$phone_number_error;?>
                        </div>
                    </div>

                    <!-- Почтовый адрес -->
                    <div class="input_style">
                        <div class="labels">
                            Почтовый адрес
                        </div>
                        <div class="inputs">
                            <input type="text" name="address" placeholder="-" value="<?=$user_info['post_address']?>" <?if($create == 0){?>disabled<?}?>>
                            <?=$address_error;?>
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

</body>
</html>
