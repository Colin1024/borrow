<?php
use yii\helpers\Url;
?>

<?php $this->beginBlock('header')?>
<style>
    .change-pwd-box{background-color: #fff;margin-top: 10px;border-radius: 3px;padding: 20px}
    .tip{padding-bottom: 15px;padding-left: 5px;font-size: 14px}
    .item{text-align: center;font-size: 25px;display: flex;justify-content: space-between;padding-bottom: 10px;border-bottom: 1px solid rgb(243, 243, 243);}
    .item .icon{color:rgb(255, 144, 0);font-size: 16px;display: inline-block;line-height: 30px}
    .unit{font-size: 16px;color:rgb(255, 144, 0);line-height: 30px}
    .item input{width: 60%;height: 30px;font-size: 16px;border: 0;}
    .btn{display: flex;justify-content: center;margin: 15px;}
    .btn button{border: 0;background-color: rgb(255, 144, 0);width: 95%;color: #ffff;font-size: 17px;border-radius: 3px;padding: 5px}
    /*.header2{height: 40px;display: flex;justify-content: space-between;font-size: 16px;line-height: 40px;padding:0 8px }*/
    .header2 a{color:rgb(153, 153, 153)}
    .header2 p{color:white}
</style>
<?php $this->endBlock()?>

<div class="content_w">
    <div class="header" style="background-color: rgb(255, 144, 0)">
        <a href="javascript:history.back();" class="back" style="display: block;width: 24px"><i class="fa fa-angle-left " style="font-size: 25px;padding: 5px"></i></a>
        <p>申请信息</p>
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
            <div class="tip">
                <p style="color: red">* 申请金额要在该产品的申请金额范围内</p><br>
                <p style="color: red">* 申请期限范围为<i style="font-weight: bolder"><?= $min_time_limit?>-<?= $max_time_limit?></i>天</p>
            </div>
                <div class="item">
                    <span class="icon">申请金额</span>
                    <input type="number" name="money" placeholder="请输入申请金额">
                    <span class="unit">元</span>
                </div>
                <div class="item">
                    <span class="icon">申请期限</span>
                    <input type="number" name="time_limit"  placeholder="请输入申请期限">
                    <span class="unit">天</span>
                </div>
                <div class="item">
                    <span class="icon">申请原因</span>
                    <input type="text" name="use"  placeholder="请输入申请原因">
                    <span class="unit"><span style="opacity: 0">天</span></span>
                </div>
                <div class="btn">
                    <button onclick="saveApply()">下一步</button>
                </div>
        </div>
    </div>
</div>

<?php $this->beginBlock('footer');?>
<script>
 // 提交申请信息
function saveApply() {
    var money = $('input[name="money"]').val();
    var time_limit = $('input[name="time_limit"]').val();
    var use = $('input[name="use"]').val();
    var min_money = <?= $product['min_money']?>;
    var max_money = <?= $product['max_money']?>;
    var min_time_limit = <?= $min_time_limit?>;
    var max_time_limit = <?= $max_time_limit?>;
    var _csrf = '<?= Yii::$app->request->csrfToken?>';
    var product_id = <?= $product['id']?>;
    if(money === ''){
        layer.open({content:'申请金额不能为空',skin:'msg',time:2});
        return false;
    }
    if(time_limit === ''){
        layer.open({content:'申请期限不能为空',skin:'msg',time:2});
        return false;
    }
    if(use === ''){
        layer.open({content:'申请原因不能为空',skin:'msg',time:2});
        return false;
    }
    if(time_limit<min_time_limit || time_limit>max_time_limit){
        layer.open({content:'申请期限不在正确的范围内',skin:'msg',time:2});
        return false;
    }
    if(money <= 0){
        layer.open({content:'申请金额需要大于0',skin:'msg',time:2});
        return false;
    }
    if(money < min_money || money > max_money){
        layer.open({content:'申请金额需在'+min_money+'-'+max_money+'之间',skin:'msg',time:2});
        return false;
    }
    $.post('<?= Url::toRoute(['product/save-apply'])?>',{money:money,time_limit:time_limit,use:use,product_id:product_id,_csrf:_csrf},function (res) {
        if(res.status === 200){
            location.href = '<?= Url::toRoute(['product/auth','product_id'=>$product['id']])?>';
        }else{
            layer.open({content:res.msg,skin:'msg',time:2});
            return false;
        }
    },'json')
}
</script>
<?php $this->endBlock('footer');?>
