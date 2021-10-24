<?php
    setcookie('id_user', $id_user, time() - 3600, "/");
    header('Location: ..\index.php');
?>