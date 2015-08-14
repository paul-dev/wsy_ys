<?php echo $header; ?>
<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
    <?php } ?>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="row"><?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-10'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?>" style="width: 80%; padding-left: 5px;"><?php echo $content_top; ?>
            <div class="page-header" style="margin-top: 0px;">
                <div class="container-fluid">
                    <div class="pull-right">
                        <a href="<?php echo $preview_url; ?>" target="_blank" class="btn btn-primary"><i class="fa fa-eye"></i> 预览</a>
                        <button type="submit" form="form-design" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
                    </div>
                    <div class="panel-body">
                        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-design" class="form-horizontal">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab-banner" data-toggle="tab"><?php echo $tab_banner; ?></a></li>
                                <li><a href="#tab-block-1" data-toggle="tab"><?php echo $tab_block; ?>1</a></li>
                                <li><a href="#tab-block-2" data-toggle="tab"><?php echo $tab_block; ?>2</a></li>
                                <li><a href="#tab-block-3" data-toggle="tab"><?php echo $tab_block; ?>3</a></li>
                                <li><a href="#tab-block-4" data-toggle="tab"><?php echo $tab_block; ?>4</a></li>
                                <li><a href="#tab-block-5" data-toggle="tab"><?php echo $tab_block; ?>5</a></li>
                                <li><a href="#tab-block-latest" data-toggle="tab">最新商品块</a></li>
                                <li><a href="#tab-block-advance" data-toggle="tab">高级<?php echo $tab_block; ?></a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active in" id="tab-banner">
                                    <table id="images" class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <td class="text-left" style="width: 35%;"><?php echo $entry_title; ?></td>
                                            <td class="text-left" style="width: 30%;"><?php echo $entry_link; ?></td>
                                            <td class="text-left"><?php echo $entry_image; ?></td>
                                            <td class="text-left" style="width: 10%;"><?php echo $entry_sort_order; ?></td>
                                            <td></td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $image_row = 0; ?>
                                        <?php foreach ($banner_images as $banner_image) { ?>
                                        <tr id="image-row<?php echo $image_row; ?>">
                                            <td class="text-left" style="width: 35%;"><?php foreach ($languages as $language) { ?>
                                                <div class="input-group pull-left"><span class="input-group-addon"><img src="image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> </span>
                                                    <input type="text" name="banner_image[<?php echo $image_row; ?>][banner_image_description][<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($banner_image['banner_image_description'][$language['language_id']]) ? $banner_image['banner_image_description'][$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" class="form-control" />
                                                </div>
                                                <?php if (isset($error_banner_image[$image_row][$language['language_id']])) { ?>
                                                <div class="text-danger"><?php echo $error_banner_image[$image_row][$language['language_id']]; ?></div>
                                                <?php } ?>
                                                <?php } ?></td>
                                            <td class="text-left" style="width: 30%;"><input type="text" name="banner_image[<?php echo $image_row; ?>][link]" value="<?php echo $banner_image['link']; ?>" placeholder="<?php echo $entry_link; ?>" class="form-control" /></td>
                                            <td class="text-left"><a href="" id="thumb-image<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $banner_image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                                <input type="hidden" name="banner_image[<?php echo $image_row; ?>][image]" value="<?php echo $banner_image['image']; ?>" id="input-image<?php echo $image_row; ?>" /></td>
                                            <td class="text-left" style="width: 10%;"><input type="text" name="banner_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $banner_image['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
                                            <td class="text-left"><button type="button" onclick="$('#image-row<?php echo $image_row; ?>, .tooltip').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                                        </tr>
                                        <?php $image_row++; ?>
                                        <?php } ?>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td class="text-left"><button type="button" onclick="addImage();" data-toggle="tooltip" title="<?php echo $button_banner_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <?php for ($i=1; $i<=5; $i++) { ?>
                                <div class="tab-pane fade" id="tab-block-<?php echo $i; ?>">
                                    <div class="form-group required">
                                        <label class="col-sm-2 control-label" for="input-name-<?php echo $i; ?>"><?php echo $entry_name; ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="block[<?php echo $i; ?>][name]" value="<?php echo $block[$i]['name']; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name-<?php echo $i; ?>" class="form-control" />
                                            <?php if (!empty($error_name)) { ?>
                                            <div class="text-danger"><?php echo $error_name; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"><?php echo $entry_image; ?></label>
                                        <div class="col-sm-10"><a href="javascript:void(0);" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $block[$i]['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                            <input type="hidden" name="block[<?php echo $i; ?>][image]" value="<?php echo $block[$i]['image']; ?>" id="input-image" />
                                            <?php if ($block[$i]['image'] && 1 != 1) { ?>
                                            <button type="button" onclick="$(this).parent().find('img').attr('src', '<?php echo $placeholder; ?>');$(this).parent().find('input').val('');$(this).hide();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-link-<?php echo $i; ?>"><?php echo $entry_link; ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="block[<?php echo $i; ?>][link]" value="<?php echo $block[$i]['link']; ?>" placeholder="<?php echo $entry_link; ?>" id="input-link-<?php echo $i; ?>" class="form-control" />
                                            <?php if (!empty($error_link)) { ?>
                                            <div class="text-danger"><?php echo $error_link; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-sort-<?php echo $i; ?>"><?php echo $entry_sort_order; ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="block[<?php echo $i; ?>][sort]" value="<?php echo $block[$i]['sort']; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-<?php echo $i; ?>" class="form-control" style="width: 15%;" />
                                            <?php if (!empty($error_sort)) { ?>
                                            <div class="text-danger"><?php echo $error_sort; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-category-<?php echo $i; ?>"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category-<?php echo $i; ?>" class="form-control" />
                                            <div id="block-category" class="well well-sm" style="height: 150px; overflow: auto;">
                                                <?php foreach ($block[$i]['categories'] as $block_category) { ?>
                                                <div id="block-category<?php echo $block_category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $block_category['name']; ?>
                                                    <input type="hidden" name="block[<?php echo $i; ?>][category][]" value="<?php echo $block_category['category_id']; ?>" />
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-filter-<?php echo $i; ?>"><span data-toggle="tooltip" title="<?php echo $help_filter; ?>"><?php echo $entry_filter; ?></span></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="filter" value="" placeholder="<?php echo $entry_filter; ?>" id="input-filter-<?php echo $i; ?>" class="form-control" />
                                            <div id="block-filter" class="well well-sm" style="height: 150px; overflow: auto;">
                                                <?php foreach ($block[$i]['filters'] as $block_filter) { ?>
                                                <div id="block-filter<?php echo $block_filter['filter_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $block_filter['name']; ?>
                                                    <input type="hidden" name="block[1][filter][]" value="<?php echo $block_filter['filter_id']; ?>" />
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-limit-<?php echo $i; ?>"><?php echo $entry_limit; ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="block[<?php echo $i; ?>][limit]" value="<?php echo $block[$i]['limit']; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit-<?php echo $i; ?>" class="form-control" style="width: 15%;" />
                                            <?php if (!empty($error_limit)) { ?>
                                            <div class="text-danger"><?php echo $error_limit; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-status-<?php echo $i; ?>"><?php echo $entry_status; ?></label>
                                        <div class="col-sm-10">
                                            <select name="block[<?php echo $i; ?>][status]" id="input-status-<?php echo $i; ?>" class="form-control" style="width: 15%;">
                                                <?php if ($block[$i]['status']) { ?>
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

                                <div class="tab-pane fade" id="tab-block-latest">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-latest-limit"><?php echo $entry_limit; ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="config_block_latest_limit" value="<?php echo $config_latest_limit; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-latest-limit" class="form-control" style="width: 15%;" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-latest-status"><?php echo $entry_status; ?></label>
                                        <div class="col-sm-10">
                                            <select name="config_block_latest_status" id="input-latest-status" class="form-control" style="width: 15%;">
                                                <?php if ($config_latest_status) { ?>
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

                                <div class="tab-pane fade" id="tab-block-advance">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-description">内容</label>
                                        <div class="col-sm-10">
                                            <textarea name="config_design_html_block" id="input-description"><?php echo $config_design_html_block; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-status-advance"><?php echo $entry_status; ?></label>
                                        <div class="col-sm-10">
                                            <select name="config_design_html_status" id="input-status-advance" class="form-control" style="width: 15%;">
                                                <?php if ($config_design_html_status) { ?>
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
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script type="text/javascript"><!--
                var image_row = <?php echo $image_row; ?>;

                function addImage() {
                    html  = '<tr id="image-row' + image_row + '">';
                    html += '  <td class="text-left" style="width: 35%;">';
                <?php foreach ($languages as $language) { ?>
                        html += '    <div class="input-group">';
                        html += '      <span class="input-group-addon"><img src="image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span><input type="text" name="banner_image[' + image_row + '][banner_image_description][<?php echo $language['language_id']; ?>][title]" value="" placeholder="<?php echo $entry_title; ?>" class="form-control" />';
                        html += '    </div>';
                    <?php } ?>
                    html += '  </td>';
                    html += '  <td class="text-left" style="width: 30%;"><input type="text" name="banner_image[' + image_row + '][link]" value="" placeholder="<?php echo $entry_link; ?>" class="form-control" /></td>';
                    html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="banner_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
                    html += '  <td class="text-left" style="width: 10%;"><input type="text" name="banner_image[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
                    html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
                    html += '</tr>';

                    $('#images tbody').append(html);

                    image_row++;
                }

                // Category
                $('input[name=\'category\']').autocomplete({
                    'source': function(request, response) {
                        $.ajax({
                            url: 'index.php?route=seller/category/autocomplete&filter_name=' +  encodeURIComponent(request),
                            dataType: 'json',
                            success: function(json) {
                                response($.map(json, function(item) {
                                    return {
                                        label: item['name'],
                                        value: item['category_id']
                                    }
                                }));
                            }
                        });
                    },
                    'select': function(item) {
                        //$('input[name=\'category\']').val('');
                        $(this).val('');

                        $(this).parent().find('#block-category' + item['value']).remove();

                        var _id = $(this).attr('id');
                        _id = _id.split('-');
                        _id = _id.pop();
                        $(this).parent().find('#block-category').append('<div id="block-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="block['+_id+'][category][]" value="' + item['value'] + '" /></div>');
                    }
                });

                $('div[id=\'block-category\']').delegate('.fa-minus-circle', 'click', function() {
                    $(this).parent().remove();
                });

                // Filter
                $('input[name=\'filter\']').autocomplete({
                    'source': function(request, response) {
                        $.ajax({
                            url: 'index.php?route=seller/filter/autocomplete&filter_name=' +  encodeURIComponent(request),
                            dataType: 'json',
                            success: function(json) {
                                response($.map(json, function(item) {
                                    return {
                                        label: item['name'],
                                        value: item['filter_id']
                                    }
                                }));
                            }
                        });
                    },
                    'select': function(item) {
                        //$('input[name=\'filter\']').val('');
                        $(this).val('');

                        $(this).parent().find('#block-filter' + item['value']).remove();

                        var _id = $(this).attr('id');
                        _id = _id.split('-');
                        _id = _id.pop();
                        $(this).parent().find('#block-filter').append('<div id="block-filter' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="block['+_id+'][filter][]" value="' + item['value'] + '" /></div>');
                    }
                });

                $('div[id=\'block-filter\']').delegate('.fa-minus-circle', 'click', function() {
                    $(this).parent().remove();
                });

                $('#input-description').summernote({
                    height: 300
                });
                //--></script>
            <script type="text/javascript"><!--
                /*$(document).delegate('a[data-toggle=\'image\']', 'click', function(e) {
                    e.preventDefault();
                    var node = this;

                    $('#form-upload').remove();

                    $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

                    $('#form-upload input[name=\'file\']').trigger('click');

                    if (typeof timer != 'undefined') {
                        clearInterval(timer);
                    }

                    timer = setInterval(function() {
                        if ($('#form-upload input[name=\'file\']').val() != '') {
                            clearInterval(timer);

                            $.ajax({
                                url: 'index.php?route=tool/upload/image',
                                type: 'post',
                                dataType: 'json',
                                data: new FormData($('#form-upload')[0]),
                                cache: false,
                                contentType: false,
                                processData: false,
                                beforeSend: function() {
                                    $(node).parent().find('.text-danger').remove();
                                    $(node).parent().find('input').after('<div class="text-danger">Loading</div>');
                                },
                                complete: function() {
                                    $(node).parent().find('.text-danger').remove();
                                },
                                success: function(json) {
                                    $(node).parent().find('.text-danger').remove();

                                    if (json['error']) {
                                        $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
                                    }

                                    if (json['success']) {
                                        //alert(json['success']);

                                        $(node).parent().find('input').attr('value', json['code']);
                                        $(node).parent().find('img').attr('src', json['src']);
                                        $(node).parent().find('button').show();
                                    }
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                }
                            });
                        }
                    }, 500);
                });*/
                //--></script>
            <?php echo $content_bottom; ?></div>
        <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>