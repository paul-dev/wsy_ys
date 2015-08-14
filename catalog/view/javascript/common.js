function getURLVar(key) {
	var value = [];

	var query = String(document.location).split('?');

	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
}

$(document).ready(function() {
	// Adding the clear Fix
	cols1 = $('#column-right, #column-left').length;
	
	if (cols1 == 2) {
		$('#content .product-layout:nth-child(2n+2)').after('<div class="clearfix visible-md visible-sm"></div>');
	} else if (cols1 == 1 || $('#row-cols-4').length > 0) {
        //$('#content .product-layout:nth-child(3n+3)').after('<div class="clearfix visible-lg"></div>');
        $('#content .product-layout:nth-child(4n+4)').after('<div class="clearfix visible-lg"></div>');
	} else {
        //$('#content .product-layout:nth-child(4n+4)').after('<div class="clearfix"></div>');
        $('#content .product-layout:nth-child(6n+6)').after('<div class="clearfix"></div>');
	}
	
	// Highlight any found errors
	$('.text-danger').each(function() {
		var element = $(this).parent().parent();
		
		if (element.hasClass('form-group')) {
			element.addClass('has-error');
		}
	});

	//image LazyLoad
	$(".product-thumb .image img").scrollLoading();
    // Override summernotes image manager
    $('button[data-event=\'showImageDialog\']').attr('data-toggle', 'filemanager').removeAttr('data-event');

    $(document).delegate('button[data-toggle=\'filemanager\']', 'click', function() {
        $('#modal-image').remove();

        $(this).parents('.note-editor').find('.note-editable').focus();

        $.ajax({
            url: 'index.php?route=seller/filemanager',
            dataType: 'html',
            beforeSend: function() {
                $('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                $('#button-image').prop('disabled', true);
            },
            complete: function() {
                $('#button-image i').replaceWith('<i class="fa fa-upload"></i>');
                $('#button-image').prop('disabled', false);
            },
            success: function(html) {
                $('body').append('<div id="modal-image" class="modal">' + html + '</div>');

                $('#modal-image').modal('show');
            }
        });
    });

    // Image Manager
    $(document).delegate('.container-fluid a[data-toggle=\'image\']', 'click', function(e) {
        e.preventDefault();

        $('.popover').popover('hide', function() {
            $('.popover').remove();
        });

        var element = this;

        $(element).popover({
            html: true,
            placement: 'right',
            trigger: 'manual',
            content: function() {
                return '<button type="button" id="button-image" class="btn btn-primary"><i class="fa fa-pencil"></i></button> <button type="button" id="button-clear" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>';
            }
        });

        $(element).popover('show');

        $('#button-image').on('click', function() {
            $('#modal-image').remove();

            $.ajax({
                url: 'index.php?route=seller/filemanager&target=' + $(element).parent().find('input').attr('id') + '&thumb=' + $(element).attr('id'),
                dataType: 'html',
                beforeSend: function() {
                    $('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                    $('#button-image').prop('disabled', true);
                },
                complete: function() {
                    $('#button-image i').replaceWith('<i class="fa fa-pencil"></i>');
                    $('#button-image').prop('disabled', false);
                },
                success: function(html) {
                    $('body').append('<div id="modal-image" class="modal">' + html + '</div>');

                    $('#modal-image').modal('show');
                }
            });

            $(element).popover('hide', function() {
                $('.popover').remove();
            });
        });

        $('#button-clear').on('click', function() {
            $(element).find('img').attr('src', $(element).find('img').attr('data-placeholder'));

            $(element).parent().find('input').attr('value', '');

            $(element).popover('hide', function() {
                $('.popover').remove();
            });
        });
    });

	// Currency
	$('#currency .currency-select').on('click', function(e) {
		e.preventDefault();

		$('#currency input[name=\'code\']').attr('value', $(this).attr('name'));

		$('#currency').submit();
	});

	// Language
	$('form#language a').on('click', function(e) {
		e.preventDefault();

		$('#language input[name=\'code\']').attr('value', $(this).attr('href'));

		$('#language').submit();
	});

	/* Search */
	$('#search input[name=\'search\']').parent().find('button').on('click', function() {
		url = $('base').attr('href') + 'index.php?route=product/search';

		var value = $('header input[name=\'search\']').val();

		if (value) {
			url += '&search=' + encodeURIComponent(value);
		}

		location = url;
	});

	$('#search input[name=\'search\']').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$('header input[name=\'search\']').parent().find('button').trigger('click');
		}
	});

	// Menu
	$('#menu .dropdown-menu').each(function() {
		var menu = $('#menu').offset();
		var dropdown = $(this).parent().offset();

		var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());

		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 5) + 'px');
		}
	});

	// Product List
	$('#list-view').click(function() {
        $('#content .product-layout > .clearfix').remove();

		//$('#content .product-layout').attr('class', 'product-layout product-list col-xs-12');
        //$('#content .row > .product-layout .product-thumb .caption >strong').attr('style', '');
        $('#content .row > .product-layout').attr('style', '');
		$('#content .row > .product-layout').attr('class', 'product-layout product-list col-xs-12');
        $('#content .row > .product-layout .product-thumb .caption >h4').next('p').show();
        $('#content .row > .product-layout .product-thumb .caption >strong').next('p').show();

		localStorage.setItem('display', 'list');
	});

	// Product Grid
	$('#grid-view').click(function() {
        $('#content .product-layout > .clearfix').remove();

		// What a shame bootstrap does not take into account dynamically loaded columns
		cols = $('#column-right, #column-left').length;

		if (cols == 2) {
			$('#content .product-layout').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-12');
		} else if (cols == 1 || $('#row-cols-4').length > 0) {
            //$('#content .product-layout').attr('class', 'product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12');
            $('#content .product-layout').attr('class', 'product-layout product-grid col-md-3 col-sm-6 col-xs-12');
            $('#content .product-layout').attr('style', 'padding: 0px;margin:4px; width: 24%;');
		} else {
			$('#content .product-layout').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12');
		}

        $('#content .product-layout .product-thumb .caption >h4').next('p').hide();
        $('#content .product-layout .product-thumb .caption >strong').next('p').hide();
        //$('#content .product-layout .product-thumb .caption >strong').attr('style', 'float:right;');

		localStorage.setItem('display', 'grid');
	});

	if (localStorage.getItem('display') == 'list') {
		$('#list-view').trigger('click');
	} else {
		$('#grid-view').trigger('click');
	}

    $('#content .product-layout').show();

	// tooltips on hover
	$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});

	// Makes tooltips work on ajax generated content
	$(document).ajaxStop(function() {
		$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});
	});

    // Pagination
    $('#button-page-jump').on('click', function(){
        var _page = $(this).parent().find('input[name="input_page_jump"]').val();
        if (isNaN(_page) || _page < 1) _page = 1;
        $(this).parent().find('input[name="input_page_jump"]').val(_page);
        if ($(this).data('url')) {
            location = $(this).data('url') + '&page=' + _page;
        }
    });

    //gotop
    $(window).scroll(function(){
    	var gotop = $('#gotop');
    	if(gotop.length>0){
    		if($(document).scrollTop()>=128){gotop.fadeIn(200);}else{gotop.fadeOut(200);}
    	}
    })
    
});

