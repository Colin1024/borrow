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
                            <label class="col-sm-2 control-label">产品标签</label>
                            <div class="col-sm-6">
                                <input type="text" name="label" class="form-control" value="<?= $model['label']?>" disabled="disabled">
                                <span style="color: red">注：两个及以上的标签请用英文逗号隔开</span>
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
                            <label class="col-sm-2 control-label">申请人数</label>
                            <div class="col-sm-2">
                                <input type="text" name="apply_num" class="form-control" value="<?= $model['apply_num']?>" disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">借款最小金额</label>
                            <div class="col-sm-2">
                                <input type="text" name="min_money" class="form-control" value="<?= $model['min_money']?>" disabled="disabled">
                            </div>
                            <label class="col-sm-2 control-label">借款最大金额</label>
                            <div class="col-sm-2">
                                <input type="text" name="max_money" class="form-control" value="<?= $model['max_money']?>" disabled="disabled">
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
                            <label class="col-sm-2 control-label">其他信息字段</label>
                            <div class="col-sm-2">
                                <input type="text" name="other_info" class="form-control" value="<?= $model['other_info']?>" disabled="disabled">
                            </div>
                            <div class="col-sm-4">
                                <p style="color:red">* 多个字段，请用英文逗号隔开。例如：QQ,微信</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">排序</label>
                            <div class="col-sm-2">
                                <input type="text" name="sort" class="form-control" value="<?= $model['sort']?>" disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">申请流程</label>
                            <div class="col-sm-6">
                                <?= htmlspecialchars_decode($model['apply_process'])?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">申请条件</label>
                            <div class="col-sm-6">
                                <?= htmlspecialchars_decode($model['apply_condition'])?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">新手指导</label>
                            <div class="col-sm-6">
                                <?= htmlspecialchars_decode($model['guide'])?>
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

