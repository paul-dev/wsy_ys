<h3 class="conth"><i class="cont-listi cont-lista"></i><?php echo $heading_title; ?></h3>
<div class="row_warp">
<div class="row">
<?php
$_i = 0;
while($_i < 10) {
?>
    <?php
    foreach ($banners as $category) {
    $_i++;
    if ($_i > 10) break;
    ?>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" style="width:19.8%; margin:0;padding:0">
        <div class="product-thumb transition">
            <div class="image"><a href="<?php echo $category['href']; ?>" target="_blank"><img data-url="<?php echo $category['thumb']; ?>" src="catalog/view/theme/zbj/image/zbj_default_pic.png" alt="<?php echo $category['name']; ?>" title="<?php echo $category['name']; ?>" class="img-responsive" /></a></div>
            <div class="caption" style="text-align: center;">
                <h4 style="height: auto;"><a href="<?php echo $category['href']; ?>" title="<?php echo $category['name']; ?>" target="_blank"><?php echo $category['name']; ?></a></h4>
            </div>
        </div>
    </div>
    <?php } ?>
<?php } ?>
   </div>
   <div class="storeinfo">
  	<h4><?php echo $text_shop_title; ?><span class="more"><a href="<?php echo $href_shop_more; ?>" target="_blank">更多好店></a></span></h4>
    <?php
    foreach($shops as $shop) {
    ?>
       <dl>
           <dt><a href="<?php echo $shop['shop_url']; ?>" target="_blank"><img src="<?php echo $shop['shop_image']; ?>" /></a></dt>
           <dd><a href="<?php echo $shop['shop_url']; ?>" target="_blank"><?php echo $shop['shop_name']; ?></a>
               <p><?php echo $shop['total_product']; ?>件商品/总销量<?php echo $shop['total_sell']; ?></p>
           </dd>
       </dl>
    <?php } ?>
  </div>
</div>
