<?php
function editor_optimize_uploaded_image($path) {
 if (!extension_loaded('gd') || !is_file($path)) return false;
 $info=@getimagesize($path);if (!$info) return false;
 $w=$info[0];$h=$info[1];$mime=$info['mime'];
 $dec=array('image/jpeg'=>'imagecreatefromjpeg','image/png'=>'imagecreatefrompng','image/webp'=>'imagecreatefromwebp');
 if (!isset($dec[$mime]) || !function_exists($dec[$mime]) || $w*$h>40000000) return false;
 $src=@call_user_func($dec[$mime],$path);if (!$src) return false;
 $scale=min(1,1920/max($w,$h));$nw=max(1,(int)round($w*$scale));$nh=max(1,(int)round($h*$scale));
 $dst=imagecreatetruecolor($nw,$nh);
 if ($mime!=='image/jpeg') {imagealphablending($dst,false);imagesavealpha($dst,true);$c=imagecolorallocatealpha($dst,0,0,0,127);imagefill($dst,0,0,$c);}
 imagecopyresampled($dst,$src,0,0,0,0,$nw,$nh,$w,$h);
 $tmp=tempnam(dirname($path),'.opt-');
 $ok=$mime==='image/jpeg'?imagejpeg($dst,$tmp,82):($mime==='image/png'?imagepng($dst,$tmp,8):imagewebp($dst,$tmp,82));
 imagedestroy($src);imagedestroy($dst);
 if ($ok && filesize($tmp)>0 && filesize($tmp)<filesize($path) && rename($tmp,$path)) return true;
 @unlink($tmp);return false;
}
