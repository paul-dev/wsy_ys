<?php
class ControllerCheckoutCheckout extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$this->response->redirect($this->url->link('checkout/cart'));
		}

        $this->load->language('checkout/checkout');

        $data['column_image'] = $this->language->get('column_image');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_total'] = $this->language->get('column_total');

        $this->load->model('tool/image');
        $this->load->model('tool/upload');

        $data['products'] = array();

        if ($this->config->get('config_cart_weight')) {
            $data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
        } else {
            $data['weight'] = '';
        }

        $cart_shops = array();
		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$this->response->redirect($this->url->link('checkout/cart'));
			}

            if ($product['image']) {
                $image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
            } else {
                $image = '';
            }

            $option_data = array();

            foreach ($product['option'] as $option) {
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

            // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $price = false;
            }

            // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
            } else {
                $total = false;
            }

            $recurring = '';

            if ($product['recurring']) {
                $frequencies = array(
                    'day'        => $this->language->get('text_day'),
                    'week'       => $this->language->get('text_week'),
                    'semi_month' => $this->language->get('text_semi_month'),
                    'month'      => $this->language->get('text_month'),
                    'year'       => $this->language->get('text_year'),
                );

                if ($product['recurring']['trial']) {
                    $recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
                }

                if ($product['recurring']['duration']) {
                    $recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                } else {
                    $recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                }
            }

            $data['products'][$product['shop_id']][] = array(
                'key'       => $product['key'],
                'shop_id'         => $product['shop_id'],
                'shop_name'       => $product['shop_name'],
                'shop_url'        => $product['shop_url'],
                'shop_key'        => $product['shop_key'],
                'shop_href'       => HTTP_SERVER . $product['shop_key'],// Shop home url.
                'product_id'      => $product['product_id'],
                'thumb'     => $image,
                'name'      => $product['name'],
                'model'     => $product['model'],
                'option'    => $option_data,
                'recurring' => $recurring,
                'quantity'  => $product['quantity'],
                'stock'     => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                'reward'    => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
                'price'     => $price,
                'total'     => $total,
                'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
            );

            $cart_shops[$product['shop_id']] = $product['shop_id'];
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		// Required by klarna
		if ($this->config->get('klarna_account') || $this->config->get('klarna_invoice')) {
			$this->document->addScript('http://cdn.klarna.com/public/kitt/toc/v1.0/js/klarna.terms.min.js');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_cart'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('checkout/checkout', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_checkout_option'] = $this->language->get('text_checkout_option');
		$data['text_checkout_account'] = $this->language->get('text_checkout_account');
		$data['text_checkout_payment_address'] = $this->language->get('text_checkout_payment_address');
		$data['text_checkout_shipping_address'] = $this->language->get('text_checkout_shipping_address');
		$data['text_checkout_shipping_method'] = $this->language->get('text_checkout_shipping_method');
		$data['text_checkout_payment_method'] = $this->language->get('text_checkout_payment_method');
        $data['text_checkout_confirm'] = $this->language->get('text_checkout_confirm');
        $data['error_shipping_method'] = $this->language->get('error_shipping_method');

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$data['error_warning'] = '';
		}

		$data['logged'] = $this->customer->isLogged();

		if (isset($this->session->data['account'])) {
			$data['account'] = $this->session->data['account'];
		} else {
			$data['account'] = '';
		}

		$data['shipping_required'] = $this->cart->hasShipping();

        $this->load->model('extension/extension');

        // Default Shipping Address
        $this->load->model('account/address');

        $data['addresses'] = $this->model_account_address->getAddresses();

        if (isset($this->session->data['shipping_address']['address_id'])) {
            $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->session->data['shipping_address']['address_id']);
        } else {
            $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
        }

        unset($this->session->data['shipping_method']);
        unset($this->session->data['shipping_methods']);

        unset($this->session->data['shipping_types']);

        // Set payment address as shipping address
        $this->session->data['payment_address'] = $this->session->data['shipping_address'];
        unset($this->session->data['payment_method']);
        unset($this->session->data['payment_methods']);

        unset($this->session->data['shop_shipping_method']);
        unset($this->session->data['shop_shipping_methods']);

        unset($this->session->data['coupons']);

        $data['shipping_types'] = array();
        $data['shipping_type'] = '';
        if (count($cart_shops) > 1) {
            $data['shipping_types'] = array(
                '2' => '平台代发'
            );
            $data['shipping_type'] = array(
                'code' => '2',
                'name' => '平台代发'
            );
        } else {
            $data['shipping_types'] = array(
                '1' => '档口直发',
                '2' => '平台代发',
            );
            $data['shipping_type'] = array(
                'code' => '1',
                'name' => '档口直发'
            );
        }
        $this->session->data['shipping_types'] = $data['shipping_types'];
        $this->session->data['shipping_type'] = $data['shipping_type'];

        $data['shipping_methods'] = array();
        $data['shop_shipping_required'] = array();
        if (isset($this->session->data['shipping_address'])) {
            // Shipping Methods
            $results = $this->model_extension_extension->getExtensions('shipping');

            if ($this->session->data['shipping_type']['code'] == '1') {
                $this->session->data['shipping_method'] = array(
                    'code'         => 'main.main',
                    'title'        => '运费合计',
                    'cost'         => 0.00,
                    'tax_class_id' => 0,
                    'text'         => $this->currency->format(0.00)
                );

                foreach ($cart_shops as $shop_id) {
                    $data['shop_shipping_required'][$shop_id] = $this->cart->hasShipping($shop_id);
                    $method_data = array();
                    foreach ($results as $result) {
                        if ($this->config->get($result['code'] . '_status')) {
                            $this->load->model('shipping/' . $result['code']);

                            $quote = $this->{'model_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address'], $shop_id);

                            if ($quote) {
                                $method_data[$result['code']] = array(
                                    'title'      => $quote['title'],
                                    'quote'      => $quote['quote'],
                                    'sort_order' => $quote['sort_order'],
                                    'error'      => $quote['error']
                                );
                            }
                        }
                    }

                    $sort_order = array();

                    foreach ($method_data as $key => $value) {
                        $sort_order[$key] = $value['sort_order'];
                    }

                    array_multisort($sort_order, SORT_ASC, $method_data);

                    if (empty($method_data)) {
                        $method_data['none'] = array(
                            'title'      => '无',
                            'quote'      => array(
                                'none' => array(
                                    'code'         => 'none.none',
                                    'title'        => '无',
                                    'cost'         => 0.00,
                                    'tax_class_id' => 0,
                                    'text'         => $this->currency->format(0.00)
                                )
                            ),
                            'sort_order' => 1,
                            'error'      => false
                        );
                    }

                    $data['shipping_methods'][$shop_id] = $method_data;
                    $this->session->data['shop_shipping_methods'][$shop_id] = $method_data;
                    if ($method_data) {
                        switch ($data['shipping_type']['code']) {
                            case '2':
                                $default = array(
                                    'title'      => '平台代发',
                                    'quote'      => array(
                                        'none' => array(
                                            'code'         => 'sys.agent',
                                            'title'        => '平台代发',
                                            'cost'         => 0.00,
                                            'tax_class_id' => 0,
                                            'text'         => $this->currency->format(0.00)
                                        )
                                    ),
                                    'sort_order' => 1,
                                    'error'      => false
                                );
                                break;
                            case '3':
                                $default = array(
                                    'title'      => '自提',
                                    'quote'      => array(
                                        'none' => array(
                                            'code'         => 'self.self',
                                            'title'        => '自提',
                                            'cost'         => 0.00,
                                            'tax_class_id' => 0,
                                            'text'         => $this->currency->format(0.00)
                                        )
                                    ),
                                    'sort_order' => 1,
                                    'error'      => false
                                );
                                break;
                            default :
                                $default = array_shift($method_data);
                                break;
                        }

                        if ($default) {
                            $method = array_shift($default['quote']);
                            $this->session->data['shop_shipping_method'][$shop_id] = $method;
                            $this->session->data['shipping_method']['cost'] += $method['cost'];
                            $this->session->data['shipping_method']['text'] = $this->currency->format($this->session->data['shipping_method']['cost']);
                        }
                    }
                }
            } else {
                foreach ($cart_shops as $shop_id => $val) {
                    $data['shop_shipping_required'][$shop_id] = $this->cart->hasShipping($shop_id);
                    $method_data = array();
                    $method_data['sys'] = array(
                        'title'      => '平台代发',
                        'quote'      => array(
                            'none' => array(
                                'code'         => 'sys.agent',
                                'title'        => '平台代发',
                                'cost'         => 0.00,
                                'tax_class_id' => 0,
                                'text'         => $this->currency->format(0.00)
                            )
                        ),
                        'sort_order' => 1,
                        'error'      => false
                    );
                    $data['shipping_methods'][$shop_id] = $method_data;
                    $this->session->data['shop_shipping_methods'][$shop_id] = $method_data;
                    if ($method_data) {
                        $default = array_shift($method_data);
                        if ($default) {
                            $method = array_shift($default['quote']);
                            $this->session->data['shop_shipping_method'][$shop_id] = $method;
                            //$this->session->data['shipping_method']['cost'] += $method['cost'];
                            //$this->session->data['shipping_method']['text'] = $this->currency->format($this->session->data['shipping_method']['cost']);
                        }
                    }
                }

                foreach ($results as $result) {
                    if ($this->config->get($result['code'] . '_status')) {
                        $this->load->model('shipping/' . $result['code']);

                        $quote = $this->{'model_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);

                        if ($quote) {
                            $method_data[$result['code']] = array(
                                'title'      => $quote['title'],
                                'quote'      => $quote['quote'],
                                'sort_order' => $quote['sort_order'],
                                'error'      => $quote['error']
                            );
                        }
                    }
                }

                $sort_order = array();

                foreach ($method_data as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $method_data);
                $this->session->data['shipping_methods'][0] = $method_data;
                if ($this->cart->hasShipping() && $method_data) {
                    $default = array_shift($method_data);
                    if ($default) {
                        $method = array_shift($default['quote']);
                        $this->session->data['shipping_method'] = $method;
                    }
                }
            }
        }

        // Totals
        $total_data = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();

        $sort_order = array();

        $results = $this->model_extension_extension->getExtensions('total');

        foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
        }

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('total/' . $result['code']);

                $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
            }
        }

        $sort_order = array();

        foreach ($total_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $total_data);

        $data['totals'] = array();

        foreach ($total_data as $total) {
            if (!array_key_exists('shop_id', $total)) continue;
            $data['totals'][$total['shop_id']][] = array(
                'title' => $total['title'],
                'text'  => $this->currency->format($total['value'])
            );
        }

        $store_ids = array_keys($data['totals']);
        $store_ids[] = 0;
        $this->load->model('checkout/coupon');
        $data['coupons'] = $this->model_checkout_coupon->getCoupons($store_ids);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/checkout.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/checkout.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/checkout/checkout.tpl', $data));
		}
	}

	public function country() {
		$json = array();

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

    public function zone() {
        $json = array();

        $this->load->model('localisation/zone');

        $zone_info = $this->model_localisation_zone->getZone($this->request->get['zone_id']);

        if ($zone_info) {
            $json = array(
                'zone_id'           => $zone_info['zone_id'],
                'name'              => $zone_info['name'],
                'city'              => $this->model_localisation_zone->getCitysByZoneCode($zone_info['code']),
                'status'            => $zone_info['status']
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function city() {
        $json = array();

        $this->load->model('localisation/zone');

        $city_info = $this->model_localisation_zone->getCity($this->request->get['city_id']);

        if ($city_info) {
            $json = array(
                'city_id'           => $city_info['id'],
                'name'              => $city_info['name'],
                'area'              => $this->model_localisation_zone->getAreasByCityCode($city_info['code']),
                'status'            => $city_info['status']
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

	public function customfield() {
		$json = array();

		$this->load->model('account/custom_field');

		// Customer Group
		if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->get['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

		foreach ($custom_fields as $custom_field) {
			$json[] = array(
				'custom_field_id' => $custom_field['custom_field_id'],
				'required'        => $custom_field['required']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

    public function total() {
        $this->load->model('extension/extension');

        // Totals
        $total_data = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();

        $sort_order = array();

        $results = $this->model_extension_extension->getExtensions('total');

        foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
        }

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('total/' . $result['code']);

                $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
            }
        }

        $sort_order = array();

        foreach ($total_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $total_data);

        $data['totals'] = array();
        $_html = '<label style="float: right; width: 25%;">';

        foreach ($total_data as $total) {
            if (!array_key_exists('shop_id', $total)) continue;
            if (isset($this->request->get['shop_id'])) {
                if ($total['shop_id'] <> $this->request->get['shop_id']) continue;
            }
            $data['totals'][$total['shop_id']][] = array(
                'title' => $total['title'],
                'text'  => $this->currency->format($total['value'])
            );
            $_html .= '<span style="float: right;">' .$total['title'] . ': ' . $this->currency->format($total['value']) . '</span><br/>';
        }

        $_html .= '</label>';

        $this->response->setOutput($_html);
    }

    public function shipping() {
        $this->load->model('extension/extension');

        $data['shipping_methods'] = array();
        if (isset($this->session->data['shipping_address'])) {
            // Shipping Methods
            $cart_shops = $this->cart->getShopSubTotal();

            if ($this->session->data['shipping_type']['code'] == '1') {
                $this->session->data['shipping_method'] = array(
                    'code'         => 'main.main',
                    'title'        => '运费合计',
                    'cost'         => 0.00,
                    'tax_class_id' => 0,
                    'text'         => $this->currency->format(0.00)
                );

                $results = $this->model_extension_extension->getExtensions('shipping');

                foreach ($cart_shops as $shop_id => $val) {
                    $method_data = array();
                    foreach ($results as $result) {
                        if ($this->config->get($result['code'] . '_status')) {
                            $this->load->model('shipping/' . $result['code']);

                            $quote = $this->{'model_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address'], $shop_id);

                            if ($quote) {
                                $method_data[$result['code']] = array(
                                    'title'      => $quote['title'],
                                    'quote'      => $quote['quote'],
                                    'sort_order' => $quote['sort_order'],
                                    'error'      => $quote['error']
                                );
                            }
                        }
                    }

                    $sort_order = array();

                    foreach ($method_data as $key => $value) {
                        $sort_order[$key] = $value['sort_order'];
                    }

                    array_multisort($sort_order, SORT_ASC, $method_data);

                    if (empty($method_data)) {
                        $method_data['none'] = array(
                            'title'      => '无',
                            'quote'      => array(
                                'none' => array(
                                    'code'         => 'none.none',
                                    'title'        => '无',
                                    'cost'         => 0.00,
                                    'tax_class_id' => 0,
                                    'text'         => $this->currency->format(0.00)
                                )
                            ),
                            'sort_order' => 1,
                            'error'      => false
                        );
                    }

                    $data['shipping_methods'][$shop_id] = $method_data;
                    $this->session->data['shop_shipping_methods'][$shop_id] = $method_data;
                    if ($method_data) {
                        $default = array_shift($method_data);
                        if ($default) {
                            $method = array_shift($default['quote']);
                            $this->session->data['shop_shipping_method'][$shop_id] = $method;
                            $this->session->data['shipping_method']['cost'] += $method['cost'];
                            $this->session->data['shipping_method']['text'] = $this->currency->format($this->session->data['shipping_method']['cost']);
                        }
                    }
                }
            } else {
                foreach ($cart_shops as $shop_id => $val) {
                    $method_data = array();
                    $method_data['sys'] = array(
                        'title'      => '平台代发',
                        'quote'      => array(
                            'sys' => array(
                                'code'         => 'sys.agent',
                                'title'        => '平台代发',
                                'cost'         => 0.00,
                                'tax_class_id' => 0,
                                'text'         => $this->currency->format(0.00)
                            )
                        ),
                        'sort_order' => 1,
                        'error'      => false
                    );
                    $data['shipping_methods'][$shop_id] = $method_data;
                    $this->session->data['shop_shipping_methods'][$shop_id] = $method_data;
                    if ($method_data) {
                        $default = array_shift($method_data);
                        if ($default) {
                            $method = array_shift($default['quote']);
                            $this->session->data['shop_shipping_method'][$shop_id] = $method;
                            //$this->session->data['shipping_method']['cost'] += $method['cost'];
                            //$this->session->data['shipping_method']['text'] = $this->currency->format($this->session->data['shipping_method']['cost']);
                        }
                    }
                }
            }
        }

        // Totals
        $total_data = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();

        $sort_order = array();

        $results = $this->model_extension_extension->getExtensions('total');

        foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
        }

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('total/' . $result['code']);

                $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
            }
        }

        $sort_order = array();

        foreach ($total_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $total_data);

        $data['totals'] = array();
        //$_html = '<label style="float: right; width: 25%;">';

        foreach ($total_data as $total) {
            if (!array_key_exists('shop_id', $total)) continue;
            if (!array_key_exists($total['shop_id'], $data['totals'])) $data['totals'][$total['shop_id']] = '';
            /*if (isset($this->request->get['shop_id'])) {
                if ($total['shop_id'] <> $this->request->get['shop_id']) continue;
            }*/
            /*$data['totals'][$total['shop_id']][] = array(
                'title' => $total['title'],
                'text'  => $this->currency->format($total['value'])
            );*/

            $data['totals'][$total['shop_id']] .= '<span style="float: right;">' .$total['title'] . ': ' . $this->currency->format($total['value']) . '</span><br/>';
        }

        //$_html .= '</label>';

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }
}