 <?php if ($categories) { ?>
 <style>
  .featured_cate{
    width:240px;
    float:left;
  }
  .featured_cate .product-thumb .image img{
    width: 100px;
    height:100px;
  }
   .featured_cate .product-thumb .caption{
    margin-left:120px;
    height:120px;
   }
   .featured_cate .product-thumb .caption h3{
      padding-top:15px;
   }
   .featured_cate .product-thumb .caption a{
    color:#f69;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    font-size: 14px;
    height: 40px;
    line-height: 20px;
   }
 </style>
<h3 class="conth" style="margin-top: 0px; margin-bottom: 0px;"><i class="cont-listi cont-lista"></i><?php echo $heading_title; ?></h3>
<div id="slideshow-category-featured" class="row owl-carousel" style="opacity: 1; width:100%; padding-bottom: 15px;">
  <?php
  $_i = 0;
  foreach ($categories as $category) {
  $_i++;
  if ($_i % 4 == 1) echo '<div class="item clearfix">';
  ?>
  <div class="featured_cate">
    <div class="product-thumb transition" style="border: none; margin-bottom: 0px;">
      <div class="image" style="float: left;">
        <a href="<?php echo $category['href']; ?>">
          <img data-src="<?php echo $category['thumb']; ?>" src="catalog/view/theme/zbj/image/zbj_default_pic.png" alt="<?php echo $category['name']; ?>" title="<?php echo $category['name']; ?>" class="img-responsive"/>
        </a>
      </div>
      <div class="caption">
        <h3><a href="<?php echo $category['href']; ?>" title="<?php echo $category['name']; ?>"><?php echo $category['name']; ?></a></h3>
        <p><?php echo $category['description']; ?></p>
      </div>
    </div>
  </div>
  <?php
  if ($_i % 4 == 0 || $_i == count($categories)) echo '</div>';
  }
  ?>
</div>
<script type="text/javascript">
<!--
    $('#slideshow-category-featured').owlCarousel({
        items: 4,
        autoPlay: 115000,
        singleItem: true,
        navigation: true,
        navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
        pagination: false,
        transitionStyle: 'fade'
    });
-->
</script>
<?php } ?>
