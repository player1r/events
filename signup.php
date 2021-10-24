<?php
    include 'connect.php';

    $current_date = new DateTime();
    $current_date->modify('-7 years');

    $degree_res = $mysql->query("SELECT * FROM `degree_user`");
    $title_res = $mysql->query("SELECT * FROM `title_user`");

    $surname_error = $name_error = $gender_error = $birthday_error = $country_error = $city_error = $degree_error = $title_error = $email_error = $phone_number_error = $password_error = $password2_error = $address_error = $checkbox_error ='';

    /*КЛЮЧИ*/
    define('SITE_KEY', '6LduLn8aAAAAAJkfANGnED_g510D4iAT-HsJdNgQ');
    define('SECRET_KEY', '6LduLn8aAAAAAC0cONkSbjWvqBTQMi36e0O6rAUs');

    /*ОБРАБОТКА ЗАПРОСА*/
    if($_POST){
        /*СОЗДАЕМ ФУНКЦИЮ КОТОРАЯ ДЕЛАЕТ ЗАПРОС НА GOOGLE СЕРВИС*/
        function getCaptcha($SecretKey) {
            $Response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".SECRET_KEY."&response={$SecretKey}");
            $Return = json_decode($Response);
            return $Return;
        }
    
    /*ПРОИЗВОДИМ ЗАПРОС НА GOOGLE СЕРВИС И ЗАПИСЫВАЕМ ОТВЕТ*/
    $Return = getCaptcha($_POST['g-recaptcha-response']);
    /*ВЫВОДИМ НА ЭКРАН ПОЛУЧЕННЫЙ ОТВЕТ*/
    // var_dump($Return);
    
    /*ЕСЛИ ЗАПРОС УДАЧНО ОТПРАВЛЕН И ЗНАЧЕНИЕ score БОЛЬШЕ 0,5 ВЫПОЛНЯЕМ КОД*/
    if($Return->success == true && $Return->score > 0.5){
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
        $password = filter_var(trim($_POST['password']),FILTER_SANITIZE_STRING);
        $password2 = filter_var(trim($_POST['password']),FILTER_SANITIZE_STRING);
        $checkbox = filter_var(trim($_POST['checkbox']),FILTER_SANITIZE_STRING);

        if(empty($surname)){
            $surname_error = "* Введите значение";
            $score = 1;
        } else {
            if(mb_strlen($surname) < 2){
                $surname_error = "* Введите корректное значение";
                $score = 1;
            }
        }

        if(empty($name)){
            $name_error = "* Введите значение";
            $score = 1;
        } else {
            if(mb_strlen($name) < 2){
                $name_error = "* Введите корректное значение";
                $score = 1;
            }
        }

        if(empty($gender)){
            $gender_error = "* Выберите значение";
            $score = 1;
        } 

        if(empty($birthday)){
            $gender_error = "* Выберите значение";
            $score = 1;
        }

        if(empty($country)){
            $country_error = "* Введите значение";
            $score = 1;
        }

        if(empty($city)){
            $city_error = "* Введите значение";
            $score = 1;
        }

        if(empty($degree)){
            $degree_error = "* Выберите значение";
            $score = 1;
        }

        if(empty($title)){
            $title_error = "* Выберите значение";
            $score = 1;
        }

        if(empty($email)){
            $email_error = "* Введите значение";
            $score = 1;
        }

        if(empty($phone_number)){
            $phone_number_error = "* Введите значение";
            $score = 1;
        }

        if(empty($address)){
            $address_error = "* Введите значение";
            $score = 1;
        }

        if(empty($password)){
            $password_error = "* Введите значение";
            $score = 1;
        } else {
            if(mb_strlen($password) < 6){
                $password_error = "* Минимальная длина 6 символов";
                $score = 1;
            }            

            //Хеширование пароля
            $options = ['cost' => 12,];
            $hash = password_hash($password, PASSWORD_BCRYPT, $options);
        }

        if(empty($password2)){
            $password2_error = "* Введите значение";
            $score = 1;
        }   else {
            if($password != $password2){
                $password2_error = "* Пароли не совпадают!";
                $score = 1;
            }
        }

        if($checkbox != 1){
            $checkbox_error = "* Согласие обязательно!";
            $score = 1;
        }

        if ($score != 1) {          
            $now = date("Y-m-d H:i:s");

            //Запрос
            $mysql->query("INSERT INTO `users` (`id`, `email`, `password`, `phone_number`, `surname`, `name`, `patronymic`, `country`, `city`, `work`, `post_address`, `degree`, `title`, `gender`, `birthday`, `creation_date`, `status`) VALUES (NULL, '$email', '$hash', '$phone_number', '$surname', '$name', '$patronymic', '$country', '$city', '$work', '$address', '$degree', '$title', '$gender', '$birthday', '$now', '1');");
            //ID пользователя
            $id_user = $mysql->query("SELECT `id` FROM `users` WHERE `email` = '$email'");
            $i_u = $id_user->fetch_assoc();
            $id_user = $i_u['id'];

            //Установка cookie
            setcookie('id_user', $id_user, time() + 3600, "/");
            
            header ("location: profile.php");
        }
    
        //Закрытие соединения
        $mysql->close();
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

        <title>Регистрация | Events</title>

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
                <a href="signin.php">Вход</a><span class="image"></span>
                <a href="#" class="selected_a">Регистрация</a>
            </div>
    </div>

    <!-- Form -->
    <div class="form_area">
            <div class="form_block">
                <h1>Регистрация</h1>
                <form action="<? echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                    <!-- Фамилия -->
                    <input type="text" name="surname" placeholder="Фамилия" maxlength="50" value="<?=$surname?>" required>
                    <?=$surname_error;?><br/>
                    <!-- Имя -->
                    <input type="text" name="name" placeholder="Имя" maxlength="50" value="<?=$name?>" required>
                    <?=$name_error;?><br/>
                    <!-- Отчество -->
                    <input type="text" name="patronymic" placeholder="Отчество" maxlength="50" value="<?=$patronymic?>"><br/>
                    <!-- Пол -->
                    <select name="gender" value="<?php echo $gender;?>">
                        <option value="" disabled selected>Пол</option>
                        <option value="0">Женский</option>
                        <option value="1">Мужской</option>
                    </select>
                    <?=$gender_error;?></br>
                    <!-- Дата рождения -->
                    <input type="date" name="birthday" placeholder="Дата рождения" value="<?=$birthday?>" max="<?=$current_date->format('Y-m-d');?>" required>
                    <?=$birthday_error;?><br/>
                    <!-- Страна -->
                    <input type="text" name="country" placeholder="Страна" value="<?=$country?>" required>
                    <?=$country_error;?><br/>
                    <!-- Город -->
                    <input type="text" name="city" placeholder="Город" value="<?=$city?>" required>
                    <?=$city_error;?><br/>
                    <!-- Место работы -->
                    <input type="text" name="work" placeholder="Место работы" value="<?=$work?>"><br/>
                    <!-- Учёная степень -->
                    <select name="degree" value="<?php echo $degree;?>">
                    <option value="" disabled selected>Учёная степень</option>
                    <?php while($mas = mysqli_fetch_array($degree_res))
                        {?>
                            <option value="<?=$mas['id']?>"><?echo $mas['name_rus'];?></option>
                        <?}?>
                    </select>
                    <?=$degree_error;?></br>
                    <!-- Учёное звание -->
                    <select name="title" value="<?php echo $title;?>">
                    <option value="" disabled selected>Учёное звание</option>
                    <?php while($mas = mysqli_fetch_array($title_res))
                        {?>
                            <option value="<?=$mas['id']?>"><?echo $mas['name_rus'];?></option>
                        <?}?>
                    </select>
                    <?=$title_error;?></br>
                    <!-- Электронная почта -->
                    <input type="email" name="email" placeholder="Электронная почта" value="<?=$email?>" required>
                    <?=$email_error;?><br/>
                    <!-- Телефон -->
                    <input type="text" name="phone_number" placeholder="Телефон" value="<?=$phone_number?>" required>
                    <?=$phone_number_error;?><br/>
                    <!-- Почтовый адрес -->
                    <input type="text" name="address" placeholder="Почтовый адрес" value="<?=$address?>" required>
                    <?=$address_error;?><br/>
                    <!-- Пароль -->
                    <input type="text" name="password" placeholder="Пароль" value="<?=$password?>" required>
                    <?=$password_error;?><br/>
                    <!-- Подтверждение пароля -->
                    <input type="text" name="password" placeholder="Подтверждение пароля" value="<?=$password2?>" required>
                    <?=$password2_error;?><br/>
                    <!-- Согласие на обработку -->
                    <label>
                        <input type="checkbox" name="checkbox" value="1" <?if ($checkbox == 1) {?>checked="checked"<?}?>required>            
                        <?=$checkbox_error;?> Даю своё согласие на обработку персональных данных в соответствии с требованиями статьи 9 <a href="http://www.kremlin.ru/acts/bank/24154" target="_blank">Федерального закона от 27.07.2006 г. № 152-ФЗ «О персональных данных»</a>
                    </label><br/>


                    <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" /><br/>
                    <button type="submit" name="sign_up">Зарегистрироваться</button>
                </form>
        </div>
    </div>

    <!-- Для ReCaptcha -->
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo SITE_KEY?>"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('<?php echo SITE_KEY;?>', {action: 'homepage'}).then(function(token) {
            //console.log(token);
            document.getElementById('g-recaptcha-response').value=token;
            });
        });
    </script>

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
