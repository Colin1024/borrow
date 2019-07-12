<?php
use yii\helpers\Url;
?>

<?php $this->beginBlock('header')?>
<?php $this->endBlock()?>

<div class="content_w">

    <div class="header" style="background-color: rgb(255, 144, 0)">
        <a href="javascript:history.back();" class="back" style="display: block;width: 24px"><i class="fa fa-angle-left " style="font-size: 25px;padding: 5px"></i></a>
        <p>产品详情</p>
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

    <!--main3-->
    <div class="detail_main3">
        <h1>申请流程</h1>
        <div class="detail_main3_con" style="padding: 10px">
            <?= htmlspecialchars_decode($product['apply_process'])?>
        </div>
    </div>
    <!--main3 end-->


    <!--main4 -->
    <div class="detail_main4 mt10">
        <h1>申请条件</h1>
        <div class="detail_main4_con">
          <?= htmlspecialchars_decode($product['apply_condition'])?>
        </div>
    </div>
    <!--main4 end-->


    <!--main5 -->
    <div class="detail_main4 mt10">
        <h1>新手指导</h1>
        <div class="detail_main4_con">
            <?= htmlspecialchars_decode($product['guide'])?>
        </div>
    </div>
    <!--main5 end-->

    <!--main6 -->
    <div class="detail_main6">
        <a href="javascript:;" onclick="apply()">立即申请</a>
    </div>
    <!--main6 end-->

</div>

<?php $this->beginBlock('footer');?>
<script>
    var is_login = '<?= Yii::$app->session['is_login']?>';
    function apply() {
        if(!is_login){
            layer.open({content:'请先登录',skin:'msg',time:1.5,end:function () {
                   location.href = '<?= Url::toRoute(['member/login'])?>';
                }});
        }else{
            // 检查最近一笔订单状态
            $.get('<?= Url::toRoute(['product/apply-status'])?>',{},function (res) {
                if(res.status === 200){
                    location.href = '<?= Url::toRoute(['product/apply','product_id'=>$product['id']])?>';
                }else{
                    layer.open({content:res.msg,skin:'msg',time:3})
                }
            },'json');
        }
    }
</script>
<?php $this->endBlock('footer');?>
