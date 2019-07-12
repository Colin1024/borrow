<?php

use yii\helpers\Url;
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta name="keywords" content=" "/>
    <meta name="author" content="order by www.lision.cn"/>
    <meta name="description" content=" "/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <title>首页</title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="<?=Url::base()?>/mobile/web/css/basic.css">
    <link rel="stylesheet" type="text/css" href="<?=Url::base()?>/mobile/web/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?=Url::base()?>/mobile/web/css/animate.css">
<!--    <link rel="stylesheet" type="text/css" href="--><?//=Url::base()?><!--/mobile/web/css/mui.min.css">-->
    <link rel="stylesheet" type="text/css" href="http://at.alicdn.com/t/font_742871_i2pjhpsb1do.css">
<!--    <link rel="stylesheet" type="text/css" href="http://at.alicdn.com/t/font_777131_dhf5wad0bb.css">-->
    <link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css">
    <style>
        .header p{color:#fff}
    </style>

    <?php if(isset($this->blocks['header']) == true):?>
        <?= $this->blocks['header'] ?>
    <?php endif;?>

</head>
<body>
<?= $content ?>

<script src="<?=Url::base()?>/mobile/web/js/jquery-1.8.3.min.js"></script>
<script src="<?=Url::base()?>/mobile/web/js/mobile/layer.js"></script>
<script src="<?=Url::base()?>/mobile/web/js/mui.min.js"></script>
<script src="//cdn.bootcss.com/eruda/1.5.2/eruda.min.js"></script>
<script>eruda.init();</script>
<!--<script src="--><?//= Url::base()?><!--/mobile/web/js/vconsole.min.js"></script>-->
<!--<script>var vConsole = new VConsole();</script>-->
</body>

<?php if(isset($this->blocks['footer']) == true):?>
    <?= $this->blocks['footer'] ?>
<?php endif;?>

</html>
