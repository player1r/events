
<?php
    if(!empty($_POST)){
        $password = $_POST['password'];
        $options = [
            'cost' => 12,
        ];
        $hash = password_hash($password, PASSWORD_BCRYPT, $options);
        echo "HASH: ", $hash;
        echo "</br>PASS verify:  ", password_verify($password, $hash);
    }
?>

<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="text" name="password">                              
    <input type="submit" value="Создать хеш">
</form>