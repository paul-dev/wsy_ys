<?php echo $header; ?>
<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="row"><?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?>" style="width: 80%; padding-left: 5px;"><?php echo $content_top; ?>
            <div class="page-header" style="margin: auto;">
                <div class="container-fluid">
                    <div class="pull-right">
                        <button type="submit" form="form-weight" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
                    </div>
                    <div class="panel-body">
                        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-weight" class="form-horizontal">
                            <div class="row">
                                <div class="col-sm-2">
                                    <ul class="nav nav-pills nav-stacked">
                                        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                                        <?php foreach ($geo_zones as $geo_zone) { ?>
                                        <li><a href="#tab-geo-zone<?php echo $geo_zone['geo_zone_id']; ?>" data-toggle="tab"><?php echo $geo_zone['name']; ?></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <div class="col-sm-10">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab-general">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
                                                <div class="col-sm-10">
                                                    <select name="weight_tax_class_id" id="input-tax-class" class="form-control">
                                                        <option value="0"><?php echo $text_none; ?></option>
                                                        <?php foreach ($tax_classes as $tax_class) { ?>
                                                        <?php if ($tax_class['tax_class_id'] == $weight_tax_class_id) { ?>
                                                        <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                                                        <?php } else { ?>
                                                        <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                                                        <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                                                <div class="col-sm-10">
                                                    <select name="weight_status" id="input-status" class="form-control">
                                                        <?php if ($weight_status) { ?>
                                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                                        <option value="0"><?php echo $text_disabled; ?></option>
                                                        <?php } else { ?>
                                                        <option value="1"><?php echo $text_enabled; ?></option>
                                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="weight_sort_order" value="<?php echo $weight_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                        <?php foreach ($geo_zones as $geo_zone) { ?>
                                        <div class="tab-pane" id="tab-geo-zone<?php echo $geo_zone['geo_zone_id']; ?>">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="首重：多少公斤内为一个价格;<br/>续重：超过首重后，超过部分每多少公斤为一个价格;<br/>留空即为不支持此物流方式;">快递</span></label>
                                                <div class="col-sm-10">
                                                    <p style="float: left; width: 50%;"><span style="float: left;">首重：</span><input type="text" placeholder="kg" name="weight_express_<?php echo $geo_zone['geo_zone_id']; ?>_first_weight" value="<?php echo ${'weight_express_' . $geo_zone['geo_zone_id'] . '_first_weight'}; ?>" class="form-control" style="width: 85%;" /></p>
                                                    <p style="float: left; width: 50%;"><span style="float: left;">价格：</span><input type="text" name="weight_express_<?php echo $geo_zone['geo_zone_id']; ?>_first_price" value="<?php echo ${'weight_express_' . $geo_zone['geo_zone_id'] . '_first_price'}; ?>" class="form-control" style="width: 85%;" /></p>
                                                    <br/>
                                                    <p style="float: left; width: 50%;"><span style="float: left;">续重：</span><input type="text" placeholder="kg" name="weight_express_<?php echo $geo_zone['geo_zone_id']; ?>_next_weight" value="<?php echo ${'weight_express_' . $geo_zone['geo_zone_id'] . '_next_weight'}; ?>" class="form-control" style="width: 85%;" /></p>
                                                    <p style="float: left; width: 50%;"><span style="float: left;">价格：</span><input type="text" name="weight_express_<?php echo $geo_zone['geo_zone_id']; ?>_next_price" value="<?php echo ${'weight_express_' . $geo_zone['geo_zone_id'] . '_next_price'}; ?>" class="form-control" style="width: 85%;" /></p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="首重：多少公斤内为一个价格;<br/>续重：超过首重后，超过部分每多少公斤为一个价格;<br/>留空即为不支持此物流方式;">平邮</span></label>
                                                <div class="col-sm-10">
                                                    <p style="float: left; width: 50%;"><span style="float: left;">首重：</span><input type="text" placeholder="kg" name="weight_postage_<?php echo $geo_zone['geo_zone_id']; ?>_first_weight" value="<?php echo ${'weight_postage_' . $geo_zone['geo_zone_id'] . '_first_weight'}; ?>" class="form-control" style="width: 85%;" /></p>
                                                    <p style="float: left; width: 50%;"><span style="float: left;">价格：</span><input type="text" name="weight_postage_<?php echo $geo_zone['geo_zone_id']; ?>_first_price" value="<?php echo ${'weight_postage_' . $geo_zone['geo_zone_id'] . '_first_price'}; ?>" class="form-control" style="width: 85%;" /></p>
                                                    <br/>
                                                    <p style="float: left; width: 50%;"><span style="float: left;">续重：</span><input type="text" placeholder="kg" name="weight_postage_<?php echo $geo_zone['geo_zone_id']; ?>_next_weight" value="<?php echo ${'weight_postage_' . $geo_zone['geo_zone_id'] . '_next_weight'}; ?>" class="form-control" style="width: 85%;" /></p>
                                                    <p style="float: left; width: 50%;"><span style="float: left;">价格：</span><input type="text" name="weight_postage_<?php echo $geo_zone['geo_zone_id']; ?>_next_price" value="<?php echo ${'weight_postage_' . $geo_zone['geo_zone_id'] . '_next_price'}; ?>" class="form-control" style="width: 85%;" /></p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="首重：多少公斤内为一个价格;<br/>续重：超过首重后，超过部分每多少公斤为一个价格;<br/>留空即为不支持此物流方式;">EMS</span></label>
                                                <div class="col-sm-10">
                                                    <p style="float: left; width: 50%;"><span style="float: left;">首重：</span><input type="text" placeholder="kg" name="weight_ems_<?php echo $geo_zone['geo_zone_id']; ?>_first_weight" value="<?php echo ${'weight_ems_' . $geo_zone['geo_zone_id'] . '_first_weight'}; ?>" class="form-control" style="width: 85%;" /></p>
                                                    <p style="float: left; width: 50%;"><span style="float: left;">价格：</span><input type="text" name="weight_ems_<?php echo $geo_zone['geo_zone_id']; ?>_first_price" value="<?php echo ${'weight_ems_' . $geo_zone['geo_zone_id'] . '_first_price'}; ?>" class="form-control" style="width: 85%;" /></p>
                                                    <br/>
                                                    <p style="float: left; width: 50%;"><span style="float: left;">续重：</span><input type="text" placeholder="kg" name="weight_ems_<?php echo $geo_zone['geo_zone_id']; ?>_next_weight" value="<?php echo ${'weight_ems_' . $geo_zone['geo_zone_id'] . '_next_weight'}; ?>" class="form-control" style="width: 85%;" /></p>
                                                    <p style="float: left; width: 50%;"><span style="float: left;">价格：</span><input type="text" name="weight_ems_<?php echo $geo_zone['geo_zone_id']; ?>_next_price" value="<?php echo ${'weight_ems_' . $geo_zone['geo_zone_id'] . '_next_price'}; ?>" class="form-control" style="width: 85%;" /></p>
                                                </div>
                                            </div>
                                            <!--
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-rate<?php echo $geo_zone['geo_zone_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_rate; ?>"><?php echo $entry_rate; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="weight_<?php echo $geo_zone['geo_zone_id']; ?>_rate" rows="5" placeholder="<?php echo $entry_rate; ?>" id="input-rate<?php echo $geo_zone['geo_zone_id']; ?>" class="form-control"><?php echo ${'weight_' . $geo_zone['geo_zone_id'] . '_rate'}; ?></textarea>
                    </div>
                  </div>
                  -->
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-status<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $entry_status; ?></label>
                                                <div class="col-sm-10">
                                                    <select name="weight_<?php echo $geo_zone['geo_zone_id']; ?>_status" id="input-status<?php echo $geo_zone['geo_zone_id']; ?>" class="form-control">
                                                        <?php if (${'weight_' . $geo_zone['geo_zone_id'] . '_status'}) { ?>
                                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                                        <option value="0"><?php echo $text_disabled; ?></option>
                                                        <?php } else { ?>
                                                        <option value="1"><?php echo $text_enabled; ?></option>
                                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php echo $content_bottom; ?></div>
        <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>