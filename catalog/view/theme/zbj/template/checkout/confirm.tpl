<?php if (!isset($redirect)) { ?>
<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <!--
    <thead>
      <tr>
        <td class="text-left"><?php echo $column_name; ?></td>
        <td class="text-left"><?php echo $column_model; ?></td>
        <td class="text-right"><?php echo $column_quantity; ?></td>
        <td class="text-right"><?php echo $column_price; ?></td>
        <td class="text-right"><?php echo $column_total; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
          <?php foreach ($product['option'] as $option) { ?>
          <br />
          &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
          <?php } ?>
          <?php if($product['recurring']) { ?>
          <br />
          <span class="label label-info"><?php echo $text_recurring_item; ?></span> <small><?php echo $product['recurring']; ?></small>
          <?php } ?></td>
        <td class="text-left"><?php echo $product['model']; ?></td>
        <td class="text-right"><?php echo $product['quantity']; ?></td>
        <td class="text-right"><?php echo $product['price']; ?></td>
        <td class="text-right"><?php echo $product['total']; ?></td>
      </tr>
      <?php } ?>
      <?php foreach ($vouchers as $voucher) { ?>
      <tr>
        <td class="text-left"><?php echo $voucher['description']; ?></td>
        <td class="text-left"></td>
        <td class="text-right">1</td>
        <td class="text-right"><?php echo $voucher['amount']; ?></td>
        <td class="text-right"><?php echo $voucher['amount']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
    -->
    <tfoot>
      <?php foreach ($totals as $total) { ?>
      <tr>
        <td colspan="4" class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
        <td class="text-right" style="width: 10%;"><?php echo $total['text']; ?></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
<?php echo $payment; ?>
<script type="text/javascript"><!--
    var _shippingAddress = $('select[name="address_id"]').find('option:selected').text();
    $('#collapse-shipping-address .panel-body').empty();
    $('#collapse-shipping-address .panel-body').append('<span>'+_shippingAddress+'</span>');

    $('select[name^="shipping_method_"]').each(function(){
        var _shopId = $(this).data('shop');
        var _comment = $('input[name="comment_'+_shopId+'"]').val();
        $('input[name="comment_'+_shopId+'"]').hide();
        $('input[name="comment_'+_shopId+'"]').parent('label').append('<span>'+_comment+'</span>');

        var _shippingMethod = $(this).find('option:selected').text();
        $(this).hide();
        $(this).after('<span style="float: right;">'+_shippingMethod+'</span>');
        $(this).remove();
    });

    $('select[name^="coupon_"]').each(function(){
        var _coupon = $(this).find('option:selected').text();
        //if ($(this).find('option:selected').val() == '') _coupon = '无';
        $(this).hide();
        $(this).after('<span style="float: right;">'+_coupon+'</span>');
        $(this).remove();
    });

    var _paymentMethod = $('input[name="payment_method"]:checked').parent('label').text();
    var _paymentComment = $('textarea[name="comment"]').val();
    $('#collapse-payment-method .panel-body').empty();
    $('#collapse-payment-method .panel-body').append('<span>'+_paymentMethod+'<br/>备注：'+_paymentComment+'</span>');

    $('#button-confirm').parent().append('<a href="<?php echo $back_href; ?>" class="btn btn-primary" id="button-later-pay"><?php echo $button_later; ?></a>');
//--></script>
<?php } else { ?>
<script type="text/javascript"><!--
location = '<?php echo $redirect; ?>';
//--></script>
<?php } ?>
