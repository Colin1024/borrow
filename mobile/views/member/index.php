<?php
use yii\helpers\Url;
?>

<?php $this->beginBlock('header')?>
<style>
    .fa{font-size: 20px}
    .mem-header{background-color: rgb(255, 144, 0);text-align: center;}
    .mem-header img{margin-top: 25px;border-radius: 50%;width: 80px}
    .mem-header .login{font-size: 18px;color: #fff;padding: 25px}
    .mem-header .login a{color: #fff;}
    .main{margin-bottom: 50px}
    .main .item-group{margin-top: 10px;background-color: #fff;border-radius: 3px}
    .item-group .item{font-size: 17px;border-bottom: 1px solid rgb(243, 243, 243);padding: 10px 15px;}
    .item a{display: flex;justify-content: space-between}
    .item .item-right{color: rgb(213, 196, 187)}

</style>
<?php $this->endBlock()?>

<div class="content_w">

    <div class="mem-header">
        <div class="avatar">
            <?php if(Yii::$app->session['is_login']):?>
                <img src="<?= $member['head_img']?>" alt="点击更换头像" onclick="fileSelect()" class="animated rotateIn">
            <?php else:?>
                <img src="<?= Url::base()?>/mobile/web/images/avatar.png" alt="点击更换头像" onclick="fileSelect()" class="animated rotateIn">
            <?php endif;?>
        </div>
        <div class="login">
            <?php if(Yii::$app->session['is_login']):?>
            <p><?= $member['tel']?></p>
            <?php else:?>
            <p><a href="<?= Url::toRoute(['member/login'])?>">登录</a>/<a href="<?= Url::toRoute(['member/register'])?>">注册</a></p>
            <?php endif;?>
        </div>
    </div>

    <div class="main">
        <div class="item-group">
            <div class="item">
                <a href="<?= Url::toRoute(['info/index'])?>">
                    <span><i class="fa fa-fw fa-user" style="color: rgb(26, 188, 156)"></i> 我的信息</span>
                    <span class="item-right"><i class="fa fa-angle-right"></i></span>
                </a>
            </div>
            <div class="item">
                <a href="<?= Url::toRoute(['member/balance'])?>">
                    <span><i class="fa fa-fw fa-cny" style="color: rgb(74, 190, 245)"></i> 我的资金</span>
                    <span class="item-right"><i class="fa fa-angle-right"></i></span>
                </a>
            </div>
            <div class="item">
                <a href="<?= Url::toRoute(['member/money-log'])?>">
                    <span><i class="fa fa-fw fa-history" style="color: rgb(255, 144, 0);"></i> 资金记录</span>
                    <span class="item-right"><i class="fa fa-angle-right"></i></span>
                </a>
            </div>
            <div class="item">
                <a href="<?= Url::toRoute(['member/apply'])?>">
                    <span><i class="fa fa-fw fa-calendar-plus-o" style="color: red;"></i> 我的借款</span>
                    <span class="item-right"><i class="fa fa-angle-right"></i></span>
                </a>
            </div>
            <div class="item">
                <a href="<?= Url::toRoute(['member/invite'])?>">
                    <span><i class="fa fa-fw fa-share-alt" style="color: rgb(181, 144, 247);"></i> 我的推广</span>
                    <span class="item-right"><i class="fa fa-angle-right"></i></span>
                </a>
            </div>
        </div>
        <?php if(Yii::$app->session['is_login']):?>
            <div class="item-group">
                <div class="item">
                    <a href="<?= Url::toRoute(['member/change-pwd']) ?>">
                        <span><i class="fa fa-fw fa-key" style="color: rgb(255, 128, 7);"></i> 修改密码</span>
                        <span class="item-right"><i class="fa fa-angle-right"></i></span>
                    </a>
                </div>
                <div class="item">
                    <a href="<?= Url::toRoute(['member/logout']) ?>">
                        <span><i class="fa fa-fw fa-sign-out" style="color: rgb(253, 79, 81);"></i> 退出登录</span>
                        <span class="item-right"><i class="fa fa-angle-right"></i></span>
                    </a>
                </div>
            </div>
        <?php endif;?>
    </div>
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
        <li>
            <a href="<?= Url::toRoute(['product/index'])?>">
                <i class="iconfont icon-jiaoyiguanli-fenxiao"></i>
                <p>全部产品</p>
            </a>
        </li>
        <li class="on">
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

<!--图片上传-->
<form id="uploadForm" enctype="multipart/form-data" style="display: none">
    <input type="file" name="file" id="file">
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken?>">
</form>

<?php $this->beginBlock('footer');?>
<script>
    var is_login = '<?= Yii::$app->session['is_login']?>';
    // 图片异步上传
    function fileSelect() {
        if(!is_login){  // 登陆后，才可以修改头像
            location.href = '<?= Url::toRoute(['member/login'])?>';
            return false;
        }
        $('#file').click();
    }

    $("#file").change(function(){
        //var _csrf = "<?//= Yii::$app->request->csrfToken?>//";
        // console.log(_csrf);
        $.ajax({
            url: '<?=Url::toRoute('member/avatar')?>',
            type: 'POST',
            cache: false,
            data: new FormData($('#uploadForm')[0]),
            dataType:'JSON',
            processData: false,
            contentType: false
        }).done(function(res) {
            if(res.status === 200){
                layer.open({content:'上传成功',style: 'border:none; background-color:#78BA32; color:#fff;',skin:'msg',time:2});
                $('.avatar img').attr('src',res.path);
            }
        });
    });

</script>
<?php $this->endBlock('footer');?>
