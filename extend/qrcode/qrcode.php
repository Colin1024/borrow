<?php
/**
	黎明互联  公益IT教育
	https://www.liminghulian.com/
	更详细的说明请参考https://www.liminghulian.com/article/31
**/

require_once 'phpqrcode/phpqrcode.php'; //引入类库
$text = "https://www.liminghulian.com/";//要生成二维码的文本
$logo = './a.png';//定义logo路径
QRcode::png($text,false,'H',4,2,false,$logo);//输出到浏览器或者生成文件

?>



