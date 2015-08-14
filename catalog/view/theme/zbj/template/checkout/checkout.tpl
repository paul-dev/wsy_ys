<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>

        <?php if ($shipping_required) { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">配送信息<?php //echo $text_checkout_shipping_address; ?></h4>
            </div>
            <div class="panel-collapse collapse" id="collapse-shipping-address">
                <div class="panel-body"></div>
            </div>
        </div>
        <div class="panel panel-default" style="display: none;">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo $text_checkout_shipping_method; ?></h4>
            </div>
            <div class="panel-collapse collapse" id="collapse-shipping-method">
                <div class="panel-body"></div>
            </div>
        </div>
        <?php } ?>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <td class="text-center"><?php echo $column_image; ?></td>
                    <td class="text-left" style="width: 35%;"><?php echo $column_name; ?></td>
                    <td class="text-left"><?php echo $column_model; ?></td>
                    <td class="text-right" style="width: 10%;"><?php echo $column_quantity; ?></td>
                    <td class="text-right"><?php echo $column_price; ?></td>
                    <td class="text-right"><?php echo $column_total; ?></td>
                </tr>
                </thead>
                <tbody>
                <?php
              foreach ($products as $shop_id => $shop_products) {
              ?>
                <tr>
                    <td class="text-left" colspan="6">
                        <a href="<?php echo $shop_products[0]['shop_href']; ?>" target="_blank"><strong><?php echo $shop_products[0]['shop_name']; ?></strong></a>
                    </td>
                </tr>
                <?php foreach ($shop_products as $product) { ?>
                <tr>
                    <td class="text-center"><?php if ($product['thumb']) { ?>
                        <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
                        <?php } ?></td>
                    <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                        <?php if (!$product['stock']) { ?>
                        <span class="text-danger">***</span>
                        <?php } ?>
                        <?php if ($product['option']) { ?>
                        <?php foreach ($product['option'] as $option) { ?>
                        <br />
                        <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                        <?php } ?>
                        <?php } ?>
                        <?php if ($product['reward']) { ?>
                        <br />
                        <small><?php echo $product['reward']; ?></small>
                        <?php } ?>
                        <?php if ($product['recurring']) { ?>
                        <br />
                        <span class="label label-info"><?php echo $text_recurring_item; ?></span> <small><?php echo $product['recurring']; ?></small>
                        <?php } ?></td>
                    <td class="text-left"><?php echo $product['model']; ?></td>
                    <td class="text-right"><?php echo $product['quantity']; ?></td>
                    <td class="text-right"><?php echo $product['price']; ?></td>
                    <td class="text-right"><?php echo $product['total']; ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td class="text-left" colspan="6">
                        <label style="float: left; width: 50%;">
                            <span style="float: left;">给卖家留言：</span>
                            <input name="comment_<?php echo $shop_id; ?>" class="form-control" style="width: 85%;" />
                        </label>
                        <label style="<?php if (!$shop_shipping_required[$shop_id]) echo 'display: none; '; ?>float: right; width: 25%;">
                            <span style="float: left;">配送方式：</span>
                            <select name="shipping_method_<?php echo $shop_id; ?>" data-shop="<?php echo $shop_id; ?>" class="form-control" style="width: 78%;">
                                <?php foreach ($shipping_methods[$shop_id] as $shipping_method) { ?>
                                <?php foreach ($shipping_method['quote'] as $quote) { ?>
                                <option value="<?php echo $quote['code']; ?>"><?php echo $quote['title']; ?> - <?php echo $quote['text']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                            <?php if (isset($coupons[$shop_id])) { ?>
                            <br/>
                            <span style="float: left;">优惠折扣：</span>
                            <select name="coupon_<?php echo $shop_id; ?>" data-shop="<?php echo $shop_id; ?>" class="form-control" style="width: 78%;">
                                <option value="">不使用</option>
                                <?php foreach ($coupons[$shop_id] as $coupon) { ?>
                                <option value="<?php echo $coupon['coupon_id']; ?>"><?php echo $coupon['name']; ?></option>
                                <?php } ?>
                            </select>
                            <?php } ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <td class="text-left" colspan="6" id="shop_total_<?php echo $shop_id; ?>">
                        <label style="float: right; width: 25%;">
                            <?php foreach ($totals[$shop_id] as $total) { ?>
                            <span style="float: right;"><?php echo $total['title'].': '.$total['text'];?></span><br/>
                            <?php } ?>
                        </label>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

        <script type="text/javascript"><!--
            $('select[name^="shipping_method_"]').on('change', function(){
                //console.log($(this).data('shop'));
                var _shopId = $(this).data('shop');
                var _method = $('select[name="shipping_method_'+_shopId+'"]').val();
                var _comment = $('input[name="comment_'+_shopId+'"]').val();
                var _this = $(this);
                $.ajax({
                    url: 'index.php?route=checkout/shipping_method/update',
                    type: 'post',
                    data: 'shop_id='+_shopId+'&shipping_method='+_method+'&comment='+_comment,
                    dataType: 'json',
                    beforeSend: function() {
                        $('#button-shipping-method').button('loading');
                    },
                    complete: function() {
                        $('#button-shipping-method').button('reset');
                    },
                    success: function(json) {
                        //$('.alert, .text-danger').remove();
                        $(_this).parent('label').find('.alert, .text-danger').remove();

                        if (json['redirect']) {
                            location = json['redirect'];
                        } else if (json['error']) {
                            if (json['error']['warning']) {
                                //$('#content').before('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                                //$(_this).hide();
                                //$(_this).prev('span').hide();
                                $(_this).after('<span class="alert alert-danger" style="float: right; margin-bottom: 0px;">'+json['error']['warning']+'</span>');
                            }
                        } else {
                            $(_this).show();
                            $(_this).prev('span').show();
                        }

                        $.ajax({
                            url: 'index.php?route=checkout/checkout/total&shop_id='+_shopId,
                            dataType: 'html',
                            success: function(html) {
                                $('#shop_total_'+_shopId).html(html);
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }
                        });

                        $.ajax({
                            url: 'index.php?route=checkout/payment_method',
                            dataType: 'html',
                            success: function(html) {
                                $('#collapse-payment-method .panel-body').html(html);

                                $('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_payment_method; ?> <i class="fa fa-caret-down"></i></a>');

                                if (!$('#collapse-payment-method').hasClass('in')) $('a[href=\'#collapse-payment-method\']').trigger('click');

                                $('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('<?php echo $text_checkout_confirm; ?>');
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }
                        });
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });

                $('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('<?php echo $text_checkout_confirm; ?>');
                $('#collapse-checkout-confirm .panel-body').html('');
            });

            $('select[name^="coupon_"]').on('change', function(){
                //console.log($(this).data('shop'));
                var _shopId = $(this).data('shop');
                var _coupon = $(this).val();
                var _this = $(this);
                $.ajax({
                    url: 'index.php?route=checkout/coupon/update',
                    type: 'post',
                    data: 'shop_id='+_shopId+'&coupon='+_coupon,
                    dataType: 'json',
                    beforeSend: function() {
                        //$('#button-shipping-method').button('loading');
                    },
                    complete: function() {
                        //$('#button-shipping-method').button('reset');
                    },
                    success: function(json) {
                        //$('.alert, .text-danger').remove();
                        $(_this).parent('label').find('.alert, .text-danger').remove();

                        if (json['redirect']) {
                            location = json['redirect'];
                        } else if (json['error']) {
                            //$(_this).hide();
                            //$(_this).prev('span').hide();
                            $(_this).after('<br/><span class="alert alert-danger" style="float: right; margin-bottom: 0px;">'+json['error']+'</span>');
                        }

                        $.ajax({
                            url: 'index.php?route=checkout/checkout/total&shop_id='+_shopId,
                            dataType: 'html',
                            success: function(html) {
                                $('#shop_total_'+_shopId).html(html);
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }
                        });

                        $.ajax({
                            url: 'index.php?route=checkout/payment_method',
                            dataType: 'html',
                            success: function(html) {
                                $('#collapse-payment-method .panel-body').html(html);

                                $('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_payment_method; ?> <i class="fa fa-caret-down"></i></a>');

                                if (!$('#collapse-payment-method').hasClass('in')) $('a[href=\'#collapse-payment-method\']').trigger('click');

                                $('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('<?php echo $text_checkout_confirm; ?>');
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }
                        });
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });

                $('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('<?php echo $text_checkout_confirm; ?>');
                $('#collapse-checkout-confirm .panel-body').html('');
            });
        //--></script>

        <div class="panel-group" id="accordion">
        <div class="panel panel-default" style="display: none;">
          <div class="panel-heading">
            <h4 class="panel-title"><?php echo $text_checkout_option; ?></h4>
          </div>
          <div class="panel-collapse collapse" id="collapse-checkout-option">
            <div class="panel-body"></div>
          </div>
        </div>
        <?php if (!$logged && $account != 'guest') { ?>
        <div class="panel panel-default" style="display: none;">
          <div class="panel-heading">
            <h4 class="panel-title"><?php echo $text_checkout_account; ?></h4>
          </div>
          <div class="panel-collapse collapse" id="collapse-payment-address">
            <div class="panel-body"></div>
          </div>
        </div>
        <?php } else { ?>
        <div class="panel panel-default" style="display: none;">
          <div class="panel-heading">
            <h4 class="panel-title"><?php echo $text_checkout_payment_address; ?></h4>
          </div>
          <div class="panel-collapse collapse" id="collapse-payment-address">
            <div class="panel-body"></div>
          </div>
        </div>
        <?php } ?>

        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title"><?php echo $text_checkout_payment_method; ?></h4>
          </div>
          <div class="panel-collapse collapse" id="collapse-payment-method">
            <div class="panel-body"></div>
          </div>
        </div>
        <div class="panel panel-default" style="display: none;">
          <div class="panel-heading">
            <h4 class="panel-title"><?php echo $text_checkout_confirm; ?></h4>
          </div>
          <div class="panel-collapse collapse" id="collapse-checkout-confirm">
            <div class="panel-body"></div>
          </div>
        </div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript"><!--
$(document).ready(function(){
    <?php if ($shipping_required) { ?>
        $.ajax({
            url: 'index.php?route=checkout/shipping_address',
            dataType: 'html',
            success: function(html) {
                $('#collapse-shipping-address .panel-body').html(html);

                //$('#collapse-shipping-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_shipping_address; ?> <i class="fa fa-caret-down"></i></a>');

                //$('a[href=\'#collapse-shipping-address\']').trigger('click');
                $('#collapse-shipping-address').css('visibility', 'visible');
                $('#collapse-shipping-address').slideDown();

                //$('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('<?php echo $text_checkout_shipping_method; ?>');
                $('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('<?php echo $text_checkout_payment_method; ?>');
                $('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('<?php echo $text_checkout_confirm; ?>');

                //$('#button-shipping-address').trigger('click');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    <?php } ?>

    $.ajax({
        url: 'index.php?route=checkout/payment_method',
        dataType: 'html',
        success: function(html) {
            $('#collapse-payment-method .panel-body').html(html);

            $('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_payment_method; ?> <i class="fa fa-caret-down"></i></a>');

            $('a[href=\'#collapse-payment-method\']').trigger('click');
            //$('#collapse-payment-method').css('visibility', 'visible');
            //$('#collapse-payment-method').slideDown();

            $('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('<?php echo $text_checkout_confirm; ?>');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });

    $('select[name^="shipping_method_"]').each(function(){
        //var _shopId = $(this).data('shop');

        if ($(this).find('option').length == 0) {
            $(this).append('<option value="none.none">无</option>');
        }

        if ($(this).val() == 'none.none') {
            $(this).hide();
            $(this).prev('span').hide();
            $(this).parent('label').append('<span class="alert alert-danger" style="float: right; margin-bottom: 0px;"><?php echo $error_shipping_method; ?></span>');
        }

    });
});

// Shipping Address         
$(document).delegate('#button-shipping-address', 'click', function() {
    $.ajax({
        url: 'index.php?route=checkout/shipping_address/save',
        type: 'post',
        data: $('#collapse-shipping-address input[type=\'text\'], #collapse-shipping-address input[type=\'hidden\'], #collapse-shipping-address input[type=\'date\'], #collapse-shipping-address input[type=\'datetime-local\'], #collapse-shipping-address input[type=\'time\'], #collapse-shipping-address input[type=\'password\'], #collapse-shipping-address input[type=\'checkbox\']:checked, #collapse-shipping-address input[type=\'radio\']:checked, #collapse-shipping-address textarea, #collapse-shipping-address select'),
        dataType: 'json',
        beforeSend: function() {
			$('#button-shipping-address').button('loading');
	    },  
        complete: function() {
			$('#button-shipping-address').button('reset');
        },          
        success: function(json) {
            $('.alert, .text-danger').remove();
            
            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                if (json['error']['warning']) {
                    $('#collapse-shipping-address .panel-body').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }
  								
				for (i in json['error']) {
					var element = $('#input-shipping-' + i.replace('_', '-'));
					
					if ($(element).parent().hasClass('input-group')) {
						$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
					} else {
						$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
					}
				}
								
				// Highlight any found errors
				$('.text-danger').parent().parent().addClass('has-error');				
            } else {
                /*$.ajax({
                    url: 'index.php?route=checkout/shipping_method',
                    dataType: 'html',
                    success: function(html) {
                        $('#collapse-shipping-method .panel-body').html(html);
                        
						$('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_shipping_method; ?> <i class="fa fa-caret-down"></i></a>');
		
						$('a[href=\'#collapse-shipping-method\']').trigger('click');

						$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('<?php echo $text_checkout_payment_method; ?>');
						$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('<?php echo $text_checkout_confirm; ?>');
						
                        $.ajax({
                            url: 'index.php?route=checkout/shipping_address',
                            dataType: 'html',
                            success: function(html) {
                                $('#collapse-shipping-address .panel-body').html(html);
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }
                        });
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                }); */

                $('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('<?php echo $text_checkout_confirm; ?>');
                $('#collapse-checkout-confirm .panel-body').html('');

                $.ajax({
                    url: 'index.php?route=checkout/checkout/shipping',
                    dataType: 'json',
                    success: function(json) {
                        $('select[name^="shipping_method_"]').each(function(){
                            var _shopId = $(this).data('shop');
                            $(this).empty();
                            var _obj = $(this);
                            var _methods = json['shipping_methods'];
                            if (_methods[_shopId]) {
                                $.each(_methods[_shopId], function(i){
                                    $.each(_methods[_shopId][i]['quote'], function(k){
                                        var _val = _methods[_shopId][i]['quote'][k];
                                        _obj.append('<option value="'+_val['code']+'">'+_val['title']+' - '+_val['text']+'</option>');
                                    });
                                });
                            }

                            if ($(this).find('option').length == 0) {
                                $(this).append('<option value="none.none">无</option>');
                            }

                            if ($(this).val() == 'none.none') {
                                $(this).hide();
                                $(this).prev('span').hide();
                                $(this).parent('label').append('<span class="alert alert-danger" style="float: right; margin-bottom: 0px;"><?php echo $error_shipping_method; ?></span>');
                            } else {
                                $(this).parent('label').find('.alert, .text-danger').remove();
                                $(this).show();
                                $(this).prev('span').show();
                            }

                            //$(this).trigger('change');
                            var _totals = json['totals'];
                            $('#shop_total_'+_shopId).html('<label style="float: right; width: 25%;">'+_totals[_shopId]+'</label>');
                        });

                        if ($('input[name="shipping_address"]:checked').val() == 'new') {
                            $.ajax({
                                url: 'index.php?route=checkout/shipping_address',
                                dataType: 'html',
                                success: function(html) {
                                    $('#collapse-shipping-address .panel-body').html(html);
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                }
                            });
                        }

                        $.ajax({
                            url: 'index.php?route=checkout/payment_method',
                            dataType: 'html',
                            success: function(html) {
                                $('#collapse-payment-method .panel-body').html(html);

                                $('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_payment_method; ?> <i class="fa fa-caret-down"></i></a>');

                                if (!$('#collapse-payment-method').hasClass('in')) $('a[href=\'#collapse-payment-method\']').trigger('click');

                                $('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('<?php echo $text_checkout_confirm; ?>');
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }
                        });

                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    }); 
});

// Payment Method
$(document).delegate('#button-payment-method', 'click', function() {
    $.ajax({
        url: 'index.php?route=checkout/payment_method/save', 
        type: 'post',
        data: $('#collapse-payment-method input[type=\'radio\']:checked, #collapse-payment-method input[type=\'checkbox\']:checked, #collapse-payment-method textarea, input[name^="comment_"]'),
        dataType: 'json',
        beforeSend: function() {
         	$('#button-payment-method').button('loading');
		},  
        complete: function() {
            $('#button-payment-method').button('reset');
        },          
        success: function(json) {
            $('.alert, .text-danger').remove();
            
            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                if (json['error']['warning']) {
                    $('#collapse-payment-method .panel-body').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }           
            } else {
                $.ajax({
                    url: 'index.php?route=checkout/confirm',
                    dataType: 'html',
                    success: function(html) {
                        $('#collapse-checkout-confirm .panel-body').html(html);

                        $('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('<?php echo $text_checkout_payment_method; ?>');
                        $('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('<a href="#collapse-checkout-confirm" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_confirm; ?> <i class="fa fa-caret-down"></i></a>');
						
						$('a[href=\'#collapse-checkout-confirm\']').trigger('click');

                        $('#collapse-payment-method').css('visibility', 'visible');
                        $('#collapse-payment-method').css('display', 'block');
                        $('#collapse-payment-method').css('height', 'auto');

                        $('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('<?php echo $text_checkout_confirm; ?>');
                        $('#collapse-checkout-confirm').parent().show();
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                }); 
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    }); 
});
//--></script> 
<?php echo $footer; ?>