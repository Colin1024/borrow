<?php
use yii\helpers\Url;
?>

<?php $this->beginBlock('header')?>
<style>
    .register-box{background-color: #fff;margin-top: 10px;border-radius: 3px;padding: 20px}
    .item{text-align: center;font-size: 25px;display: flex;justify-content: space-between;padding-bottom: 10px;border-bottom: 1px solid rgb(243, 243, 243)}
    .item .icon{width: 10%;color:rgb(255, 144, 0)}
    .item input{width: 85%;height: 30px;font-size: 16px;border: 0;}
    .item .captcha{font-size: 10px;background-color: rgb(255, 144, 0);padding: 3px;border-radius: 3px;color:#fff}
    .btn{display: flex;justify-content: center;margin: 15px;}
    .btn button{border: 0;background-color: rgb(255, 144, 0);width: 95%;color: #ffff;font-size: 17px;border-radius: 3px;padding: 5px}
    .login-register{display: flex;justify-content: center;font-size: 16px;padding: 5px;background-color: #fff}
    .login-register a{color:rgb(74, 190, 245);}
</style>
<?php $this->endBlock()?>

<div class="content_w">

    <div class="header" style="background-color: rgb(255, 144, 0)">
        <a href="javascript:history.back();" class="back" style="display: block;width: 24px"><i class="fa fa-angle-left " style="font-size: 25px;padding: 5px"></i></a>
        <p>注册</p>
    </div>

    <div class="main">
        <div class="register-box">
            <form action="" method="post" onsubmit="return false;">
                <input type="hidden" value="<?= Yii::$app->session['pre_id']?>" name="pre_id">
                <div class="item">
                    <span class="icon"><i class="fa fa-phone"></i></span>
                    <input type="text" name="phone" placeholder="请输入手机号">
                </div>
                <div class="item">
                    <span class="icon"><i class="fa fa-code"></i></span>
                    <input type="text" name="captcha"  placeholder="请输入验证码" style="width: 60%">
                    <span><a href="javascript:;" class="captcha">获取验证码</a></span>
                </div>
                <div class="item">
                    <span class="icon"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password"  placeholder="请输入密码">
                </div>
                <div class="item">
                    <span class="icon"><i class="fa fa-lock"></i></span>
                    <input type="password" name="re_password"  placeholder="请输入确认密码">
                </div>
                <div class="btn">
                    <button type="submit">注册</button>
                </div>
                <div class="login-register"><a href="<?= Url::toRoute(['member/login'])?>">立即注册</a></div>
            </form>
        </div>
    </div>
</div>

<?php $this->beginBlock('footer');?>
<script>
    // 提交表单
    $('.btn button').click(function () {
        var phone = $('input[name="phone"]').val();
        var captcha = $('input[name="captcha"]').val();
        var password = $('input[name="password"]').val();
        var re_password = $('input[name="re_password"]').val();
        var pre_id = $('input[name="pre_id"]').val();
        var _csrf = '<?= Yii::$app->request->csrfToken?>';
        if(check(phone,captcha,password,re_password)){
            $.post('<?=  Url::toRoute(['member/register'])?>',{_csrf:_csrf,phone:phone,captcha:captcha,password:password,re_password:re_password,pre_id:pre_id},function (res) {
                console.log(res);
                if(res.status === 200){
                    layer.open({content:res.msg,style: 'border:none; background-color:#78BA32; color:#fff;',skin:'msg',time:2,end:function () {
                            location.href = '<?= Url::toRoute(['member/index'])?>';
                        }})
                }else{
                    layer.open({content:res.msg,skin:'msg',time:2});
                }
            },'json')
        }
    });

    // 字段检查
    function check(phone,captcha,password,re_password) {
        if(phone === ''){
            layer.open({content:'手机号不能为空',skin:'msg',time:2});
            return false;
        }
        if(!/^1[3,5,7,8,9]\d{9}$/.test(phone)){
            layer.open({content:'手机号格式不正确',skin:'msg',time:2});
            return false;
        }
        if(captcha === ''){
            layer.open({content:'验证码不能为空',skin:'msg',time:2});
            return false;
        }
        if(!/^\d{6}$/.test(captcha)){
            layer.open({content:'验证码格式不正确',skin:'msg',time:2});
            return false;
        }
        if(password === ''){
            layer.open({content:'密码不能为空',skin:'msg',time:2});
            return false;
        }
        if(re_password === ''){
            layer.open({content:'确认密码不能为空',skin:'msg',time:2});
            return false;
        }
        if(password !== re_password){
            layer.open({content:'两次密码不一致',skin:'msg',time:2});
            return false;
        }
        return true;
    }

    // 获取验证码
    var captcha_status = 1;
    $('.captcha').click(function () {
        if(!captcha_status){
            return false;
        }
        var phone = $('input[name="phone"]').val();
        if(phone === ''){
            layer.open({content:'手机号不能为空',skin:'msg',time:2});
            return false;
        }
        if(!/^1[3,5,7,8,9]\d{9}$/.test(phone)){
            layer.open({content:'手机号格式不正确',skin:'msg',time:2});
            return false;
        }
        $.get('<?= Url::toRoute(['member/captcha'])?>',{phone:phone},function (res) {
                if(res.status === 200){
                    layer.open({content:res.msg,style: 'border:none; background-color:#78BA32; color:#fff;',skin:'msg',time:2});
                    captcha_status = 0;
                    resetCaptcha();
                }else{
                    layer.open({content:res.msg,skin:'msg',time:2});
                }
        },'json')
    })

    // 重置验证码
     function resetCaptcha() {
         var count = 60;
         var timer = setInterval(function () {
                 $('.captcha').html('重新发送('+count+'s)');
                 if(count === 0){
                     $('.captcha').html('重新发送');
                     captcha_status = 1;
                     clearInterval(timer);
                 }
                 count--;
         },1000)
     }

</script>
<?php $this->endBlock('footer');?>
