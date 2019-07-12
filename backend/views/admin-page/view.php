
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
                                <input type="text" name="key" class="form-control" value="<?= $model['key']?>" disabled><span style="color:red">*具有唯一性</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">标题</label>
                            <div class="col-sm-2">
                                <input type="text" name="title" class="form-control" value="<?= $model['title']?>" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">内容</label>
                            <div class="col-sm-8">
                                <div><?= htmlspecialchars_decode($model['content'])?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button  class="btn btn-default col-sm-offset-2" onclick="history.back(-1)">返回</button>
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
    </div>
</section>
