<?php
//funчуo para upload de imagem (jpg)
function upload($arquivo, $destino, $lar, $alt)
{
	$quality = 100; //qualidade (de 0 a 100)
	$wmax = $lar; //largura mсxima
	$hmax = $alt; //altura mсxima
	$source = imagecreatefromjpeg($arquivo['tmp_name']);
	$orig_w = imagesx($source);
	$orig_h = imagesy($source);
		
	if ($orig_w>$wmax || $orig_h>$hmax){
	   $thumb_w = $wmax;
	   $thumb_h = $hmax;
	   if ($thumb_w/$orig_w*$orig_h > $thumb_h) {
		   $thumb_w = round($thumb_h*$orig_w/$orig_h);
	   } else {
		   $thumb_h = round($thumb_w*$orig_h/$orig_w);
	   }
	} 
	else {
	   $thumb_w = $orig_w;
	   $thumb_h = $orig_h;
	}
		
	$thumb = imagecreatetruecolor($thumb_w,$thumb_h);
	imagecopyresampled($thumb,$source,0,0,0,0,$thumb_w,$thumb_h,$orig_w,$orig_h);
		
	if (imagejpeg($thumb, $destino, $quality)){
		return true;
		exit;
	} else {
		return false;
		exit;
	}
		
	imagedestroy($thumb);
}
?>