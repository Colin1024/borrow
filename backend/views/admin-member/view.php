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
                                <input type="text" name="name" class="form-control" value="<?= $model['name']?>" disabled="disabled" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">产品描述</label>
                            <div class="col-sm-6">
                                <input type="text" name="description" class="form-control" value="<?= $model['description']?>" disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">缩略图</label>
                            <div class="col-sm-2">
                                <img src="<?= Url::base() ?><?= $model['thumb']?>" id="thumb" width="75" height="65" >
                            </div>
                            <label class="col-sm-2 control-label">背景图</label>
                            <div class="col-sm-2">
                                <img src="<?= Url::base() ?><?= $model['background']?>" width="75" height="65" id="background">
                            </div>
                        </div>
                        <div class="form-group">

                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">状态</label>
                            <div class="col-sm-2">
                                <select name="is_show" class="form-control" disabled="disabled">
                                    <option value="1" <?= $model['is_show'] == 1 ? 'selected' : ''?>>上架</option>
                                    <option value="2" <?= $model['is_show'] == 2 ? 'selected' : ''?>>下架</option>
                                </select>
                            </div>
                            <label class="col-sm-2 control-label">分类</label>
                            <div class="col-sm-2">
                                <select name="cate_id" class="form-control" disabled="disabled">
                                    <?php foreach($category as $val):?>
                                        <option value="<?=$val['id']?>" <?= $model['cate_id'] == $val['id'] ? 'selected' : ''?>><?=$val['name']?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">身份认证</label>
                            <div class="col-sm-2">
                                <select name="auth_need" class="form-control" disabled="disabled">
                                    <option value="1" <?= $model['auth_need'] == 1 ? 'selected' : ''?>>需要</option>
                                    <option value="2" <?= $model['auth_need'] == 2 ? 'selected' : ''?>>不需要</option>
                                </select>
                            </div>
                            <label class="col-sm-2 control-label">运营商认证</label>
                            <div class="col-sm-2">
                                <select name="tel_auth_need" class="form-control" disabled="disabled">
                                    <option value="2" <?= $model['tel_auth_need'] == 2 ? 'selected' : ''?>>不需要</option>
                                    <option value="1" <?= $model['tel_auth_need'] == 1 ? 'selected' : ''?>>需要</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">出借人姓名</label>
                            <div class="col-sm-2">
                                <input type="text" name="lender_name" class="form-control" value="<?= $model['lender_name']?>" disabled="disabled">
                            </div>
                            <label class="col-sm-2 control-label">身份证号码 </label>
                            <div class="col-sm-2">
                                <input type="text" name="lender_idcard" class="form-control" value="<?= $model['lender_idcard']?>" disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">排序</label>
                            <div class="col-sm-2">
                                <input type="text" name="sort" class="form-control" value="<?= $model['sort']?>" disabled="disabled">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button  class="btn btn-default col-sm-offset-2" onclick="history.back()">返回</button>

                </div>
                <!-- /.box-footer -->
            </div>
        </div>
    </div>
</section>

