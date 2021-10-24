<?php
    include 'connect.php';
    
    $id_user = $_COOKIE['id_user'];
    $id_event = $_POST['id_event'];

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

    $user_info = $mysql->query("SELECT `*` FROM `users` WHERE `id` = '$id_user'");
    while ($mas = mysqli_fetch_array($user_info)){
        $surname = $mas['surname'];
        $name = $mas['name'];
        $patronymic = $mas['patronymic'];
            if ($patronymic == '-') {
                $patronymic = '';
            }
        $gender = $mas['gender'];
            if ($gender == '0') {
                $gender = 'а';
            } else {
                $gender = '';
            }
    }

    $event_info = $mysql->query("SELECT `*`
                            FROM `events`
                            WHERE `id` = '$id_event'");
    while ($mas = mysqli_fetch_array($event_info)){
        $name_event = $mas['name'];
            $day = date("d ",strtotime($mas['date']));
            $mounth = $rumonth[date('m',strtotime($mas['date']))];
            $year = date(" Y",strtotime($mas['date']));
        $place = $mas['place'];
        $id_organization = $mas['id_organization'];
    }

    $organization_info = $mysql->query("SELECT `*`
                            FROM `users`
                            WHERE `id` = '$id_organization'");
    while ($mas = mysqli_fetch_array($organization_info)){
        $surname_organization = $mas['surname'];
        $name_organization = $mas['name'];
        $name_organization = mb_substr($name_organization, 0, 1).'.';
        $patronymic_organization = $mas['patronymic'];
        if ($patronymic_organization == '-') {
            $patronymic_organization = '';
        } else {
            $patronymic_organization = mb_substr($patronymic_organization, 0, 1).'.';
        }
    }

    $id_z = $mysql->query("SELECT `id_z`
                            FROM `participants`
                            WHERE `id_user` = '$id_user' AND `id_event` = '$id_event'");
    $i_z = $id_z->fetch_assoc();
    $id_z = $i_z['id_z'];


    // подключаем шрифты
	    define('FPDF_FONTPATH',"fpdf/font/");
    // подключаем библиотеку
        require('fpdf/fpdf.php');
    
    // создаем PDF документ
        $pdf=new FPDF('L','mm','A4');
    // устанавливаем заголовок документа
        $pdf->SetTitle("Download certificate");
    
    // создаем страницу
        $pdf->AddPage();
            
    // добавляем шрифт ариал
        $pdf->AddFont('Arial','','arial.php'); 
    // устанавливаем шрифт Ариал
        $pdf->SetFont('Arial');
    // устанавливаем цвет шрифта
        $pdf->SetTextColor(0,0,0);
    // устанавливаем размер шрифта
        $pdf->SetFontSize(44);
    // добавляем текст
        $pdf->SetXY(20,40);
        $pdf->Write(0,iconv('utf-8', 'windows-1251',"СЕРТИФИКАТ"));

        $pdf->SetFontSize(24);
        $pdf->SetXY(20,55);
        $pdf->Write(0,iconv('utf-8', 'windows-1251',"подтверждает, что"));

        $pdf->SetFontSize(32);
        $pdf->SetXY(20,80);
        $pdf->Write(0,iconv('utf-8', 'windows-1251',"$surname $name $patronymic"));

        $pdf->SetFontSize(24);
        $pdf->SetXY(20,105);
        $pdf->Write(0,iconv('utf-8', 'windows-1251',"принял$gender участие в мероприятии"));

        $pdf->SetFontSize(24);
        $pdf->SetXY(20,120);
        $pdf->Write(0,iconv('utf-8', 'windows-1251',"\"$name_event\""));

        $pdf->SetFontSize(24);
        $pdf->SetXY(20,135);
        $pdf->Write(0,iconv('utf-8', 'windows-1251',"$day$mounth$year"));

        $pdf->SetFontSize(22);
        $pdf->SetXY(20,155);
        $pdf->Write(0,iconv('utf-8', 'windows-1251',"$surname_organization  $name_organization $patronymic_organization"));
    
        $pdf->SetFontSize(18);
        $pdf->SetXY(20,165);
        $pdf->Write(0,iconv('utf-8', 'windows-1251',"$place"));

        $pdf->SetFontSize(12);
        $pdf->SetXY(20,185);
        $pdf->Write(0,iconv('utf-8', 'windows-1251',"Номер сертификата: $id_z"));
    
    // добавляем на страницу изображение    

        //$pdf->Image( '../assets/images/test.jpg', 100, 100, 100 );
        
            
    // выводим документа в браузере
        $pdf->Output('certificate.pdf','I');
    
    ?>
    