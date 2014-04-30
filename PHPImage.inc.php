<?php
/************************************************
* Module: PHPImage.inc.php						*
* Author Name: J.D. Stone						*
* 												*
* Purpose: Creates thumbnail image with title	*
*		   of library item						*
*************************************************/

header("Content-type: image/png");

class PHPImage {
	public function __construct() {
		
	}

	public function generateImage($height, $width) {
		putenv('GDFONTPATH=' . realpath('.'));
		$text = $_GET['text'];
		$font = 'fonts/DejaVuSerif';
		$font_size = 12;
		if ($width > 100) {
			$font_size = 50;
		}
		$image = imagecreatetruecolor($height,$width);
		$white = imagecolorallocate($image,255,255,255);
		$black = imagecolorallocate($image,0,0,0);
		imagefilledrectangle($image,1,1,$height-2,$width-2,$white);
		$tsize = imagettfbbox($font_size,0,$font,$text);
		$dx = abs($tsize[2]-$tsize[0]);
		$dy = abs($tsize[5]-$tsize[3]);
		$x = (imagesx($image) - $dx) / 2;
		$y = (imagesy($image) - $dy) / 2 + $dy;
		// draw text
		imagettftext($image,$font_size,0,$x,$y,$black,$font,$text);
		imagepng($image);
		imagedestroy($image);
	}
}

$image = new PHPImage();
if ((isset($_GET['h'])) && (is_numeric($_GET['h'])) && (isset($_GET['w'])) && (is_numeric($_GET['w']))) {
	$image->generateImage($_GET['h'], $_GET['w']);
}
?>