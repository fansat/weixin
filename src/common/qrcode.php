<?php
/**
 * Created by PhpStorm.
 * User: Marcus
 * Date: 18/11/5
 * Time: 15:23
 */


require __DIR__ . '/phpqrcode.php';
return QRcode::png($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4, $saveandprint=false);