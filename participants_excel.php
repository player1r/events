<?php
    include 'connect.php';
    require_once 'Classes/PHPExcel.php';
    
    $current_date = date("d.m.Y H-i-s");

    $id_event = $_POST['id_event'];

    $event = $mysql->query("SELECT `name`
                        FROM `events`
                        WHERE `id` = '$id_event'");
    $e = $event->fetch_assoc();
    $event = $e['name'];

    $date = $mysql->query("SELECT `date`
                        FROM `events`
                        WHERE `id` = '$id_event'");
    $d = $date->fetch_assoc();
    $date = $d['date'];

    $time = $mysql->query("SELECT `time`
                        FROM `events`
                        WHERE `id` = '$id_event'");
    $t = $time->fetch_assoc();
    $time = $t['time'];
    $time = date("H-i-s",$time);

    $response = $mysql->query(
        "SELECT        
            `u`.`surname`,
            `u`.`name`,
            `u`.`patronymic`,
            `u`.`work`,
            `se`.`name_rus`,
            `p`.`type_participation`,
            `p`.`topic`
        FROM `participants` `p`
        JOIN `users` `u` ON (`u`.`id` = `p`.`id_user`)
        JOIN `section_event` `se` ON (`se`.`id` = `p`.`id_section_event`)   
        WHERE 
            `p`.`id_event` = '$id_event'
            AND
            `p`.`activity` = '1'
        ORDER BY `u`.`surname` ASC;");

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Events")
                                ->setLastModifiedBy("Events");

    // Add some data
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Название мероприятия: '.$event)
                ->mergeCells('A1:E1')
                ->setCellValue('A2', 'ФИО')
                ->setCellValue('B2', 'Место работы')
                ->setCellValue('C2', 'Секция')
                ->setCellValue('D2', 'Тип участия')
                ->setCellValue('E2', 'Тема выступения (у докладчиков)');

    $style_header = array(
        // шрифт
        'font'=>array(
            'bold' => true,
            'name' => 'Times New Roman',
            'size' => 12
        ),
        // выравнивание
        'alignment' => array(
            'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
            'wrap' => true
        ),
        
        //рамки
        'borders' => array(
            'outline' => array(
                'style'=>PHPExcel_Style_Border::BORDER_THIN,                
            ),
            'inside' => array(
                'style'=>PHPExcel_Style_Border::BORDER_THIN,                
            )
        )
    );

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:E2')->applyFromArray($style_header);

    $i = 3;
    while($mas = mysqli_fetch_array($response)) {
        $fio = $mas['surname'];
                                
        if(!empty($mas['name'])) {
            $fio = $fio." ".$mas['name'];
        }

        if(strlen($mas['patronymic']) > 2) {
            $fio = $fio." ".$mas['patronymic'];
        }
        
        if($mas['type_participation'] == 1) {
            $type_participation = "Докладчик";
        }
        if($mas['type_participation'] == 0) {
            $type_participation = "Слушатель";
        }

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit('A'.$i, $fio, PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.$i, $mas['work'], PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('C'.$i, $mas['name_rus'], PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('D'.$i, $type_participation, PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('E'.$i, $mas['topic'], PHPExcel_Cell_DataType::TYPE_STRING);
        
            $i++;
    }

    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:E'.$i)->getFont()->setName('Times New Roman');
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:E'.$i)->getFont()->setSize(12);

    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('Участники');

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Disposition: attachment;filename="Участники - '.$event.' от '.$date.' '.$time.' - '.$current_date.'.xlsx"');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
?>