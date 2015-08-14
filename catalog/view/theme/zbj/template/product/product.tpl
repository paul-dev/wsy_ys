<?php echo $header; ?>
<?php if ($auction_date) { ?>
<script type="text/javascript"><!--
    var _auctionDate = <?php echo $auction_date; ?>;
    var _auction = setInterval(function(){
        var _m = false;
        var _h = false;
        var _d = false;
        _auctionDate.second--;
        if (_auctionDate.second < 0) {
            _auctionDate.minute--;
            _auctionDate.second = 59;
            _m = true;
        }
        if (_auctionDate.minute < 0) {
            _auctionDate.hour--;
            _auctionDate.minute = 59;
            _h = true;
        }
        if (_auctionDate.hour < 0) {
            _auctionDate.day--;
            _auctionDate.hour = 23;
            _d = true;
        }
        if (_auctionDate.day < 0) {
            _auctionDate.day = 0;
            clearInterval(_auction);
            $('#button-bidding').unbind('click');
            $('#button-bidding').hide();
            window.location.reload();
        }

        $('#auction_second').hide();
        if (_m) $('#auction_minute').hide();
        if (_h) $('#auction_hour').hide();
        if (_d) $('#auction_day').hide();

        $('#auction_day').html((_auctionDate.day < 10 ? '0' : '') + _auctionDate.day);
        $('#auction_hour').html((_auctionDate.hour < 10 ? '0' : '') + _auctionDate.hour);
        $('#auction_minute').html((_auctionDate.minute < 10 ? '0' : '') + _auctionDate.minute);
        $('#auction_second').html((_auctionDate.second < 10 ? '0' : '') + _auctionDate.second);

        if (_m) $('#auction_minute').slideDown();
        if (_h) $('#auction_hour').slideDown();
        if (_d) $('#auction_day').slideDown();
        $('#auction_second').fadeIn();
    }, 1000);
    //--></script>
<?php } ?>
<?php if ($special_date) { ?>
<script type="text/javascript"><!--
    var _specialDate = <?php echo $special_date; ?>;
    var _special = setInterval(function(){
        var _m = false;
        var _h = false;
        var _d = false;
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

        $('#special_second').hide();
        if (_m) $('#special_minute').hide();
        if (_h) $('#special_hour').hide();
        if (_d) $('#special_day').hide();

        $('#special_day').html((_specialDate.day < 10 ? '0' : '') + _specialDate.day);
        $('#special_hour').html((_specialDate.hour < 10 ? '0' : '') + _specialDate.hour);
        $('#special_minute').html((_specialDate.minute < 10 ? '0' : '') + _specialDate.minute);
        $('#special_second').html((_specialDate.second < 10 ? '0' : '') + _specialDate.second);

        if (_m) $('#special_minute').slideDown();
        if (_h) $('#special_hour').slideDown();
        if (_d) $('#special_day').slideDown();
        $('#special_second').fadeIn();
    }, 1000);
//--></script>
<?php } ?>
<div class="container">
  <ul class="breadcrumb" style="display: none;">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="row">

        <link rel="stylesheet" href="catalog/view/theme/zbj/stylesheet/goods_detail.css">
        <link rel="stylesheet" href="catalog/view/theme/zbj/js/jqzoom-core/jquery.jqzoom.css" type="text/css">

        <script src="catalog/view/theme/zbj/js/jqzoom-core/jquery.jqzoom-core.js" type="text/javascript"></script>
        <div class="boxall clearfix">
        <div class="goods_item clearfix">
            <div style="padding:0 0 20px 20px">
                <div class="goods_pic">
                    <div class="main_pic_box">
                        <div class="main_goods_pic zbj_pic">
                            <a href="<?php echo $popup; ?>" class="jqzoom" rel='gal1' title="<?php echo $heading_title; ?>">
                                <img class="goods_detail_main_pic" src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>">
                            </a>
                        </div>
                    </div>
                    <div class="goods_thumb_pic">
                        <ul class="clearfix">
                            <li>
                                <a class="tb_pic zbj_pic zoomThumbActive" rel="{gallery: 'gal1', smallimage: '<?php echo $thumb; ?>',largeimage: '<?php echo $popup; ?>'}">
                                    <img src="<?php echo $mini; ?>"  alt="">
                                </a>
                            </li>
                            <?php foreach ($images as $image) { ?>
                            <li>
                                <a class="tb_pic zbj_pic" rel="{gallery: 'gal1', smallimage: '<?php echo $image['thumb']; ?>',largeimage: '<?php echo $image['popup']; ?>'}">
                                    <img src="<?php echo $image['mini']; ?>"  alt="">
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="goods_social">
                    <ul class="clearfix">
                        <li><a href="javascript:void(0)" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>(<?php echo $total_wish; ?>)" onclick="wishlist.add('<?php echo $product_id; ?>');"><i class="fa fa-heart"></i>收藏商品</a></li>
                        <li><a href="javascript:void(0)" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product_id; ?>');"><i class="fa fa-exchange"></i>加入对比</a></li>
                    </ul>
                </div>
                 <!-- AddBaidu Share Button BEGIN -->
                <div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more"></a><a title="分享到QQ空间" href="#" class="bds_qzone" data-cmd="qzone"></a><a title="分享到新浪微博" href="#" class="bds_tsina" data-cmd="tsina"></a><a title="分享到腾讯微博" href="#" class="bds_tqq" data-cmd="tqq"></a><a title="分享到人人网" href="#" class="bds_renren" data-cmd="renren"></a><a title="分享到微信" href="#" class="bds_weixin" data-cmd="weixin"></a><a title="分享到Facebook" href="#" class="bds_fbook" data-cmd="fbook"></a><a title="分享到Twitter" href="#" class="bds_twi" data-cmd="twi"></a><a title="分享到linkedin" href="#" class="bds_linkedin" data-cmd="linkedin"></a></div>
                <script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"0","bdSize":"24"},"share":{},"image":{"tag":["img_share"],"viewList":["qzone","tsina","tqq","renren","weixin","fbook","twi","linkedin"],"viewText":"分享到：","viewSize":"16"},"selectShare":{"bdContainerClass":null,"bdSelectMiniList":["qzone","tsina","tqq","renren","weixin","fbook","twi","linkedin"]}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
                <!-- AddBaidu Share Button END -->
            </div>
        </div>
        <div class="goods_detail_info" id="product">
            <div style="padding:0 0 20px 20px">
                <div class="goods_title">
                    <h2><?php echo $heading_title; ?></h2>
                    <p class="goods_dec">
                        <?php echo $short_desc; ?>
                    </p>
                </div>
                <div class="promo-meta clearfix">
                    <div class="goods_price">
                        <p>
                            <?php if ($price) { ?>
                                <?php if ($special) { ?>
                            <span class="zbj_price"><?php echo $special; ?></span>
                            <span class="sup_price"><?php echo $price; ?></span>
                                <?php } else { ?>
                            <span class="zbj_price"><?php echo $price; ?></span>
                                <?php } ?>
                            <?php } ?>
                        </p>
                    </div>
                    <!-- <div class="goods_comment">
                        <div class="tb-rate-counter">
                            <a href="javascript:void(0);" onclick="$('#nav-comment li').eq(3).trigger('click');"><strong><?php echo $total_sell; ?></strong><span>交易成功</span></a>
                        </div>
                        <div class="tb-rate-counter">
                            <a href="javascript:void(0);" onclick="$('#nav-comment li').eq(2).trigger('click');"><strong><?php echo $total_reviews; ?></strong><span>累计评论</span></a>
                        </div>
                    </div> -->
                </div>
                <!-- 限时抢购 -->
                <?php if ($special_date) { ?>
                <div class="products_style">
                    <dl>
                        <dt class="label campaign"><span>限</span></dt>
                        <dd class="content">
                            <div class="remaining-time">
                                距离恢复原价仅剩
                                <span id="special_day" style="padding-right: 0px;">0</span> 天
                                <span id="special_hour" style="padding-right: 0px;">0</span> 小时
                                <span id="special_minute" style="padding-right: 0px;">0</span> 分
                                <span id="special_second" style="padding-right: 0px;">0</span> 秒
                            </div>
                        </dd>
                    </dl>
                </div>
                <?php } ?>

                <!-- 竞拍 -->
                <?php if ($auction_date) { ?>
                <div class="products_style">
                    <dl>
                        <dt class="label campaign"><span style="background-color:#f66">竞</span></dt>
                        <dd class="content" style="width: 90%;">
                            <div class="auction_price">
                                <p>每次竞价:<span><?php echo $auction_step; ?></span></p>
                                <!--<a class="auction_btn" href="#">竞价(￥600.00)</a>-->
                                <button type="button" id="button-bidding" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary btn-lg btn-block" disabled="disabled">竞价</button>
                            </div>
                            <div class="remaining-time">
                                距离竞拍结束还剩
                                <span id="auction_day">0</span>天
                                <span id="auction_hour">0</span>小时
                                <span id="auction_minute">0</span>分
                                <span id="auction_second">0</span>秒
                            </div>
                        </dd>
                    </dl>
                </div>
                <?php } ?>

                <div class="products_style">
                    <?php if ($tax) { ?>
                    <dl>
                        <dt class="label"><?php echo $text_tax; ?></dt>
                        <dd class="content"><?php echo $tax; ?></dd>
                    </dl>
                    <?php } ?>
                    <?php if ($points) { ?>
                    <dl>
                        <dt class="label"><?php echo $text_points; ?></dt>
                        <dd class="content"><?php echo $points; ?></dd>
                    </dl>
                    <?php } ?>
                    <?php if ($discounts) { ?>
                        <?php foreach ($discounts as $discount) { ?>
                        <dl>
                            <dt class="label"><?php echo $discount['quantity']; ?><?php echo $text_discount; ?></dt>
                            <dd class="content"><?php echo $discount['price']; ?></dd>
                        </dl>
                        <?php } ?>
                    <?php } ?>

                    <?php if ($manufacturer) { ?>
                    <dl>
                        <dt class="label"><?php echo $text_manufacturer; ?></dt>
                        <dd class="content"><a href="<?php echo $manufacturers; ?>" target="_blank"><?php echo $manufacturer; ?></a></dd>
                    </dl>
                    <?php } ?>
                    <dl>
                        <dt class="label"><?php echo $text_model; ?></dt>
                        <dd class="content"><?php echo $model; ?></dd>
                    </dl>
                    <?php if ($reward) { ?>
                    <dl>
                        <dt class="label"><?php echo $text_reward; ?></dt>
                        <dd class="content"><?php echo $reward; ?></dd>
                    </dl>
                    <?php } ?>
                    <dl>
                        <dt class="label"><?php echo $text_stock; ?></dt>
                        <dd class="content"><?php echo $stock; ?></dd>
                    </dl>
                </div>

                <ul class="item-data"> 
                    <li>销量 <span class="item-data-wrap"> <?php echo $total_sell; ?>件 </span> </li>
                    <li class="item-data-middle">喜欢 <span class="item-data-wrap"> <?php echo $total_wish; ?>人 </span> </li>
                    <li>好评率 <span class="item-data-wrap"> <a href="#" id="js-comment"><?php echo $good_percent; ?></a> (<?php echo $total_reviews; ?>人) </span> </li>
                </ul>

                <?php if ($options) { ?>
                    <?php foreach ($options as $option) { ?>
                        <?php if ($option['type'] == 'select') { ?>
                            <!-- 下拉列表 -->
                            <div class="products_style">
                                <dl>
                                    <dt class="label mt4"><?php echo $option['name']; ?></dt>
                                    <dd class="select content" style="width: 80%;">
                                        <div class="selected_box">
                                            <span class="selected_option" style="width: 100%; height: 34px;">--请选择--</span>
                                            <i class="select_position fa fa-caret-down"></i>
                                        </div>
                                        <div class="option_box">
                                            <ul class="select_list">
                                                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                                                <li data-option="<?php echo $option_value['product_option_value_id']; ?>">
                                                    <?php echo $option_value['name']; ?>
                                                    <?php if ($option_value['price']) { ?>
                                                    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                                    <?php } ?>
                                                </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <span class="selected_info">&nbsp;</span>
                                    </dd>
                                </dl>
                                <select name="option[<?php echo $option['product_option_id']; ?>]" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" style="display: none;">
                                    <option value=""><?php echo $text_select; ?></option>
                                    <?php foreach ($option['product_option_value'] as $option_value) { ?>
                                    <option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                                        <?php if ($option_value['price']) { ?>
                                        (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                        <?php } ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        <?php } ?>

                        <?php if ($option['type'] == 'radio') { ?>
                            <!-- 单选按钮 -->
                            <div class="products_style">
                                <dl>
                                    <dt class="label mt4"><?php echo $option['name']; ?></dt>
                                    <dd class="radio content">
                                        <ul>
                                            <?php foreach ($option['product_option_value'] as $option_value) { ?>
                                            <li data-option="<?php echo $option_value['product_option_value_id']; ?>"><a href="javascript:void(0)" title="<?php echo $option_value['name']; ?><?php if ($option_value['price']) echo '('.$option_value['price_prefix'].$option_value['price'].')'; ?>"><?php echo $option_value['name']; ?></a></li>
                                            <?php } ?>
                                        </ul>
                                        <span class="selected_info">&nbsp;</span>
                                    </dd>
                                </dl>
                                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                                <div class="radio" style="display:none;">
                                    <label>
                                        <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                                        <?php echo $option_value['name']; ?>
                                        <?php if ($option_value['price']) { ?>
                                        (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                        <?php } ?>
                                    </label>
                                </div>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <?php if ($option['type'] == 'checkbox') { ?>
                            <!-- 复选框 -->
                            <div class="products_style">
                                <dl>
                                    <dt class="label mt4"><?php echo $option['name']; ?></dt>
                                    <dd class="checkbox content">
                                        <ul>
                                            <?php foreach ($option['product_option_value'] as $option_value) { ?>
                                            <li data-option="<?php echo $option_value['product_option_value_id']; ?>"><a href="javascript:void(0);" title="<?php echo $option_value['name']; ?><?php if ($option_value['price']) echo '('.$option_value['price_prefix'].$option_value['price'].')'; ?>"><?php echo $option_value['name']; ?></a></li>
                                            <?php } ?>
                                        </ul>
                                        <span class="selected_info">&nbsp;</span>
                                    </dd>
                                </dl>
                                <div id="input-option<?php echo $option['product_option_id']; ?>" style="display: none;">
                                    <?php foreach ($option['product_option_value'] as $option_value) { ?>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                                            <?php echo $option_value['name']; ?>
                                            <?php if ($option_value['price']) { ?>
                                            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                            <?php } ?>
                                        </label>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ($option['type'] == 'image') { ?>
                            <!-- 图像 -->
                            <div class="products_style">
                                <dl>
                                    <dt class="label mt4"><?php echo $option['name']; ?></dt>
                                    <dd class="radio images content">
                                        <ul>
                                            <?php foreach ($option['product_option_value'] as $option_value) { ?>
                                            <li data-option="<?php echo $option_value['product_option_value_id']; ?>">
                                                <a href="javascript:void(0)" title="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>">
                                                    <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name']; ?>" />
                                                </a>
                                                <span class="images_info"><?php echo $option_value['name']; ?></span>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                        <span class="selected_info">&nbsp;</span>
                                    </dd>
                                </dl>
                                <div id="input-option<?php echo $option['product_option_id']; ?>" style="display: none;">
                                    <?php foreach ($option['product_option_value'] as $option_value) { ?>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                                            <?php echo $option_value['name']; ?>
                                            <?php if ($option_value['price']) { ?>
                                            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                            <?php } ?>
                                        </label>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ($option['type'] == 'text') { ?>
                            <!-- 文本 -->
                            <div class="products_style">
                                <dl>
                                    <dt class="label mt4"><?php echo $option['name']; ?></dt>
                                    <dd class="textbox content" style="width: 80%;">
                                        <div class="text_info">
                                            <input type="text" style="width: 100%; height: 34px;" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        <?php } ?>

                        <?php if ($option['type'] == 'textarea') { ?>
                            <!-- 多行文本 -->
                            <div class="products_style">
                                <dl>
                                    <dt class="label mt4"><?php echo $option['name']; ?></dt>
                                    <dd class="textareabox content" style="width: 80%;">
                                        <div class="textarea_info">
                                            <textarea style="width: 100%;" name="option[<?php echo $option['product_option_id']; ?>]" rows="5" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['value']; ?></textarea>
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        <?php } ?>

                        <?php if ($option['type'] == 'date') { ?>
                            <!-- date -->
                            <div class="products_style">
                                <dl>
                                    <dt class="label mt4"><?php echo $option['name']; ?></dt>
                                    <dd class="dateTime content" style="width: 80%;">
                                        <div class="dateTime_info input-group date">
                                            <input style="width: 100%; height: 34px;" type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                                            <span class="input-group-btn">
                                            <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                                            </span>
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        <?php } ?>

                        <?php if ($option['type'] == 'datetime') { ?>
                            <!-- datetime -->
                            <div class="products_style">
                                <dl>
                                    <dt class="label mt4"><?php echo $option['name']; ?></dt>
                                    <dd class="dateTime content" style="width: 80%;">
                                        <div class="dateTime_info input-group datetime">
                                            <input style="width: 100%; height: 34px;" type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                                                        <span class="input-group-btn">
                                                        <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                                                        </span>
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        <?php } ?>

                        <?php if ($option['type'] == 'time') { ?>
                            <!-- time -->
                            <div class="products_style">
                                <dl>
                                    <dt class="label mt4"><?php echo $option['name']; ?></dt>
                                    <dd class="dateTime content" style="width: 80%;">
                                        <div class="dateTime_info input-group time">
                                            <input style="width: 100%; height: 34px;" type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                                                        <span class="input-group-btn">
                                                        <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                                                        </span>
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        <?php } ?>

                        <?php if ($option['type'] == 'file') { ?>
                            <!-- file -->
                            <div class="products_style">
                                <dl>
                                    <dt class="label mt4"><?php echo $option['name']; ?></dt>
                                    <dd class="dateTime content" style="width: 80%;">
                                        <button type="button" id="button-upload<?php echo $option['product_option_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default btn-block"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                                        <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" id="input-option<?php echo $option['product_option_id']; ?>" />
                                    </dd>
                                </dl>
                            </div>
                        <?php } ?>

                    <?php }?>
                <?php } ?>

                <?php if ($recurrings) { ?>
                <hr>
                <h3><?php echo $text_payment_recurring ?></h3>
                <div class="form-group required">
                    <select name="recurring_id" class="form-control">
                        <option value=""><?php echo $text_select; ?></option>
                        <?php foreach ($recurrings as $recurring) { ?>
                        <option value="<?php echo $recurring['recurring_id'] ?>"><?php echo $recurring['name'] ?></option>
                        <?php } ?>
                    </select>
                    <div class="help-block" id="recurring-description"></div>
                </div>
                <?php } ?>

                <!-- quantity -->
                <div class="products_style">
                    <dl>
                        <dt class="label" style="margin-top:10px;">数量</dt>
                        <dd class="products_num content">
                            <span class="products_num_cut products_num_btn"><i class="fa fa-minus"></i></span><input type="text" name="quantity" value="<?php echo $minimum; ?>" id="input-quantity" style="IME-MODE: disabled;" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')"><span class="products_num_add products_num_btn"><i class="fa fa-plus"></i></span>件
                        </dd>
                    </dl>
                </div>
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />

                <?php if ($minimum > 1) { ?>
                <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_minimum; ?></div>
                <?php } ?>

                <?php if (!$is_preview) { ?>
                <div class="zbj-btn-box clearfix">
                    <button type="button" id="buy_now"  data-loading-text="<?php echo $text_loading; ?>" class="button-buy btn btn-primary btn-lg cart-buy">立即购买</button>
                    <button type="button" id="" data-loading-text="<?php echo $text_loading; ?>" class="button-cart btn btn-primary btn-lg btn-block cart-buy"><i class="fa fa-shopping-cart" style="font-size:17px;margin-right:7px;"></i><?php echo $button_cart; ?></button>
                    <!--
                    <div class="zbj-btn-buy"><a href="#" class="linkbuy">立即购买</a></div>
                    <div class="zbj-btn-add"><a href="#" class="linkadd"><i class="fa fa-shopping-cart"></i>加入购物车</a></div>
                    -->
                </div>
                <?php } ?>
            </div>
        </div>
    </div>



      </div>
      
      <?php if ($tags) { ?>
      <p><?php echo $text_tags; ?>
        <?php for ($i = 0; $i < count($tags); $i++) { ?>
        <?php if ($i < (count($tags) - 1)) { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
        <?php } else { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
        <?php } ?>
        <?php } ?>
      </p>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>

    <?php if ($popular_products) { ?>
    <h3><?php echo $text_popular; ?></h3>
    <div class="row">
        <?php $i = 0; ?>
        <?php foreach ($popular_products as $product) { ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-lg-6 col-md-6 col-sm-12 col-xs-12'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-lg-4 col-md-4 col-sm-6 col-xs-12 col_20'; ?>
        <?php } else { ?>
        <?php $class = 'col-lg-3 col-md-3 col-sm-6 col-xs-12'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
            <div class="product-thumb transition">
                <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
                <div class="caption">
                    <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                    <p style="display: none;"><?php echo $product['description']; ?></p>
                    <?php if ($product['rating']) { ?>
                    <div class="rating">
                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                        <?php if ($product['rating'] < $i) { ?>
                        <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                        <?php } else { ?>
                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>
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
               <!--  <div class="button-group">
                    <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"><span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span> <i class="fa fa-shopping-cart"></i></button>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>(<?php echo $product['total_wish']; ?>)" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
                </div> -->

            </div>

        </div>
        <?php if (($column_left && $column_right) && ($i % 2 == 0)) { ?>
        <div class="clearfix visible-md visible-sm"></div>
        <?php } elseif (($column_left || $column_right) && ($i % 3 == 0)) { ?>
        <div class="clearfix visible-md"></div>
        <?php } elseif ($i % 4 == 0) { ?>
        <div class="clearfix visible-md"></div>
        <?php } ?>
        <?php $i++; ?>
        <?php } ?>
    </div>
    <?php } ?>

	<div class="product_content" style="margin-top:30px;">
		<div class="product_left">
			<h3>商品分类</h3>
				<div class="cat_wrap">
					<h4>查看所有商品</h4>
					<ul class="cat_list">
						<li><a href="<?php echo $shop_url . '&sort=latest'; ?>">最新上架</a></li>
						<li><a href="<?php echo $shop_url . '&sort=hot'; ?>">热卖单品</a></li>
					</ul>
					<h4>全部商品</h4>
					<ul class="cat_all">
                        <?php foreach ($category_tree as $category) { ?>
						<li><a href="<?php echo $category['href']; ?>" title="<?php echo $category['name']; ?>"><?php echo $category['name']; ?></a></li>
                            <?php foreach ($category['children'] as $children) { ?>
                                <li><a href="<?php echo $children['href']; ?>" title="<?php echo $children['name']; ?>"><?php echo $children['name']; ?></a></li>
                            <?php } ?>
                        <?php } ?>
					</ul>
				</div>
				<h3>其他人还买了</h3>
				<div class="cat_more">
					<ul>
						<?php foreach($bestseller_products as $product) { ?>
                        <li>
							<div class="cat_more_wrap">
								<a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a>
								<span class="cat_price">
                                    <?php if($product['special']) { ?>
                                    <?php echo $product['special']; ?>
                                    <?php } else { ?>
                                    <?php echo $product['price']; ?>
                                    <?php } ?>
                                </span>
							</div>
							<div class="cat_grey"><span><i class="cat_ico"></i><?php echo $product['total_wish']; ?>人喜欢</span><span class="cat_sales">销量(<?php echo $product['total_sell']; ?>)</span></div>
						</li>
                        <?php } ?>
					</ul>
				</div>
		</div>
		<div class="product_right">
		  <ul id="nav-comment" class="nav nav-tabs">
            <li class="active"><a href="#tab-description" data-toggle="tab"><strong><?php echo $tab_description; ?></strong></a></li>
            <?php if ($attribute_groups) { ?>
            <li><a href="#tab-specification" data-toggle="tab"><strong><?php echo $tab_attribute; ?></strong></a></li>
            <?php } ?>
            <?php //if ($review_status) { ?>
            <li><a href="#tab-review" data-toggle="tab"><strong><?php echo $tab_review; ?></strong></a></li>
            <?php //} ?>
            <li><a href="#tab-transactionRecords" data-toggle="tab"><strong>成交记录(<?php echo $total_sell; ?>)</strong></a></li>

              <?php if ($finished_auctions) { ?>
              <li><a href="#tab-auctions" data-toggle="tab"><strong>竞拍</strong></a></li>
              <?php } ?>

            <li style="float:right"> <button type="button" id="" data-loading-text="<?php echo $text_loading; ?>" class="button-cart btn btn-primary btn-lg btn-block" style="padding:15px 30px;z-index:999"><?php echo $button_cart; ?></button></li>
            <li style="float:right"><span class="price" style="color:#f69;font-size: 20px;line-height: 50px;padding-right: 20px;"><?php echo $special ? $special : $price; ?></span></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-description"><?php echo $description; ?></div>
            <?php if ($attribute_groups) { ?>
            <div class="tab-pane" id="tab-specification">
              <table class="table table-bordered">
                <?php foreach ($attribute_groups as $attribute_group) { ?>
                <thead>
                  <tr>
                    <td colspan="2"><strong><?php echo $attribute_group['name']; ?></strong></td>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                  <tr>
                    <td><?php echo $attribute['name']; ?></td>
                    <td><?php echo $attribute['text']; ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
                <?php } ?>
              </table>
            </div>
            <?php } ?>
            <?php //if ($review_status) { ?>
            <div class="tab-pane" id="tab-review">
              <!--<form class="form-horizontal">-->
                <div class="goods-star" id="goods-star">           
                    <h2 class="goods-star-title">店铺评分(最近90天共<?php echo $shop_total_reviews; ?>人次评分)</h2>
                        <ul>                        
                            <li>                
                                <label>描述一致:</label>
                                <span class="item-tag-progressbar">
                                    <i class="item-tag-progress" style="width:<?php echo $shop_rating['rating_product']/5*100; ?>%;"></i>
                                </span><?php echo $shop_rating['rating_product']; ?>
                            </li>
                            <li>
                                <label>质量满意:</label>
                                <span class="item-tag-progressbar">
                                    <i class="item-tag-progress" style="width:<?php echo $shop_rating['rating_quality']/5*100; ?>%;"></i>
                                </span><?php echo $shop_rating['rating_quality']; ?>
                            </li>
                            <li>
                                <label>服务态度:</label>
                                <span class="item-tag-progressbar">
                                    <i class="item-tag-progress" style="width:<?php echo $shop_rating['rating_service']/5*100; ?>%;"></i>
                                </span><?php echo $shop_rating['rating_service']; ?>
                            </li>
                            <li>
                                <label>发货速度:</label>
                                    <span class="item-tag-progressbar">
                                        <i class="item-tag-progress" style="width:<?php echo $shop_rating['rating_deliver']/5*100; ?>%;"></i>
                                    </span><?php echo $shop_rating['rating_deliver']; ?>
                            </li>
                        </ul>
                </div>
                <div class="goods-comment-per">
                    <label>商品好评率：</label><em><?php echo $good_percent; ?></em>
                </div>
                <div class="clearfix">
                    <ul class="" id="review-sub-tabs">
                        <li class="active"><a href="#tab-review-all" data-toggle="tab">全部评论(<?php echo $total_reviews; ?>)</a></li>
                        <li><a href="#tab-review-good" data-toggle="tab">好评(<?php echo $good_reviews; ?>)</a></li>
                        <li><a href="#tab-review-normal" data-toggle="tab">中评(<?php echo $normal_reviews; ?>)</a></li>
                        <li><a href="#tab-review-bad" data-toggle="tab">差评(<?php echo $bad_reviews; ?>)</a></li>
                    </ul>
                </div>
                <div class="tab-pane" id="tab-review-all">
                    <div id="review"></div>
                </div>
                <div class="tab-pane" id="tab-review-good" style="display: none;">
                    <div id="review-good"></div>
                </div>
                <div class="tab-pane" id="tab-review-normal" style="display: none;">
                    <div id="review-normal"></div>
                </div>
                <div class="tab-pane" id="tab-review-bad" style="display: none;">
                    <div id="review-bad"></div>
                </div>

                  <!--
                <h2><?php echo $text_write; ?></h2>
                <?php if ($review_guest) { ?>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                    <input type="text" name="name" value="" id="input-name" class="form-control" />
                  </div>
                </div>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label" for="input-review"><?php echo $entry_review; ?></label>
                    <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>
                    <div class="help-block"><?php echo $text_note; ?></div>
                  </div>
                </div>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label"><?php echo $entry_rating; ?></label>
                    &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
                    <input type="radio" name="rating" value="1" />
                    &nbsp;
                    <input type="radio" name="rating" value="2" />
                    &nbsp;
                    <input type="radio" name="rating" value="3" />
                    &nbsp;
                    <input type="radio" name="rating" value="4" />
                    &nbsp;
                    <input type="radio" name="rating" value="5" />
                    &nbsp;<?php echo $entry_good; ?></div>
                </div>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label" for="input-captcha"><?php echo $entry_captcha; ?></label>
                    <input type="text" name="captcha" value="" id="input-captcha" class="form-control" />
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12"> <img src="index.php?route=tool/captcha" alt="" id="captcha" /> </div>
                </div>
                <div class="buttons clearfix">
                  <div class="pull-right">
                    <button type="button" id="button-review" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_continue; ?></button>
                  </div>
                </div>

                <?php } else { ?>
                <?php echo $text_login; ?>
                <?php } ?>

              </form>
              -->
            </div>
            <?php //} ?>

              <?php if ($finished_auctions) { ?>
              <div class="tab-pane" id="tab-auctions">
                  <table class="table table-bordered table-hover">
                      <thead>
                      <th style="">
                          起拍价
                      </th>
                      <th style="">
                          成交价
                      </th>
                      <th style="">
                          竞拍人
                      </th>
                      <th class="text-right">时间</th>
                      </thead>
                      <?php foreach ($finished_auctions as $auction) { ?>
                      <tr>
                          <td style="">
                              <?php echo $auction['base_price']; ?>
                          </td>
                          <?php if (isset($auction['bidding_customer'])) { ?>
                          <td style="">
                              <?php echo $auction['bidding_price']; ?>
                          </td>
                          <td style="">
                              <?php echo $auction['bidding_customer']; ?>
                          </td>
                          <?php } else { ?>
                          <td style="" colspan="2">
                              流拍
                          </td>
                          <?php } ?>
                          <td class="text-right"><?php echo $auction['auction_end']; ?></td>
                      </tr>
                      <?php } ?>
                  </table>
              </div>
              <?php } ?>

              <!-- 成交记录 -->
              <div class="tab-pane" id="tab-transactionRecords">

              </div>

          </div>
		  </div>
		  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'recurring_id\'], input[name="quantity"]').change(function(){
	$.ajax({
		url: 'index.php?route=product/product/getRecurringDescription',
		type: 'post',
		data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#recurring-description').html('');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			
			if (json['success']) {
				$('#recurring-description').html(json['success']);
			}
		}
	});
});
    $('#review-sub-tabs li a').on('click', function(){
        $('div[id^="tab-review-"]').hide();
        var _id = $(this).attr('href').replace('#', '');
        $('div[id="'+_id+'"]').show();
    });
//--></script> 
<script type="text/javascript"><!--
$('.button-cart').on('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('.button-cart').button('loading');
		},
		complete: function() {
			$('.button-cart').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

            $('#modal-cart').remove();

			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						var element = $('#input-option' + i.replace('_', '-'));
						
						if (element.parent().hasClass('input-group')) {
							element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						} else {
							element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						}
					}
				}
				
				if (json['error']['recurring']) {
					$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
				}
				
				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');

                var html  = '<div id="modal-cart" class="modal fade">';
                //html += '<div class="modal-backdrop  in" style="height: 100%;"></div>';
                html += '  <div class="modal-dialog">';
                html += '    <div class="modal-content">';
                html += '      <div class="modal-header">';
                html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
                html += '        <h4 class="modal-title">加入购物车失败！</h4>';
                html += '      </div>';
                html += '      <div class="modal-body">请检查各选择项！</div>';
                html += '    </div>';
                html += '  </div>';
                html += '</div>';

                $('body').append(html);
                $('#modal-cart .modal-dialog').css('margin-top', $(window).height() / 2 - $('#modal-cart .modal-dialog .modal-content').height() / 2 - 50 + 'px');

                $('#modal-cart').modal('show');
                setTimeout(function(){
                    $('#modal-cart').modal('hide');
                }, 3000);
			}
			
			if (json['success']) {
				//$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                var html  = '<div id="modal-cart" class="modal fade">';
                //html += '<div class="modal-backdrop  in" style="height: 100%;"></div>';
                html += '  <div class="modal-dialog">';
                html += '    <div class="modal-content">';
                html += '      <div class="modal-header">';
                html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
                html += '        <h4 class="modal-title">加入购物车成功！</h4>';
                html += '      </div>';
                html += '      <div class="modal-body">' + json['success'] + '</div>';
                html += '    </div>';
                html += '  </div>';
                html += '</div>';

                $('body').append(html);
                $('#modal-cart .modal-dialog').css('margin-top', $(window).height() / 2 - $('#modal-cart .modal-dialog .modal-content').height() / 2 - 50 + 'px');

                $('#modal-cart').modal('show');
                setTimeout(function(){
                    $('#modal-cart').modal('hide');
                }, 3000);

				$('#cart > button').html('<i class="fa fa-shopping-cart"></i> ' + json['total']);

                $('#header-cart-label a').attr('title', json['total']);
                var _pos = json['total'].indexOf(' ');
                var _num = json['total'].substring(0, _pos);
                $('#header-cart-label a .shopping_cart_num').html(_num);
				
				//$('html, body').animate({ scrollTop: 0 }, 'slow');
				
				$('#cart > ul').load('index.php?route=common/cart/info ul li');
			}
		}
	});
});

    $('.button-buy').on('click',  function(){
        $.ajax({
            url: 'index.php?route=checkout/cart/add',
            type: 'post',
            data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
            dataType: 'json',
            beforeSend: function() {
                $('.button-buy').button('loading');
            },
            complete: function() {
                $('.button-buy').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();
                $('.form-group').removeClass('has-error');

                $('#modal-cart').remove();

                if (json['error']) {
                    if (json['error']['option']) {
                        for (i in json['error']['option']) {
                            var element = $('#input-option' + i.replace('_', '-'));

                            if (element.parent().hasClass('input-group')) {
                                element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                            } else {
                                element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                            }
                        }
                    }

                    if (json['error']['recurring']) {
                        $('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
                    }

                    // Highlight any found errors
                    $('.text-danger').parent().addClass('has-error');

                    var html  = '<div id="modal-cart" class="modal fade">';
                    //html += '<div class="modal-backdrop  in" style="height: 100%;"></div>';
                    html += '  <div class="modal-dialog">';
                    html += '    <div class="modal-content">';
                    html += '      <div class="modal-header">';
                    html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
                    html += '        <h4 class="modal-title">加入购物车失败！</h4>';
                    html += '      </div>';
                    html += '      <div class="modal-body">请检查各选择项！</div>';
                    html += '    </div>';
                    html += '  </div>';
                    html += '</div>';

                    $('body').append(html);
                    $('#modal-cart .modal-dialog').css('margin-top', $(window).height() / 2 - $('#modal-cart .modal-dialog .modal-content').height() / 2 - 50 + 'px');

                    $('#modal-cart').modal('show');
                    setTimeout(function(){
                        $('#modal-cart').modal('hide');
                    }, 3000);
                }

                if (json['success']) {
                    window.location = 'index.php?route=checkout/checkout';
                }
            }
        });
    });

//--></script> 
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});

$('.time').datetimepicker({
	pickDate: false
});

$('button[id^=\'button-upload\']').on('click', function() {
	var node = this;
	
	$('#form-upload').remove();
	
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');
	
	$('#form-upload input[name=\'file\']').trigger('click');
	
	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}
	
	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);
			
			$.ajax({
				url: 'index.php?route=tool/upload',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).button('loading');
				},
				complete: function() {
					$(node).button('reset');
				},
				success: function(json) {
					$('.text-danger').remove();
					
					if (json['error']) {
						$(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
					}
					
					if (json['success']) {
						alert(json['success']);
						
						$(node).parent().find('input').attr('value', json['code']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
//--></script> 
<script type="text/javascript"><!--
    var navTabsWidth = $('.product_right #nav-comment').width();
    var navTabsLeft = $('.product_right #nav-comment').offset().left;
    var navTabsTop = $('.product_right #nav-comment').offset().top;
    $(window).scroll(function(){
        var WscrollTop = $(document).scrollTop();
        if(WscrollTop>=navTabsTop){
            $('.product_right #nav-comment').css({'position':'fixed','left':navTabsLeft,'top':'0px','width':navTabsWidth+'px','zIndex':888});
        }else{
            $('.product_right #nav-comment').css({'position':'static'});
        }
    });

    $('#nav-comment li').click(function(){
        $('html, body').animate({ scrollTop: navTabsTop - 10 +'px' }, 'slow');
    });

$('#review').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

    $('#review').fadeOut('slow');

    $('#review').load(this.href);

    $('#review').fadeIn('slow');
});

    $('#review-good').delegate('.pagination a', 'click', function(e) {
        e.preventDefault();

        $('#review-good').fadeOut('slow');

        $('#review-good').load(this.href);

        $('#review-good').fadeIn('slow');
    });
    $('#review-normal').delegate('.pagination a', 'click', function(e) {
        e.preventDefault();

        $('#review-normal').fadeOut('slow');

        $('#review-normal').load(this.href);

        $('#review-normal').fadeIn('slow');
    });
    $('#review-bad').delegate('.pagination a', 'click', function(e) {
        e.preventDefault();

        $('#review-bad').fadeOut('slow');

        $('#review-bad').load(this.href);

        $('#review-bad').fadeIn('slow');
    });

    $('#tab-transactionRecords').delegate('.pagination a', 'click', function(e) {
        e.preventDefault();

        $('#tab-transactionRecords').fadeOut('slow');

        $('#tab-transactionRecords').load(this.href);

        $('#tab-transactionRecords').fadeIn('slow');
    });

    $('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');
    $('#review-good').load('index.php?route=product/product/review&t=5&product_id=<?php echo $product_id; ?>');
    $('#review-normal').load('index.php?route=product/product/review&t=3&product_id=<?php echo $product_id; ?>');
    $('#review-bad').load('index.php?route=product/product/review&t=1&product_id=<?php echo $product_id; ?>');

    $('#tab-transactionRecords').load('index.php?route=product/product/transaction&product_id=<?php echo $product_id; ?>');

$('#button-review').on('click', function() {
	$.ajax({
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('#button-review').button('loading');
		},
		complete: function() {
			$('#button-review').button('reset');
			$('#captcha').attr('src', 'index.php?route=tool/captcha#'+new Date().getTime());
			$('input[name=\'captcha\']').val('');
		},
		success: function(json) {
			$('.alert-success, .alert-danger').remove();
			
			if (json['error']) {
				$('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}
			
			if (json['success']) {
				$('#review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
				
				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').prop('checked', false);
				$('input[name=\'captcha\']').val('');
			}
		}
	});
});

    // Option select
    $(function(){
        $('.products_style .select .selected_box .selected_option').click(function(){
            var _this = $(this);
            var option_box = $(this).parent().next('.option_box');
            $(this).css({'color': '#f69','background': '#ffefe4','border': '1px solid #f69'});
            if (option_box.is(':hidden')) {
                option_box.show();
            } else {
                option_box.hide();
            }
            option_box.find('ul li').click(function(){
                var selectIndex = $(this).index();
                _this.html($(this).html());
                _this.css({'color': '#666','background': '#fff','border': '1px solid #ccc'});
                option_box.hide();
            })
            return false;
        });
        $('.products_style .select .selected_box .selected_option').hover(function(){
            $(this).css({'color': '#f69','background': '#ffefe4','border': '1px solid #f69'});
        },function(){
            if ($(this).parent().next('.option_box').css('display') == 'none') {
                $(this).css({'color': '#666','background': '#fff','border': '1px solid #ccc'});
            }
        });
        $(document).click(function(e){
            $('.products_style .select .option_box').hide();
            $('.products_style .select .selected_box .selected_option').css({'color': '#666','background': '#fff','border': '1px solid #ccc'});
        });
    });

    // Option radio
    $(function(){
        $('.products_style .radio ul li').click(function(){
            $(this).addClass('selected').siblings().removeClass('selected');
            $(this).parent().next('.selected_info').html($(this).find('a').attr('title'));

            var _value = $(this).data('option');
            $(this).closest('.products_style').find('input[type="radio"]').each(function(){
                if ($(this).val() == _value) {
                    $(this).trigger('click');
                    return false;
                }
            });
        });
    });

    // Option checkbox
    $(function(){
        $('.products_style .checkbox ul li').click(function(){
            if($(this).hasClass('selected')){
                $(this).removeClass('selected');
            }else{
                $(this).addClass('selected');
            }

            var _html = '';
            var _value = [];
            $('.products_style .checkbox ul li.selected').each(function(){
                _html += $(this).find('a').attr('title') + '<br/>';
                _value.push(''+$(this).data('option'))
            });

            $(this).parent().next('.selected_info').html(_html);

            var _checked = [];
            $(this).closest('.products_style').find('input[type="checkbox"]:checked').each(function(){
                _checked.push(''+$(this).val());
            });
            $(this).closest('.products_style').find('input[type="checkbox"]:checkbox').each(function(){
                if ($.inArray(''+$(this).val(), _value) === -1 && $.inArray(''+$(this).val(), _checked) !== -1) {
                    $(this).trigger('click');
                } else if ($.inArray(''+$(this).val(), _value) !== -1 && $.inArray(''+$(this).val(), _checked) === -1) {
                    $(this).trigger('click');
                }
            });
        });
    });

    // Quantity plus/sub
    $(function(){
        //数量选择+-号
        $('.products_num_cut').click(function(){
            var quantity = $('#input-quantity').val();
            quantity = parseInt(quantity);
            if(quantity<=<?php echo $minimum; ?>){
                $(this).css({'color':'#ccc','cursor':'not-allowed'});
                return false;
            }
            var cut_quantity = quantity-1;
            $('#input-quantity').val(cut_quantity);
            if(cut_quantity==<?php echo $minimum; ?>){
                $(this).css({'color':'#ccc','cursor':'not-allowed'});
            }
            $('.products_num_add').css({'color':'#666','cursor':'pointer'});
        }).trigger('click');
        $('.products_num_add').click(function(){
            var quantity = $('#input-quantity').val();
            quantity = parseInt(quantity);
            /*if(quantity>=10){
             $(this).css({'color':'#ccc','cursor':'not-allowed'});
             return false;
             }*/
            var add_quantity = quantity+1;
            $('#input-quantity').val(add_quantity);
            /*if(add_quantity>=10){
             $(this).css({'color':'#ccc','cursor':'not-allowed'});
             }*/
            $('.products_num_cut').css({'color':'#666','cursor':'pointer'});
        });
    });

$(document).ready(function() {
	$('.thumbnails').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled:true
		}
	});

    $('.jqzoom').jqzoom({
        zoomType: 'standard',
        zoomWidth: 400,
        //zoomWindow  default width
        zoomHeight: 400,
        //lens:true,
        preloadImages: false,
        alwaysOn:false
    });

    <?php if ($auction_date) { ?>
    var _bidding = setInterval(function(){
        activeBidding();
    }, 5000);

    activeBidding();

    function activeBidding() {
        $.ajax({
            url: 'index.php?route=product/product/auction&product_id=<?php echo $product_id; ?>&auction_id=<?php echo $product_auction['product_auction_id']; ?>',
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
                $('#button-bidding').button('loading');
                $('#button-bidding').addClass('disabled');
            },
            complete: function() {
                //$('#button-bidding').button('reset');
            },
            success: function(json) {
                //$('.alert-success, .alert-danger').remove();

                if (json['error']) {
                    $('.alert-success, .alert-danger').remove();
                    $('.breadcrumb').after('<div class="alert alert-danger">' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                } else {
                    var _html = '<p>起拍价：<span><?php echo $auction_price; ?></span></p>';
                    if (json['customer_id'] && json['customer_name']) {
                        _html += "<p>最高价：<span>" + json['price_now'] + "(" + json['customer_name'] + ")</span></p>";
                    } else {
                        _html += "<p>最高价：<span>无</span></p>";
                    }
                    $('#auction_label').html(_html);
                    $('#button-bidding').html('竞价(' + json['price_step'] + ')');

                    $('#button-bidding').removeClass('disabled');
                    $('#button-bidding').removeAttr('disabled');
                    $('#button-bidding').unbind('click');
                    $('#button-bidding').bind('click', function(){
                        $.ajax({
                            url: 'index.php?route=product/product/bidding',
                            type: 'post',
                            dataType: 'json',
                            data: 'product_id=<?php echo $product_id; ?>&auction_id=<?php echo $product_auction['product_auction_id']; ?>&price=' + json['bidding_price'],
                            beforeSend: function() {
                                $('#button-bidding').button('loading');
                                $('#button-bidding').addClass('disabled');
                            },
                            complete: function() {
                                //$('#button-bidding').button('reset');
                            },
                            success: function(json) {
                                $('.alert-success, .alert-danger').remove();

                                if (json['error']) {
                                    $('.breadcrumb').after('<div class="alert alert-danger">' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                                }

                                if (json['success']) {
                                    $('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                                }

                                clearInterval(_bidding);
                                activeBidding();
                                _bidding = setInterval(function(){
                                   activeBidding();
                                }, 5000);
                            }
                        });
                    });
                }
            }
        });
    }
    <?php } ?>
});
//--></script> 
<?php echo $footer; ?>
