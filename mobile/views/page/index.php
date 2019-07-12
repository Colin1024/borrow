<?php
use yii\helpers\Url;
?>

<?php $this->beginBlock('header')?>
<style>
    .text_main{background-color: #fff;margin-top: 10px;border-radius: 5px;padding: 10px 20px;}
</style>
<?php $this->endBlock()?>

<div class="content_w">

    <div class="header" style="background-color: rgb(255, 144, 0)">
        <a href="<?= Url::toRoute(['index/index'])?>" class="back" style="display: block;width: 24px"><i class="fa fa-angle-left " style="font-size: 25px;padding: 5px"></i></a>
        <p><?= $page['title']?></p>
    </div>

    <div class="text_main">
        <?= htmlspecialchars_decode($page['content'])?>
    </div>

</div>
