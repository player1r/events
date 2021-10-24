<?php
    include 'connect.php';   

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
    
    $id_user = $_COOKIE['id_user'];

    $id_event = $_POST['id_event'];
    if ($id_event == NULL) {
        $id_event = $_GET['id_event'];
    }

    //Название мероприятия
    $name_conf = $mysql->query("SELECT `name` FROM `events` WHERE `id` = '$id_event'");
    $n = $name_conf->fetch_assoc();
    $name_conf = $n['name'];
    
    $section_event_res = $mysql->query("SELECT * FROM `section_event` WHERE `id_event` = '$id_event'");

    $section_event_count = $mysql->query("SELECT COUNT(*) FROM `section_event` WHERE `id_event` = '$id_event'");
    $s_e_c = $section_event_count->fetch_assoc();
    $section_event_count = $s_e_c['COUNT(*)'];
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

    <title>Заявка на участие | Events</title>
    

</head>

<body>
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
            <a href="event.php?id_conf=<?=$id_event;?>"><?=$name_conf;?></a><span class="image"></span>
            <a href="#" class="selected_a">Регистрация на мероприятие</a>            
        </div>
    </div>
    
    <!-- Form -->
    <div class="form_area">
            <div class="form_block">
                <h1>Выберите тип участия</h1>


    <? $choose = $_POST['radio']?>

    <form method="post" name="type_form" target="_self">
        <input name="radio" type="radio" value="0" <?if ($choose == "0") {?>checked<?}?>>Слушатель</input>
        </br>
        <input name="radio" type="radio" value="1" <?if ($choose == "1") {?>checked<?}?>>Докладчик</input>
        </br>
        <input name="id_event" type="hidden" value="<?=$id_event;?>">
    </form>


    <script>
    var radio = document.type_form.radio;
    for (var i = 0; i < radio.length; ++i) {
        radio[i].onchange = function () {
            document.type_form.submit();
        }
    }
    </script>


    <?if ($choose != NULL) {?>
        <form method="post" action="reg_z.php" target="_self">
            <? if ($section_event_count != '0') {?>
                <label>Секция</label>
                <select name="section_event" value="<?php echo $section_event;?>" required>
                    <option value="" disabled selected hidden></option>
                    <?php while($mas = mysqli_fetch_array($section_event_res))
                    {?>
                        <option value="<?=$mas['id']?>" <?if($section_event == $mas['id']){?>selected<?}?>><?echo $mas['name_rus'];?></option>
                    <?}?>
                </select>
                </br>
            <?} else {?>
                <input name="section_event" type="hidden" value="0">
            <?}?>

        <?if ($choose == "1") {?>
            <input placeholder="Тема выступления" name="topic" type="text" value="<?=$topic;?>" required>
            </br>            
            <input placeholder="Оборудование для выступления" name="technic" type="text" value="<?=$technic;?>" required>
            </br>            
            <input placeholder="Объём выстпуления, страниц" name="scope" type="number" value="<?=$scope;?>" required>
            </br>
            <select name="housing" value="<?php echo $housing;?>" required>
                <option value="" disabled selected>Потребность в жилье</option>
                <option value="1">Нуждаюсь в жилье</option>
                <option value="0">Не нуждаюсь в жилье</option>
            </select>
            </br>
            <select name="publication" value="<?php echo $publication;?>" required>
                <option value="" disabled selected>Потребность в публикации статьи</option>
                <option value="1">Нуждаюсь в публикации</option>
                <option value="0">Не нуждаюсь в публикации</option>
            </select>
            </br>
        <?}?>
            
            <input name="type_participation" type="hidden" value="<?=$choose;?>">
            <button type="submit" name="id_event" value="<?=$id_event;?>" onsubmit="return confirm('Вы подтверждаете корректность заявки?');">Отправить заявку</button>
        </form>
    <?}?>
        </div>
        </div>
        
    <!-- Конец PHP -->
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