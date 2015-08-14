<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
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
      <h1><?php echo $heading_title; ?></h1>
      <?php if ($orders) { ?>
      <div class="table-responsive">
        <style>
          table.order_table tr th,table.order_table tr td{border:1px solid #ccc;vertical-align:middle;}
        </style>
        <table class="table order_table">
          <thead>
            <tr style="border-top:1px solid #ccc">
              <th class="text-left">名称</th>
              <th class="text-left">型号</th>
              <th class="text-right">单价</th>
              <th class="text-right">数量</th>
              <th class="text-right"><?php echo $column_total; ?></th>
              <th class="text-right">状态</th>
              <th class="text-right">操作</th>
            </tr>
          </thead>
          <tbody>
            <?php $last_id = '0'; ?>
            <?php foreach ($orders as $order) { ?>
                <?php
                    if ($order['parent_id'] <> $last_id) {
                        if ($last_id <> '0') echo '<tr><td colspan="7" style="border:none;height:12px;"></td></tr>';
                        $last_id = $order['parent_id'];
                    }
                ?>
                <tr>
                    <td class="text-left" colspan="5" style="background-color: #e8e8e8;border-right:none">
                        #<?php echo $order['order_id']; ?>,
                        <a href="<?php echo $order['shop_info']['shop_url']; ?>" target="_blank"><?php echo $order['shop_info']['config_name']; ?></a>,
                        <?php echo $order['date_added']; ?>
                    </td>
                    <td colspan="2" class="text-right" style="background-color: #e8e8e8;border-left:none">
                        <?php if (!empty($order['action'])) { ?>
                        <?php foreach($order['action'] as $action) { ?>
                            <?php if (isset($action['confirm'])) { ?>
                            <a href="javascript:void(0);" onclick="if (confirm('<?php echo $action['confirm']; ?>')) window.location.href = '<?php echo $action['href']; ?>';" class="btn btn-info" style="line-height: 5px;"><?php echo $action['name']; ?></a>
                            <?php } else { ?>
                            <a href="<?php echo $action['href']; ?>" class="btn btn-info" style="line-height: 5px;"<?php if (isset($action['target'])) echo ' target="'.$action['target'].'"'; ?>><?php echo $action['name']; ?></a>
                            <?php } ?>
                        <?php } ?>
                        <?php } else { ?>
                        &nbsp;
                        <?php } ?>
                    </td>
                  <!--
                  <td class="text-right">#<?php echo $order['order_id']; ?></td>
                  <td class="text-left"><?php echo $order['status']; ?></td>
                  <td class="text-left"><?php echo $order['date_added']; ?></td>
                  <td class="text-right"><?php echo $order['products_amount']; ?></td>
                  <td class="text-left"><?php echo $order['shipping_name']; ?></td>
                  <td class="text-right"><?php echo $order['total']; ?></td>
                  <td class="text-right"><a href="<?php echo $order['href']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
                  -->
                </tr>
                <?php foreach ($order['products'] as $product) { ?>
                <tr>
                    <td class="text-left">
                        <a href="<?php echo $product['href']; ?>" target="_blank">
                            <img src="<?php echo $product['thumb']; ?>" />
                        </a>
                        <a href="<?php echo $product['href']; ?>" target="_blank"><?php echo $product['name']; ?></a>
                        <?php foreach ($product['option'] as $option) { ?>
                        <br />
                        &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                        <?php } ?></td>
                    <td class="text-left"><?php echo $product['model']; ?></td>
                    <td class="text-right"><?php echo $product['price']; ?></td>
                    <td class="text-right"><?php echo $product['quantity']; ?></td>
                    <td class="text-right"><?php echo $product['total']; ?></td>
                    <td class="text-right">
                        <?php echo $order['status']; ?>
                        <?php if ($product['return']) { ?>
                        <br/>
                        <a href="<?php echo $product['return']; ?>" target="_blank"><small class="text-danger">(<?php echo $product['return_status']; ?>)</small></a>
                        <?php } ?>
                    </td>
                    <td class="text-right" style="white-space: nowrap;">
                        <?php if (!empty($product['actions'])) { ?>
                        <?php foreach ($product['actions'] as $action) { ?>
                        <p style="margin-bottom: 0px;"><a href="<?php echo $action['href']; ?>"><?php echo $action['name']; ?></a></p>
                        <?php } ?>
                        <?php } else { ?>
                        &nbsp;
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="text-right"><?php echo $pagination; ?></div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <div class="buttons clearfix" style="display: none;">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>