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
                            <label class="col-sm-2 control-label">标识</label>
                            <div class="col-sm-2">
                                <input type="text" name="key" class="form-control" required><span style="color:red">*具有唯一性</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">标题</label>
                            <div class="col-sm-2">
                                <input type="text" name="title" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">内容</label>
                            <div class="col-sm-8">
                                <script id="editor" type="text/plain"></script>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button  class="btn btn-primary col-sm-offset-2" id="save">保存</button>
                    <a href="<?= Url::toRoute(['admin-page/index']) ?>">
                        <button  class="btn btn-default " style="margin-left: 5px">取消</button>
                    </a>
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
    </div>
</section>

<script>

    // ueditor
    var ue = UE.getEditor('editor',{
        initialFrameWidth :'100%',//设置编辑器宽度
        initialFrameHeight:400,//设置编辑器高度
        scaleEnabled:true,//设置不自动调整高度
        wordCount:false
    });


    // 提交
    $('#save').click(function () {
        var _csrf = '<?= Yii::$app->request->csrfToken?>';
        var key = $('input[name="key"]').val();
        var title = $('input[name="title"]').val();
        var content = ue.getContent();
        if(key === ''){
            layer.msg('标识不能为空',{icon:2});
            return false;
        }
        if(title === ''){
            layer.msg('标题不能为空',{icon:2});
            return false;
        }

        if(content === ''){
            layer.msg('内容不能为空',{icon:2});
            return false;
        }

        $.post('<?= Url::toRoute(['admin-page/create'])?>',{_csrf:_csrf,key:key,title:title,content:content},function (res) {
            if(res.status === 200){
                layer.msg(res.msg,{icon:1,time:1500},function () {
                    location.href = '<?= Url::toRoute(['admin-page/index'])?>'
                })
            }else{
                layer.msg(res.msg,{icon:2,time:1500})
            }
        },'json')

    })


</script>