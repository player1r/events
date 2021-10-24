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
        $type = filter_var(trim($_POST['type']),FILTER_SANITIZE_STRING);
        $form = filter_var(trim($_POST['form']),FILTER_SANITIZE_STRING);
        $scale = filter_var(trim($_POST['scale']),FILTER_SANITIZE_STRING);
        $date_min = filter_var(trim($_POST['date_min']),FILTER_SANITIZE_STRING);
        $date_max = filter_var(trim($_POST['date_max']),FILTER_SANITIZE_STRING);
        $submit = filter_var(trim($_POST['submit']),FILTER_SANITIZE_STRING);


        if((empty($type))&&(empty($scale))&&(empty($form))&&(empty($date_min))&&(empty($date_max))) {
            $error = 1;
        } else {

            if(!empty($type)){
                $type_request = "= '".$type."'";
            }

            if(!empty($form)){
                $form_request = "= '".$form."'";
            }

            if(!empty($scale)){
                $scale_request = "= '".$scale."'";
            }

            if(!empty($date_min)){
                $date_min_request = ">= '".$date_min."'";
            }

            if(!empty($date_max)){
                $date_max_request = "<= '".$date_max."'";
            }

            // echo ">", $type, "</br>";
            // echo ">", $form, "</br>";
            // echo ">", $scale, "</br>";
            // echo ">", $date_min, "</br>";
            // echo ">", $date_max, "</br>";
            // echo ">", $submit, "</br>";

            $response = $mysql->query(
                "SELECT 
                    `e`.`name`,
                    `te`.`name_rus` as `type_name`,
                    `fe`.`name_rus` as `form_name`,
                    `se`.`name_rus` as `scale_name`, 
                    `e`.`date`, 
                    `u`.`surname`, 
                    `u`.`name` as `user_name`, 
                    `u`.`patronymic`,
                    `u`.`id`,
                    `e`.`status`
                FROM `events` `e`
                JOIN `type_event` `te` ON (`te`.`id` = `e`.`type`)
                JOIN `form_event` `fe` ON (`fe`.`id` = `e`.`form`)
                JOIN `scale_event` `se` ON (`se`.`id` = `e`.`scale`)
                JOIN `users` `u` ON (`u`.`id` = `e`.`id_organization`)
                WHERE 
                    `e`.`type`$type_request 
                    AND
                    `e`.`form`$form_request
                    AND
                    `e`.`scale`$scale_request
                    AND
                    `e`.`date`$date_min_request
                    AND
                    `e`.`date`$date_max_request;");

            $response_count = $mysql->query(
                "SELECT 
                    COUNT(*)
                FROM `events`
                WHERE 
                    `type`$type_request 
                    AND
                    `form`$form_request
                    AND
                    `scale`$scale_request
                    AND
                    `date`$date_min_request
                    AND
                    `date`$date_max_request;");


            $r_c = $response_count->fetch_assoc();
            $response_count = $r_c['COUNT(*)'];

        }
    }
    
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">

    <title>Отчёты | Events</title>

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
        <p><a href="../index.php">Главная</a> / <a href="admin_panel.php">Панель администратора</a> / <a href="organization_profile_admin.php">Мероприятия</a> / Сформировать отчет</p>

        <h1>Панель администратора</h1>

        <p><a href="admin_panel.php">Профиль</a> | <a href="organization_profile_admin.php">Мероприятия</a> | <a href="users_admin.php">Пользователи</a></p>
                
        <h3>Формирование отчёта</h3>        

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" target="_self">
            Тип
            <select name="type" value="<?php echo $degree;?>">
            <option value="">-</option>
            <?php while($mas = mysqli_fetch_array($type_res))
                {?>
                    <option value="<?=$mas['id']?>" <?if($type == $mas['id']){?>selected<?}?>><?echo $mas['name_rus'];?></option>
                <?}?>
            </select>
            </br>

            Форма
            <select name="form" value="<?php echo $degree;?>">
            <option value="">-</option>
            <?php while($mas = mysqli_fetch_array($form_res))
                {?>
                    <option value="<?=$mas['id']?>" <?if($form == $mas['id']){?>selected<?}?>><?echo $mas['name_rus'];?></option>
                <?}?>
            </select>
            </br>

            Масштаб
            <select name="scale" value="<?php echo $degree;?>">
            <option value="">-</option>
            <?php while($mas = mysqli_fetch_array($scale_res))
                {?>
                    <option value="<?=$mas['id']?>" <?if($scale == $mas['id']){?>selected<?}?>><?echo $mas['name_rus'];?></option>
                <?}?>
            </select>
            </br>
            
            Период проведения</br>
            от
            <input type="date" name="date_min" value="<?=$date_min;?>">
            до
            <input type="date" name="date_max" value="<?=$date_max;?>">

            </br>
            <button type="submit" name="submit" value="1">Сформировать</button>
        </form>
        </br>

        <?if ($submit == '1') {
            if ($error == 1) {?>            
                Укажите параметры для формирования отчёта
            <?} else {?>

                <?if ($response_count != 0) {?>
                    <form method="post" action="response_excel_admin.php" target="_blank">
                        <input type="hidden" name="type" value="<?=$type;?>">
                        <input type="hidden" name="form" value="<?=$form;?>">
                        <input type="hidden" name="scale" value="<?=$scale;?>">
                        <input type="hidden" name="date_min" value="<?=$date_min;?>">
                        <input type="hidden" name="date_max" value="<?=$date_max;?>">
                        <button type="submit">Выгрузить отчёт</button>
                    </form>
                <?}?>
            
                Найдено записей: <?=$response_count;?>
                <table>
                    <tr>
                        <th>№</th>
                        <th>Название</th>
                        <th>Тип</th>
                        <th>Форма</th>
                        <th>Масштаб</th>
                        <th>Дата проведения</th>
                        <th>Организатор</th>
                        <th>Статус</th>
                    </tr>

                    <?
                        $i = 0;
                        while ($mas = mysqli_fetch_array($response)){
                    ?>

                    <tr onmouseover="this.style.background='rgba(0, 168, 247, 0.1)';" onmouseout="this.style.background='white';">
                        <td>
                            <?
                                $i++;                         
                            ?>
                            <?=$i;?>
                        </td>

                        <td>
                            <?=$mas['name'];?>
                        </td>
                        <td>
                            <?=$mas['type_name'];?>
                        </td>
                        <td>
                            <?=$mas['form_name'];?>
                        </td>
                        <td>
                            <?=$mas['scale_name'];?>
                        </td>
                        <td>
                            <?=date("d ",strtotime($mas['date'])),$rumonth[date('m',strtotime($mas['date']))],date(" Y",strtotime($mas['date']));?>
                        </td>
                        <td>
                            <?
                                echo $mas['surname'];
                                
                                if(!empty($mas['user_name'])) {
                                    echo " ".mb_substr($mas['user_name'], 0, 1).".";
                                }

                                if(strlen($mas['patronymic']) > 2) {
                                    echo " ".mb_substr($mas['patronymic'], 0, 1).".";
                                }

                                echo " (ID = ".$mas['id'].")";
                            ?>
                        </td>
                        <td>
                            <?
                                if($mas['status'] == 1) {
                                    echo "Активное";
                                }
                                if($mas['status'] == 0) {
                                    echo "Проведено";
                                }
                                if($mas['status'] == 2) {
                                    echo "Отменено";
                                }
                            ?>
                        </td>

                                
                        
                    </tr>
                    <?}?>

                </table>
            <?}?>
        <?}?>
        
        


        <? $mysql->close();?>

    <!-- Footer -->
    <div class="footer_m_p">
        ©2021 Events
    </div>
    
    <?} else { echo "Запрашиваемый ресурс не найден";}?>
</body>
</html>
