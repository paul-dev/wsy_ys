shop home
<h3 class="conth"><i class="cont-listi cont-lista"></i><?php echo $heading_title_1; ?> <span class="more"><a href="#">查看更多></a></span></h3>
<div class="row_warp">
<div class="row">

  <?php foreach ($products as $product) { ?>
  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <div class="product-thumb transition">
      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
      <div class="caption">
        <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
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
          <span>266人已购买</span>
      </div>
      <div class="button-group">
        <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
      </div>
    </div>
  </div>
  <?php } ?>
   </div>
   <div class="storeinfo">
  	<h4><?php echo $text_title_1; ?><span class="more"><a href="#">更多好店></a></span></h4>
    <?php
    foreach($shops as $shop) {
    ?>
       <dl>
           <dt><a href="<?php echo $shop['shop_url']; ?>"><img src="<?php echo $shop['shop_image']; ?>" /></a></dt>
           <dd><a href="<?php echo $shop['shop_url']; ?>"><?php echo $shop['shop_name']; ?></a>
               <p>166件商品/总销量89125</p>
           </dd>
       </dl>
    <?php } ?>
  </div>
</div>

<!-- topseller category 1 -->
<h3 class="conth"><i class="cont-listi cont-lista"></i><?php echo $heading_title_2; ?> <span class="more"><a href="#">查看更多></a></span></h3>
<div class="row_warp">
    <div class="row">

        <?php foreach ($products as $product) { ?>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="product-thumb transition">
                <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
                <div class="caption">
                    <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
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
                    <span>266人已购买</span>
                </div>
                <div class="button-group">
                    <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    <div class="storeinfo">
        <h4><?php echo $text_title_2; ?><span class="more"><a href="#">更多好店></a></span></h4>
        <dl>
            <dt><a href="#"><img src="http://local.zbj.com/catalog/view/theme/zbj/image/d1379a02a498b4db62a1535a5ef1_130_130.ch.jpg_b035bc3f_s2_180_255.jpg" /></a></dt>
            <dd><a href="#">1313气场女装</a>
                <p>166件商品/总销量89125</p>
            </dd>
        </dl>
        <dl>
            <dt><a href="#"><img src="http://local.zbj.com/catalog/view/theme/zbj/image/d1379a02a498b4db62a1535a5ef1_130_130.ch.jpg_b035bc3f_s2_180_255.jpg" /></a></dt>
            <dd><a href="#">1313气场女装</a>
                <p>166件商品/总销量89125</p>
            </dd>
        </dl>
        <dl>
            <dt><a href="#"><img src="http://local.zbj.com/catalog/view/theme/zbj/image/d1379a02a498b4db62a1535a5ef1_130_130.ch.jpg_b035bc3f_s2_180_255.jpg" /></a></dt>
            <dd><a href="#">1313气场女装</a>
                <p>166件商品/总销量89125</p>
            </dd>
        </dl>
        <dl>
            <dt><a href="#"><img src="http://local.zbj.com/catalog/view/theme/zbj/image/d1379a02a498b4db62a1535a5ef1_130_130.ch.jpg_b035bc3f_s2_180_255.jpg" /></a></dt>
            <dd><a href="#">1313气场女装</a>
                <p>166件商品/总销量89125</p>
            </dd>
        </dl>
        <dl>
            <dt><a href="#"><img src="http://local.zbj.com/catalog/view/theme/zbj/image/d1379a02a498b4db62a1535a5ef1_130_130.ch.jpg_b035bc3f_s2_180_255.jpg" /></a></dt>
            <dd><a href="#">1313气场女装</a>
                <p>166件商品/总销量89125</p>
            </dd>
        </dl>

        <dl>
            <dt><a href="#"><img src="http://local.zbj.com/catalog/view/theme/zbj/image/d1379a02a498b4db62a1535a5ef1_130_130.ch.jpg_b035bc3f_s2_180_255.jpg" /></a></dt>
            <dd><a href="#">1313气场女装</a>
                <p>166件商品/总销量89125</p>
            </dd>
        </dl>
        <dl>
            <dt><a href="#"><img src="http://local.zbj.com/catalog/view/theme/zbj/image/d1379a02a498b4db62a1535a5ef1_130_130.ch.jpg_b035bc3f_s2_180_255.jpg" /></a></dt>
            <dd><a href="#">1313气场女装</a>
                <p>166件商品/总销量89125</p>
            </dd>
        </dl>
        <dl>
            <dt><a href="#"><img src="http://local.zbj.com/catalog/view/theme/zbj/image/d1379a02a498b4db62a1535a5ef1_130_130.ch.jpg_b035bc3f_s2_180_255.jpg" /></a></dt>
            <dd><a href="#">1313气场女装</a>
                <p>166件商品/总销量89125</p>
            </dd>
        </dl>
        <dl>
            <dt><a href="#"><img src="http://local.zbj.com/catalog/view/theme/zbj/image/d1379a02a498b4db62a1535a5ef1_130_130.ch.jpg_b035bc3f_s2_180_255.jpg" /></a></dt>
            <dd><a href="#">1313气场女装</a>
                <p>166件商品/总销量89125</p>
            </dd>
        </dl>
        <dl>
            <dt><a href="#"><img src="http://local.zbj.com/catalog/view/theme/zbj/image/d1379a02a498b4db62a1535a5ef1_130_130.ch.jpg_b035bc3f_s2_180_255.jpg" /></a></dt>
            <dd><a href="#">1313气场女装</a>
                <p>166件商品/总销量89125</p>
            </dd>
        </dl>
        <dl>
            <dt><a href="#"><img src="http://local.zbj.com/catalog/view/theme/zbj/image/d1379a02a498b4db62a1535a5ef1_130_130.ch.jpg_b035bc3f_s2_180_255.jpg" /></a></dt>
            <dd><a href="#">1313气场女装</a>
                <p>166件商品/总销量89125</p>
            </dd>
        </dl>
    </div>
</div>
