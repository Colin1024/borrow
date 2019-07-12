<?php
use yii\helpers\Url;
?>

<?php $this->beginBlock('header')?>
<style>
    .login-way{background-color: #fff;display: flex;justify-content: space-around;font-size: 15px;padding: 10px}
    .login-box{background-color: #fff;margin-top: 10px;border-radius: 3px;padding: 20px}
    .item{text-align: center;font-size: 25px;display: flex;justify-content: space-between;padding-bottom: 10px;border-bottom: 1px solid rgb(243, 243, 243)}
    .item .icon{width: 10%;color:rgb(255, 144, 0)}
    .item input{width: 85%;height: 30px;font-size: 16px;border: 0;}
    .btn{display: flex;justify-content: center;margin: 15px;}
    .btn button{border: 0;background-color: rgb(255, 144, 0);width: 95%;color: #ffff;font-size: 17px;border-radius: 3px;padding: 5px}
    .login-register{display: flex;justify-content: center;font-size: 16px;padding: 5px;background-color: #fff}
    .login-register a{color:rgb(74, 190, 245);}
</style>
<?php $this->endBlock()?>

<div class="content_w">

    <div class="header" style="background-color: rgb(255, 144, 0)">
        <a href="<?= Url::toRoute(['member/index'])?>" class="back" style="display: block;width: 24px"><i class="fa fa-angle-left " style="font-size: 25px;padding: 5px"></i></a>
        <p>登录</p>
    </div>

    <div class="main">
        <div class="login-way">
            <span><a href="<?= Url::toRoute(['member/login'])?>" style="color: rgb(74, 190, 245)" >账号密码登录</a></span>
            <span><a href="<?= Url::toRoute(['member/phone-login'])?>" >手机号快捷登录</a></span>
        </div>
        <div class="login-box">
            <form action="" method="post" onsubmit="return false;">
                <div class="item">
                    <span class="icon"><i class="fa fa-phone"></i></span>
                    <input type="text" name="phone" placeholder="请输入手机号">
                </div>
                <div class="item">
                    <span class="icon"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password"  placeholder="请输入密码">
                </div>
                <div class="btn">
                    <button type="submit">登录</button>
                </div>
                <div class="login-register"><a href="<?= Url::toRoute(['member/register'])?>">立即注册</a></div>
            </form>
        </div>
    </div>
</div>

<?php $this->beginBlock('footer');?>
<script>
    // 提交表单
    $('.btn button').click(function () {
        var phone = $('input[name="phone"]').val();
        var password = $('input[name="password"]').val();
        var _csrf = '<?= Yii::$app->request->csrfToken?>';
        if(phone === ''){
            layer.open({content:'手机号不能为空',skin:'msg',time:2});
            return false;
        }
        if(!/^1[3,5,7,8,9]\d{9}$/.test(phone)){
            layer.open({content:'手机号格式不正确',skin:'msg',time:2});
            return false;
        }
        if(password === ''){
            layer.open({content:'密码不能为空',skin:'msg',time:2});
            return false;
        }
        $('button[type="submit"]').html('登录中 <i class="fa fa-spinner fa-spin"></i>');
        $.post('<?=  Url::toRoute(['member/login'])?>',{_csrf:_csrf,phone:phone,password:password},function (res) {
                if(res.status === 200){
                    layer.open({content:res.msg,style: 'border:none; background-color:#78BA32; color:#fff;',skin:'msg',time:2,end:function () {
                            location.href = '<?= Url::toRoute(['member/index'])?>';
                        }})
                }else{
                    layer.open({content:res.msg,skin:'msg',time:2});
                    $('button[type="submit"]').html('登录');
                }
         },'json')
    })
</script>
<?php $this->endBlock('footer');?>
