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

    

    <title>Events | Вход</title>

</head>

<body>

    <p><a href="../index.php">Главная</a> / Вход</p>

    
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <p>Электронная почта</p>
        <input type="text" name="email" value="<?=$email?>" required>
        <?=$login_error;?>

        <p>Пароль</p>
        <input type="password" name="password" required>
        <?=$pass_error; echo "</br>";?>

        <?=$error; echo "</br>";?>
        </br>
        <button type="submit">Войти</button>
    </form>

    <p>У Вас ещё нет аккаунта? <a href="signup.php">Зарегистрируйтесь</a></p>

            <!-- Footer -->
            <? $mysql->close();?>
    <div>
        ©2021 Events
    </div>
    

</body>
</html>
