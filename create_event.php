<?php
    include 'connect.php';
    
    $id_user = $_COOKIE['id_user'];

    $current_date = new DateTime();

    $type_event_res = $mysql->query("SELECT * FROM `type_event`");
    $scale_event_res = $mysql->query("SELECT * FROM `scale_event`");
    $form_event_res = $mysql->query("SELECT * FROM `form_event`");
    
    $id_event = uniqid();

    if($_POST){
        $name = filter_var(trim($_POST['name']),FILTER_SANITIZE_STRING);
        $place = filter_var(trim($_POST['place']),FILTER_SANITIZE_STRING);
        $address = filter_var(trim($_POST['address']),FILTER_SANITIZE_STRING);
        $about = filter_var(trim($_POST['about']),FILTER_SANITIZE_STRING);
        $form_event = filter_var(trim($_POST['form_event']),FILTER_SANITIZE_STRING);
        $type_event = filter_var(trim($_POST['type_event']),FILTER_SANITIZE_STRING);
        $scale_event = filter_var(trim($_POST['scale_event']),FILTER_SANITIZE_STRING);
        $sections_number = filter_var(trim($_POST['sections_number']),FILTER_SANITIZE_STRING);
        $date = filter_var(trim($_POST['date']),FILTER_SANITIZE_STRING);
        $time = filter_var(trim($_POST['time']),FILTER_SANITIZE_STRING);
        $id_event = filter_var(trim($_POST['id_event']),FILTER_SANITIZE_STRING);
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

    <title>Новое мероприятие | Events</title>

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
                    <a href="my_events.php">Мероприятия</a>
                    <a href="my_certificates.php">Сертификаты</a>
                    <a href="organization_profile.php" class="selected_a">Кабинет организатора</a>
                </div>
                <h2>Новое мероприятие</h2>
                <div class="info">
                    
        <form method="post" action="create_event_part_2.php" target="_self">
        <!-- ID -->
            <input type="hidden" name="id_event" value="<?=$id_event?>">
        <!-- Название -->
            <div class="input_style">
                <div class="labels">
                    Название мероприятия
                </div>
                <div class="inputs">
                    <input type="text" name="name" value="<?=$name?>" required>
                </div>
            </div>

        <!-- Описание -->
            <div class="input_style">
                <div class="labels">
                    Описание мероприятия
                </div>
                <div class="inputs">
                    <textarea name="about" required><?=$about?></textarea>
                </div>
            </div>

        <!-- Дата -->
            <div class="input_style">
                <div class="labels">
                    Дата проведения
                </div>
                <div class="inputs">
                    <input type="date" name="date" value="<?=$date?>" min="<?=$current_date->format('Y-m-d');?>" required>
                </div>
            </div>

        <!-- Время -->
            <div class="input_style">
                <div class="labels">
                    Время проведения
                </div>
                <div class="inputs">
                    <input type="time" name="time" value="<?=$time?>" required>
                </div>
            </div>

        <!-- Место -->
            <div class="input_style">
                <div class="labels">
                    Место проведения
                </div>
                <div class="inputs">
                    <input type="text" name="place" value="<?=$place?>" required>
                </div>
            </div>

        <!-- Адрес -->
            <div class="input_style">
                <div class="labels">
                    Адрес места проведения
                </div>
                <div class="inputs">
                    <input type="text" name="address" value="<?=$address?>" required>
                </div>
            </div>

        <!-- Тип -->
            <div class="input_style">
                <div class="labels">
                    Тип
                </div>
                <div class="inputs">
                <select name="type_event" value="<?php echo $type_event;?>" required>
                    <option value="" disabled selected hidden></option>
                                
                    <?php while($mas = mysqli_fetch_array($type_event_res))
                    {?>
                        <option value="<?=$mas['id']?>" <?if($type_event == $mas['id']){?>selected<?}?>><?echo $mas['name_rus'];?></option>
                    <?}?>
                </select>
                </div>
            </div>

        <!-- Масштаб -->
            <div class="input_style">
                <div class="labels">
                    Масштаб
                </div>
                <div class="inputs">
                <select name="scale_event" value="<?php echo $scale_event;?>" required>
                <option value="" disabled selected hidden></option>
                <?php while($mas = mysqli_fetch_array($scale_event_res))
                {?>
                    <option value="<?=$mas['id']?>" <?if($scale_event == $mas['id']){?>selected<?}?>><?echo $mas['name_rus'];?></option>
                <?}?>
                </select>
                </div>
            </div>

        <!-- Форма -->
            <div class="input_style">
                <div class="labels">
                    Форма
                </div>
                <div class="inputs">
                <select name="form_event" value="<?php echo $form_event;?>" required>
                <option value="" disabled selected hidden></option>
                <?php while($mas = mysqli_fetch_array($form_event_res))
                {?>
                    <option value="<?=$mas['id']?>" <?if($form_event == $mas['id']){?>selected<?}?>><?echo $mas['name_rus'];?></option>
                <?}?>
                </select>
                </div>
            </div>

         <!-- Количество секций -->
         <div class="input_style">
                <div class="labels">
                    Количество секций
                </div>
                <div class="inputs">
                    <input type="number" name="sections_number" value="<?=$sections_number?>" required>
                </div>
            </div>

        
            <button type="submit">Далее</button>
        </form>
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
