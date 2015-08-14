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
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-10'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>" style="width: 80%; padding-left: 5px;"><?php echo $content_top; ?>
      <h2><?php echo $product_title; ?></h2>
      <?php if ($products) { ?>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-center"><?php echo $column_image; ?></td>
            <td class="text-left"><?php echo $column_name; ?></td>
            <td class="text-left"><?php echo $column_model; ?></td>
            <td class="text-right"><?php echo $column_stock; ?></td>
            <td class="text-right"><?php echo $column_price; ?></td>
            <td class="text-right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $product) { ?>
          <tr>
            <td class="text-center"><?php if ($product['thumb']) { ?>
              <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
              <?php } ?></td>
            <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></td>
            <td class="text-left"><?php echo $product['model']; ?></td>
            <td class="text-right"><?php echo $product['stock']; ?></td>
            <td class="text-right"><?php if ($product['price']) { ?>
              <div class="price">
                <?php if (!$product['special']) { ?>
                <?php echo $product['price']; ?>
                <?php } else { ?>
                <b><?php echo $product['special']; ?></b> <s><?php echo $product['price']; ?></s>
                <?php } ?>
              </div>
              <?php } ?></td>
            <td class="text-right"><button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');" data-toggle="tooltip" title="<?php echo $button_cart; ?>" class="btn btn-primary"><i class="fa fa-shopping-cart"></i></button>
              <a href="<?php echo $product['remove']; ?>" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-times"></i></a></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>

        <h2><?php echo $shop_title; ?></h2>
        <?php if ($shops) { ?>
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <td class="text-center"><?php echo $column_shop_logo; ?></td>
                <td class="text-left"><?php echo $column_shop_name; ?></td>
                <td class="text-left"><?php echo $column_shop_owner; ?></td>
                <td class="text-left"><?php echo $column_shop_address; ?></td>
                <td class="text-left"><?php echo $column_shop_level; ?></td>
                <td class="text-right"><?php echo $column_action; ?></td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($shops as $shop) { ?>
            <tr>
                <td class="text-center"><?php if ($shop['thumb']) { ?>
                    <a href="<?php echo $shop['shop_url']; ?>"><img src="<?php echo $shop['thumb']; ?>" alt="<?php echo $shop['shop_name']; ?>" title="<?php echo $shop['shop_name']; ?>" /></a>
                    <?php } ?></td>
                <td class="text-left"><a href="<?php echo $shop['shop_url']; ?>"><?php echo $shop['shop_name']; ?></a></td>
                <td class="text-left"><?php echo $shop['shop_owner']; ?></td>
                <td class="text-left"><?php echo $shop['shop_address']; ?></td>
                <td class="text-left"></td>
                <td class="text-right">
                    <a href="<?php echo $shop['remove']; ?>" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-times"></i></a></td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>
        <p><?php echo $text_empty; ?></p>
        <?php } ?>

      <div class="buttons clearfix">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?> 