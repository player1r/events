<?php
    include 'connect.php';

    if(!empty($_POST)){
        $email = filter_var(trim($_POST['email']),FILTER_SANITIZE_STRING);
        $password = filter_var(trim($_POST['password']),FILTER_SANITIZE_STRING);

        if(!empty($email)){
            $hash = $mysql->query("SELECT `password` FROM `users` WHERE `email` = '$email'");

            if(!empty($password)){

                if(!empty($hash)){
                    $h = $hash->fetch_assoc();
                    $hash = $h['password'];
        
                    $result = password_verify($password, $hash);
                    if($result == 1) {
                        //ID пользователя
                        $id_user = $mysql->query("SELECT `id` FROM `users` WHERE `email` = '$email'");
                        $i_u = $id_user->fetch_assoc();
                        $id_user = $i_u['id'];

                        //Установка cookie
                        setcookie('id_user', $id_user, time() + 3600, "/");
                        
                        header('Location: ../index.php');
                    } else {
                        $error = "Неверный логин или пароль";
                    }
                }
                else {
                    $error = "Неверный логин или пароль";
                }
            } else {
                $pass_error = "Введите пароль";
            }
        } else {
            $login_error = "Введите логин";
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

    <title>Events | Вход</title>

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
                <a href="#" class="selected_a">Вход</a>
            </div>
    </div>

        <!-- Form -->
        <div class="form_area">
            <div class="form_block">
                <h1>Вход</h1>
                <form  method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <input placeholder="Электронная почта" type="text" name="email" value="<?=$email?>" required><?=$login_error;?><br/>
                    <input placeholder="Пароль" type="password" name="password" required><?=$pass_error;?></br>
                    <button type="submit">Войти</button>
                </form>
                <p>Нет аккаунта? <a href="signup.php">Зарегистрируйтесь</a></p>
            </div>
        </div>

            <!-- Footer -->
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
