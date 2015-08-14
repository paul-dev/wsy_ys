<?php if ($block_forum) { ?>
<h3 class="conth"><i class="cont-listi cont-lista"></i>热门社区</h3>
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12" style="width: 20%; padding-left: 0px;">
        <div class="product-thumb transition" style="border: none;">
            <a href="http://www.zhubaojie.com/" target="_blank">
                <img src="/image/catalog/forum_image/forum_01.gif" style="width: 225px;">
            </a>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12" style="width: 20%; padding-left: 0px;">
        <div class="product-thumb transition" style="border: none;">
            <a href="http://www.zhubaojie.com/" target="_blank">
                <img src="/image/catalog/forum_image/forum_02.gif" style="width: 225px;">
            </a>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12" style="width: 20%; padding-left: 0px;">
        <div class="product-thumb transition" style="border: none;">
            <a href="http://www.zhubaojie.com/" target="_blank">
                <img src="/image/catalog/forum_image/forum_03.gif" style="width: 225px;">
            </a>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12" style="width: 20%; padding-left: 0px;">
        <div class="product-thumb transition" style="border: none;">
            <a href="http://www.zhubaojie.com/" target="_blank">
                <img src="/image/catalog/forum_image/forum_04.jpg" style="width: 225px;">
            </a>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12" style="width: 20%; padding-left: 0px;">
        <div class="product-thumb transition" style="border: none;">
            <a href="http://www.zhubaojie.com/" target="_blank">
                <img src="/image/catalog/forum_image/forum_05.jpg" style="width: 225px;">
            </a>
        </div>
    </div>
</div>
<?php } ?>

<?php foreach ($modules as $module) { ?>
<?php echo $module; ?>
<?php } ?>