<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-10'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <!--<h1><?php echo $heading_title; ?></h1>
      <!--<label class="control-label" for="input-search"><?php echo $entry_search; ?></label>-->
      <div class="row" style="display: none;">
        <div class="col-sm-4">
          <input type="text" name="search" value="<?php echo $search; ?>" placeholder="<?php echo $text_keyword; ?>" id="input-search" class="form-control" />
        </div>
        <div class="col-sm-3">
          <select name="category_id" class="form-control">
            <option value="0"><?php echo $text_category; ?></option>
            <?php foreach ($categories as $category_1) { ?>
            <?php if ($category_1['category_id'] == $category_id) { ?>
            <option value="<?php echo $category_1['category_id']; ?>" selected="selected"><?php echo $category_1['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $category_1['category_id']; ?>"><?php echo $category_1['name']; ?></option>
            <?php } ?>
            <?php foreach ($category_1['children'] as $category_2) { ?>
            <?php if ($category_2['category_id'] == $category_id) { ?>
            <option value="<?php echo $category_2['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $category_2['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
            <?php } ?>
            <?php foreach ($category_2['children'] as $category_3) { ?>
            <?php if ($category_3['category_id'] == $category_id) { ?>
            <option value="<?php echo $category_3['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $category_3['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
            <?php } ?>
            <?php } ?>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
        <div class="col-sm-4">
          <label class="checkbox-inline">
            <?php if ($sub_category) { ?>
            <input type="checkbox" name="sub_category" value="1" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="sub_category" value="1" />
            <?php } ?>
            <?php echo $text_sub_category; ?></label>
            <label class="checkbox-inline">
                <?php if ($description) { ?>
                <input type="checkbox" name="description" value="1" id="description" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="description" value="1" id="description" />
                <?php } ?>
                <?php echo $entry_description; ?></label>
            <input type="button" value="<?php echo $button_search; ?>" id="button-search" class="btn btn-primary" />
        </div>
      </div>
      <!--<h2><?php //echo $text_search; ?>&nbsp;</h2>-->
      <?php if ($products) { ?>
      <div class="row" style="margin-top: 10px; display: none;">
        <div class="col-sm-3 hidden-xs">
          <div class="btn-group">
            <button type="button" id="list-view1" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
            <button type="button" id="grid-view1" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
          </div>
        </div>
        <div class="col-sm-1 col-sm-offset-2 text-right">
          <label class="control-label" for="input-sort"><?php echo $text_sort; ?></label>
        </div>
        <div class="col-sm-3 text-right">
          <select id="input-sort" class="form-control col-sm-3" onchange="location = this.value;">
            <?php foreach ($sorts as $sort_data) { ?>
            <?php if ($sort_data['value'] == $sort . '-' . $order) { ?>
            <option value="<?php echo $sort_data['href']; ?>" selected="selected"><?php echo $sort_data['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $sort_data['href']; ?>"><?php echo $sort_data['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
        <div class="col-sm-1 text-right">
          <label class="control-label" for="input-limit"><?php echo $text_limit; ?></label>
        </div>
        <div class="col-sm-2 text-right">
          <select id="input-limit" class="form-control" onchange="location = this.value;">
            <?php foreach ($limits as $limits) { ?>
            <?php if ($limits['value'] == $limit) { ?>
            <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>

        <div class="row cata_title clearfix">
            <div class="col-xs-12">
                <!-- <label class="control-label" style="font-weight: bold;"><?php echo $text_limit; ?></label>
                <a href="javascript:void(0);" id="list-view" style="color: #f69;"><?php echo $button_list; ?></a>
                <a href="javascript:void(0);" id="grid-view" style="color: #f69;"><?php echo $button_grid; ?></a>

                <label class="control-label" style="font-weight: bold; padding-left: 20px;"><?php echo $text_sort; ?></label>
                <?php foreach ($sorts as $sort_data) { ?>
                <?php if ($sort_data['value'] == $sort . '-' . $order) { ?>
                <a href="<?php echo $sort_data['href']; ?>" style="background-color: #f69; color: #fff; font-weight: bold;"><?php echo $sort_data['text']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_data['href']; ?>" style="color: #f69;"><?php echo $sort_data['text']; ?></a>
                <?php } ?>
                <?php } ?> -->
                <span class="control-span"><?php echo $text_limit; ?></span>
                <ul class="category">
                    <li><a id="list-view"  class="first" href="javascript:void(0);"><?php echo $button_list; ?></a></li>
                    <li><a id="grid-view" class=" last" href="javascript:void(0);"><?php echo $button_grid; ?></a></li>
                </ul>

                <span class="control-span"><?php echo $text_sort; ?></span>
                <ul class="category">
                  <?php 
                  $_i=0; 
                  foreach ($sorts as $sort_data) { 
                    $_i++;
                    ?>
                    <li><a class="<?php 
                    if ($sort_data['value'] == $sort . '-' . $order) { echo ' active '; };
                     if($_i ==1){echo ' first ';};
                      if($_i==count($sorts)){echo ' last ';};
                       ?>" href="<?php echo $sort_data['href']; ?>"><?php echo $sort_data['text']; ?></a></li>
                  <?php } ?>
                </ul>

                <p style="float: right; padding-left: 10px;"><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a></p>
            </div>
        </div>

      <div class="row" id="row-cols-4">
        <?php foreach ($products as $product) { ?>
        <div class="good_store_wrap">
          <div class="product-thumb good_shop_list">
            <div class="image">
              <a href="<?php echo $product['shop_info']['shop_url']; ?>">
                <img style="display:inline-block" data-url="<?php echo $product['shop_info']['shop_image']; ?>" src="catalog/view/theme/zbj/image/zbj_default_pic.png" alt="<?php echo $product['shop_info']['shop_name']; ?>" title="<?php echo $product['shop_info']['shop_name']; ?>" class="img-responsive good_shop_pic" />
              </a>
            </div>
            <div class="caption clearfix" style="padding-bottom: 0px;">
              <a href="<?php echo $product['shop_info']['shop_url']; ?>" class="shop_avatar" target="_blank">
                  <img src="<?php echo $product['shop_info']['shop_logo']; ?>" alt="<?php echo $product['shop_info']['shop_name']; ?>" title="<?php echo $product['shop_info']['shop_name']; ?>" />
              </a>
              <span>
                <h4 style="margin:0; overflow: hidden; white-space: nowrap; margin-top: 12px;"><a href="<?php echo $product['shop_info']['shop_url']; ?>" title="<?php echo $product['shop_info']['shop_name']; ?>" alt="<?php echo $product['shop_info']['shop_name']; ?>"><strong style="color: #f69;"><?php echo $product['shop_info']['shop_name']; ?></strong></a></h4>
                <p class="online_show"><?php echo $product['shop_info']['shop_comment']; ?></p>
              </span>
              <div class="good_shopinfo clearfix">
                    <span>粉丝: <em><?php echo $product['shop_info']['total_wish']; ?></em></span>
                    <span>评分: <em><?php echo $product['shop_info']['ratings']['rating_average']; ?></em></span>
                    <span>销量: <em><?php echo $product['shop_info']['total_sell']; ?></em></span>
                    <span>商品: <em><?php echo $product['shop_info']['total_product']; ?></em></span>
              </div>
            </div>
            <div class="good_shop_btn">
              <a class="button" href="javascript:void(0);" onclick="wishlist.shop('<?php echo $product['shop_info']['store_id']; ?>');" style="margin-right:10px;"><i class="icon-add">+</i>关注</a>
              <a class="button" href="<?php echo $product['shop_info']['shop_url']; ?>">去逛逛</a>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      <div class="row" style="margin-top: 20px;">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript"><!--
$('#button-search').bind('click', function() {
	url = '<?php echo $search_url; ?>'.replace('&amp;', '&');
	
	var search = $('#content input[name=\'search\']').prop('value');
	
	if (search) {
		url += '&search=' + encodeURIComponent(search);
	}

	var category_id = $('#content select[name=\'category_id\']').prop('value');
	
	if (category_id > 0) {
		url += '&category_id=' + encodeURIComponent(category_id);
	}
	
	var sub_category = $('#content input[name=\'sub_category\']:checked').prop('value');
	
	if (sub_category) {
		url += '&sub_category=true';
	}
		
	var filter_description = $('#content input[name=\'description\']:checked').prop('value');
	
	if (filter_description) {
		url += '&description=true';
	}

	location = url;
});

$('#content input[name=\'search\']').bind('keydown', function(e) {
	if (e.keyCode == 13) {
		$('#button-search').trigger('click');
	}
});

$('select[name=\'category_id\']').on('change', function() {
	if (this.value == '0') {
		$('input[name=\'sub_category\']').prop('disabled', true);
	} else {
		$('input[name=\'sub_category\']').prop('disabled', false);
	}
});

$('select[name=\'category_id\']').trigger('change');

$('.good_shop_list').hover(function(){
  $(this).find('.good_shop_btn').css('visibility','visible');
},function(){
  $(this).find('.good_shop_btn').css('visibility','hidden');
})
--></script> 
<?php echo $footer; ?> 