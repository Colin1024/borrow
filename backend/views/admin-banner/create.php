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
                            <label class="col-sm-2 control-label">标题</label>
                            <div class="col-sm-4">
                                <input type="text" name="title" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">轮播图</label>
                            <div class="col-sm-4">
                                <img src="<?= Url::base() ?>/backend/web/dist/img/add_img.png" id="pic" width="75" height="65" onclick="fileSelect()">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">状态</label>
                            <div class="col-sm-4">
                                <select name="status" class="form-control">
                                    <option value="1">显示</option>
                                    <option value="2">隐藏</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">链接</label>
                            <div class="col-sm-4">
                                <input type="text" name="link" class="form-control" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">排序</label>
                            <div class="col-sm-4">
                                <input type="text" name="sort" class="form-control" value="10">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button  class="btn btn-primary col-sm-offset-2" id="save">保存</button>
                    <a href="<?= Url::toRoute(['admin-banner/index']) ?>">
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

<script>
    // 图片异步上传
    function fileSelect() {
        $('#file').click();
    }

    $("#file").change(function(){
        $.ajax({
            url: '<?=Url::toRoute('admin-banner/upload')?>',
            type: 'POST',
            cache: false,
            data: new FormData($('#uploadForm')[0]),
            dataType:'JSON',
            processData: false,
            contentType: false
        }).done(function(res) {
            if(res.status === 200){
                $('#pic').attr('src',res.path);
            }
        });
    });

    // 提交
    $('#save').click(function () {
        var _csrf = '<?= Yii::$app->request->csrfToken?>';
        var title = $('input[name="title"]').val();
        var pic = $('#pic').attr('src');
        var status = $('select[name="status"]').val();
        var sort = $('input[name="sort"]').val();
        var link = $('input[name="link"]').val();
        if(title === ''){
            layer.msg('标题不能为空',{icon:2});
            return false;
        }
        if(pic === '<?= Url::base() ?>/backend/web/dist/img/add_img.png' ){
            layer.msg('轮播图不能为空',{icon:2});
            return false;
        }
        if(sort === ''){
            layer.msg('排序不能为空',{icon:2});
            return false;
        }
        if(!/^\d+$/.test(sort)){
            layer.msg('排序不合法',{icon:2});
            return false;
        }
        $.post('<?= Url::toRoute(['admin-banner/create'])?>',{_csrf:_csrf,title:title,pic:pic,status:status,link:link,sort:sort},function (res) {
            if(res.status === 200){
                layer.msg(res.msg,{icon:1,time:1500},function () {
                    location.href = '<?= Url::toRoute(['admin-banner/index'])?>'
                })
            }else{
                layer.msg(res.msg,{icon:2,time:1500})
            }
        },'json')

    })


</script>