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
                            <label class="col-sm-2 control-label">产品名</label>
                            <div class="col-sm-2">
                                <input type="text" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">产品描述</label>
                            <div class="col-sm-6">
                                <input type="text" name="description" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">产品标签</label>
                            <div class="col-sm-6">
                                <input type="text" name="label" class="form-control">
                                <span style="color: red">注：两个及以上的标签请用英文逗号隔开</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">缩略图</label>
                            <div class="col-sm-2">
                                <img src="<?= Url::base() ?>/backend/web/dist/img/add_img.png" id="thumb" width="75" height="65" onclick="fileSelect()">
                            </div>
                            <label class="col-sm-2 control-label">背景图</label>
                            <div class="col-sm-2">
                                <img src="<?= Url::base() ?>/backend/web/dist/img/add_img.png" width="75" height="65" id="background">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">状态</label>
                            <div class="col-sm-2">
                                <select name="is_show" class="form-control">
                                    <option value="1">上架</option>
                                    <option value="2">下架</option>
                                </select>
                            </div>
                            <label class="col-sm-2 control-label">分类</label>
                            <div class="col-sm-2">
                                <select name="cate_id" class="form-control">
                                    <?php foreach($category as $val):?>
                                        <option value="<?=$val['id']?>"><?=$val['name']?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">申请人数</label>
                            <div class="col-sm-2">
                                <input type="text" name="apply_num" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">借款最小金额</label>
                            <div class="col-sm-2">
                                <input type="text" name="min_money" class="form-control">
                            </div>
                            <label class="col-sm-2 control-label">借款最大金额</label>
                            <div class="col-sm-2">
                                <input type="text" name="max_money" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">身份认证</label>
                            <div class="col-sm-2">
                                <select name="auth_need" class="form-control">
                                    <option value="1">需要</option>
                                    <option value="2">不需要</option>
                                </select>
                            </div>
                            <label class="col-sm-2 control-label">运营商认证</label>
                            <div class="col-sm-2">
                                <select name="tel_auth_need" class="form-control">
                                    <option value="2">不需要</option>
                                    <option value="1">需要</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">其他信息字段</label>
                            <div class="col-sm-2">
                                <input type="text" name="other_info" class="form-control">
                            </div>
                            <div class="col-sm-4">
                                <p style="color:red">* 多个字段，请用英文逗号隔开。例如：QQ,微信</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">排序</label>
                            <div class="col-sm-2">
                                <input type="text" name="sort" class="form-control" value="10">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">申请流程</label>
                            <div class="col-sm-6">
                                <script id="apply_process" type="text/plain"></script>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">申请条件</label>
                            <div class="col-sm-6">
                                <script id="apply_condition" type="text/plain"></script>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">新手指导</label>
                            <div class="col-sm-6">
                                <script id="guide" type="text/plain"></script>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button  class="btn btn-primary col-sm-offset-2" id="save">保存</button>
                    <a href="<?= Url::toRoute(['admin-product/index']) ?>">
                        <button  class="btn btn-default " style="margin-left: 5px">取消</button>
                    </a>
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
    </div>
</section>
<!--图片上传-->
<form id="uploadForm" enctype="multipart/form-data" style="display: none">
    <input type="file" name="file" id="file">
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken?>">
</form>

<form id="uploadForm2" enctype="multipart/form-data" style="display: none">
    <input type="file" name="file_bg" id="file_bg">
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken?>">
</form>
<script>
    // ueditor
    var apply_process = UE.getEditor('apply_process',{
        initialFrameWidth :'100%',//设置编辑器宽度
      //  initialFrameHeight:400,//设置编辑器高度
        scaleEnabled:true,//设置不自动调整高度
        wordCount:false
    });
    var apply_condition = UE.getEditor('apply_condition',{
        initialFrameWidth :'100%',//设置编辑器宽度
        //  initialFrameHeight:400,//设置编辑器高度
        scaleEnabled:true,//设置不自动调整高度
        wordCount:false
    });
    var guide = UE.getEditor('guide',{
        initialFrameWidth :'100%',//设置编辑器宽度
        //  initialFrameHeight:400,//设置编辑器高度
        scaleEnabled:true,//设置不自动调整高度
        wordCount:false
    });
</script>
<script>
    // 图片异步上传
    function fileSelect() {
        $('#file').click();
    }

    $("#file").change(function(){
        //var _csrf = "<?//= Yii::$app->request->csrfToken?>//";
       // console.log(_csrf);
        $.ajax({
            url: '<?=Url::toRoute('admin-product/upload')?>',
            type: 'POST',
            cache: false,
            data: new FormData($('#uploadForm')[0]),
            dataType:'JSON',
            processData: false,
            contentType: false
        }).done(function(res) {
            if(res.status === 200){
                $('#thumb').attr('src',res.path);
            }
        });
    });

    // 背景图
    $('#background').click(function () {
        $('#file_bg').click();
    });

    $("#file_bg").change(function(){
        $.ajax({
            url: '<?=Url::toRoute('admin-product/upload')?>',
            type: 'POST',
            cache: false,
            data: new FormData($('#uploadForm2')[0]),
            dataType:'JSON',
            processData: false,
            contentType: false
        }).done(function(res) {
            if(res.status === 200){
                $('#background').attr('src',res.path);
            }
        });
    });

    // 提交
    $('#save').click(function () {
        var _csrf = '<?= Yii::$app->request->csrfToken?>';
        var name = $('input[name="name"]').val();
        var description = $('input[name="description"]').val();
        var thumb = $('#thumb').attr('src');
        var background = $('#background').attr('src');
        var is_show = $('select[name="is_show"]').val();
        var cate_id = $('select[name="cate_id"]').val();
        var auth_need = $('select[name="auth_need"]').val();
        var tel_auth_need = $('select[name="tel_auth_need"]').val();
        var other_info = $('input[name="other_info"]').val();
        var sort = $('input[name="sort"]').val();
        var label = $('input[name="label"]').val();
        var apply_num = $('input[name="apply_num"]').val();
        var min_money = $('input[name="min_money"]').val();
        var max_money = $('input[name="max_money"]').val();
        var apply_process =  UE.getEditor('apply_process').getContent();
        var apply_condition =  UE.getEditor('apply_condition').getContent();
        var guide =  UE.getEditor('guide').getContent();
        if(name === ''){
            layer.msg('产品名不能为空',{icon:2});
            return false;
        }
        if(description === ''){
            layer.msg('产品描述不能为空',{icon:2});
            return false;
        }
        if(thumb === '<?= Url::base() ?>/backend/web/dist/img/add_img.png' ){
            layer.msg('缩略图不能为空',{icon:2});
            return false;
        }
        if(background === '<?= Url::base() ?>/backend/web/dist/img/add_img.png' ){
            layer.msg('背景图不能为空',{icon:2});
            return false;
        }
        $.post('<?= Url::toRoute(['admin-product/create'])?>',{_csrf:_csrf,name:name,description:description,thumb:thumb,background:background,
            is_show:is_show,cate_id:cate_id,auth_need:auth_need,tel_auth_need:tel_auth_need,other_info:other_info,apply_process:apply_process,apply_condition:apply_condition,guide:guide,
            label:label,apply_num:apply_num,min_money:min_money,max_money:max_money,sort:sort},function (res) {
                if(res.status === 200){
                    layer.msg(res.msg,{icon:1,time:1500},function () {
                        location.href = '<?= Url::toRoute(['admin-product/index'])?>'
                    })
                }else{
                    layer.msg(res.msg,{icon:2,time:1500})
                }
        },'json')

    })


</script>