<?php

header("Cache-Control: private, max-age=10800, pre-check=10800");
header("Pragma: private");
// Set to expire in 2 days
header("Expires: " . date(DATE_RFC822, strtotime(" 2 day")));
if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])){
    // if the browser has a cached version of this image, send 304
    header('Last-Modified: ' . $_SERVER['HTTP_IF_MODIFIED_SINCE'], true, 304);
    exit;
}
$image = imagecreatetruecolor(100, 100);

$c = hex2rgb(urldecode($_GET["color"]));

$alpha = 0;
if(isset($_GET["alpha"])){
    $alpha = abs(round((127 * $_GET["alpha"]) - 127));
}

imagealphablending($image, false);
imagesavealpha($image, true);

$color = imagecolorallocatealpha($image, $c[0], $c[1], $c[2], (int)$alpha);

imagefill($image, 0, 0, $color);


header("Content-Type: image/png");
imagepng($image);

function hex2rgb($hex){
    $hex = str_replace("#", "", $hex);

    if(strlen($hex) == 3){
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
    }else{
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    $rgb = array($r, $g, $b);
    //return implode(",", $rgb); // returns the rgb values separated by commas
    return $rgb; // returns an array with the rgb values
}
