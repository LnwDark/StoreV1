<?php 
if(!empty($_GET['id'])){
    $code_id =$_GET['id'];
    $lct =$_GET['lct'];
    $amount = $_GET['amount'];
    $myDateTime = DateTime::createFromFormat('Y-m-d', $_GET['created_at']);
    $created_at = $myDateTime->format('d-m-Y');
    $part_name = $_GET['part_name'];
    $run = "php print.php $code_id  $lct $amount $created_at $part_name";
    exec($run);
}
