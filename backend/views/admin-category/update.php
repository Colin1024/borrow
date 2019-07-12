<?php
use yii\helpers\Url;
?>
<div id="create_content" style="margin-top: 10px;">
    <div class="form-group">
        <label class="col-sm-2 control-label">ID</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="update[id]" placeholder="必填" disabled value="<?= $model->id ?>">
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">分类名</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="update[name]" placeholder="必填" value="<?= $model->name ?>">
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-10 pull-right">
            <button class="btn btn-primary" id="update_btn">确定</button>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<script>
    $('#update_btn').click(function () {
        var update_name = $('input[name="update[name]"]').val();
        var update_id = $('input[name="update[id]"]').val();
        if(update_name == ''){
            layer.msg('分类名不能为空',{icon:2,time:1500});
            return false;
        }
        $.get('<?= Url::toRoute(['admin-category/update'])?>',{id:update_id,name:update_name},function(res){
            if(res.status == 200){
                layer.msg(res.msg,{icon:1,time:1500},function(){
                    //location.reload();
                    window.parent.location.reload();
                })
            }else{
                layer.msg(res.msg,{icon:2,time:1500});
            }
        },'json')
    })
</script>