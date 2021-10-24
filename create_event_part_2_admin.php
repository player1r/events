<?php
    include 'connect.php';
    
    $id_user = $_COOKIE['id_user'];

    $admin = $mysql->query("SELECT `admin`
                        FROM `users`
                        WHERE `id` = '$id_user'");
    $adm = $admin->fetch_assoc();
    $admin = $adm['admin'];

    $name = $place = $address = $about = $form_event = $type_event = $scale_event = $sections_number = $date = $time = '';

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
        <h3>Добавление секций</h3>

        
        <form method="post" action="create_event_success_admin.php" enctype="multipart/form-data" target="_self">
            <!-- ID -->
            <input type="hidden" name="id_event" value="<?=$id_event?>">
            <!-- Название -->
            <input type="hidden" name="name" value="<?=$name?>">
            <!-- Описание -->
            <textarea style="display:none;" name="about"><?=$about?></textarea>
            <!-- Место -->
            <input type="hidden" name="place" value="<?=$place?>" >
            <!-- Адрес -->
            <input type="hidden" name="address" value="<?=$address?>">
            <!-- Количество секций -->
            <input type="hidden" name="sections_number" value="<?=$sections_number?>">
            <!-- Дата -->
            <input type="hidden" name="date" value="<?=$date?>">
            <!-- Время -->
            <input type="hidden" name="time" value="<?=$time?>">
            <!-- Форма -->
            <input type="hidden" name="form_event" value="<?=$form_event?>">
            <!-- Тип -->
            <input type="hidden" name="type_event" value="<?=$type_event?>">
            <!-- Масштаб -->
            <input type="hidden" name="scale_event" value="<?=$scale_event?>">

            <?for($i=0; $i < $sections_number; $i++) {?>
            <!-- Название секции-->
                <input type="text" name="name_section[]" placeholder="Название секции" required>
                </br>
            <?}?>

            <label>Заставка</label>
            <input type="file" name="file" accept="image/jpeg,image/png">
            </br>

            <button type="submit">Создать</button>
        </form>

        
            
        <form method="post" action="create_event_admin.php" target="_self">
            <!-- ID -->
            <input type="hidden" name="id_event" value="<?=$id_event?>">
            <!-- Название -->
            <input type="hidden" name="name" value="<?=$name?>">
            <!-- Описание -->
            <textarea style="display:none;" name="about"><?=$about?></textarea>
            <!-- Место -->
            <input type="hidden" name="place" value="<?=$place?>" >
            <!-- Адрес -->
            <input type="hidden" name="address" value="<?=$address?>">
            <!-- Количество секций -->
            <input type="hidden" name="sections_number" value="<?=$sections_number?>">
            <!-- Дата -->
            <input type="hidden" name="date" value="<?=$date?>">
            <!-- Время -->
            <input type="hidden" name="time" value="<?=$time?>">
            <!-- Форма -->
            <input type="hidden" name="form_event" value="<?=$form_event?>">
            <!-- Тип -->
            <input type="hidden" name="type_event" value="<?=$type_event?>">
            <!-- Масштаб -->
            <input type="hidden" name="scale_event" value="<?=$scale_event?>">
        
            <button type="submit">Назад</button>
        </form>
        
        <a href="organization_profile_admin.php">Отмена</a>


        <? $mysql->close();?>

    <!-- Footer -->
    <div class="footer_m_p">
        ©2021 Events
    </div>

<?} else { echo "Запрашиваемый ресурс не найден";}?>
</body>
</html>
