<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

?>

<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->

<link rel="stylesheet" href="<?=Url::base()?>/backend/web/plugins/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css">
<script src="<?=Url::base()?>/backend/web/plugins/bootstrap-switch/dist/js/bootstrap-switch.min.js"></script>

<?php $this->endBlock(); ?>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">系统设置</h3>
                    <?php if(Yii::$app->session->hasFlash('info')):?>
                    <span style="color: green;font-weight: bolder" class="col-sm-offset-4"><?= Yii::$app->session->getFlash('info')?></span>
                    <?php endif;?>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-12">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#web" data-toggle="tab" aria-expanded="true">网站设置</a></li>
                                <li class=""><a href="#sms" data-toggle="tab" aria-expanded="false">短信设置</a></li>
                                <li class="" id="commission_li"><a href="#commission" data-toggle="tab" aria-expanded="false">分销设置</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="web">
                                    <?php ActiveForm::begin(['options' => ['class' => 'form-horizontal']])?>
                                    <h5>基本设置：</h5><hr>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">网站标题</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="title" value="<?=$data['title']['content']?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">网站描述</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" name="description"><?=$data['description']['content']?></textarea>
                                            </div>
                                        </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">网站开关</label>
                                        <div class="col-sm-8">
                                            <input name="website_status" type="checkbox" checked>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">QQ咨询号码</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="qq" value="<?=$data['qq']['content']?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">电话咨询号码</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="tel" value="<?=$data['tel']['content']?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">申请期限范围(天)</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="time_limit_scale" value="<?=$data['time_limit_scale']['content']?>" placeholder="请填入申请期限的最小天数和最大天数，中间用英文逗号隔开">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">最小提现金额(元)</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="min_withdraw_money" value="<?=$data['min_withdraw_money']['content']?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" id="website_btn" class="btn btn-danger">保存</button>
                                            <a href="<?= Url::to(['admin-system/index'])?>"><button type="button" class="btn btn-default" style="margin-left: 5px">取消</button></a>
                                        </div>
                                    </div>
                                    <?php ActiveForm::end();?>

                                    <?php ActiveForm::begin(['options' => ['class' => 'form-horizontal']])?>
                                    <h5>接口设置：</h5><hr>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">银行卡四要素secret_id</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="bank_card4_secret_id" value="<?=$data['bank_card4_secret_id']['content']?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">银行卡四要素secret_key</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="bank_card4_secret_key" value="<?=$data['bank_card4_secret_key']['content']?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">银行卡四要素开关</label>
                                        <div class="col-sm-3">
                                            <input name="bank_card4_status" type="checkbox" checked>
                                        </div>
                                        <div class="col-sm-6">
                                            <span style="color:red">购买链接：https://market.cloud.tencent.com/products/5295#</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="button" id="bank_card4_btn" class="btn btn-danger">保存</button>
                                                <a href="<?= Url::to(['admin-system/index'])?>"><button type="button" class="btn btn-default" style="margin-left: 5px">取消</button></a>
                                            </div>
                                    </div>
                                    <?php ActiveForm::end();?>
                                </div>

                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="sms">
                                    <?php ActiveForm::begin(['options' => ['class' => 'form-horizontal']])?>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">腾讯云短信 appid</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="sms_appid" value="<?=$data['sms_appid']['content']?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">腾讯云短信 appkey</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="sms_appkey" value="<?=$data['sms_appkey']['content']?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">登录验证码模板 id</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" name="sms_yzm_tpl_id" value="<?=$data['sms_yzm_tpl_id']['content']?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" id="sms_btn" class="btn btn-danger">保存</button>
                                            <a href="<?= Url::to(['admin-system/index'])?>"><button type="button" class="btn btn-default" style="margin-left: 5px">取消</button></a>
                                        </div>
                                    </div>
                                    <?php ActiveForm::end();?>
                                </div>
                                <!-- /.tab-pane -->

                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="commission">
                                    <?php ActiveForm::begin(['options' => ['class' => 'form-horizontal']])?>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">一级分佣</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" name="commission_one" value="<?=$data['commission_one']['content']?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">二级分佣</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" name="commission_two" value="<?=$data['commission_two']['content']?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">三级分佣</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" name="commission_three" value="<?=$data['commission_three']['content']?>">
                                        </div>
                                    </div>

                                    <!--<div class="form-group">
                                        <label class="col-sm-2 control-label">分销开关</label>
                                        <div class="col-sm-8">
                                            <input name="commission_status" type="checkbox" checked>
                                        </div>
                                    </div>-->

                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" id="commission_btn" class="btn btn-danger">保存</button>
                                            <a href="<?= Url::to(['admin-system/index'])?>"><button type="button" class="btn btn-default" style="margin-left: 5px">取消</button></a>
                                        </div>
                                    </div>
                                    <?php ActiveForm::end();?>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div>
                        <!-- /.nav-tabs-custom -->
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->


