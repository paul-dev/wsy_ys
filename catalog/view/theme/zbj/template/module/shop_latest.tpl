<h3 class="conth"><i class="cont-listi cont-lista"></i><?php echo $heading_title; ?></h3>
<div class="row">
    <?php foreach ($products as $product) { ?>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" style="margin:0px;">
        <div class="product-thumb transition">
            <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
            <div class="caption">
                <h4><a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><?php echo $product['name']; ?></a></h4>
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

                <p>
                  <a href="javascript:void(0)" class="product_enshrine left" title="<?php echo $button_wishlist . '('.$product['total_wish'].')'; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>',this);"><?php echo $button_wishlist . '('.$product['total_wish'].')'; ?></a>
                  <span class="right">销量(<?php echo $product['total_sell']; ?>)</span>
                  <div style="clear:both"></div>
                </p>



          <!-- <span>
              <?php if (!empty($product['total_buyer'])) { ?>
              <span><?php echo $product['total_buyer']; ?>人已购买</span>
              <?php } ?>
              <?php if (!empty($product['total_sell'])) { ?>
              <span>累计售出<?php echo $product['total_sell']; ?>件</span>
              <?php } ?>
              <span>&nbsp;</span>
          </span> -->
            </div>
            <!-- <div class="button-group">
                <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist . '('.$product['total_wish'].')'; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
            </div> -->
        </div>
    </div>
    <?php } ?>
</div>
