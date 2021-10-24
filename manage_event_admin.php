<?php
    include 'connect.php';
    
    $id_user = $_COOKIE['id_user'];

    $admin = $mysql->query("SELECT `admin`
                        FROM `users`
                        WHERE `id` = '$id_user'");
    $adm = $admin->fetch_assoc();
    $admin = $adm['admin'];

    if($_POST) {
        $id_event = $_POST['id_event'];
    } else {
        $id_event = $_COOKIE['id_event'];
    }


    $current_date = new DateTime();
    
    $info = $mysql->query("SELECT `*`
                                FROM `events`
                                WHERE `id` = '$id_event'");

    $event_info = mysqli_fetch_array($info);

    $section_info = $mysql->query("SELECT `*`
                                FROM `section_event`
                                WHERE `id_event` = '$id_event'");

    $type_event_res = $mysql->query("SELECT * FROM `type_event`");
    $scale_event_res = $mysql->query("SELECT * FROM `scale_event`");
    $form_event_res = $mysql->query("SELECT * FROM `form_event`");

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
        $cancel = $_POST['cancel'];

        if ($cancel != 1) {
            $create_mode = $_POST['create'];
            if($create_mode == "0"){
                $create = 0;

                $name_event = filter_var(trim($_POST['name_event']),FILTER_SANITIZE_STRING);
                $place = filter_var(trim($_POST['place']),FILTER_SANITIZE_STRING);
                $address = filter_var(trim($_POST['address']),FILTER_SANITIZE_STRING);
                $about = filter_var(trim($_POST['about']),FILTER_SANITIZE_STRING);
                $form_event = filter_var(trim($_POST['form_event']),FILTER_SANITIZE_STRING);
                $type_event = filter_var(trim($_POST['type_event']),FILTER_SANITIZE_STRING);
                $scale_event = filter_var(trim($_POST['scale_event']),FILTER_SANITIZE_STRING);
                $sections_number = filter_var(trim($_POST['sections_number']),FILTER_SANITIZE_STRING);
                $name = filter_var(trim($_POST['background_image']),FILTER_SANITIZE_STRING);
                $date = filter_var(trim($_POST['date']),FILTER_SANITIZE_STRING);
                $time = filter_var(trim($_POST['time']),FILTER_SANITIZE_STRING);

                // Название <input type="file">
                $input_name = 'file';
                
                if (!empty($_FILES[$input_name])) {
                    // Разрешенные расширения файлов.
                    $allow = array();
                    
                    // Запрещенные расширения файлов.
                    $deny = array(
                        'phtml', 'php', 'php3', 'php4', 'php5', 'php6', 'php7', 'phps', 'cgi', 'pl', 'asp', 
                        'aspx', 'shtml', 'shtm', 'htaccess', 'htpasswd', 'ini', 'log', 'sh', 'js', 'html', 
                        'htm', 'css', 'sql', 'spl', 'scgi', 'fcgi'
                    );
                    
                    // Директория куда будут загружаться файлы.
                    $path = __DIR__ . '../../assets/images/';
                    
                    if (isset($_FILES[$input_name])) {
                        // Проверим директорию для загрузки.
                        if (!is_dir($path)) {
                            mkdir($path, 0777, true);
                        }
                    
                        // Преобразуем массив $_FILES в удобный вид для перебора в foreach.
                        $files = array();
                        $diff = count($_FILES[$input_name]) - count($_FILES[$input_name], COUNT_RECURSIVE);
                        if ($diff == 0) {
                            $files = array($_FILES[$input_name]);
                        } else {
                            foreach($_FILES[$input_name] as $k => $l) {
                                foreach($l as $i => $v) {
                                    $files[$i][$k] = $v;
                                }
                            }		
                        }	
                        
                        foreach ($files as $file) {
                            $error = $success = '';
                    
                            // Проверим на ошибки загрузки.
                            if (!empty($file['error']) || empty($file['tmp_name'])) {
                                switch (@$file['error']) {
                                    case 1:
                                    case 2: $error = 'Превышен размер загружаемого файла.'; break;
                                    case 3: $error = 'Файл был получен только частично.'; break;
                                    case 4: $error = 'Файл не был загружен.'; break;
                                    case 6: $error = 'Файл не загружен - отсутствует временная директория.'; break;
                                    case 7: $error = 'Не удалось записать файл на диск.'; break;
                                    case 8: $error = 'PHP-расширение остановило загрузку файла.'; break;
                                    case 9: $error = 'Файл не был загружен - директория не существует.'; break;
                                    case 10: $error = 'Превышен максимально допустимый размер файла.'; break;
                                    case 11: $error = 'Данный тип файла запрещен.'; break;
                                    case 12: $error = 'Ошибка при копировании файла.'; break;
                                    default: $error = 'Файл не был загружен - неизвестная ошибка.'; break;
                                }
                            } elseif ($file['tmp_name'] == 'none' || !is_uploaded_file($file['tmp_name'])) {
                                $error = 'Не удалось загрузить файл.';
                            } else {
                                // Оставляем в имени файла только буквы, цифры и некоторые символы.
                                $pattern = "[^a-zа-яё0-9,~!@#%^-_\$\?\(\)\{\}\[\]\.]";
                                $name = mb_eregi_replace($pattern, '-', $file['name']);
                                $name = mb_ereg_replace('[-]+', '-', $name);
                                
                                // Т.к. есть проблема с кириллицей в названиях файлов (файлы становятся недоступны).
                                // Сделаем их транслит:
                                $converter = array(
                                    'а' => 'a',   'б' => 'b',   'в' => 'v',    'г' => 'g',   'д' => 'd',   'е' => 'e',
                                    'ё' => 'e',   'ж' => 'zh',  'з' => 'z',    'и' => 'i',   'й' => 'y',   'к' => 'k',
                                    'л' => 'l',   'м' => 'm',   'н' => 'n',    'о' => 'o',   'п' => 'p',   'р' => 'r',
                                    'с' => 's',   'т' => 't',   'у' => 'u',    'ф' => 'f',   'х' => 'h',   'ц' => 'c',
                                    'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',  'ь' => '',    'ы' => 'y',   'ъ' => '',
                                    'э' => 'e',   'ю' => 'yu',  'я' => 'ya', 
                                
                                    'А' => 'A',   'Б' => 'B',   'В' => 'V',    'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
                                    'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',    'И' => 'I',   'Й' => 'Y',   'К' => 'K',
                                    'Л' => 'L',   'М' => 'M',   'Н' => 'N',    'О' => 'O',   'П' => 'P',   'Р' => 'R',
                                    'С' => 'S',   'Т' => 'T',   'У' => 'U',    'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
                                    'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',  'Ь' => '',    'Ы' => 'Y',   'Ъ' => '',
                                    'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
                                );
                    
                                $name = strtr($name, $converter);
                                $parts = pathinfo($name);
                    
                                if (empty($name) || empty($parts['extension'])) {
                                    $error = 'Недопустимое тип файла';
                                } elseif (!empty($allow) && !in_array(strtolower($parts['extension']), $allow)) {
                                    $error = 'Недопустимый тип файла';
                                } elseif (!empty($deny) && in_array(strtolower($parts['extension']), $deny)) {
                                    $error = 'Недопустимый тип файла';
                                } else {
                                    // Чтобы не затереть файл с таким же названием, добавим префикс.
                                    $i = 0;
                                    $prefix = '';
                                    while (is_file($path . $parts['filename'] . $prefix . '.' . $parts['extension'])) {
                                        $prefix = '(' . ++$i . ')';
                                    }
                                    $name = $parts['filename'] . $prefix . '.' . $parts['extension'];
                    
                                    // Перемещаем файл в директорию.
                                    if (move_uploaded_file($file['tmp_name'], $path . $name)) {
                                        // Далее можно сохранить название файла в БД и т.п.
                                        $success = 'Файл «' . $name . '» успешно загружен.';
                                    } else {
                                        $error = 'Не удалось загрузить файл.';
                                    }
                                }
                            }
                            
                            // Выводим сообщение о результате загрузки.
                            // if (!empty($success)) {
                            //     echo '<p>' . $success . '</p>';		
                            // } else {
                            //     echo '<p>' . $error . '</p>';
                            // }
                        }
                    }
                }

                // текст запроса
                $mysql->query(
                    "UPDATE `events` SET
                    `name` = '$name_event', 
                    `about` = '$about', 
                    `place` = '$place', 
                    `address` = '$address', 
                    `type` = '$type_event', 
                    `scale` = '$scale_event', 
                    `form` = '$form_event',
                    `background_image` = '$name', 
                    `date` = '$date', 
                    `time` = '$time'
                    WHERE `id` = '$id_event';");

                for ($x = 0; $x < $sections_number; $x++)
                {
                    $name_rus = $_POST['section_name'][$x];
                    $id_name_rus = $_POST['section_id'][$x];
                    $mysql->query("UPDATE `section_event` SET
                                    `name_rus` = '$name_rus'
                                    WHERE `id` = '$id_name_rus';");
                    
                }

                setcookie('id_event', $id_event, time() + 3600, "/");
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

    <title>Просмотр мероприятия | Events</title>

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
                <a href="#" class="selected_a">Просмотр мероприятия</a>
            </div>
    </div>

    

    <!-- Main_part -->
    <div class="profile_area" style="margin-bottom: 150px;">
            <div class="profile_block">
                <h1>Панель администратора</h1>
                <div class="nav">
                    <a href="admin_panel.php">Профиль</a>
                    <a href="organization_profile_admin.php" class="selected_a">Мероприятия</a>
                    <a href="users_admin.php">Пользователи</a>
                </div>
                <div class="info">

                <form action="<? echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                    <!-- Название -->
                    <div class="input_style">
                        <div class="labels">
                            Название
                        </div>
                        <div class="inputs">
                            <input required type="text" name="name_event" placeholder="Название" maxlength="100" value="<?=$event_info['name']?>" <?if($create == 0){?>disabled<?}?>>
                        </div>
                    </div>

                    <!-- Описание -->
                    <div class="input_style">
                        <div class="labels">
                            Описание
                        </div>
                        <div class="inputs">
                            <textarea required type="text" name="about" placeholder="Описание" maxlength="1500" <?if($create == 0){?>disabled<?}?>><?=$event_info['about']?></textarea>
                        </div>
                    </div>

                    <!-- Место проведения -->
                    <div class="input_style">
                        <div class="labels">
                            Место проведения
                        </div>
                        <div class="inputs">
                            <input required type="text" name="place" placeholder="Место" maxlength="300" value="<?=$event_info['place']?>" <?if($create == 0){?>disabled<?}?>>
                        </div>
                    </div>

                    <!-- Адрес места проведения -->
                    <div class="input_style">
                        <div class="labels">
                            Адрес проведения
                        </div>
                        <div class="inputs">
                            <input required type="text" name="address" placeholder="Адрес" maxlength="300" value="<?=$event_info['address']?>" <?if($create == 0){?>disabled<?}?>>
                        </div>
                    </div>


                    <!-- Дата проведения -->
                    <div class="input_style">
                        <div class="labels">
                        Дата
                        </div>
                        <div class="inputs">
                            <input required type="date" name="date" maxlength="300" min="<?=$current_date->format('Y-m-d');?>" value="<?=$event_info['date']?>" <?if($create == 0){?>disabled<?}?>>
                        </div>
                    </div>

                    <!-- Время проведения -->
                    <div class="input_style">
                        <div class="labels">
                        Время
                        </div>
                        <div class="inputs">
                            <input required type="time" name="time" maxlength="300" value="<?=$event_info['time']?>" <?if($create == 0){?>disabled<?}?>>
                        </div>
                    </div>

                    <!-- Тип -->
                    <div class="input_style">
                        <div class="labels">
                        Тип
                        </div>
                        <div class="inputs">
                            <select name="type_event" value="<?php echo $type_event;?>" required <?if($create == 0){?>disabled<?}?>>
                            <option value="" disabled selected hidden></option>
                                        
                            <?php while($mas = mysqli_fetch_array($type_event_res))
                            {?>
                                <option value="<?=$mas['id']?>" <?if($event_info['type'] == $mas['id']){?>selected<?}?>><?echo $mas['name_rus'];?></option>
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
                            <select name="scale_event" value="<?php echo $scale_event;?>" required <?if($create == 0){?>disabled<?}?>>
                            <option value="" disabled selected hidden></option>
                            <?php while($mas = mysqli_fetch_array($scale_event_res))
                            {?>
                                <option value="<?=$mas['id']?>" <?if($event_info['scale'] == $mas['id']){?>selected<?}?>><?echo $mas['name_rus'];?></option>
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
                            <select name="form_event" value="<?php echo $form_event;?>" required <?if($create == 0){?>disabled<?}?>>
                            <option value="" disabled selected hidden></option>
                            <?php while($mas = mysqli_fetch_array($form_event_res))
                            {?>
                                <option value="<?=$mas['id']?>" <?if($event_info['form'] == $mas['id']){?>selected<?}?>><?echo $mas['name_rus'];?></option>
                            <?}?>
                            </select>
                        </div>
                    </div>

                    <!-- Заставка -->
                    <div class="input_style"  style="margin-top: 10px;">
                        <div class="labels">
                            Фоновое изображение
                        </div>
                        <div class="inputs">
                            <input type="hidden" name="background_image" value="<?=$event_info['background_image'];?>">
                            <a class="ac" href="../assets/images/<?=$event_info['background_image'];?>" target="_blank"><?=$event_info['background_image'];?></a>
                            <?if($create != 0) {?>
                                </br>
                                <input type="file" name="file" accept="image/jpeg,image/png" >
                            <?}?>
                        </div>
                    </div>

            <?$count = 0;?>
            <?while ($mas = mysqli_fetch_array($section_info)){?>
                    <div class="input_style">
                        <div class="labels">
                            Секция №<?$count+1;?>
                        </div>
                        <div class="inputs">
                            <input type="hidden" name="section_id[]" value="<?=$mas['id'];?>">
                            <input required type="text" name="section_name[]" maxlength="50" value="<?=$mas['name_rus'];?>" <?if($create == 0){?>disabled<?}?>>
                        </div>
                    </div>

                <br/>
                <?$count++;?>
            <?}?>

            <input type="hidden" name="sections_number" value="<?=$count;?>">
            <input type="hidden" name="id_event" value="<?=$id_event;?>">
            <div class="input_style">
                <div class="right">
        <?if ($event_info['status'] == '1') {?>
            <button type="submit" name="create" value="<?if($create == 0){?>1<?} else {?>0<?}?>"><?if($create == 0){?>Изменить<?} else {?>Сохранить<?}?></button>
        <?}?>
        </form>
        </div>
                <div class="left">
                <?if($create == 1){?>
                <form action="<? echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                    <input type="hidden" name="id_event" value="<?=$id_event;?>">
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

<?} else { echo "Запрашиваемый ресурс не найден";}?>
</body>
</html>
