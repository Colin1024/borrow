<?php
/**
	��������  ����IT����
	https://www.liminghulian.com/
	����ϸ��˵����ο�https://www.liminghulian.com/article/31
**/

require_once 'phpqrcode/phpqrcode.php'; //�������
$text = "https://www.liminghulian.com/";//Ҫ���ɶ�ά����ı�
$logo = './a.png';//����logo·��
QRcode::png($text,false,'H',4,2,false,$logo);//�������������������ļ�

?>



