<?php
use yii\helpers\Url;
?>

<?php $this->beginBlock('header')?>
<style>
    .change-pwd-box{background-color: #fff;margin-top: 10px;border-radius: 3px;padding: 20px;text-align: center}
    .change-pwd-box p{font-size: 14px;line-height: 25px}
    .item input{width: 60%;height: 30px;font-size: 16px;border: 0;}
    .btn{display: flex;justify-content: center;margin: 15px;}
    .btn button{border: 0;background-color: rgb(255, 144, 0);width: 95%;color: #ffff;font-size: 17px;border-radius: 3px;padding: 5px}
    .header2 a{color:rgb(153, 153, 153)}
    .header2 p{color:white}
</style>
<?php $this->endBlock()?>

<div class="content_w">

    <div class="header" style="background-color: rgb(255, 144, 0)">
        <a href="javascript:history.back();" class="back" style="display: block;width: 24px"><i class="fa fa-angle-left " style="font-size: 25px;padding: 5px"></i></a>
        <p>结果提示</p>
    </div>

    <!--main1 -->
    <div class="detail_main1">
        <div class="detail_main1_con">
            <h2>
                <img src="<?= $product['thumb']?>"/>
            </h2>
            <p><?= $product['name']?></p>
            <p>申请金额: <?= $product['min_money']?>-<?= $product['max_money']?>元</p>
            <h3>
                <?php
                $labels = explode(",",trim($product['label'],','));
                foreach ($labels as $label):?>
                    <i><?= $label?></i>
                <?php endforeach;?>
            </h3>
        </div>
    </div>
    <!--main1 end-->
    <div class="main">
        <div class="change-pwd-box">
            <img src="<?= Url::Base()?>/mobile/web/images/apply_success2.png" alt="" style="width: 80px">
            <p style="padding-top: 5px">申请成功，请留意审核消息</p>
            <p>可以在个人中心，我的借款中查看申请进度</p>
            <p>工作时间：09:00--18:00</p>
            <div class="btn">
                <button onclick="backIndex()">返回首页</button>
            </div>
        </div>
    </div>
</div>

<?php $this->beginBlock('footer');?>
<script>
    // 提交
    function backIndex() {
        location.href = '<?= Url::toRoute(['index/index'])?>';
    }
</script>
<?php $this->endBlock('footer');?>
