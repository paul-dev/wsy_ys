<h3 class="conth"><i class="cont-listi cont-lista"></i><?php echo $heading_title; ?>
    <?php if ($effect == '1') { ?>
    <a href="javascript:void(0);" id="slideshow-next-<?php echo $module_id; ?>" style="float: right;"><span style="font-size:14px"><em style="padding-right:5px;font-style:normal">换一批</em><i class="fa fa-undo"></i></span></a>
    <?php } ?>
</h3>

<div class="row" id="slideshow-<?php echo $module_id; ?>"<?php if ($effect == '1') echo ' style="overflow: hidden; border-right:1px solid #e5e5e5;"'; ?>>
<?php
$_i = 0;
while($_i < 12) {
?>
  <?php
  foreach ($products as $product) {
  $_i++;
  if ($_i > 12) break;
  if ($effect == '1' && $_i % 6 == 1) echo '<div class="item" style="width: 1200px;">';
  ?>
  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" style="margin:0px;padding:0px; width: 16.6666667%;">
    <div class="product-thumb transition">
      <div class="image"><a href="<?php echo $product['href']; ?>"><img data-url="<?php echo $product['thumb']; ?>" src="catalog/view/theme/zbj/image/zbj_default_pic.png" alt="<?php echo $product['full_name']; ?>" title="<?php echo $product['full_name']; ?>" class="img-responsive" /></a></div>
      <div class="caption">
        <h4><a href="<?php echo $product['href']; ?>" title="<?php echo $product['full_name']; ?>"><?php echo $product['name']; ?></a></h4>
        <p style=" display:none;"><?php echo $product['description']; ?></p>
        <?php if ($product['rating']) { ?>
       <!--  <div class="rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($product['rating'] < $i) { ?>
          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
        </div> -->
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
          <span class="store-up" data-toggle="tooltip" title="<?php echo $button_wishlist . '('.$product['total_wish'].')'; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></span>
        </p>
        <?php } ?>
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
     <!--  <div class="button-group">
        <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist . '('.$product['total_wish'].')'; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
      </div>  -->
    </div>
  </div>
  <?php if ($_i % 6 == 0) echo '</div>'; ?>
  <?php } ?>
<?php } ?>

</div>
<script>
  $(function(){
    //var slide = $('#slideshow-<?php echo $module_id; ?>').find('.product-thumb:last').css('border-right','1px solid #ccc');
  });
</script>
<?php if ($effect == '1') { ?>
<script type="text/javascript"><!--
    $('#slideshow-<?php echo $module_id; ?>').owlCarousel({
        items: 6,
        autoPlay: false,
        singleItem: true,
        navigation: false,
        //navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
        pagination: false,
        stopOnHover: false,
        scrollPerPage: true,
        transitionStyle: 'fade',
        beforeInit: function(elem) {
            elem.children().sort(function(){
                return Math.round(Math.random()) - 0.5;
            }).each(function(){
                $(this).appendTo(elem);
            });
        },
        afterInit: function(elem) {
            $('#slideshow-next-<?php echo $module_id; ?>').on('click', function(){
               elem.trigger('owl.next');
            });
        }
    });
--></script>
<?php } else { ?>
<script type="text/javascript"><!--
    $('#slideshow-<?php echo $module_id; ?>').children().sort(function(){
        return Math.round(Math.random()) - 0.5;
    }).each(function(){
        $(this).appendTo($('#slideshow-<?php echo $module_id; ?>'));
    });
--></script>
<?php } ?>
