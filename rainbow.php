<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

//See if they passed a image argument
if( isset($argv[1]) == FALSE ) {
    die("\r\nNo picture was passed through!\r\n");
}

//Validate the argument is a valid image
if( function_exists("finfo_file") == FALSE ) {
    die("\r\nFunction finfo_file needs to exist for this!\r\n");
}


//See what the MIME type is of the file...
$resFinfo = finfo_open(FILEINFO_MIME_TYPE);
switch( strtoupper(finfo_file($resFinfo, $argv[1])) ) {
    case 'IMAGE/JPEG' :
    case 'IMAGE/JPG' :
        $resImage = imagecreatefromjpeg($argv[1]);
        break;
    default :
        die("\r\nInvalid file type!\r\n");
        break;
}

if( is_resource($resImage) === FALSE ) {
    die("\r\nSorry, something went wrong!\r\n");
}


$arrImageSize = getimagesize($argv[1]);
$resTrueColor = imagecreatetruecolor($arrImageSize[0], $arrImageSize[1]);


//Check truecolor was a success
if($resTrueColor === FALSE) {
    die("\r\nSorry, something went wrong!\r\n");
}

//Now we draw the rectangles to make the rainbow <3

$intHeightOfBlock = $arrImageSize[1] / 8;


$arrColours = array(
    'hot_pink' => imagecolorallocate($resImage, 255, 105, 180), //Sexuality
    'red' => imagecolorallocate($resImage, 255, 0, 0), //Life
    'orange' => imagecolorallocate($resImage, 255, 128, 0), //Healing
    'yellow' => imagecolorallocate($resImage, 255, 255, 0), //Sunlight
    'green' => imagecolorallocate($resImage, 0, 128, 0), //Nature
    'turquoise' => imagecolorallocate($resImage, 51, 221, 221), //Magic/Art
    'blue' => imagecolorallocate($resImage, 0, 0, 225), //Serenity/Harmony
    'violet' => imagecolorallocate($resImage, 160, 0, 192) //Spirit
);

$intIteration = 0;
foreach($arrColours as $intHexColour) {
    imagefilledrectangle($resTrueColor,
        0,
        $intHeightOfBlock * $intIteration,
        $arrImageSize[0],
        ($intHeightOfBlock * $intIteration) + $intHeightOfBlock,
        $intHexColour);
    $intIteration++;
}

imagecopymerge($resImage, $resTrueColor, 0, 0, 0, 0, $arrImageSize[0], $arrImageSize[1], 30);
imagejpeg($resImage, 'rainbow_'.$argv[1]);
imagedestroy($resImage);

die("\r\nImage has been rainbowfied!\r\n");