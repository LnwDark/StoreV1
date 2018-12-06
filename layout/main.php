<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link href="../../assets/css/bootstrap.css" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; ?>
<?php
require __DIR__ . '../vendor/autoload.php';
use kartik\select2\Select2;
echo Select2::widget([
    'name' => 'state_10',
    'data' => ['1','2'],
    'options' => [
        'placeholder' => 'Select provinces ...',
        'multiple' => true
    ],
]);
?>
    <div class="container">
        <h4>นำสินค้าเข้า</h4>
        <div class="row">
            <div class="col-4">
                <form>
                    <div class="form-group">
                        <label for="exampleInputEmail1">ค้นหาสินค้า</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="เลือกสินค้า...">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">จำนวนที่นำเข้า</label>
                        <input type="number" class="form-control" id="exampleInputPassword1" placeholder="จำนวน...">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <div class="col-4">sd</div>
        </div>
    </div>
</body>
</html>