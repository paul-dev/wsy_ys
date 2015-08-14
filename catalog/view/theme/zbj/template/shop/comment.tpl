<?php echo $header; ?>
<div class="container">
    <div class="row"><?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?>">
            <?php //echo $content_top; ?><?php //echo $content_bottom; ?>
            <link rel="stylesheet" href="catalog/view/theme/zbj/stylesheet/base.css">
            <link rel="stylesheet" href="catalog/view/theme/zbj/stylesheet/comment-detail.css">
            <div class="comment_wrapper">
                <div class="shop_info clearfix">
                    <div class="seller_info">
                        <div class="s_title">卖家信息</div>
                        <ul class="s_info">
                            <li>店铺名称：<?php echo $shop_name; ?></li>
                            <li>所在地区：<?php echo $shop_zone.$shop_city; ?></li>
                            <li>创店时间：<?php echo $shop_create_date; ?></li>
                            <li>累计销售：<?php echo $total_sell; ?></li>
                        </ul>
                        <div class="clear_f"></div>
                        <div class="shop_service">
                            <div class="ser_item">
                                <img src="catalog/view/theme/zbj/image/shop_serven.png">
                                <span>7天无理由退货</span>
                            </div>
                            <div class="ser_item">
                                <img src="catalog/view/theme/zbj/image/shop_pei.png">
                                <span>先行赔付</span>
                            </div>
                            <div class="ser_item">
                                <img src="catalog/view/theme/zbj/image/shop_fan.png">
                                <span>退货补贴运费</span>
                            </div>
                            <div class="clear_f"></div>
                        </div>
                    </div>
                    <div class="shop_detail">
                        <div class="shop_score">
                            <div class="score_list">
                                <h3 class="detail_title">店铺动态评分</h3>
                                <span class="detail_desc">(最近90天)</span>
                                <?php
                                $rating_title = array(
                                    'rating_product' => '描述一致：',
                                    'rating_quality' => '质量满意：',
                                    'rating_service' => '服务态度：',
                                    'rating_deliver' => '发货速度：',
                                );
                                foreach ($shop_rating as $key => $rating) {
                                if (!array_key_exists($key, $rating_title)) continue;
                                ?>
                                <div class="score_item<?php if ($key == 'rating_product') echo ' score_selected'; ?>" data-score-name="<?php echo $key; ?>">
                                    <span class="ml20"><?php echo $rating_title[$key]; ?></span>
                                    <span class="avg_score"><?php echo $rating; ?></span><span>分</span>
                                    <?php
                                        if ($rating > $average_rating[$key]) {
                                            echo '<span class="level_icon g_level">高于同行业</span><span>'.number_format(($rating - $average_rating[$key])/$average_rating[$key]*100, 2).'%</span>';
                                        } elseif ($rating < $average_rating[$key]) {
                                            echo '<span class="level_icon l_level">低于同行业</span><span>'.number_format(($average_rating[$key] - $rating)/$average_rating[$key]*100, 2).'%</span>';
                                        } else {
                                            echo '<span class="level_icon m_level">持平同行业</span><span>— —</span>';
                                        }
                                    ?>
                                    <div class="clear_f"></div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="score_detail">
                                <?php foreach ($shop_rating as $key => $rating) {
                                if (!array_key_exists($key, $rating_title)) continue;
                                ?>
                                <!-- <?php echo $key; ?> detail -->
                                <div class="score_detail_list"<?php if ($key == 'rating_product') echo ' style="display:block"'; ?>>
                                    <div class="star_wrapper">
                                        <span class="star_gray"><span class="star_yellow"<?php echo ' style="width:'.$rating/5*100 . '%;"'; ?>></span></span>
                                        <span class="avg_score"><?php echo $rating; ?></span>
                                        <span class="mt8 score-extra-text">分（最近90天共&nbsp;<?php echo $total_reviews; ?>&nbsp;人评分）</span>
                                        <div class="clear_f"></div>
                                    </div>
                                    <ul class="score_show_list">
                                        <?php foreach ($score_rating[$key] as $score => $val) { ?>
                                        <li>
                                            <span class="score_title"><?php echo $score; ?>分</span>
                                            <span class="score_icon"<?php echo ' style="width:'.($total_reviews ? number_format($val/$total_reviews*100, 2)*2 : ($score==5?200:0)).'px;"'; ?>></span>
                                            <span class="score_percent"><?php echo $total_reviews ? round($val/$total_reviews*100) : ($score==5?100:0); ?>%</span>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <?php } ?>
                            </div>
                            <script>
                                $(function(){
                                    $(".score_list .score_item").mouseenter(function(){
                                        var act = $(this).index('.score_item');
                                        $(this).addClass('score_selected').siblings('.score_item').removeClass('score_selected');
                                        $('.score_detail .score_detail_list').eq(act).show().siblings().hide();
                                    });
                                });
                            </script>
                        </div>
                        <div class="recent_service" style="display: none;">
                            <div class="service_list">
                                <h3 class="detail_title">店铺90天内服务情况</h3>
                                <div class="service_title">
                                    <div class="shop">本店</div>
                                    <div class="industry">行业</div>
                                    <p class="clear_f"></p>
                                </div>
                                <div class="service_item service_selected" data-service-name="_avg_send_time">
                                    <div class="s_title">平均发货时间：</div>
                                    <div class="shop">
                                        <span class="shop_num">1.59</span><span>天</span>
                                    </div>
                                    <div class="industry">
                                        <span class="industry_num">0.64</span><span>天</span>
                                    </div>
                                    <p class="clear_f"></p><p></p>
                                </div>
                                <div class="service_item" data-service-name="_refund_rate" style="float: left;">
                                    <div class="s_title">有理由退款率：</div>
                                    <div class="shop">
                                        <span class="shop_num">0.16</span><span>%</span>
                                    </div>
                                    <div class="industry">
                                        <span class="industry_num">0.48</span><span>%</span>
                                    </div>
                                    <p class="clear_f"></p><p></p>
                                </div>
                            </div>
                            <div class="service_detail">
                                <ul>
                                    <li>
                                        <h2 class="timecost">平均发货时间：<span>1.59</span>天</h2>
                                        <p>最近90天商家平均发货时间</p>
                                    </li>
                                    <li>
                                        <h2 class="timecost">有理由退款率：<span>0.16</span>%</h2>
                                        <p>最近90天商家全部支付订单中，发生退款且责任划分在商家一方的订单比例</p>
                                    </li>
                                </ul>
                            </div>
                            <script>
                                $(function(){
                                    $(".service_list .service_item").mouseenter(function(){
                                        var act = $(this).index('.service_item');
                                        $(this).addClass('service_selected').siblings('.service_item').removeClass('service_selected');
                                        $('.service_detail ul li').eq(act).show().siblings().hide();
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <div class="comment_main">
                    <h2 class="sale_comment">购买评价（<?php echo $total_all; ?>）</h2>
                    <ul class="comment_list" id="review-list">

                    </ul>
                </div>
                <div class="promise clearfix">
                    <ul>
                        <li>
                            <a class="pro_ico" href="#"><img src="catalog/view/theme/zbj/image/pc_seven_new.png" alt=""></a>
                            <h2 class="pro_tle"><a href="#">7天无理由退货</a></h2>
                            <p><a href="#">轻松购物有保障</a></p>
                        </li>
                        <li>
                            <a class="pro_ico" href="#"><img src="catalog/view/theme/zbj/image/pc_pei_new.png" alt=""></a>
                            <h2 class="pro_tle"><a href="#">确保真品, 假一赔三</a></h2>
                            <p><a href="#">轻松购物有保障</a></p>
                        </li>
                        <li>
                            <a class="pro_ico" href="#"><img src="catalog/view/theme/zbj/image/pc_fan_new.png" alt=""></a>
                            <h2 class="pro_tle"><a href="#">厂家直供, 一手货源</a></h2>
                            <p><a href="#">轻松购物有保障</a></p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <script type="text/javascript"><!--
            $('#review-list').delegate('.pagination a', 'click', function(e) {
                e.preventDefault();

                $('#review-list').fadeOut();

                $('#review-list').load(this.href);

                $('#review-list').fadeIn('slow');
            });

            $('#review-list').load('index.php?route=shop/comment/review&shop_id=<?php echo $shop_id; ?>');
        //--></script>

        <?php echo $column_right; ?>
    </div>
</div>
<?php echo $footer; ?>
