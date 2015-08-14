<?php echo $header; ?>

<link rel="stylesheet" href="catalog/view/theme/zbj/stylesheet/base.css">
<link rel="stylesheet" type="text/css" href="catalog/view/theme/zbj/stylesheet/special_infinite.css">

<?php echo $header_top; ?>

<div class="setted_content">
    <div class="special_service clearfix">
        <ul>
            <li>
                <img src="catalog/view/theme/zbj/image/circular.png" alt="">
                <h2 class="tit mt">最大珠宝街电商</h2>
                <p class="mt">百万注册用户</p>
            </li>
            <li>
                <img src="catalog/view/theme/zbj/image/circular.png" alt="">
                <h2 class="tit mt">品质保证</h2>
                <p class="mt">所有商品均有国检证书保证</p>
            </li>
            <li>
                <img src="catalog/view/theme/zbj/image/circular.png" alt="">
                <h2 class="tit mt">品牌真品防伪码</h2>
                <p class="mt">支持品牌官网验真</p>
            </li>
            <li>
                <img src="catalog/view/theme/zbj/image/circular.png" alt="">
                <h2 class="tit mt">15天无忧退换货</h2>
                <p class="mt">不满意请放心退货</p>
            </li>
            <li>
                <img src="catalog/view/theme/zbj/image/circular.png" alt="">
                <h2 class="tit mt">货到付款</h2>
                <p class="mt">当面验货 满意签收</p>
            </li>
        </ul>
    </div>
    <div class="cata_title cata-layout clearfix">
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


        <p class="goods_ratio" style=""><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a></p>
        <s class="bottom-line"></s>
    </div>
    <div class="special_sell_list">
        <?php if ($products) { ?>
        <div class="products_list">
            <ul class="clearfix">
                <?php foreach ($products as $product) { ?>
                <li>
                    <div class="image">
                        <a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"></a>
                    </div>
                    <p class="goods_name"><a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><?php echo $product['name']; ?></a></p>
                    <div class="goods_price" style="height: 35px; overflow: visible;">
                        <?php if ($product['special']) { ?>
                        <span class="special_price"><?php echo $product['special']; ?></span>
                        <span class="discount"><?php echo $product['discount']; ?>折</span>
                        <span class="prime_price"><?php echo $product['price']; ?></span>
                        <?php } else { ?>
                        <span class="special_price"><?php echo $product['price']; ?></span>
                        <?php } ?>
                        <?php if ($product['special_date']) { ?>
                        <br/><span class="special_date" data-date="<?php echo $product['special_date']['day'].'-'.$product['special_date']['hour'].'-'.$product['special_date']['minute'].'-'.$product['special_date']['second']; ?>">
                        距结束：<em><?php echo $product['special_date']['day']; ?></em>天
                        <em><?php echo $product['special_date']['hour']; ?></em>小时
                        <em><?php echo $product['special_date']['minute']; ?></em>分
                        <em><?php echo $product['special_date']['second']; ?></em>秒
                        </span>
                        <?php } ?>
                    </div>
                </li>
                <?php } ?>
            </ul>
        </div>
        <?php } else { ?>
        <p><?php echo $text_empty; ?></p>
        <?php } ?>
    </div>
    <div class="pagination_box clearfix" style="margin-top: 20px;">
        <div class="pagination_left"><?php echo $pagination; ?></div>
        <div class="pagination_right"><?php echo $results; ?></div>
    </div>

    <?php echo $content_bottom; ?>

</div>
<script type="text/javascript"><!--
    $(document).ready(function(){
        var _special = setInterval(function(){
            $('.special_date').each(function(){
                var _m = false;
                var _h = false;
                var _d = false;
                var _specialDate = new Object();
                var _dateStr = $(this).data('date').split('-');
                _specialDate.day = _dateStr[0];
                _specialDate.hour = _dateStr[1];
                _specialDate.minute = _dateStr[2];
                _specialDate.second = _dateStr[3];

                _specialDate.second--;
                if (_specialDate.second < 0) {
                    _specialDate.minute--;
                    _specialDate.second = 59;
                    _m = true;
                }
                if (_specialDate.minute < 0) {
                    _specialDate.hour--;
                    _specialDate.minute = 59;
                    _h = true;
                }
                if (_specialDate.hour < 0) {
                    _specialDate.day--;
                    _specialDate.hour = 23;
                    _d = true;
                }
                if (_specialDate.day < 0) {
                    _specialDate.day = 0;
                    clearInterval(_special);
                    window.location.reload();
                }

                $(this).data('date', _specialDate.day + '-' + _specialDate.hour + '-' + _specialDate.minute + '-' + _specialDate.second);

                $(this).find('em').eq(3).hide();
                if (_m) $(this).find('em').eq(2).hide();
                if (_h) $(this).find('em').eq(1).hide();
                if (_d) $(this).find('em').eq(0).hide();

                $(this).find('em').eq(0).html((_specialDate.day < 10 ? '0' : '') + _specialDate.day);
                $(this).find('em').eq(1).html((_specialDate.hour < 10 ? '0' : '') + _specialDate.hour);
                $(this).find('em').eq(2).html((_specialDate.minute < 10 ? '0' : '') + _specialDate.minute);
                $(this).find('em').eq(3).html((_specialDate.second < 10 ? '0' : '') + _specialDate.second);

                if (_m) $(this).find('em').eq(2).slideDown();
                if (_h) $(this).find('em').eq(1).slideDown();
                if (_d) $(this).find('em').eq(0).slideDown();
                $(this).find('em').eq(3).fadeIn();
            });

        }, 1000);
    });
//--></script>
<?php echo $footer; ?>