<?php
use yii\helpers\Url;
?>

<?php $this->beginBlock('header')?>
<style>
    .change-pwd-box{background-color: #fff;margin-top: 10px;border-radius: 3px;padding: 20px}
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
        <p>身份&手机号认证</p>
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
                <div class="item">
                    <span class="icon">真实姓名</span>
                    <input type="text" name="real_name" placeholder="请输入真实姓名" value="<?= $member['name']?>">
                    <span class="unit" style="opacity: 0">元</span>
                </div>
                <div class="item">
                    <span class="icon">身份证号</span>
                    <input type="number" name="id_card"  placeholder="请输入身份证号" value="<?= $member['id_card']?>">
                    <span class="unit" style="opacity: 0">天</span>
                </div>
                <div class="item">
                    <span class="icon">银行卡号</span>
                    <input type="number" name="bank_card"  placeholder="请输入银行卡号" value="<?= $member['bank_card']?>">
                    <span class="unit"><span style="opacity: 0">天</span></span>
                </div>
            <div class="item">
                <span class="icon">绑定号码</span>
                <input type="number" name="bank_tel"  placeholder="请输入银行卡绑定的手机号" value="<?= $member['bank_tel']?>">
                <span class="unit"><span style="opacity: 0">天</span></span>
            </div>
                <div class="btn">
                    <button onclick="saveAuth()">下一步</button>
                </div>
        </div>
    </div>
</div>

<?php $this->beginBlock('footer');?>
<script>
 // 提交申请信息
function saveAuth() {
    var real_name = $('input[name="real_name"]').val();
    var id_card = $('input[name="id_card"]').val();
    var bank_card = $('input[name="bank_card"]').val();
    var bank_tel = $('input[name="bank_tel"]').val();
    var product_id = <?= $product['id']?>;
    var tel_auth_need = <?= $product['tel_auth_need']?>;
    var other_info_need = '<?= $product['other_info']?>';
    var _csrf = '<?= Yii::$app->request->csrfToken?>';
    if(real_name === ''){
        layer.open({content:'真实姓名不能为空',skin:'msg',time:2});
        return false;
    }
    if(id_card === ''){
        layer.open({content:'身份证号不能为空',skin:'msg',time:2});
        return false;
    }
    if(bank_card === ''){
        layer.open({content:'银行卡号不能为空',skin:'msg',time:2});
        return false;
    }
    if(bank_tel === ''){
        layer.open({content:'绑定手机号不能为空',skin:'msg',time:2});
        return false;
    }
    if(!/^\d{18}|\d{17}X$/i.test(id_card)){
        layer.open({content:'身份证号格式不正确',skin:'msg',time:2});
        return false;
    }
    if(!/^([1-9]{1})(\d{14}|\d{18})$/.test(bank_card)){
        layer.open({content:'银行卡号格式不正确',skin:'msg',time:2});
        return false;
    }
    if(!/^1[3,5,7,8,9]\d{9}$/.test(bank_tel)){
        layer.open({content:'绑定手机号格式不正确',skin:'msg',time:2});
        return false;
    }
    $.post('<?= Url::toRoute(['product/save-auth'])?>',{real_name:real_name,id_card:id_card,bank_card:bank_card,bank_tel:bank_tel,product_id:product_id,_csrf:_csrf},function (res) {
        if(res.status === 200){
            if(tel_auth_need === 1){
                location.href = res.url;
            }else{
                if(!other_info_need){  // 不需要其他信息字段
                    $('.btn button').html('申请中 <i class="fa fa-spinner fa-spin"></i>');
                    setTimeout(function () {
                        location.href = '<?= Url::toRoute(['product/other-info','product_id'=>$product['id']])?>';
                    },1000);
                }else{
                    location.href = '<?= Url::toRoute(['product/other-info','product_id'=>$product['id']])?>';
                }
            }
        }else{
            layer.open({content:res.msg,skin:'msg',time:2});
        }
    },'json')
}
</script>
<?php $this->endBlock('footer');?>