// Cart add remove functions
var cart = {
	'add': function(product_id, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart/add',
			type: 'post',
			data: 'product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},			
			success: function(json) {
				$('.alert, .text-danger').remove();
                $('#modal-cart').remove();

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
					//$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

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
					
					// Need to set timeout otherwise it wont update the total
					setTimeout(function () {
						$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
                        $('#header-cart-label a').attr('title', json['total']);
                        var _pos = json['total'].indexOf(' ');
                        var _num = json['total'].substring(0, _pos);
                        $('#header-cart-label a .shopping_cart_num').html(_num);
					}, 100);
				
					//$('html, body').animate({ scrollTop: 0 }, 'slow');

					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			}
		});
	},
	'update': function(key, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart/update',
			type: 'post',
			data: 'key=' + key + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				//$('#cart > button').button('loading');
			},
			complete: function() {
				//$('#cart > button').button('reset');
			},			
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
                    $('#header-cart-label a').attr('title', json['total']);
                    var _pos = json['total'].indexOf(' ');
                    var _num = json['total'].substring(0, _pos);
                    $('#header-cart-label a .shopping_cart_num').html(_num);
				}, 100);

				if (/*getURLVar('route') == 'checkout/cart' || */getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			}
		});
	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},			
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
                    $('#header-cart-label a').attr('title', json['total']);
                    var _pos = json['total'].indexOf(' ');
                    var _num = json['total'].substring(0, _pos);
                    $('#header-cart-label a .shopping_cart_num').html(_num);
				}, 100);
					
				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			}
		});
	}
}

