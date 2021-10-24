<?php    
    include 'connect.php';
    require_once 'Classes/PHPExcel.php';

    $parameter = filter_var(trim($_POST['parameter']),FILTER_SANITIZE_STRING);
    $date_min = filter_var(trim($_POST['date_min']),FILTER_SANITIZE_STRING);
    $date_max = filter_var(trim($_POST['date_max']),FILTER_SANITIZE_STRING);

    if(!empty($date_min)){
        $date_min_request = ">= '".$date_min."'";
    }

    if(!empty($date_max)){
        $date_max_request = "<= '".$date_max."'";
    }

    if((!empty($date_min))&&(!empty($date_max))){
        $period = "Период: c ".$date_min." по ".$date_max;
    } else if((!empty($date_min))||(!empty($date_max))){
        if(!empty($date_min)){
            $period = "Период: c ".$date_min;
        } else {
            $period = "Период: по ".$date_max;
        }
    } else {
        $period = "Период: за всё время";
    }

    //по типу
    if ($parameter == "1") {
        $column_1 = "Название типа";
        $title_chart = "Типы мероприятий";
        $types = $mysql->query(
            "SELECT `*`
            FROM `type_event`");
    }

    if ($parameter == "2") {
        $column_1 = "Название формы";
        $title_chart = "Формы мероприятий";
        $types = $mysql->query(
            "SELECT `*`
            FROM `form_event`");
    }

    if ($parameter == "3") {
        $column_1 = "Название масштаба";
        $title_chart = "Масштабы мероприятий";
        $types = $mysql->query(
            "SELECT `*`
            FROM `scale_event`");
    }

    $i = 0;                                
    while ($mas = mysqli_fetch_array($types)){
        $id = $mas['id'];
        $type[$i] = $mas['name_rus'];
        $num = $mysql->query(
                        "SELECT COUNT(*)
                        FROM `events`
                        WHERE `type` = '$id' 
                        AND 
                        `date`$date_min_request
                        AND
                        `date`$date_max_request;");

        $n = $num->fetch_assoc();
        $response[$i] = $n['COUNT(*)'];
        $i++;                    
    }    

    $current_date = date("d.m.Y H-i-s");

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    $objWorksheet = $objPHPExcel->getActiveSheet();

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Events")
                                ->setLastModifiedBy("Events");

    // Add some data
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', $period)
                ->mergeCells('A1:B1')
                ->setCellValue('A2', $column_1)
                ->setCellValue('B2', 'Количество');
                
    $style_header = array(
        // шрифт
        'font'=>array(
            'bold' => true
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

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:B2')->applyFromArray($style_header);

    $index = 3;
    for($ind = 0; $ind < $i; $ind++){

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$index, $type[$ind])
                ->setCellValue('B'.$index, $response[$ind]);

        $index++;
    }
    $index--;

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:B'.$index)->getFont()->setName('Times New Roman');
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:B'.$index)->getFont()->setSize(12);

    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);

    $dataSeriesLabels1 = array(
        new PHPExcel_Chart_DataSeriesValues('String', 'Отчёт!$B$2', NULL, 1),	//	2011
    );
    $xAxisTickValues1 = array(
        new PHPExcel_Chart_DataSeriesValues('String', 'Отчёт!$A$3:$A$'.$index, NULL, $i),	//	Q1 to Q4
    );
    $dataSeriesValues1 = array(
        new PHPExcel_Chart_DataSeriesValues('Number', 'Отчёт!$B$3:$B$'.$index, NULL, $i),
    );
    //	Build the dataseries
    $series1 = new PHPExcel_Chart_DataSeries(
        PHPExcel_Chart_DataSeries::TYPE_DONUTCHART,				// plotType
        NULL,			                                        // plotGrouping (Pie charts don't have any grouping)
        range(0, count($dataSeriesValues1)-1),					// plotOrder
        $dataSeriesLabels1,										// plotLabel
        $xAxisTickValues1,										// plotCategory
        $dataSeriesValues1										// plotValues
    );
    //	Set up a layout object for the Pie chart
    $layout1 = new PHPExcel_Chart_Layout();
    $layout1->setShowVal(FALSE);
    $layout1->setShowPercent(FALSE);
    
    //	Set the series in the plot area
    $plotArea1 = new PHPExcel_Chart_PlotArea($layout1, array($series1));
    //	Set the chart legend
    $legend1 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
    
    $title1 = new PHPExcel_Chart_Title($title_chart);
    //	Create the chart
    $chart1 = new PHPExcel_Chart(
        'chart1',		// name
        $title1,		// title
        $legend1,		// legend
        $plotArea1,		// plotArea
        true,			// plotVisibleOnly
        0,				// displayBlanksAs
        NULL,			// xAxisLabel
        NULL			// yAxisLabel		- Pie charts don't have a Y-Axis
    );
    
    //	Set the position where the chart should appear in the worksheet
    $chart1->setTopLeftPosition('D2');
    $chart1->setBottomRightPosition('J15');
    
    //	Add the chart to the worksheet
    $objWorksheet->addChart($chart1);


    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('Отчёт');

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

    header('Content-Disposition: attachment;filename="Отчёт администратора от '.$current_date.'.xlsx"');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->setIncludeCharts(TRUE);

    $objWriter->save('php://output');
    exit;
?>