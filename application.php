<?php
    include 'connect.php';

    
    $id_user = $_COOKIE['id_user'];

    $id_z = $_POST['id_z'];
    $id_event = $_POST['id_event'];
    $type = $_POST['type'];

    $user_info = $mysql->query("SELECT `*`
                                FROM `users`
                                WHERE `id` = '$id_user'");

    while ($mas = mysqli_fetch_array($user_info))
    {
        $surname = $mas['surname'];
        $name = $mas['name'];
        $patronymic = $mas['patronymic'];
        if($patronymic=='-') {
            $patronymic = '';
        }
        $country = $mas['country'];
		$city = $mas['city'];
		$work = $mas['work'];
    	$post_address = $mas['post_address'];
		$degree = $mas['degree'];
        $title = $mas['title'];
        $email = $mas['email'];
        $phone_number = $mas['phone_number'];
    }

    $application = $mysql->query("SELECT `*`
                                FROM `participants`
                                WHERE `id_z` = '$id_z'");

    while ($mas = mysqli_fetch_array($application))
    {
        $type_participation = $mas['type_participation'];        
        $id_event = $mas['id_event'];
        $id_section_event = $mas['id_section_event'];
        $topic = $mas['topic'];
        $technic = $mas['technic'];
		$housing = $mas['housing'];
		$publication = $mas['publication'];
    	$scope = $mas['scope'];
		$activity = $mas['activity'];
    }

    $name_event = $mysql->query("SELECT `name`
                                FROM `events`
                                WHERE `id` = '$id_event'");
    $n_e = $name_event->fetch_assoc();
    $name_event = $n_e['name'];

    $name_section = $mysql->query("SELECT `name_rus`
                                FROM `section_event`
                                WHERE `id` = '$id_section_event'");
    $n_s = $name_section->fetch_assoc();
    $name_section = $n_s['name_rus'];

    $name_degree = $mysql->query("SELECT `name_rus`
                                FROM `degree_user`
                                WHERE `id` = '$degree'");
    $n_d = $name_degree->fetch_assoc();
    $name_degree = $n_d['name_rus'];

    $name_title = $mysql->query("SELECT `name_rus`
                                FROM `title_user`
                                WHERE `id` = '$title'");
    $n_t = $name_title->fetch_assoc();
    $name_title = $n_t['name_rus'];

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

    <title>Заявка на участие в "<?=$name_event;?>" | Events</title>

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

                <?if ($type != NULL){?>
                    <a href="../index.php">Главная</a><span class="image"></span>
                    <a href="organization_profile.php">Кабинет организатора</a><span class="image"></span>
                    <a href="participants_event.php?id_event=<?=$id_event;?>&type=<?=$type;?>">Участники</a><span class="image"></span>
                    <a href="#" class="selected_a">Просмотр заявки</a>
                <?} else {?>
                    <a href="../index.php">Главная</a><span class="image"></span>
                    <a href="profile.php">Личный кабинет</a><span class="image"></span>
                    <a href="my_events.php">Мероприятия</a><span class="image"></span>
                    <a href="#" class="selected_a">Просмотр заявки</a>
                <?}?>

            </div>
    </div>

    <!-- Main_part -->
    <div class="profile_area">
            <div class="profile_block">
                <div class="nav">
                    <a href="profile.php">Профиль</a>
                    <a href="my_events.php"  <?if ($type == NULL){?>class="selected_a"<?}?>>Мероприятия</a>
                    <a href="my_certificates.php">Сертификаты</a>
                    <a href="organization_profile.php" <?if ($type != NULL){?>class="selected_a"<?}?>>Кабинет организатора</a>
                </div>
                <h1>Заявка №<span style="font-family: 'Open Sans';"><?=$id_z;?></span></h1>            
        
        <div class="application">
            <div class="app_item">
                <div class="labels">Статус</div>
                <div class="values">
                <? if($activity == 1) {?>
                    Подтверждена
                <?}?>
                <? if($activity == 0) {?>
                    Отменено
                <?}?>
                <? if($activity == 2) {?>
                    Ожидает рассмотрения
                <?}?>
                <? if($activity == 3) {?>
                    Отказ
                <?}?>
                </div>
            </div>

            <div class="app_item">
                <div class="labels">Мероприятие</div>
                <div class="values"><?=$name_event;?></div>
            </div>

            <div class="app_item">
                <div class="labels">Секция</div>
                <div class="values"><?=$name_section;?></div>
            </div>

            <div class="app_item">
                <div class="labels">Тип участия</div>
                <div class="values"><?if ($type_participation == '1') {
                        echo "Докладчик";
                    } else {
                        echo "Слушатель";
                    }?></div>
            </div>

            <div class="app_item">
                <div class="labels">ФИО</div>
                <div class="values"><?=$surname;?> <?=$name;?> <?=$patronymic;?></div>
            </div>

            <div class="app_item">
                <div class="labels">Звание</div>
                <div class="values"><?=$name_title;?></div>
            </div>

            <div class="app_item">
                <div class="labels">Степень</div>
                <div class="values"><?=$name_degree;?></div>
            </div>

            <div class="app_item">
                <div class="labels">Место проживания</div>
                <div class="values"><?=$country;?>, <?=$city;?></div>
            </div>

            <div class="app_item">
                <div class="labels">Место работы</div>
                <div class="values"><?=$work;?></div>
            </div>

            <div class="app_item">
                <div class="labels">Эл. почта</div>
                <div class="values"><?=$email;?></div>
            </div>

            <div class="app_item">
                <div class="labels">Контактный телефон</div>
                <div class="values"><?=$phone_number;?></div>
            </div>

            <?if ($type_participation == '1') {?>

                <div class="app_item">
                    <div class="labels">Тема выступления</div>
                    <div class="values"><?=$topic;?></div>
                </div>

                <div class="app_item">
                    <div class="labels">Объём выступления (стр.)</div>
                    <div class="values"><?=$scope;?></div>
                </div>

                <div class="app_item">
                    <div class="labels">Потребность в публикации статьи</div>
                    <div class="values"><? if($publication == 1) {?>
                        Да
                    <?} else {?> Нет <?}?>
                    </div>
                </div>

                <div class="app_item">
                    <div class="labels">Оборудование для выступления</div>
                    <div class="values"><?=$technic;?></div>
                </div>

                <div class="app_item">
                    <div class="labels">Потребность в жилье</div>
                    <div class="values"><? if($housing == 1) {?>
                    Да
                <?} else {?> Нет <?}?>
            </div>
                </div>
            <?}?>

        </div>
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