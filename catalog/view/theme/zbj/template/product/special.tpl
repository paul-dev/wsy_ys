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
      <h2><?php echo $heading_title; ?></h2>

      <div class="row" style="display: none;">
        <div class="col-sm-3">
          <div class="btn-group hidden-xs">
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

        <div class="cata_title clearfix">
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
        <div class="product-layout product-list col-xs-12" style="display: none;">
          <div class="product-thumb">
            <div class="image"><a href="<?php echo $product['href']; ?>"><img data-url="<?php echo $product['thumb']; ?>" src="catalog/view/theme/zbj/image/zbj_default_pic.png" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
            <div class="caption">
                <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                <?php if ($product['shop_name']) { ?>
                <strong><a href="<?php echo $product['shop_url']; ?>"><?php echo $product['shop_name']; ?></a></strong>
                <?php } ?>
                <p style="display: none;"><?php echo $product['description']; ?></p>
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
              <?php if ($product['price']) { ?>
              <p class="price">
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
            </div>
            <div class="button-group">
              <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
              <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
              <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
            </div>
          </div>
        </div>
        <?php } ?>
          <?php } else { ?>
          <p><?php echo $text_empty; ?></p>
          <?php } ?>
      </div>
      <div class="row" style="margin-top: 20px;">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>

      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>