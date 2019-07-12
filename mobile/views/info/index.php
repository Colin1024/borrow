<?php
use yii\helpers\Url;
?>

<?php $this->beginBlock('header');?>
<style>
    .main .item-group{margin-top: 10px;background-color: #fff;border-radius: 3px}
    .item-group .item{font-size: 17px;border-bottom: 1px solid rgb(243, 243, 243);padding: 10px 15px;}
    .item a{display: flex;justify-content: space-between}
    .item .item-right{color: rgb(213, 196, 187)}
</style>
<?php $this->endBlock()?>

<div class="content_w">

    <div class="header" style="background-color: rgb(255, 144, 0)">
        <a href="javascript:history.back()" class="back" style="display: block;width: 24px"><i class="fa fa-angle-left " style="font-size: 25px;padding: 5px"></i></a>
        <p>我的信息</p>
    </div>

    <div class="main">
        <div class="item-group">
            <div class="item">
                <a href="javascript:;">
                    <span><i class="fa fa-fw fa-phone" style="color: rgb(255, 128, 7);font-size: 16px"></i>&nbsp;&nbsp;手机号码</span>
                    <span class="item-right"><?= $member['tel']?></span>
                </a>
            </div>
            <div class="item">
                <a href="javascript:;">
                    <span><i class="fa fa-fw fa-user-o" style="color: rgb(74, 190, 245)"></i>&nbsp;&nbsp;用户姓名</span>
                    <span class="item-right"><?= $member['name'] ? : '未填写'?></span>
                </a>
            </div>
            <div class="item">
                <a href="javascript:;">
                    <span><i class="fa  fa-fw fa-id-card-o" style="color: rgb(26, 188, 156)"></i>&nbsp;&nbsp;身份证号码</span>
                    <span class="item-right"><?= $member['id_card'] ? substr($member['id_card'],0,3).'****'.substr($member['id_card'],-4) : '未填写'?></span>
                </a>
            </div>
            <div class="item">
                <a href="javascript:;">
                    <span><i class="fa fa-fw fa-credit-card" style="color: rgb(181, 144, 247);font-size: 16px"></i>&nbsp;&nbsp;银行卡号码</span>
                    <span class="item-right"><?= $member['bank_card'] ? substr($member['bank_card'],0,3).'****'.substr($member['bank_card'],-4) : '未填写'?></span>
                </a>
            </div>
        </div>

    </div>

</div>

<?php $this->beginBlock('footer')?>
<?php $this->endBlock()?>

