<?php

use yii\helpers\Url;

?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">添加</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">推荐人ID</label>
                            <div class="col-sm-2">
                                <input type="text" name="pre_id" class="form-control" placeholder="选填项" value="<?= $model['pre_id']?>">
                                <input type="hidden" name="id" class="form-control"  value="<?= $model['id']?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">用户姓名</label>
                            <div class="col-sm-2">
                                <input type="text" name="name" class="form-control" value="<?= $model['name']?>">
                            </div>
                            <label class="col-sm-2 control-label">手机号</label>
                            <div class="col-sm-2">
                                <input type="text" name="tel" class="form-control" value="<?= $model['tel']?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">身份证号</label>
                            <div class="col-sm-2">
                                <input type="text" name="id_card" class="form-control" value="<?= $model['id_card']?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">银行卡号</label>
                            <div class="col-sm-2">
                                <input type="text" name="bank_card" class="form-control" value="<?= $model['bank_card']?>">
                            </div>
                            <label class="col-sm-2 control-label">绑定手机号</label>
                            <div class="col-sm-2">
                                <input type="text" name="bank_tel" class="form-control" value="<?= $model['bank_tel']?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">身份认证</label>
                            <div class="col-sm-2">
                                <select name="is_auth" class="form-control">
                                    <option value="1" <?= $model['is_auth'] == 1 ? 'selected' : '' ?> >否</option>
                                    <option value="2" <?= $model['is_auth'] == 2 ? 'selected' : '' ?> >是</option>
                                </select>
                            </div>
                            <label class="col-sm-2 control-label">运营商认证</label>
                            <div class="col-sm-2">
                                <select name="tel_auth" class="form-control">
                                    <option value="1" <?= $model['tel_auth'] == 1 ? 'selected' : '' ?>>否</option>
                                    <option value="2" <?= $model['tel_auth'] == 2 ? 'selected' : '' ?>>是</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button  class="btn btn-primary col-sm-offset-2" id="save">保存</button>
                    <a href="<?= Url::toRoute(['admin-member/index']) ?>">
                        <button  class="btn btn-default " style="margin-left: 5px">取消</button>
                    </a>
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
    </div>
</section>

<script>
    // 提交
    $('#save').click(function () {
        var _csrf = '<?= Yii::$app->request->csrfToken?>';
        var id = $('input[name="id"]').val();
        var pre_id = $('input[name="pre_id"]').val();
        var name = $('input[name="name"]').val();
        var tel = $('input[name="tel"]').val();
        var id_card = $('input[name="id_card"]').val();
        var bank_card = $('input[name="bank_card"]').val();
        var bank_tel = $('input[name="bank_tel"]').val();
        var is_auth = $('select[name="is_auth"]').val();
        var tel_auth = $('select[name="tel_auth"]').val();
        if(pre_id !== ''){
            if(!/^[1-9]\d*$/.test(pre_id)){
                layer.msg('推荐人ID不合法',{icon:2});
                return false;
            }
        }
        if(name === ''){
            layer.msg('用户姓名不能为空',{icon:2});
            return false;
        }
        if(tel === ''){
            layer.msg('手机号不能为空',{icon:2});
            return false;
        }
        if(!/^1[3,5,7,8,9]\d{9}$/.test(tel)){
            layer.msg('手机号格式不正确',{icon:2});
            return false;
        }
        if(id_card === ''){
            layer.msg('身份证号不能为空',{icon:2});
            return false;
        }
        if(!/^\d{18}|\d{17}X$/i.test(id_card)){
            layer.msg('身份证号格式不正确',{icon:2});
            return false;
        }
        if(bank_card === ''){
            layer.msg('银行卡号不能为空',{icon:2});
            return false;
        }
        if(!/^([1-9]{1})(\d{14}|\d{18})$/.test(bank_card)){
            layer.msg('银行卡号格式不正确',{icon:2});
            return false;
        }
        if(bank_tel === ''){
            layer.msg('绑定手机号不能为空',{icon:2});
            return false;
        }
        if(!/^1[3,5,7,8,9]\d{9}$/.test(bank_tel)){
            layer.msg('绑定手机号格式不正确',{icon:2});
            return false;
        }

        $.post('<?= Url::toRoute(['admin-member/update'])?>',{_csrf:_csrf,id:id,pre_id:pre_id,name:name,tel:tel,id_card:id_card,bank_card:bank_card,
        bank_tel:bank_tel,is_auth:is_auth,tel_auth:tel_auth},function (res) {
                if(res.status === 200){
                    layer.msg(res.msg,{icon:1,time:1500},function () {
                        location.href = '<?= Url::toRoute(['admin-member/index'])?>'
                    })
                }else{
                    layer.msg(res.msg,{icon:2,time:1500})
                }
        },'json')
    })

</script>