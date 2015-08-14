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
      <h1><?php echo $heading_title; ?></h1>
      <!--<label class="control-label" for="input-search"><?php echo $entry_search; ?></label>-->
      <div class="row">
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
      <h2><?php //echo $text_search; ?>&nbsp;</h2>

      <div class="row" style="margin-top: 10px; display: none;">
        <div class="col-sm-3 hidden-xs">
          <div class="btn-group">
            <button type="button" id="list-view1" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
            <button type="button" id="grid-view1" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
            &nbsp;&nbsp;<p style="float: right;"><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a></p>
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

        <div class="cata_title clearfix">
               <!--  <label class="control-label" style="font-weight: bold;"><?php echo $text_limit; ?></label>
                <a href="javascript:void(0);" id="list-view" style="color: #f69;"><?php echo $button_list; ?></a>
                <a href="javascript:void(0);" id="grid-view" style="color: #f69;"><?php echo $button_grid; ?></a>

                <label class="control-label" style="font-weight: bold; padding-left: 20px;"><?php echo $text_sort; ?></label>
                <?php foreach ($sorts as $sort_data) { ?>
                <?php if ($sort_data['value'] == $sort . '-' . $order) { ?>
                <a href="<?php echo $sort_data['href']; ?>" style="background-color: #f69; color: #fff; font-weight: bold;"><?php echo $sort_data['text']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_data['href']; ?>" style="color: #f69;"><?php echo $sort_data['text']; ?></a>
                <?php } ?>
                <?php } ?>

                <label class="control-label" style="font-weight: bold; padding-left: 20px;">价格：</label>
                <?php foreach ($filter_prices as $price) { ?>
                <?php if ($price['value'] == $filter_price) { ?>
                <a href="<?php echo $price['href']; ?>" style="background-color: #f69; color: #fff; font-weight: bold;"><?php echo $price['text']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $price['href']; ?>" style="color: #f69;"><?php echo $price['text']; ?></a>
                <?php } ?>
                <?php } ?> -->
                <!-- <span class="control-span"><?php echo $text_limit; ?></span>
                <ul class="category">
                    <li><a id="list-view"  class="first" href="javascript:void(0);"><?php echo $button_list; ?></a></li>
                    <li><a id="grid-view" class=" last" href="javascript:void(0);"><?php echo $button_grid; ?></a></li>
                </ul> -->

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

                <span class="control-span">价格:</span>
                <ul class="category">
                  <?php 
                  $_i=0; 
                  foreach ($filter_prices as $price) { 
                    $_i++;
                    ?>
                    <li><a class="<?php 
                    if ($price['value'] == $filter_price) { echo ' active '; };
                     if($_i ==1){echo ' first ';};
                      if($_i==count($filter_prices)){echo ' last ';};
                       ?>" href="<?php echo $price['href']; ?>"><?php echo $price['text']; ?></a></li>
                  <?php } ?>
                </ul>

                <p style="float: right; padding-left: 10px;"><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a></p>
        </div>

      <div class="row">
        <?php if ($products) { ?>
        <?php foreach ($products as $product) { ?>
        <div class="product-wrap">
          <div class="product-thumb">
            <div class="product-tag">
              <!--
                <span><img src="http://d06.res.meilishuo.net/ap/a/35/c9/89c6945418bd7c15196a496cc06b_100_100.c6.jpg" alt=""></span>
              <span><img src="http://d06.res.meilishuo.net/ap/a/35/c9/89c6945418bd7c15196a496cc06b_100_100.c6.jpg" alt=""></span>
              -->
            </div>
            <div class="image"><a href="<?php echo $product['href']; ?>"><img data-url="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" src="catalog/view/theme/zbj/image/zbj_default_pic.png" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>


            <!-- <div class="caption" style="padding-bottom: 0px;">
              <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                <?php if ($product['shop_name']) { ?>
                <strong><a href="<?php echo $product['shop_url']; ?>"><?php echo $product['shop_name']; ?></a></strong>
                <?php } ?>
                <p style="display: none;"><?php echo $product['description']; ?></p>
              <?php if ($product['price']) { ?>
              <p class="price" style="height: auto;">
                <?php if (!$product['special']) { ?>
                <?php echo $product['price']; ?>
                <?php } else { ?>
                <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
                <?php } ?>
                <?php if ($product['tax']) { ?>
                <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                <?php } ?>
              </p>
              <?php } ?>
                <?php if ($product['points']) { ?>
                <h4 style="height: auto;">可使用积分：<?php echo $product['points']; ?> points</h4>
                <?php } ?>
              <?php if ($product['rating']) { ?>
              <div class="rating">
                <?php for ($i = 1; $i <= 5; $i++) { ?>
                <?php if ($product['rating'] < $i) { ?>
                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                <?php } else { ?>
                <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                <?php } ?>
                <?php } ?>
              </div>
              <?php } ?>
            </div> -->
            <div class="caption" style="padding-bottom:0px;">
              <?php if ($product['price']) { ?>
              <p class="price" style="line-height:25px;margin:0px;">
                  <?php if (!$product['special']) { ?>
                  <?php echo $product['price']; ?>
                  <?php } else { ?>
                  <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
                  <?php } ?>
                  <?php if ($product['tax']) { ?>
                  <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                  <?php } ?>
              </p>
              <?php } ?>
              <h4 class="product_names" style="margin-bottom: 0px;"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                <?php if ($product['points']) { ?>
                <h4 style="height: auto; margin-top: -5px; margin-bottom: 0px;">可使用积分：<?php echo $product['points']; ?> points</h4>
                <?php } ?>
                <span><a href="<?php echo $product['shop_info']['shop_url']; ?>" style="color:#f69;"><?php echo $product['shop_name']; ?></a></span>
              <p style="display: none;"><?php echo $product['description']; ?></p>
              <div class="authentication-icon" style="margin-top: 0px;">
                <div class="left" style="width:150px;overflow:hidden">
                  <span title="<?php echo $product['shop_info']['shop_zone'].$product['shop_info']['shop_city']; ?>"><?php echo $product['shop_info']['shop_zone'].$product['shop_info']['shop_city']; ?></span>
                </div>
                <div class="right">
                  <span><a href="<?php echo $product['shop_info']['link_live_chat']; ?>"><i class="fa fa-comment"></i></a></span>
                </div>
              </div>             
            </div>
            <!-- <div class="button-group">
              <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
              <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
              <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
            </div> -->
          </div>
        </div>
        <?php } ?>
          <?php } else { ?>
          <div class="col-xs-12"><p><?php echo $text_empty; ?></p></div>
          <?php } ?>
      </div>
      <div class="row" style="margin-top: 20px;">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>

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
--></script> 
<?php echo $footer; ?> 