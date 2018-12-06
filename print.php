<?php
require __DIR__ . '/vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
$connector = new \Mike42\Escpos\PrintConnectors\WindowsPrintConnector("POS58");
//$connector = new FilePrintConnector("POS58");
$printer = new Printer($connector);
/* Initialize */
//$id =$argv[1];
$id = $argv[1];
$Locations = $argv[2];
$qty = $argv[3];
$date =$argv[4];
$part_name = $argv[5];
//$Locations = 'P-test';
//$qty = '2000';
//$id ='1539688124';
$type = Printer::BARCODE_CODE39;
$position = Printer::BARCODE_TEXT_NONE;
// $printer->text($id);

if (!empty($id)) {

//    $printer->feed(6);
    
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("N:". $part_name .' '. 'Q:'.$qty. "\n");
    $printer->setBarcodeHeight(50);
//    $printer->setBarcodeWidth(200);
    $printer->setBarcodeTextPosition($position);
    $printer->barcode($id, $type);
    $printer->text("L:". $Locations ." D:".$date);
    $printer->feed();
    $printer->feed(1);
    $printer->feed();
//    $printer->feed(2);

//    $testStr='test';
//    $printer -> setJustification(Printer::JUSTIFY_CENTER);
////    $printer -> qrCode($testStr);
//    $printer->qrCode($testStr,1,2,1);
//    $printer -> text("Same example, centred\n");
////    $printer -> setJustification();
//    $printer -> feed();
//    $printer -> qrCode($testStr, Printer::QR_ECLEVEL_L, 1);
    $printer->close();
}

