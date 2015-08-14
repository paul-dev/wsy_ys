<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-10'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>" style="width: 80%; padding-left: 5px;"><?php echo $content_top; ?>
      <h2 style="display: none;"><?php echo $text_shop_info; ?></h2>
      <div class="seller">
      <a class="shop_avatar" href="<?php echo $shop_url; ?>">
      	<img src="<?php echo $shop_logo; ?>" />
      </a>
      <span><a href="<?php echo $shop_url; ?>"><?php echo $shop_info['config_name']; ?></a><p>粉丝：<?php echo $shop_info['total_wish']; ?></p></span>
      <span>
      	<ul class="shop_assess">
        	<li>
            	描述
                <a href="javascript:void(0);"><?php echo $shop_rating['rating_product']; ?><i class="assess_up"></i></a>
            </li>
            <li>
            	质量
                <a href="javascript:void(0);"><?php echo $shop_rating['rating_quality']; ?><i class="assess_up"></i></a>
            </li>
            <li>
            	服务
                <a href="javascript:void(0);"><?php echo $shop_rating['rating_service']; ?><i class="assess_up"></i></a>
            </li>
            <li>
            	发货
                <a href="javascript:void(0);"><?php echo $shop_rating['rating_deliver']; ?><i class="assess_up"></i></a>
            </li>
            <div class="comment"><a href="#">买家评论（<?php echo $shop_info['total_reviews']; ?>）</a></div>
            <div class="into-shop"><a class="intoshop_btn" href="<?php echo $shop_url; ?>"><i class="intoshop_btn_ico"></i>
进入店铺</a></div>
        </ul>
      </span>
      </div>
    
      <div class="sale_message" style="display: none;">
      	<ul>
        	<li>
            	<div class="sale_top">
                	总订单数
                    <span>12</span>
                </div>
                <div class="sale_content">
                	<i class="fa fa-shopping-cart"></i>
                    <span><h2>3</h2></span>
                </div>
                <div class="sale_bottom">
                	<a href="#">查看更多...</a>
                </div>
            </li>
            <li>
            	<div class="sale_top">
                	总销售额
                    <span>12</span>
                </div>
                <div class="sale_content">
                	<i class="fa fa-credit-card"></i>
                    <span><h2>3</h2></span>
                </div>
                <div class="sale_bottom">
                	<a href="#">查看更多...</a>
                </div>
            </li>
            <li>
            	<div class="sale_top">
                	总会员数
                    <span>12</span>
                </div>
                <div class="sale_content">
                	<i class="fa fa-user"></i>
                    <span><h2>3</h2></span>
                </div>
                <div class="sale_bottom">
                	<a href="#">查看更多...</a>
                </div>
            </li>
            <li>
            	<div class="sale_top">
                	关注人数
                    
                </div>
                <div class="sale_content">
                	<i class="fa fa-eye"></i>
                    <span><h2>3</h2></span>
                </div>
                <div class="sale_bottom">
                	<a href="#">查看更多...</a>
                </div>
            </li>
        </ul>
      </div>
      
      <div class="new_orders">
      	<ul>
        <h3><i class="fa fa-shopping-cart"></i>待处理订单</h3>
        	<li>
            	<span>订单ID</span>
            	<span>会员</span>
                <span>状态</span>
                <span>下单时间</span>
                <span>总计</span>
                <span class="last">操作</span>
            </li>
            <?php foreach ($orders as $order) { ?>
            <li>
                <span><?php echo $order['order_id']; ?></span>
                <span><?php echo $order['customer']; ?></span>
                <span><?php echo $order['status']; ?></span>
                <span><?php echo $order['date_added']; ?></span>
                <span><?php echo $order['total']; ?></span>
                <span class="last"><a class="btn btn-info" href="<?php echo $order['view']; ?>"><i class="fa fa-eye"></i></a></span>
            </li>
            <?php } ?>
        </ul>
      </div>
        <div class="trading new_orders">
      	<ul>
        <h3><i class="fa fa-credit-card"></i>交易统计</h3>
        	<li>
            	<span>时间</span>
            	<span>下单数</span>
                <span>下单金额</span>
                <span>成交数</span>
                <span>成交额</span>
                <span class="last">成交率</span>
            </li>
            <li>
            	<span>本月</span>
            	<span><?php echo $this_month['total_order']?></span>
                <span><?php echo $this_month['order_total']?></span>
                <span><?php echo $this_month['total_complete']?></span>
                <span><?php echo $this_month['complete_total']?></span>
                <span class="last"><?php echo $this_month['total_complete'] > 0 ? number_format($this_month['total_complete'] / $this_month['total_order'] * 100, 1) : '0'; ?>%</span>
            </li>
            <li>
            	<span>上月</span>
            	<span><?php echo $last_month['total_order']?></span>
                <span><?php echo $last_month['order_total']?></span>
                <span><?php echo $last_month['total_complete']?></span>
                <span><?php echo $last_month['complete_total']?></span>
                <span class="last"><?php echo $last_month['total_complete'] > 0 ? number_format($last_month['total_complete'] / $last_month['total_order'] * 100, 1) : '0'; ?>%</span>
            </li>
            <li>
            	<span>前月</span>
            	<span><?php echo $old_month['total_order']?></span>
                <span><?php echo $old_month['order_total']?></span>
                <span><?php echo $old_month['total_complete']?></span>
                <span><?php echo $old_month['complete_total']?></span>
                <span class="last"><?php echo $old_month['total_complete'] > 0 ? number_format($old_month['total_complete'] / $old_month['total_order'] * 100, 1) : '0'; ?>%</span>
            </li>
        </ul>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>