<?php $this->beginBlock('footer'); ?>
<!-- <body></body>后代码块 -->
<script>

            // bootstrapSwitch 控件
            $('input[name="website_status"]').bootstrapSwitch({
                onSwitchChange:function(event,state){
                   if(state){
                       window.website_status = 1;
                   }else{
                       window.website_status = 0;
                   }
                }
            });
            var website_status = <?= $data['website_status']['content']?>;
            if(website_status){
                $('input[name="website_status"]').bootstrapSwitch('state',true);
            }else{
                $('input[name="website_status"]').bootstrapSwitch('state',false);
            }

            // 网站设置
            $('#website_btn').click(function () {
                var _csrf = '<?= Yii::$app->request->getCsrfToken()?>';
                var title = $('input[name="title"]').val();
                var qq = $('input[name="qq"]').val();
                var tel = $('input[name="tel"]').val();
                var time_limit_scale = $('input[name="time_limit_scale"]').val();
                var min_withdraw_money = $('input[name="min_withdraw_money"]').val();
                var description = $('textarea[name="description"]').val();
                var website_status = window.website_status;
                if(title === ''){
                    layer.msg('标题不能为空',{icon:2,time:1500});
                    return false;
                }
                $.post('<?= Url::toRoute(['admin-system/update'])?>',{_csrf:_csrf,title:title,description:description,website_status:website_status,qq:qq,tel:tel,time_limit_scale:time_limit_scale,min_withdraw_money:min_withdraw_money},function (res) {
                        if(res.status === 200){
                            layer.msg(res.msg,{icon:1,time:1500},function () {
                                location.reload();
                            })
                        }else{
                            layer.msg(res.msg,{icon:2,time:1500});
                        }
                },'json')
            });

            // bootstrapSwitch 控件
            $('input[name="bank_card4_status"]').bootstrapSwitch({
                onSwitchChange:function(event,state){
                    if(state){
                        window.bank_card4_status = 1;
                    }else{
                        window.bank_card4_status = 0;
                    }
                }
            });
            var bank_card4_status = <?= $data['bank_card4_status']['content']?>;
            if(bank_card4_status){
                $('input[name="bank_card4_status"]').bootstrapSwitch('state',true);
            }else{
                $('input[name="bank_card4_status"]').bootstrapSwitch('state',false);
            }

            // 接口设置
            $('#bank_card4_btn').click(function () {
                var _csrf = '<?= Yii::$app->request->getCsrfToken()?>';
                var bank_card4_secret_id = $('input[name="bank_card4_secret_id"]').val();
                var bank_card4_secret_key = $('input[name="bank_card4_secret_key"]').val();
                var bank_card4_status = window.bank_card4_status;
                $.post('<?= Url::toRoute(['admin-system/update'])?>',{_csrf:_csrf,bank_card4_secret_id:bank_card4_secret_id,bank_card4_secret_key:bank_card4_secret_key,bank_card4_status:bank_card4_status},function (res) {
                    if(res.status === 200){
                        layer.msg(res.msg,{icon:1,time:1500},function () {
                            location.reload();
                        })
                    }else{
                        layer.msg(res.msg,{icon:2,time:1500});
                    }
                },'json')
            });


            // bootstrapSwitch 控件
            $('input[name="commission_status"]').bootstrapSwitch({
                onSwitchChange:function(event,state){
                    if(state){
                        window.commission_status = 1;
                    }else{
                        window.commission_status = 0;
                    }
                }
            });

            // 点击选项卡
            $('#commission_li').click(function () {
                setTimeout('commission_status()',100)
            });

            // 初始化分佣状态
            function commission_status() {
                 window.commission_status = <?= $data['commission_status']['content']?>;
                if(commission_status){
                    $('input[name="commission_status"]').bootstrapSwitch('state',true);
                }else{
                    $('input[name="commission_status"]').bootstrapSwitch('state',false);
                }
            }


            // 分佣设置
            $('#commission_btn').click(function () {
                var _csrf = '<?= Yii::$app->request->getCsrfToken()?>';
                var commission_one = parseFloat($('input[name="commission_one"]').val());
                var commission_two = parseFloat($('input[name="commission_two"]').val());
                var commission_three = parseFloat($('input[name="commission_three"]').val());
                var commission_status = window.commission_status;
                if(isNaN(commission_one) || isNaN(commission_two) || isNaN(commission_three)){
                    layer.msg('分佣系数不合法',{icon:2,time:1500});
                    return false;
                }
                $.post('<?= Url::toRoute(['admin-system/update'])?>',{_csrf:_csrf,commission_one:commission_one,commission_two:commission_two,commission_three:commission_three,commission_status:commission_status},function (res) {
                    if(res.status === 200){
                        layer.msg(res.msg,{icon:1,time:1500},function () {
                            location.href = '<?= Url::toRoute(['admin-system/index'])?>' + '#commission';
                        })
                    }else{
                        layer.msg(res.msg,{icon:2,time:1500});
                    }
                },'json')
            });

            // 短信设置
            $('#sms_btn').click(function () {
                var _csrf = '<?= Yii::$app->request->getCsrfToken()?>';
                var sms_appid = $('input[name="sms_appid"]').val();
                var sms_appkey = $('input[name="sms_appkey"]').val();
                var sms_yzm_tpl_id = $('input[name="sms_yzm_tpl_id"]').val();
                if(sms_appid === ''){
                    layer.msg('appid不能为空',{icon:2,time:1500});
                    return false;
                }
                if(sms_appkey === ''){
                    layer.msg('appkey不能为空',{icon:2,time:1500});
                    return false;
                }
                if(sms_yzm_tpl_id === ''){
                    layer.msg('登录验证码模板id不能为空',{icon:2,time:1500});
                    return false;
                }
                $.post('<?= Url::toRoute(['admin-system/update'])?>',{_csrf:_csrf,sms_appid:sms_appid,sms_appkey:sms_appkey,sms_yzm_tpl_id:sms_yzm_tpl_id},function (res) {
                    if(res.status === 200){
                        layer.msg(res.msg,{icon:1,time:1500},function () {
                            location.reload();
                        })
                    }else{
                        layer.msg(res.msg,{icon:2,time:1500});
                    }
                },'json')
            });

</script>
<?php $this->endBlock(); ?>