<?php
error_reporting(E_ERROR | E_PARSE);
require_once 'phpexcel/Classes/PHPExcel.php';

if (isset($_POST['submit'])) {

    $objPHPExcel = new PHPExcel();

    $sheet = $objPHPExcel->getActiveSheet();

    //FIRST B2B
    $objWorkSheet = $objPHPExcel->createSheet(0);

    $objWorkSheet->setTitle('B2B');
    $objWorkSheet->getColumnDimension("A")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("B")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("C")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("D")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("E")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("F")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("G")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("H")->setAutoSize(true);
    $objWorkSheet->getStyle("A1:H1")->getFont()->setBold(true);

    $objWorkSheet->setCellValue('A1', 'GSTIN/UIN of Recipient');
    $objWorkSheet->setCellValue('B1', 'Invoice Number');
    $objWorkSheet->setCellValue('C1', 'Invoice date');
    $objWorkSheet->setCellValue('D1', 'Invoice Value');
    $objWorkSheet->setCellValue('E1', 'Rate');
    $objWorkSheet->setCellValue('F1', 'Taxable Value');
    $objWorkSheet->setCellValue('G1', 'CGST');
    $objWorkSheet->setCellValue('H1', 'SGST');

    for($i = 0; $i < $_POST['total']; $i++){
        for($j = 0; $j < 8 ; $j++){
            $bValue = $i+2;
            switch($j){
                case 0:$aValue = 'A';break;
                case 1:$aValue = 'B';break;
                case 2:$aValue = 'C';break;
                case 3:$aValue = 'D';break;
                case 4:$aValue = 'E';break;
                case 5:$aValue = 'F';break;
                case 6:$aValue = 'G';break;
                case 7:$aValue = 'H';break;
            }
            
            $objWorkSheet->setCellValue( $aValue . $bValue, $_POST["$i-$j"]);
        }
    }

    //FILE NAME
    $FILENAME = "purchase";

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $FILENAME . '.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
}
?>