<?php
require_once('connect.php');
require __DIR__ . '/vendor/autoload.php';

use BarcodeBakery\Common\BCGFontFile;
use BarcodeBakery\Common\BCGColor;
use BarcodeBakery\Common\BCGDrawing;
use BarcodeBakery\Barcode\BCGcode128;

//get data from table
$sql = "SELECT size, barcode, qty, retail_price FROM tbl_barcode";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {    
        //generate all barcode images 
        $font = new BCGFontFile('./example/font/Arial.ttf', 18);
        $colorFront = new BCGColor(0, 0, 0);
        $colorBack = new BCGColor(255, 255, 255);

        // Barcode Part
        $code = new BCGcode128();
        $code->setScale(2);
        $code->setThickness(30);
        $code->setForegroundColor($colorFront);
        $code->setBackgroundColor($colorBack);
        $code->setFont($font);
        // $code->setStrictMode(true);
        $code->parse($row['barcode']);

        // Drawing Part
        $drawing = new BCGDrawing('./images/barcodes/'.$row['barcode'].'.png', $colorBack);
        $drawing->setBarcode($code);
        $drawing->draw();

        header('Content-Type: image/png');

        $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

        //preparing array for displaying barcode in html table
    }
} else {
    echo "0 results";
}

//generate html table for barcode

?>