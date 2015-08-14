<?php echo $header; ?>

<link rel="stylesheet" href="catalog/view/theme/zbj/stylesheet/base.css">
<link rel="stylesheet" type="text/css" href="catalog/view/theme/zbj/stylesheet/special_auction.css">

<?php echo $header_top; ?>

  <div class="setted_content">
    <!-- <div class="cata_title cata-layout clearfix">
        <span class="control-span"><?php echo $text_limit; ?></span>
        <ul class="category">
            <li><a id="list-view"  class="first" href="javascript:void(0);"><?php echo $button_list; ?></a></li>
            <li><a id="grid-view" class=" last" href="javascript:void(0);"><?php echo $button_grid; ?></a></li>
        </ul>

        <span class="control-span">类型:</span>
        <ul class="category">
          <?php
          $_i=0;
          foreach ($types as $type_data) {
            $_i++;
            ?>
            <li><a class="<?php
            if ($type_data['value'] == $type) { echo ' active '; };
             if($_i ==1){echo ' first ';};
              if($_i==count($types)){echo ' last ';};
               ?>" href="<?php echo $type_data['href']; ?>"><?php echo $type_data['text']; ?></a></li>
          <?php } ?>
        </ul>

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

        <p class="goods_ratio"><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a></p>
    </div> -->
    <div class="auction_tab clearfix">
      <ul>
          <?php
          foreach ($sorts as $sort_data) {
              if ($sort_data['value'] == $sort . '-' . $order) {
                echo '<li class="curr"><a href="'.$sort_data['href'].'">'.$sort_data['text'].'</a></li>';
              } else {
              echo '<li><a href="'.$sort_data['href'].'">'.$sort_data['text'].'</a></li>';
              }
          }
          ?>
      </ul>
    </div>

    <div class="auction_sell_list">
        <?php if ($products) { ?>
        <div class="products_list">
            <ul class="clearfix">
                <?php foreach ($products as $product) { ?>
                <li>
                    <div class="image">
                        <a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"></a>
                    </div>
                    <div class="a_info clearfix">
                        <div class="a_left">
                            <div class="a_name"><a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><?php echo $product['name']; ?></a></div>
                            <div class="curr_price">当前价<span><?php echo $product['bidding_price']; ?></span></div>
                            <div class="a_time">
                                <?php if ($product['auction_start'] > date('Y-m-d H:i:s')) { ?>
                                开始于：<?php echo $product['auction_start']; ?>
                                <?php } elseif ($product['auction_end'] < date('Y-m-d H:i:s')) { ?>
                                结束于：<?php echo $product['auction_end']; ?>
                                <?php } else { ?>
                                截止于：<?php echo $product['auction_end']; ?>
                                <?php } ?>
                                <!--距结束<span><em>04</em>时<em>36</em>分</span>-->
                            </div>
                        </div>
                        <div class="a_right">
                            <div class="auction_num"><strong><?php echo $product['total_bidding']; ?></strong><br>次出价</div>
                        </div>
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

<?php echo $footer; ?>