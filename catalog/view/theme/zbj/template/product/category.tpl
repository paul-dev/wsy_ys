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
    <div id="content" class="<?php echo $class; ?>" style="width: 80%; padding-left: 5px;">
        <!--
        <h2><?php echo $heading_title; ?></h2>
        <?php if ($thumb || $description) { ?>
        <div class="row">
          <?php if ($thumb) { ?>
          <div class="col-sm-2"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail" /></div>
          <?php } ?>
          <?php if ($description) { ?>
          <div class="col-sm-10"><?php echo $description; ?></div>
          <?php } ?>
        </div>
        <hr>
        <?php } ?>
        -->
      <?php if (1 <> 1 && $categories) { ?>
          <h3><?php echo $text_refine; ?></h3>
          <?php if (1==1 /*count($categories) <= 5*/) { ?>
          <div class="row">

              <?php foreach ($categories as $category) { ?>
              <div class="col-lg-3" style="width: auto;">
                  <ul>
                      <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
                  </ul>
              </div>
              <?php } ?>

          </div>
          <?php } else { ?>
          <div class="row">
            <?php foreach (array_chunk($categories, ceil(count($categories) / 4)) as $categories) { ?>
            <div class="col-sm-3">
              <ul>
                <?php foreach ($categories as $category) { ?>
                <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
                <?php } ?>
              </ul>
            </div>
            <?php } ?>
          </div>
          <?php } ?>
      <?php } ?>
        <?php echo $content_top; ?>

      <div class="row" style="display: none;">
        <div class="col-xs-12">
          <div class="btn-group hidden-xs">
            <button type="button" id="list-view1" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
            <button type="button" id="grid-view1" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
          </div>
        </div>
        <div class="col-md-2 text-right" style="display: none;">
          <label class="control-label" for="input-sort" style="font-weight: bold;"><?php echo $text_sort; ?></label>
        </div>
        <div class="col-md-3 text-right" style="display: none;">
            <select id="input-sort" class="form-control" onchange="location = this.value;">
            <?php foreach ($sorts as $sort_data) { ?>
            <?php if ($sort_data['value'] == $sort . '-' . $order) { ?>
            <option value="<?php echo $sort_data['href']; ?>" selected="selected"><?php echo $sort_data['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $sort_data['href']; ?>"><?php echo $sort_data['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
        <div class="col-md-1 text-right" style="display: none;">
          <label class="control-label" for="input-limit"><?php echo $text_limit; ?></label>
        </div>
        <div class="col-md-2 text-right" style="display: none;">
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
        </div>
      <div class="row">
        <?php if ($products) { ?>
        <?php foreach ($products as $product) { ?>
        <div class="product-wrap">
          <div class="product-thumb">
            <div class="image"><a href="<?php echo $product['href']; ?>" style="padding:0px;"><img data-url="<?php echo $product['thumb']; ?>" src="catalog/view/theme/zbj/image/zbj_default_pic.png" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="product-img" /></a></div>
            <div style="margin-top:10px;">
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
                  <h4 class="product_names"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                  <span><a href="<?php echo $product['shop_url']; ?>" style="color:#f69;"><?php echo $product['shop_name']; ?></a></span>
                  <p style="display: none;"><?php echo $product['description']; ?></p>
                  <div class="authentication-icon">
                    <div class="left" style="width:150px;overflow:hidden">
                    	<span title="<?php echo $product['shop_location']; ?>"><?php echo $product['shop_location']; ?></span>
                    </div>
                     <div class="right">
                    	<span><a href="<?php echo $product['link_live_chat']; ?>"><i class="fa fa-comment"></i></a></span>
                    </div>
                  </div>             
                </div>
            </div>
          </div>
        </div>
        <?php } ?>
          <?php } else { ?>
          <p><?php echo $text_empty; ?></p>
          <?php } ?>
      </div>
      <div class="row" style="margin-top:20px;text-align:center">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>

      <?php if (1<>1 && !$categories && !$products) { ?>
      <p><?php echo $text_empty; ?></p>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