var voucher = {
	'add': function() {

	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
                    $('#header-cart-label a').attr('title', json['total']);
                    var _pos = json['total'].indexOf(' ');
                    var _num = json['total'].substring(0, _pos);
                    $('#header-cart-label a .shopping_cart_num').html(_num);
				}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			}
		});
	}
}

var wishlist = {
	'add': function(product_id,thisObj) {
		$.ajax({
			url: 'index.php?route=account/wishlist/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.alert').remove();
                $('#modal-wish').remove();

                var _message = false;

				if (json['success']) {
					//$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    _message = json['success'];
					if(thisObj){
						//console.log(thisObj);
						$(thisObj).html('收藏('+json['total_wish']+')');
					}
				}

				if (json['info']) {
					//$('#content').parent().before('<div class="alert alert-info"><i class="fa fa-info-circle"></i> ' + json['info'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    _message = json['info'];
				}

                if (_message !== false) {
                    var html  = '<div id="modal-wish" class="modal fade">';
                    //html += '<div class="modal-backdrop  in" style="height: 100%;"></div>';
                    html += '  <div class="modal-dialog">';
                    html += '    <div class="modal-content">';
                    html += '      <div class="modal-header">';
                    html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
                    html += '        <h4 class="modal-title">收藏成功！</h4>';
                    html += '      </div>';
                    html += '      <div class="modal-body">' + _message + '</div>';
                    html += '    </div>';
                    html += '  </div>';
                    html += '</div>';

                    $('body').append(html);
                    $('#modal-wish .modal-dialog').css('margin-top', $(window).height() / 2 - $('#modal-wish .modal-dialog .modal-content').height() / 2 - 50 + 'px');

                    $('#modal-wish').modal('show');
                    setTimeout(function(){
                        $('#modal-wish').modal('hide');
                    }, 3000);
                }

				$('#wishlist-total').html(json['total']);

				//$('html, body').animate({ scrollTop: 0 }, 'slow');
			}
		});
	},
    'shop': function(shop_id) {
        $.ajax({
            url: 'index.php?route=account/wishlist/add',
            type: 'post',
            data: 'shop_id=' + shop_id,
            dataType: 'json',
            success: function(json) {
                $('.alert').remove();
                $('#modal-wish').remove();

                var _message = false;

                if (json['success']) {
                    //$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    _message = json['success'];
                }

                if (json['info']) {
                    //$('#content').parent().before('<div class="alert alert-info"><i class="fa fa-info-circle"></i> ' + json['info'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    _message = json['info'];
                }

                if (_message !== false) {
                    var html  = '<div id="modal-wish" class="modal fade">';
                    //html += '<div class="modal-backdrop  in" style="height: 100%;"></div>';
                    html += '  <div class="modal-dialog">';
                    html += '    <div class="modal-content">';
                    html += '      <div class="modal-header">';
                    html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
                    html += '        <h4 class="modal-title">收藏成功！</h4>';
                    html += '      </div>';
                    html += '      <div class="modal-body">' + _message + '</div>';
                    html += '    </div>';
                    html += '  </div>';
                    html += '</div>';

                    $('body').append(html);
                    $('#modal-wish .modal-dialog').css('margin-top', $(window).height() / 2 - $('#modal-wish .modal-dialog .modal-content').height() / 2 - 50 + 'px');

                    $('#modal-wish').modal('show');
                    setTimeout(function(){
                        $('#modal-wish').modal('hide');
                    }, 3000);
                }

                $('#wishlist-total').html(json['total']);

                //$('html, body').animate({ scrollTop: 0 }, 'slow');
            }
        });
    },
	'remove': function() {

	}
}

var compare = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?route=product/compare/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.alert').remove();

				if (json['success']) {
					$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					$('#compare-total').html(json['total']);

					$('html, body').animate({ scrollTop: 0 }, 'slow');
				}
			}
		});
	},
	'remove': function() {

	}
}

