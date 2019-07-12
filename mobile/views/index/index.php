<?php
use yii\helpers\Url;
?>

<?php $this->beginBlock('header');?>
    <style>
        .more{background-color: #fff;text-align: center;font-size: 16px;padding: 10px;border-radius: 1px}
    </style>
<?php $this->endBlock()?>

<div class="content_w">
    <!--轮播图banner-->
    <div class="index_banner">
        <div id="slideBox" class="slideBox">
            <div class="bd">
                <ul>
                    <!--动态绑定-->
                </ul>
            </div>
            <div class="hd">
                <ul></ul>
            </div>
        </div>

    </div>
    <!--轮播图banner end-->

    <!--nav -->
    <div class="index_nav">
        <ul>
            <li>
                <a href="<?= Url::toRoute(['product/index'])?>">
                    <img src="<?= Url::base()?>/mobile/web/images/icon1.png"/>
                    <p>全部产品</p>
                </a>
            </li>
            <li>
                <a href="<?= Url::toRoute(['page/index','id'=>1])?>">
                    <img src="<?= Url::base()?>/mobile/web/images/icon2.png"/>
                    <p>关于我们</p>
                </a>
            </li>
            <li>
                <a href="<?= Url::toRoute(['page/index','id'=>4])?>">
                    <img src="<?= Url::base()?>/mobile/web/images/icon3.png"/>
                    <p>审批流程</p>
                </a>
            </li>
            <li>
                <a href="javascript:invite()">
                    <img src="<?= Url::base()?>/mobile/web/images/icon4.png"/>
                    <p>推广分销</p>
                </a>
            </li>
            <div class="clear"></div>
        </ul>

    </div>
    <!--nav end-->

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

    <!--footer-->
    <div class="footer">
        <ul>
            <li class="on">
                <a href="<?= Url::toRoute(['index/index'])?>">
                    <i class="iconfont icon-lobby"></i>
                    <p>首页</p>
                </a>
            </li>
            <li>
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
            <div class="clear"></div>
        </ul>

    </div>
    <!--footer main-->
</div>

<?php $this->beginBlock('footer');  ?>
<!-- <body></body>后代码块 -->
<script src="<?= Url::base()?>/mobile/web/js/TouchSlide.1.1.js"></script>
<script>
    // 动态加载轮播图
    var imgs = <?= $banners?>;
      for(var i=0;i<imgs.length;i++){
         $("#slideBox ul").append('<li><a class="pic" href="'+imgs[i].link+'"><img src="'+imgs[i].pic+'" /></a></li>')
      }

      //Swiper组件
        TouchSlide({
            slideCell:"#slideBox",
            titCell:".hd ul",
            mainCell:".bd ul",
            effect:"leftLoop",
            autoPage:true,
            autoPlay:true
        });

      // 推广分销
    function invite() {
        var is_login = '<?= Yii::$app->session['is_login']?>';
        if(!is_login){
            layer.open({content:'请登录后查看',skin:'msg',time:2})
        }else{
            location.href = '<?= Url::toRoute(['member/invite'])?>';
        }
    }

    // 加载更多
    var offset = 5;
    $('.more').click(function () {
        $('.more').html('加载中 <i class="fa fa-spinner fa-spin"></i>');
        $.get('<?= Url::toRoute(['index/more'])?>',{offset:offset},function (res) {
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

<?php $this->endBlock(); ?>