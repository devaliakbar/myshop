<?php
error_reporting(E_ERROR | E_PARSE);
require_once 'phpexcel/Classes/PHPExcel.php';

if (isset($_POST['submit'])) {
    $objPHPExcel = new PHPExcel();

    $sheet = $objPHPExcel->getActiveSheet();

//FIRST

    $objWorkSheet = $objPHPExcel->createSheet(0);

    $objWorkSheet->setTitle('Summary');
    $objWorkSheet->getColumnDimension("A")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("B")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("C")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("D")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("E")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("F")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("G")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("H")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("I")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("J")->setAutoSize(true);
    $objWorkSheet->getStyle("A1:J1")->getFont()->setBold(true);
    $objWorkSheet->getStyle("A3:J3")->getFont()->setBold(true);

    $objWorkSheet->setCellValue('A1', 'No\. Of HSN');
    $objWorkSheet->setCellValue('A2', $_POST['s---thsn']);

    $objWorkSheet->setCellValue('E1', 'Total Value');
    $objWorkSheet->setCellValue('E2', $_POST['s---tot']);

    $objWorkSheet->setCellValue('F1', 'Total Taxable Value');
    $objWorkSheet->setCellValue('F2', $_POST['s---tax']);

    $objWorkSheet->setCellValue('G1', 'Total Integrated Tax');
    $objWorkSheet->setCellValue('G2', $_POST['s---igst']);

    $objWorkSheet->setCellValue('H1', 'Total Central Tax');
    $objWorkSheet->setCellValue('H2', $_POST['s---cgst']);

    $objWorkSheet->setCellValue('I1', 'Total State/UT Tax');
    $objWorkSheet->setCellValue('I2', $_POST['s---sgst']);

    $objWorkSheet->setCellValue('J1', 'Total Cess');
    $objWorkSheet->setCellValue('J2', $_POST['s---cess']);

    $objWorkSheet->setCellValue('A3', 'HSN');
    $objWorkSheet->setCellValue('B3', 'Description');
    $objWorkSheet->setCellValue('C3', 'UQC');
    $objWorkSheet->setCellValue('D3', 'Total Quantity');
    $objWorkSheet->setCellValue('E3', 'Total Value');
    $objWorkSheet->setCellValue('F3', 'Taxable Value');
    $objWorkSheet->setCellValue('G3', 'Integrated Tax Amount');
    $objWorkSheet->setCellValue('H3', 'Central Tax Amount');
    $objWorkSheet->setCellValue('I3', 'State/UT Tax Amount');
    $objWorkSheet->setCellValue('J3', 'Cess Amount');

    for ($i = 0; $i < $_POST['s---thsn']; $i++) {
        for ($j = 0; $j < 10; $j++) {
            $bValue = $i + 4;
            switch ($j) {
                case 0:$aValue = 'A';
                    break;
                case 1:$aValue = 'B';
                    break;
                case 2:$aValue = 'C';
                    break;
                case 3:$aValue = 'D';
                    break;
                case 4:$aValue = 'E';
                    break;
                case 5:$aValue = 'F';
                    break;
                case 6:$aValue = 'G';
                    break;
                case 7:$aValue = 'H';
                    break;
                case 8:$aValue = 'I';
                    break;
                case 9:$aValue = 'J';
                    break;
            }

            $objWorkSheet->setCellValue($aValue . $bValue, $_POST["$i---$j"]);
        }
    }

//SECOND B2B
    $objWorkSheet = $objPHPExcel->createSheet(1);

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

    for ($i = 0; $i < $_POST['s-total']; $i++) {
        for ($j = 0; $j < 8; $j++) {
            $bValue = $i + 2;
            switch ($j) {
                case 0:$aValue = 'A';
                    break;
                case 1:$aValue = 'B';
                    break;
                case 2:$aValue = 'C';
                    break;
                case 3:$aValue = 'D';
                    break;
                case 4:$aValue = 'E';
                    break;
                case 5:$aValue = 'F';
                    break;
                case 6:$aValue = 'G';
                    break;
                case 7:$aValue = 'H';
                    break;
            }

            $objWorkSheet->setCellValue($aValue . $bValue, $_POST["$i-$j"]);
        }
    }

//THIRD B2C
    $objWorkSheet = $objPHPExcel->createSheet(2);

    $objWorkSheet->setTitle('B2C');
    $objWorkSheet->getColumnDimension("A")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("B")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("C")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("D")->setAutoSize(true);
    $objWorkSheet->getColumnDimension("E")->setAutoSize(true);
    $objWorkSheet->getStyle("A1:E1")->getFont()->setBold(true);

    $objWorkSheet->setCellValue('A1', 'Place Of Supply');
    $objWorkSheet->setCellValue('B1', 'Rate Of Tax');
    $objWorkSheet->setCellValue('C1', 'Taxable Value');
    $objWorkSheet->setCellValue('D1', 'CGST');
    $objWorkSheet->setCellValue('E1', 'SGST');

    for ($i = 0; $i < $_POST['total']; $i++) {
        for ($j = 0; $j < 5; $j++) {
            $bValue = $i + 2;
            switch ($j) {
                case 0:$aValue = 'A';
                    break;
                case 1:$aValue = 'B';
                    break;
                case 2:$aValue = 'C';
                    break;
                case 3:$aValue = 'D';
                    break;
                case 4:$aValue = 'E';
                    break;
            }

            $objWorkSheet->setCellValue($aValue . $bValue, $_POST["$i--$j"]);
        }
    }
//FILE NAME
    $FILENAME = "sales";

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $FILENAME . '.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
}
