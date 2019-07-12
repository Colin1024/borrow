<?php
use yii\helpers\Url;
?>
<?php $this->beginBlock('header');?>
<style>
    .cate_nav{background-color: #fff;font-size: 1rem;padding: .6rem;display: flex;justify-content: space-between;};
    .text_main{background-color: #fff;margin-top: .5rem}
     .more{background-color: #fff;text-align: center;font-size: 16px;padding: 10px;border-radius: 1px}
</style>
<?php $this->endBlock();?>
<div class="content_w">
    <div class="header" style="background-color: rgb(255, 144, 0)">
<!--        <a href="--><?//= Url::toRoute(['index/index'])?><!--" class="back" style="display: block;width: 24px"><i class="fa fa-angle-left " style="font-size: 25px;padding: 5px"></i></a>-->
        <p>全部产品</p>
    </div>
    
    <div class="cate_nav">
            <span style="color:rgb(255, 128, 7)">
                <i class="fa fa-bars"></i> 分类：
            </span>
        <div class="cate">
            <a href="<?= Url::toRoute(['product/index'])?>">全部</a>
        </div>
        <?php foreach ($cates as $cate):?>
            <div class="cate">
                <a href="<?= Url::toRoute(['product/index','cate_id'=>$cate['id']])?>"><?= $cate['name']?></a>
            </div>
        <?php endforeach;?>
    </div>

    <!--main -->
    <div class="index_main">
        <ul class="product-list">
            <?php foreach ($products as $product):?>
                <li>
                    <h1>
                        <?= $product['name']?>
                        <?php
                        $labels = explode(",",trim($product['label'],','));
                        foreach ($labels as $label):?>
                            <i><?= $label?></i>
                        <?php endforeach;?>
                    </h1>
                    <div class="index_main_con">
                        <div class="imc_left fl">
                            <img src="<?= $product['thumb']?>"/>
                        </div>
                        <div class="imc_mid fl">
                            <h2><?= $product['description']?></h2>
                            <div class="imc_mid_con ">
                                <div class="imcm_left fl">
                                    <p>申请人数</p>
                                    <h3><?= $product['apply_num']?>人</h3>
                                </div>
                                <div class="imcm_right fl">
                                    <p>申请金额</p>
                                    <h3><?= $product['min_money']?>-<?= $product['max_money']?>元</h3>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="imc_right fr">
                            <a href="<?= Url::toRoute(['product/detail','id'=>$product['id']])?>">立即申请</a>
                        </div>
                        <div class="clear"></div>
                    </div>
                </li>
            <?php endforeach;?>
        </ul>
        <div class="more">加载更多</div>
    </div>
    <!--main end-->
</div>

<!--footer-->
<div class="footer">
    <ul>
        <li>
            <a href="<?= Url::toRoute(['index/index'])?>">
                <i class="iconfont icon-lobby"></i>
                <p>首页</p>
            </a>
        </li>
        <li class="on">
            <a href="<?= Url::toRoute(['product/index'])?>">
                <i class="iconfont icon-jiaoyiguanli-fenxiao"></i>
                <p>全部产品</p>
            </a>
        </li>
        <li>
            <a href="<?= Url::toRoute(['member/index'])?>">
                <i class="iconfont icon-geren"></i>
                <p>个人中心</p>
            </a>
        </li>
        <li>
            <a href="<?= Url::toRoute(['system/index'])?>">
                <i class="iconfont icon-kefu"></i>
                <p>客服</p>
            </a>
        </li>
    </ul>

</div>
<!--footer main-->


<?php $this->beginBlock('footer')?>
<script>
    // 分类id
    var cate_id = '<?= isset($_GET['cate_id']) ? $_GET['cate_id'] : ''?>';
    //分类选中效果
   var url = location.href;
   var origin = location.origin;
    var cate = $('.cate a');
    cate.each(function () {
        var cate_url = origin + $(this).attr('href');
        if(cate_url === url){
           $(this).css({'color':'rgb(77, 192, 246)'})
        }
    })

    // 加载更多
    var offset = 5;
    $('.more').click(function () {
        $('.more').html('加载中 <i class="fa fa-spinner fa-spin"></i>');
        $.get('<?= Url::toRoute(['product/more'])?>',{cate_id:cate_id,offset:offset},function (res) {
         //   console.log(res);
            if(res.status === 200){
                $('.product-list').append(res.data);
                offset = res.offset;
                $('.more').html('加载更多');
            }else{
                $('.more').html('没有更多数据');
            }
        },'json')
    })
</script>
<?php $this->endBlock()?>
