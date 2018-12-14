<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
<!--    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>-->
     <link href="assets/css/bootstrap.css" rel="stylesheet">
    <style>

    </style>
</head>

<body>
<?php include 'layout/header.php'; ?>
<p id="message" class="text-danger"></p>
<p id="globalDiv"></p>

<div class="container" id="app" v-cloak>
    <h4>นำสินค้าเข้า</h4>
  <button  class="btn btn-info" @click="fetchData()" >ReloadData</button>

    <div class="row">
        <!-- Modal -->
        <div class="modal fade" id="printBarcode" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="cancel"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">พิมพ์ barcode</h4>
                    </div>
                    <div class="modal-body">
                        <p>part name : {{dataBarcode.part_name}}</p>
                        <p>เลขที่ barcode : {{dataBarcode.barcode}}</p>
                        <p>ที่จัดเก็บ : {{dataBarcode.locations}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" @click="cancel">Close</button>
                        <button type="button" class="btn btn-primary" @click="print()"><span class="glyphicon glyphicon-barcode"></span>
                            Print Barcode
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button trigger modal -->
        <div class="col-md-4">
        <div class="card">
  <div class="card-body">

   
            <div class="form-group">
                <label for="exampleInputEmail1">ค้นหาสินค้า</label>
                <v-select label="inventory_id" :options="dataListProduct" v-model="select"></v-select>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">จำนวนที่นำเข้า</label>
                <input type="number" class="form-control" id="exampleInputPassword1" placeholder="จำนวน..."
                       v-model="amount">
            </div>
            <button type="submit" class="btn btn-primary" @click="SaveProduct" :disabled="amount ==''  ">บันทึกข้อมูล</button>
            </div>
</div>
        </div>

        <div class="col-md-4" v-if="productDetails != null">
            <ul class="list-group" v-for="model in productDetails">
                <li class="list-group-item">
                    <label for="">part name</label>
                    <span class="badge"><h5>{{model.inventory_id}}</h5></span>
                </li>
                <li class="list-group-item">
                    <label for="">ที่จัดเก็บ</label>
                    <span class="badge"><h5>{{model.keep_area_2}}</h5></span>
                </li>
                <li class="list-group-item">
                    <label>จำนวนคงเหลือ</label>
                    <span class="badge"><h5>{{model.balance}}</h5></span>
                </li>
            </ul>
        </div>
        <div class="col-md-8">
        <div class="row">
        <div class="col-md-6">
        <input type="text" class="form-control" placeholder="ค้นหา part" v-model="search"> 
        
        </div>
        <div class="col-md-6">
            <input type="text" class="form-control"placeholder="หาวันที่..." v-model="search_date">
        </div>
        </div>
            <table class="table table-bordered">
            	<thead>
            		<tr>
            			<th class="text-center" >#</th>
                        <th>วันที่</th>
                        <th>part name</th>
                        <th>ที่อยู่</th>
                        <th>จำนวน</th>
                        <th>###</th>
            		</tr>
            	</thead>
            	<tbody>
            		<tr v-for="(model,index) in filterProduct" :class="check(model)">
            			<td class="text-center">{{index+1}}</td>
                        <td>{{model.created_at}}</td>
                        <td>{{model.product_name}}</td>
                        <td>{{model.keep_area_2}}</td>
                        <td>{{model.amount}}</td>
                        <td>
                        <Button class="btn btn-success btn-xs" @click="reprint(model)"><span class="glyphicon glyphicon-repeat"></span> Reprint</Button>
                        <Button class="btn btn-danger btn-xs" @click="remove(model)" v-if="model.updated_at == null"><span class="glyphicon glyphicon-remove"></span></Button></td>
            		</tr>
            	</tbody>
            </table>
        </div>
    </div>
</div>

<script src="assets/js/jquery-3.3.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/vue.js"></script>
<script src="assets/js/vue-select.js"></script>
<script src="https://unpkg.com/axios@0.18.0/dist/axios.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
<?php
require __DIR__ . '/vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

$connector = new \Mike42\Escpos\PrintConnectors\WindowsPrintConnector("POS581");
//$connector = new FilePrintConnector("POS58");
$printer = new Printer($connector);
/* Initialize */
//$id =$argv[1];
$id = !empty($_GET['id']) ? $_GET['id'] : '';
$Locations = !empty($_GET['lct']) ? $_GET['lct'] : '';
$qty = !empty($_GET['amount']) ? $_GET['amount'] : '';
$date = !empty($_GET['created_at']) ? $_GET['created_at'] : '';
$part_name = !empty($_GET['part_name']) ? $_GET['part_name'] : '';
$count_lote = !empty($_GET['count_lote']) ? $_GET['count_lote'] : '';
//$Locations = 'P-test';
//$qty = '2000';
//$id ='1539688124';
$type = Printer::BARCODE_CODE39;
$position = Printer::BARCODE_TEXT_NONE;
// $printer->text($id);

if (!empty($id)) {
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("N:" . $part_name . ' ' . 'L:' . $count_lote . "\n");
    $printer->setBarcodeHeight(50);
    $printer->setBarcodeTextPosition($position);
    $printer->barcode($id, $type);
    $printer->text("L:" . $Locations . " D:" . $date);
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

}
$printer->close();

?>