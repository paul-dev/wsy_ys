<?php
class ControllerAccountOrder extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/order', $url, 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_empty'] = $this->language->get('text_empty');

		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_shipping'] = $this->language->get('column_shipping');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_total'] = $this->language->get('column_total');

		$data['button_view'] = $this->language->get('button_view');
        $data['button_return'] = $this->language->get('button_return');
        $data['button_continue'] = $this->language->get('button_continue');

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['orders'] = array();

		$this->load->model('account/order');
        $this->load->model('tool/upload');

        $this->load->model('tool/image');
        $this->load->model('setting/setting');

		$order_total = $this->model_account_order->getTotalOrders();

		$results = $this->model_account_order->getOrders(($page - 1) * 10, 10);

		foreach ($results as $result) {
			$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
			$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

            $order_products = array();
            $products = $this->model_account_order->getOrderProducts($result['order_id']);

            foreach ($products as $product) {
                $option_data = array();

                $options = $this->model_account_order->getOrderOptions($product['order_id'], $product['order_product_id']);

                foreach ($options as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {
                        $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                        if ($upload_info) {
                            $value = $upload_info['name'];
                        } else {
                            $value = '';
                        }
                    }

                    $option_data[] = array(
                        'name'  => $option['name'],
                        'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                    );
                }

                if (!empty($product['image']) && is_file(DIR_IMAGE . $product['image'])) {
                    $thumb = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
                } else {
                    $thumb = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
                }

                $actions = array();
                $actions[] = array(
                    'name' => $this->language->get('button_view'),
                    'href' => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], 'SSL'),
                );

                if (in_array($result['order_status_id'], array(2,3,15)) && !$product['return_id']) {
                    $actions[] = array(
                        'name' => $this->language->get('button_return'),
                        'href' => $this->url->link('account/return/add', 'order_id=' . $product['order_id'] . '&product_id=' . $product['order_product_id'], 'SSL')
                    );
                }

                switch ($result['order_status_id']) {
                    case 5 :
                        if (!$product['review_id']) {
                            $actions[] = array(
                                'name' => $this->language->get('button_review'),
                                'href' => $this->url->link('account/order/review', 'order_id=' . $product['order_id'] . '&product_id=' . $product['order_product_id'], 'SSL')
                            );
                        }
                        break;
                }

                $order_products[] = array(
                    'name'     => $product['name'],
                    'thumb'    => $thumb,
                    'model'    => $product['model'],
                    'option'   => $option_data,
                    'quantity' => $product['quantity'],
                    'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $result['currency_code'], $result['currency_value']),
                    'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $result['currency_code'], $result['currency_value']),
                    'href'     => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                    'return_status' => $product['return_status'],
                    'return'   => $product['return_id'] ? $this->url->link('account/return/info', 'return_id='.$product['return_id'], 'SSL') : '',
                    'actions'  => $actions
                );
            }

            //Shop info
            $store_id = $result['store_id'];

            $shop_info = $this->model_setting_setting->getSetting('config', $store_id);

            $shop_info['shop_id'] = $store_id;

            if (!empty($shop_info['config_logo']) && is_file(DIR_IMAGE . $shop_info['config_logo'])) {
                $shop_info['shop_logo'] = $this->model_tool_image->resize($shop_info['config_logo'], 50, 50);
            } else {
                $shop_info['shop_logo'] = $this->model_tool_image->resize('no_image.png', 50, 50);
            }

            // Shop home url.
            $shop_info['shop_url'] = HTTP_SERVER . $shop_info['config_key'];

            $order_action = array();
            switch ($result['order_status_id']) {
                case 1 :
                    if (!empty($result['payment_url'])) {
                        $order_action[] = array(
                            'name' => '去付款',
                            'href' => $result['payment_url'],
                            'target' => '_blank'
                        );
                    }
                    $order_action[] = array(
                        'name' => $this->language->get('action_cancel'),
                        'confirm' => $this->language->get('text_confirm_cancel'),
                        'href' => $this->url->link('account/order/cancel', 'order_id=' . $result['order_id'], 'SSL')
                    );
                    break;
                case 3 :
                    $order_action[] = array(
                        'name' => $this->language->get('action_confirm'),
                        'confirm' => $this->language->get('text_confirm_confirm'),
                        'href' => $this->url->link('account/order/confirm', 'order_id=' . $result['order_id'], 'SSL')
                    );
                    break;
            }

			$data['orders'][] = array(
				'order_id'   => $result['order_id'],
                'parent_id'  => $result['parent_id'],
				'name'       => $result['fullname'],
                'shipping_name' => $result['shipping_fullname'],
				'status'     => $result['status'],
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
				'shop_info'  => $shop_info,
                'products'   => $order_products,
                'products_amount'   => ($product_total + $voucher_total),
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'href'       => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], 'SSL'),
                'action'     => $order_action
			);
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/order', 'page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($order_total - 10)) ? $order_total : ((($page - 1) * 10) + 10), $order_total, ceil($order_total / 10));

		$data['continue'] = $this->url->link('account/account', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/order_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/order_list.tpl', $data));
		}
	}

	public function info() {
		$this->load->language('account/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->model('account/order');

		$order_info = $this->model_account_order->getOrder($order_id);

		if ($order_info) {
			$this->document->setTitle($this->language->get('text_order'));

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', 'SSL')
			);

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/order', $url, 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_order'),
				'href' => $this->url->link('account/order/info', 'order_id=' . $this->request->get['order_id'] . $url, 'SSL')
			);

			$data['heading_title'] = $this->language->get('text_order');

			$data['text_order_detail'] = $this->language->get('text_order_detail');
			$data['text_invoice_no'] = $this->language->get('text_invoice_no');
			$data['text_order_id'] = $this->language->get('text_order_id');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$data['text_shipping_address'] = $this->language->get('text_shipping_address');
			$data['text_payment_method'] = $this->language->get('text_payment_method');
			$data['text_payment_address'] = $this->language->get('text_payment_address');
			$data['text_history'] = $this->language->get('text_history');
			$data['text_comment'] = $this->language->get('text_comment');

			$data['column_name'] = $this->language->get('column_name');
			$data['column_model'] = $this->language->get('column_model');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total'] = $this->language->get('column_total');
			$data['column_action'] = $this->language->get('column_action');
			$data['column_date_added'] = $this->language->get('column_date_added');
			$data['column_status'] = $this->language->get('column_status');
			$data['column_comment'] = $this->language->get('column_comment');

			$data['button_reorder'] = $this->language->get('button_reorder');
			$data['button_return'] = $this->language->get('button_return');
			$data['button_continue'] = $this->language->get('button_continue');

			if (isset($this->session->data['error'])) {
				$data['error_warning'] = $this->session->data['error'];

				unset($this->session->data['error']);
			} else {
				$data['error_warning'] = '';
			}

			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}

			if ($order_info['invoice_no']) {
				$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}

			$data['order_id'] = $this->request->get['order_id'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

			if ($order_info['payment_address_format']) {
				$format = $order_info['payment_address_format'];
			} else {
				$format = '{fullname}{telephone}' . "\n" . '{company}' . "\n" . '{address}' . "\n" . '{area}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{fullname}',
                '{telephone}',
				'{company}',
				'{address}',
                '{area}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'fullname' => $order_info['payment_fullname'],
                'telephone' => isset($order_info['payment_custom_field'][1]) ? ', '.$order_info['payment_custom_field'][1] : '',
				'company'   => $order_info['payment_company'],
				'address' => $order_info['payment_address'],
                'area'      => $order_info['payment_area'],
                'city'      => $order_info['payment_city'],
				'postcode'  => $order_info['payment_postcode'],
				'zone'      => $order_info['payment_zone'],
				'zone_code' => $order_info['payment_zone_code'],
				'country'   => $order_info['payment_country']
			);

			$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$data['payment_method'] = $order_info['payment_method'];

			if ($order_info['shipping_address_format']) {
				$format = $order_info['shipping_address_format'];
			} else {
				$format = '{fullname}{telephone}' . "\n" . '{company}' . "\n" . '{address}' . "\n" . '{area}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{fullname}',
                '{telephone}',
				'{company}',
				'{address}',
                '{area}',
                '{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'fullname' => $order_info['shipping_fullname'],
                'telephone' => isset($order_info['shipping_custom_field'][1]) ? ', '.$order_info['shipping_custom_field'][1] : '',
                'company'   => $order_info['shipping_company'],
				'address' => $order_info['shipping_address'],
                'area'      => $order_info['shipping_area'],
                'city'      => $order_info['shipping_city'],
				'postcode'  => $order_info['shipping_postcode'],
				'zone'      => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
				'country'   => $order_info['shipping_country']
			);

			$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$data['shipping_method'] = $order_info['shipping_method'];

			$this->load->model('catalog/product');
			$this->load->model('tool/upload');

			// Products
			$data['products'] = array();

            $_shopOrderIds = array();
            if ($_shopOrders = $this->model_account_order->getShopOrders($this->request->get['order_id'])) {
                foreach ($_shopOrders as $_order) {
                    $_shopOrderIds[] = $_order['order_id'];
                }
            }

            if ($_shopOrderIds) {
                $products = $this->model_account_order->getShopOrderProducts($_shopOrderIds);
            } else {
                $products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);
            }

			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_account_order->getOrderOptions($product['order_id'], $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}

				$product_info = $this->model_catalog_product->getProduct($product['product_id']);

				if ($product_info) {
					$reorder = $this->url->link('account/order/reorder', 'order_id=' . $product['order_id'] . '&order_product_id=' . $product['order_product_id'], 'SSL');
				} else {
					$reorder = '';
				}

				$data['products'][] = array(
					'name'     => $product['name'],
					'model'    => $product['model'],
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'reorder'  => $reorder,
                    'href_return' => $product['return_id'] ? $this->url->link('account/return/info', 'return_id='.$product['return_id'], 'SSL') : '',
					'return'   => in_array($order_info['order_status_id'], array(2,3,15)) && !$product['return_id'] ? $this->url->link('account/return/add', 'order_id=' . $product['order_id'] . '&product_id=' . $product['order_product_id'], 'SSL') : ''
				);
			}

			// Voucher
			$data['vouchers'] = array();

			$vouchers = $this->model_account_order->getOrderVouchers($this->request->get['order_id']);

			foreach ($vouchers as $voucher) {
				$data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			// Totals
			$data['totals'] = array();

			$totals = $this->model_account_order->getOrderTotals($this->request->get['order_id']);

			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
				);
			}

			$data['comment'] = nl2br($order_info['comment']);

			// History
			$data['histories'] = array();

			$results = $this->model_account_order->getOrderHistories($this->request->get['order_id']);

			foreach ($results as $result) {
                $comment = $result['comment'];
                if (preg_match('/^a:\d{1}:\{.*/i', $result['comment'])) {
                    $comment = unserialize($result['comment']);
                }
                if (is_array($comment)) {
                    $comment = join("\n", $comment);
                } else {
                    $comment = $result['comment'];
                }

                $data['histories'][] = array(
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'status'     => $result['status'],
					'comment'    => $result['notify'] ? nl2br($comment) : ''
				);
			}

            $data['continue'] = $this->url->link('account/order', '', 'SSL');

            if ($order_info['order_status_id'] == '1' && !empty($order_info['payment_url'])) {
                $data['continue_pay'] = $order_info['payment_url'];
            } else {
                $data['continue_pay'] = '';
            }

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_info.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/order_info.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/account/order_info.tpl', $data));
			}
		} else {
			$this->document->setTitle($this->language->get('text_order'));

			$data['heading_title'] = $this->language->get('text_order');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/order', '', 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_order'),
				'href' => $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL')
			);

			$data['continue'] = $this->url->link('account/order', '', 'SSL');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
	}

	public function reorder() {
		$this->load->language('account/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('account/order');

		$order_info = $this->model_account_order->getOrder($order_id);

		if ($order_info) {
			if (isset($this->request->get['order_product_id'])) {
				$order_product_id = $this->request->get['order_product_id'];
			} else {
				$order_product_id = 0;
			}

			$order_product_info = $this->model_account_order->getOrderProduct($order_id, $order_product_id);

			if ($order_product_info) {
				$this->load->model('catalog/product');

				$product_info = $this->model_catalog_product->getProduct($order_product_info['product_id']);

				if ($product_info) {
					$option_data = array();

					$order_options = $this->model_account_order->getOrderOptions($order_product_info['order_id'], $order_product_id);

					foreach ($order_options as $order_option) {
						if ($order_option['type'] == 'select' || $order_option['type'] == 'radio' || $order_option['type'] == 'image') {
							$option_data[$order_option['product_option_id']] = $order_option['product_option_value_id'];
						} elseif ($order_option['type'] == 'checkbox') {
							$option_data[$order_option['product_option_id']][] = $order_option['product_option_value_id'];
						} elseif ($order_option['type'] == 'text' || $order_option['type'] == 'textarea' || $order_option['type'] == 'date' || $order_option['type'] == 'datetime' || $order_option['type'] == 'time') {
							$option_data[$order_option['product_option_id']] = $order_option['value'];
						} elseif ($order_option['type'] == 'file') {
							$option_data[$order_option['product_option_id']] = $this->encryption->encrypt($order_option['value']);
						}
					}

					$this->cart->add($order_product_info['product_id'], $order_product_info['quantity'], $option_data);

					$this->session->data['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $product_info['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
				} else {
					$this->session->data['error'] = sprintf($this->language->get('error_reorder'), $order_product_info['name']);
				}
			}
		}

		$this->response->redirect($this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL'));
	}

    public function cancel() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->load->language('account/order');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $this->load->model('account/order');

        $order_info = $this->model_account_order->getOrder($order_id);
        if ($order_info) {
            if ($order_info['order_status_id'] == 1) {
                $this->load->model('checkout/order');
                $this->model_checkout_order->addOrderHistory($order_id, 7, $this->language->get('text_cancel'), true);
                $this->session->data['success'] = sprintf($this->language->get('text_cancel_success'), '#'.$order_id);
            } else {
                $this->session->data['error'] = sprintf($this->language->get('error_order_status'), '#'.$order_id);
            }
        } else {
            $this->session->data['error'] = sprintf($this->language->get('error_order_not_exist'), '#'.$order_id);
        }

        $this->response->redirect($this->url->link('account/order', '', 'SSL'));
    }

    public function confirm() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->load->language('account/order');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $this->load->model('account/order');

        $order_info = $this->model_account_order->getOrder($order_id);
        if ($order_info) {
            if ($order_info['order_status_id'] == 3) {
                $this->load->model('checkout/order');
                $this->model_checkout_order->addOrderHistory($order_id, 5, $this->language->get('text_confirm'), true);
                $this->session->data['success'] = sprintf($this->language->get('text_confirm_success'), '#'.$order_id);
            } else {
                $this->session->data['error'] = sprintf($this->language->get('error_order_status'), '#'.$order_id);
            }
        } else {
            $this->session->data['error'] = sprintf($this->language->get('error_order_not_exist'), '#'.$order_id);
        }

        $this->response->redirect($this->url->link('account/order', '', 'SSL'));
    }

    public function review() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->load->language('account/order');

        $this->load->model('account/order');

        if (isset($this->request->get['order_id'])) {
            $order_id = (int)$this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $order_info = $this->model_account_order->getOrder($order_id);

        if (empty($order_info) || (int)$order_info['order_status_id'] !== 5 ) {
            $this->session->data['error'] = sprintf($this->language->get('error_order_status'), '#'.$order_id);
            $this->response->redirect($this->url->link('account/order', '', 'SSL'));
            return;
        }

        $this->load->model('catalog/product');

        if (isset($this->request->get['product_id'])) { // Order product id
            $product_id = (int)$this->request->get['product_id'];
        } else {
            $product_id = 0;
        }

        $order_product = $this->model_account_order->getOrderProductBy($order_id, $product_id);

        if (empty($order_product)) {
            $this->session->data['error'] = sprintf($this->language->get('error_not_exist'), $order_product['name']);
            $this->response->redirect($this->url->link('account/order', '', 'SSL'));
            return;
        }

        if ((int)$order_product['review_id'] > 0 ) {
            $this->session->data['error'] = sprintf($this->language->get('error_product_reviewed'), $order_product['name']);
            $this->response->redirect($this->url->link('account/order', '', 'SSL'));
            return;
        }

        //$product_info = $this->model_catalog_product->getProduct($order_product['product_id']);

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            unset($this->session->data['captcha']);

            $this->request->post['store_id'] = $order_info['store_id'];
            $this->request->post['order_id'] = $order_id;
            $this->request->post['order_product_id'] = $product_id;
            $this->request->post['name'] = $this->customer->getFullName();
            $this->request->post['rating'] = $this->request->post['rating_level'];

            $this->load->model('catalog/review');
            $this->model_catalog_review->addReview($order_product['product_id'], $this->request->post);

            $this->session->data['success'] = sprintf($this->language->get('text_review_success'), $order_product['name']);

            $this->response->redirect($this->url->link('account/order', '', 'SSL'));
        }

        $this->document->setTitle($this->language->get('review_title'));
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('account/order', '', 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('review_title'),
            'href' => 'javascript:void(0);'
        );

        $data['heading_title'] = $this->language->get('review_title');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['rating_level'])) {
            $data['error_rating_level'] = $this->error['rating_level'];
        } else {
            $data['error_rating_level'] = '';
        }

        if (isset($this->error['rating_product'])) {
            $data['error_rating_product'] = $this->error['rating_product'];
        } else {
            $data['error_rating_product'] = '';
        }

        if (isset($this->error['rating_quality'])) {
            $data['error_rating_quality'] = $this->error['rating_quality'];
        } else {
            $data['error_rating_quality'] = '';
        }

        if (isset($this->error['rating_service'])) {
            $data['error_rating_service'] = $this->error['rating_service'];
        } else {
            $data['error_rating_service'] = '';
        }

        if (isset($this->error['rating_deliver'])) {
            $data['error_rating_deliver'] = $this->error['rating_deliver'];
        } else {
            $data['error_rating_deliver'] = '';
        }

        if (isset($this->error['text'])) {
            $data['error_text'] = $this->error['text'];
        } else {
            $data['error_text'] = '';
        }

        if (isset($this->error['captcha'])) {
            $data['error_captcha'] = $this->error['captcha'];
        } else {
            $data['error_captcha'] = '';
        }

        $data['action'] = $this->url->link('account/order/review', 'order_id='.$order_id.'&product_id='.$product_id, 'SSL');

        if (isset($this->request->post['comment'])) {
            $data['comment'] = $this->request->post['comment'];
        } else {
            $data['comment'] = '';
        }

        $data['back'] = $this->url->link('account/account', '', 'SSL');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/review_form.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/review_form.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/review_form.tpl', $data));
        }
    }

    protected function validate() {
        if (!isset($this->request->post['rating_level']) || !in_array((int)$this->request->post['rating_level'], array(1,3,5))) {
            $this->error['rating_level'] = $this->language->get('error_level');
        }

        if (!isset($this->request->post['rating_product']) || $this->request->post['rating_product'] > 5) {
            $this->error['rating_product'] = $this->language->get('error_rating');
        }

        if (!isset($this->request->post['rating_quality']) || $this->request->post['rating_quality'] > 5) {
            $this->error['rating_quality'] = $this->language->get('error_rating');
        }

        if (!isset($this->request->post['rating_service']) || $this->request->post['rating_service'] > 5) {
            $this->error['rating_service'] = $this->language->get('error_rating');
        }

        if (!isset($this->request->post['rating_deliver']) || $this->request->post['rating_deliver'] > 5) {
            $this->error['rating_deliver'] = $this->language->get('error_rating');
        }

        if (utf8_strlen($this->request->post['text']) > 1000) {
            $this->error['text'] = $this->language->get('error_text');
        }

        if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
            $this->error['captcha'] = $this->language->get('error_captcha');
        }

        return !$this->error;
    }
}