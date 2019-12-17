<?php
    include_once('../../php/conn.php');
    if(isset($_REQUEST["q"])){
        require "vendor/autoload.php";
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $itemId = $_REQUEST["q"];
        $itemId = sprintf('%04u', $itemId);

        $query = "SELECT total FROM amount WHERE type='S' AND itemid='$itemId' AND total > 0;";
    	$result = mysqli_query($conn, $query);
	
	if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $price = $row['total'];
            $price = round($price,2);
	
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
    html{
        background: #000;
    }
        body{
            background: #fff;
            width:88.9mm;
            margin: auto;
        }
        .barcode-sec{
            height:32mm;
            display: flex;
            }

            .barcode-sec .barcode{
                width:50%;
    display: flex;
    align-items: center;
    flex-direction: column;
    justify-content: center;
            }
            .barcode-sec .barcode p{
                margin: 2px 0;
                font-size: 12px;
            }
            .barcode-sec .barcode img{
                margin-top: 6px;
            }



    </style>
</head>
<body>
<div class="barcode-sec">
   <div class="barcode">
   <?php 
    echo '<img src="data:image/png;base64,'  . base64_encode($generator->getBarcodeWithText($itemId, $generator::TYPE_CODE_128)) . '">'; 
    echo "<p class='price'>MRP <span>$price/-</span></p>";
?> 
<p class="shop">Faiz Baby Shop</p>
   </div>
   <div class="barcode">
   <?php 
    echo '<img src="data:image/png;base64,'  . base64_encode($generator->getBarcodeWithText($itemId, $generator::TYPE_CODE_128)) . '">'; 
    echo "<p class='price'>MRP <span>$price/-</span></p>";
?> 
<p class="shop">Faiz Baby Shop</p>
   </div>
</div>
</body>
</html>
<?php
	}
}else{
    echo "<script>alert('Set MRP and Try Again:$itemId');</script>";
}
    }
?>