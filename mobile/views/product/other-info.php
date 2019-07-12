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
    .header2 a{color:rgb(153, 153, 153)}
    .header2 p{color:white}
</style>
<?php $this->endBlock()?>

<div class="content_w">

    <div class="header" style="background-color: rgb(255, 144, 0)">
        <a href="javascript:history.back();" class="back" style="display: block;width: 24px"><i class="fa fa-angle-left " style="font-size: 25px;padding: 5px"></i></a>
        <p>其他相关信息</p>
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
            <?php $i=0;foreach ($fields as $field):?>
                <div class="item">
                    <span class="icon"><?= $field?></span>
                    <input type="text" name="field_<?= $i?>" id="field_<?= $i?>" placeholder="请输入<?= $field?>">
                    <span class="unit" style="opacity: 0">元</span>
                </div>
                <?php $i++?>
            <?php endforeach;?>
                <div class="btn">
                    <button onclick="saveOtherInfo()">下一步</button>
                </div>
        </div>
    </div>
</div>

<?php $this->beginBlock('footer');?>
<script>
    // 提交
    function saveOtherInfo() {
        var apply_info = <?= json_encode(Yii::$app->session['apply_info'])?>;
        var fields = <?= json_encode($fields)?>;
        if(!apply_info){
            layer.open({content: '你已经提交过申请了，请不要重复提交', skin: 'msg', time: 2});
            return false;
        }
        for (i = 0; i < fields.length; i++) {
            if ($('#field_' + i).val() === '') {
                layer.open({content: $('#field_' + i).prev().text() + '不能为空', skin: 'msg', time: 2});
                return false;
            }
        }
        var obj = {};
        obj['product_id'] = <?= $product['id']?>;
        obj['_csrf'] = '<?= Yii::$app->request->csrfToken?>';
        for (i = 0; i < fields.length; i++) {
            obj['field_'+i] = $('#field_' + i).val();
        }
        $.post('<?= Url::toRoute(['product/save-other-info'])?>',obj,function (res) {
            if(res.status === 200){
                $('.btn button').html('申请中 <i class="fa fa-spinner fa-spin"></i>');
                setTimeout(function () {
                    location.href = '<?= Url::toRoute(['product/final','product_id'=>$product['id']])?>';
                },1000);
            }else{
                layer.open({content:res.msg,skin:'msg',time:2});
            }
        },'json')
    }
</script>
<?php $this->endBlock('footer');?>