/* Agree to Terms */
$(document).delegate('.agree', 'click', function(e) {
	e.preventDefault();

	$('#modal-agree').remove();

	var element = this;
	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-agree" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
			html += '      </div>';
			html += '      <div class="modal-body">' + data + '</div>';
			html += '    </div>';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);

			$('#modal-agree').modal('show');
		}
	});
});

/* fast login*/
function fastLogin(){
	$('#modal-login').remove();
	var element = this;
	html  = '<div id="modal-login" class="modal">';
	html += '  <div class="modal-dialog"  style="width:350px;">';
	html += '    <div class="modal-content">';
	html += '      <div class="modal-header">';
	html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
	html += '        <h4 class="modal-title">登录</h4>';
	html += '      </div>';
	html += '      <div class="modal-body">';
	html += '	   		<form action="/index.php?route=account/login" method="post" enctype="multipart/form-data">';
	html += '	   			<div class="form-group">';
	html += '	   				<label class="control-label" for="input-email">E-Mail 地址</label>';
	html += '    				<input type="text" name="email" value="" placeholder="E-Mail 地址" id="input-email" class="form-control">';
	html += '  				</div>';
	html += '				<div class="form-group">';
	html += '	   				<label class="control-label" for="input-password">密码</label>';
	html += '    				<input type="password" name="password" value="" placeholder="密码" id="input-password" class="form-control">';
	html += '  					<p style="margin-top:10px;"><a href="/index.php?route=account/forgotten">忘记密码？</a></p>';
	html += '				</div>';
	html += '	   			<input type="submit" value="登录" id="submit-login-btn" class="btn btn-primary">';
	html += '    		</form>';
	html += '	   </div>';
	html += '    </div>';
	html += '  </div>';
	html += '</div>';
	$('body').append(html);
	$('#modal-login').modal('show');
}
// Autocomplete */
(function($) {
	$.fn.autocomplete = function(option) {
		return this.each(function() {
			this.timer = null;
			this.items = new Array();
	
			$.extend(this, option);
	
			$(this).attr('autocomplete', 'off');
			
			// Focus
			$(this).on('focus', function() {
				this.request();
			});
			
			// Blur
			$(this).on('blur', function() {
				setTimeout(function(object) {
					object.hide();
				}, 200, this);				
			});
			
			// Keydown
			$(this).on('keydown', function(event) {
				switch(event.keyCode) {
					case 27: // escape
						this.hide();
						break;
					default:
						this.request();
						break;
				}				
			});
			
			// Click
			this.click = function(event) {
				event.preventDefault();
	
				value = $(event.target).parent().attr('data-value');
	
				if (value && this.items[value]) {
					this.select(this.items[value]);
				}
			}
			
			// Show
			this.show = function() {
				var pos = $(this).position();
	
				$(this).siblings('ul.dropdown-menu').css({
					top: pos.top + $(this).outerHeight(),
					left: pos.left
				});
	
				$(this).siblings('ul.dropdown-menu').show();
			}
			
			// Hide
			this.hide = function() {
				$(this).siblings('ul.dropdown-menu').hide();
			}		
			
			// Request
			this.request = function() {
				clearTimeout(this.timer);
		
				this.timer = setTimeout(function(object) {
					object.source($(object).val(), $.proxy(object.response, object));
				}, 200, this);
			}
			
			// Response
			this.response = function(json) {
				html = '';
	
				if (json.length) {
					for (i = 0; i < json.length; i++) {
						this.items[json[i]['value']] = json[i];
					}
	
					for (i = 0; i < json.length; i++) {
						if (!json[i]['category']) {
							html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
						}
					}
	
					// Get all the ones with a categories
					var category = new Array();
	
					for (i = 0; i < json.length; i++) {
						if (json[i]['category']) {
							if (!category[json[i]['category']]) {
								category[json[i]['category']] = new Array();
								category[json[i]['category']]['name'] = json[i]['category'];
								category[json[i]['category']]['item'] = new Array();
							}
	
							category[json[i]['category']]['item'].push(json[i]);
						}
					}
	
					for (i in category) {
						html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';
	
						for (j = 0; j < category[i]['item'].length; j++) {
							html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
						}
					}
				}
	
				if (html) {
					this.show();
				} else {
					this.hide();
				}
	
				$(this).siblings('ul.dropdown-menu').html(html);
			}
			
			$(this).after('<ul class="dropdown-menu"></ul>');
			$(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));	
			
		});
	}
})(window.jQuery);