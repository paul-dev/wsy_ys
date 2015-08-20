<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-weight" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
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

                    <?php
                    for ($i=1;$i<=20;$i++) {
                    if (!isset(${'weight_custom'.$i.'_' . $geo_zone['geo_zone_id'] . '_name'})) continue;
                    ?>
                    <div class="form-group" data-rowid="<?php echo $i; ?>">
                        <label class="col-sm-2 control-label" style="padding-left: 0px; padding-right: 0px;"><button type="button" onclick="$(this).closest(\'.form-group\').remove();" data-toggle="tooltip" title="" class="btn btn-danger" style="float: left; margin-right: 3px;"><i class="fa fa-minus-circle"></i></button> <input type="text" placeholder="名称" name="weight_custom<?php echo $i.'_'.$geo_zone['geo_zone_id']; ?>_name" value="<?php echo ${'weight_custom'.$i.'_' . $geo_zone['geo_zone_id'] . '_name'}; ?>" class="form-control" style="width: 69%;" /></label>
                        <div class="col-sm-10">
                            <p style="float: left; width: 50%;"><span style="float: left;">首重：</span><input type="text" placeholder="kg" name="weight_custom<?php echo $i.'_'.$geo_zone['geo_zone_id']; ?>_first_weight" value="<?php echo ${'weight_custom'.$i.'_' . $geo_zone['geo_zone_id'] . '_first_weight'}; ?>" class="form-control" style="width: 85%;" /></p>
                            <p style="float: left; width: 50%;"><span style="float: left;">价格：</span><input type="text" name="weight_custom<?php echo $i.'_'.$geo_zone['geo_zone_id']; ?>_first_price" value="<?php echo ${'weight_custom'.$i.'_' . $geo_zone['geo_zone_id'] . '_first_price'}; ?>" class="form-control" style="width: 85%;" /></p>
                            <br/>
                            <p style="float: left; width: 50%;"><span style="float: left;">续重：</span><input type="text" placeholder="kg" name="weight_custom<?php echo $i.'_'.$geo_zone['geo_zone_id']; ?>_next_weight" value="<?php echo ${'weight_custom'.$i.'_' . $geo_zone['geo_zone_id'] . '_next_weight'}; ?>" class="form-control" style="width: 85%;" /></p>
                            <p style="float: left; width: 50%;"><span style="float: left;">价格：</span><input type="text" name="weight_custom<?php echo $i.'_'.$geo_zone['geo_zone_id']; ?>_next_price" value="<?php echo ${'weight_custom'.$i.'_' . $geo_zone['geo_zone_id'] . '_next_price'}; ?>" class="form-control" style="width: 85%;" /></p>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <p class="text-left" style="float: right;">
                                <button type="button" onclick="addShipping('<?php echo $geo_zone['geo_zone_id']; ?>');" data-toggle="tooltip" title="" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button>
                            </p>
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
</div>

<script language="JavaScript">
    function addShipping(gid) {
        var _row = $('#tab-geo-zone'+gid).find('.form-group').length - 4;
        if (_row > 5) {
            alert('最多只能添加5个自定义配送方式！');
            return false;
        }
        if (_row > 1) {
            _row = $('#tab-geo-zone'+gid).find('.form-group').last().prev().prev().data('rowid') + 1;
        }
        var _html = '<div class="form-group" data-rowid="'+_row+'">';
        _html += '<label class="col-sm-2 control-label" style="padding-left: 0px; padding-right: 0px;"><button type="button" onclick="$(this).closest(\'.form-group\').remove();" data-toggle="tooltip" title="" class="btn btn-danger" style="float: left; margin-right: 3px;"><i class="fa fa-minus-circle"></i></button> <input type="text" placeholder="名称" name="weight_custom'+_row+'_'+gid+'_name" value="" class="form-control" style="width: 69%;" /></label>';
        _html += '<div class="col-sm-10">';
        _html += '<p style="float: left; width: 50%;"><span style="float: left;">首重：</span><input type="text" placeholder="kg" name="weight_custom'+_row+'_'+gid+'_first_weight" value="" class="form-control" style="width: 85%;" /></p>';
        _html += '<p style="float: left; width: 50%;"><span style="float: left;">价格：</span><input type="text" name="weight_custom'+_row+'_'+gid+'_first_price" value="" class="form-control" style="width: 85%;" /></p>';
        _html += '<br/>';
        _html += '<p style="float: left; width: 50%;"><span style="float: left;">续重：</span><input type="text" placeholder="kg" name="weight_custom'+_row+'_'+gid+'_next_weight" value="" class="form-control" style="width: 85%;" /></p>';
        _html += '<p style="float: left; width: 50%;"><span style="float: left;">价格：</span><input type="text" name="weight_custom'+_row+'_'+gid+'_next_price" value="" class="form-control" style="width: 85%;" /></p>';
        _html += '</div>';
        _html += '</div>';

        $('#tab-geo-zone'+gid).find('.form-group').last().prev().before(_html);
    }
</script>
<?php echo $footer; ?> 