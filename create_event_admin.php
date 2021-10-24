<?php
    include 'connect.php';
    
    $id_user = $_COOKIE['id_user'];

    $admin = $mysql->query("SELECT `admin`
                        FROM `users`
                        WHERE `id` = '$id_user'");
    $adm = $admin->fetch_assoc();
    $admin = $adm['admin'];

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

    <title>Новое мероприятие | Events</title>

</head>

<body>

<?if ($admin == '1') {?>
    
    <?if ($_COOKIE['id_user'] == null) {?>
        <a href="signin.php">Войти</a>
    <?} else {?>
        <form method="post" action="exit.php" target="_self">
            <button type="submit">Выход</button>
        </form>
    <?}?>

    

    <!-- Main -->
    <p><a href="../index.php">Главная</a> / <a href="admin_panel.php">Панель администратора</a> / <a href="organization_profile_admin.php">Мероприятия</a> / Создание мероприятия</p>

        <h1>Панель администратора</h1>

        <p><a href="admin_panel.php">Профиль</a> | <a href="organization_profile_admin.php">Мероприятия</a> | <a href="users_admin.php">Пользователи</a></p>
        
        <h2>Новое мероприятие</h2>

        
        <form method="post" action="create_event_part_2_admin.php" target="_self">
        <!-- ID -->
            <input type="hidden" name="id_event" value="<?=$id_event?>">
        <!-- Название -->
            <input type="text" name="name" placeholder="Название мероприятия" value="<?=$name?>" required>
            </br>
        <!-- Описание -->
            <textarea name="about" placeholder="Описание мероприятия" required><?=$about?></textarea>
            </br>
        <!-- Дата -->
            <input type="date" name="date" placeholder="Дата проведения" value="<?=$date?>" min="<?=$current_date->format('Y-m-d');?>" required>
            </br>    
        <!-- Время -->
            <input type="time" name="time" placeholder="Время проведения" value="<?=$time?>" required>
            </br> 
        <!-- Место -->
            <input type="text" name="place" placeholder="Место проведения" value="<?=$place?>" required>
            </br>
        <!-- Адрес -->
            <input type="text" name="address" placeholder="Адрес места проведения" value="<?=$address?>" required>
            </br>
        <!-- Тип -->
        <label>Тип</label>
            <select name="type_event" value="<?php echo $type_event;?>" required>
            <option value="" disabled selected hidden></option>
                        
            <?php while($mas = mysqli_fetch_array($type_event_res))
            {?>
                <option value="<?=$mas['id']?>" <?if($type_event == $mas['id']){?>selected<?}?>><?echo $mas['name_rus'];?></option>
            <?}?>

            </select>
            </br>
        <!-- Масштаб -->
        <label>Масштаб</label>
        <select name="scale_event" value="<?php echo $scale_event;?>" required>
            <option value="" disabled selected hidden></option>
            <?php while($mas = mysqli_fetch_array($scale_event_res))
            {?>
                <option value="<?=$mas['id']?>" <?if($scale_event == $mas['id']){?>selected<?}?>><?echo $mas['name_rus'];?></option>
            <?}?>
            </select>
            </br>
        <!-- Форма -->
        <label>Форма</label>
        <select name="form_event" value="<?php echo $form_event;?>" required>
            <option value="" disabled selected hidden></option>
            <?php while($mas = mysqli_fetch_array($form_event_res))
            {?>
                <option value="<?=$mas['id']?>" <?if($form_event == $mas['id']){?>selected<?}?>><?echo $mas['name_rus'];?></option>
            <?}?>
            </select>
            </br>
         <!-- Количество секций -->
         <input type="number" name="sections_number" placeholder="Количество секций" value="<?=$sections_number?>" required>
            </br>
        
            <button type="submit">Далее</button>
        </form>
        
        


        <? $mysql->close();?>

    <!-- Footer -->
    <div class="footer_m_p">
        ©2021 Events
    </div>

    <?} else { echo "Запрашиваемый ресурс не найден";}?>
</body>
</html>
