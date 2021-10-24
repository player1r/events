<?
    include 'connect.php';
    
    $id_user = $_COOKIE['id_user'];

    $id_event = filter_var(trim($_POST['id_event']),FILTER_SANITIZE_STRING);
    $type_participation = filter_var(trim($_POST['type_participation']),FILTER_SANITIZE_STRING);
    $section_event = filter_var(trim($_POST['section_event']),FILTER_SANITIZE_STRING);
    $topic = filter_var(trim($_POST['topic']),FILTER_SANITIZE_STRING);
    $technic = filter_var(trim($_POST['technic']),FILTER_SANITIZE_STRING);
    $scope = filter_var(trim($_POST['scope']),FILTER_SANITIZE_STRING);
    $housing = filter_var(trim($_POST['housing']),FILTER_SANITIZE_STRING);
    $publication = filter_var(trim($_POST['publication']),FILTER_SANITIZE_STRING);   

    if ($type_participation == "0") {
        $mysql->query("INSERT INTO `participants` (`id_event`, `id_section_event`,`id_user`,`type_participation`, `topic`, `technic`, `housing`, `publication`, `scope`,`activity`) VALUES ('$id_event', '$section_event','$id_user','$type_participation', '-', '-', '0', '0', '0','2');");
    } else if ($type_participation == "1"){
        $mysql->query("INSERT INTO `participants` (`id_event`, `id_section_event`,`id_user`,`type_participation`, `topic`, `technic`, `housing`, `publication`, `scope`,`activity`) VALUES ('$id_event', '$section_event','$id_user','$type_participation', '$topic', '$technic', '$housing', '$publication', '$scope','2');");
    }
    $mysql->close();

    header("location: event.php?id_conf=".$id_event."");
?>