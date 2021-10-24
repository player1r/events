<?php    
    include 'connect.php';
    require_once 'Classes/PHPExcel.php';

    $parameter = filter_var(trim($_POST['parameter']),FILTER_SANITIZE_STRING);
    $date_min = filter_var(trim($_POST['date_min']),FILTER_SANITIZE_STRING);
    $date_max = filter_var(trim($_POST['date_max']),FILTER_SANITIZE_STRING);

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

    $current_date = date("d.m.Y H-i-s");

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Events")
                                ->setLastModifiedBy("Events");

    // Add some data
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Название мероприятия')
                ->mergeCells('A1:A2')
                ->setCellValue('B1', 'Тип')
                ->mergeCells('B1:B2')
                ->setCellValue('C1', 'Форма')
                ->mergeCells('C1:C2')
                ->setCellValue('D1', 'Масштаб')
                ->mergeCells('D1:D2')
                ->setCellValue('E1', 'Дата проведения')
                ->mergeCells('E1:E2')
                ->setCellValue('F1', 'Организатор')
                ->mergeCells('F1:G1')
                ->setCellValue('F2', 'ФИО')
                ->setCellValue('G2', 'ID')
                ->setCellValue('H1', 'Статус')
                ->mergeCells('H1:H2');

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

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:H2')->applyFromArray($style_header);

    $i = 3;
    while($mas = mysqli_fetch_array($response)) {
        $fio = $mas['surname'];
                                
        if(!empty($mas['user_name'])) {
            $fio = $fio." ".mb_substr($mas['user_name'], 0, 1).".";
        }

        if(strlen($mas['patronymic']) > 2) {
            $fio = $fio." ".mb_substr($mas['patronymic'], 0, 1).".";
        }
        
        if($mas['status'] == 1) {
            $status = "Активное";
        }
        if($mas['status'] == 0) {
            $status = "Проведено";
        }
        if($mas['status'] == 2) {
            $status = "Отменено";
        }

        $date = date("d",strtotime($mas['date'])).".".date('m',strtotime($mas['date'])).".".date("Y",strtotime($mas['date']));

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit('A'.$i, $mas['name'], PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.$i, $mas['type_name'], PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('C'.$i, $mas['form_name'], PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('D'.$i, $mas['scale_name'], PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('E'.$i, $date)
                ->setCellValueExplicit('F'.$i, $fio, PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('G'.$i, $mas['id'])
                ->setCellValueExplicit('H'.$i, $status, PHPExcel_Cell_DataType::TYPE_STRING);

        $objPHPExcel->setActiveSheetIndex(0)->getStyle('E'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
        $i++;
    }

    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('H')->setAutoSize(true);


    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('Отчёт');

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Disposition: attachment;filename="Отчёт администратора от '.$current_date.'.xlsx"');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
?>