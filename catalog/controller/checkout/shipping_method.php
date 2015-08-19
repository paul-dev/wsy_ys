<?php
class ControllerCheckoutShippingMethod extends Controller {
	public function index() {
		$this->load->language('checkout/checkout');

        if (!(isset($this->session->data['shipping_types']) && isset($this->session->data['shipping_type']) && array_key_exists($this->session->data['shipping_type']['code'], $this->session->data['shipping_types']))) {
            $data['error_warning'] = '配送类型异常！';
        }

        if (!isset($data['error_warning']) && isset($this->request->post['shipping_type']) && array_key_exists($this->request->post['shipping_type'], $this->session->data['shipping_types'])) {
            if ((int)$this->request->post['shipping_type'] == 1) {
                $this->session->data['shipping_type'] = array(
                    'code' => '1',
                    'name' => '档口直发'
                );
            } else {
                $this->session->data['shipping_type'] = array(
                    'code' => '2',
                    'name' => '平台代发'
                );
            }
        }

		if (!isset($data['error_warning']) && isset($this->session->data['shipping_address'])) {
			// Shipping Methods
            $method_data = array();

            if ($this->session->data['shipping_type']['code'] == '1') {
                $method_data['main'] = array(
                    'title'      => '运费合计',
                    'quote'      => array(
                        'none' => array(
                            'code'         => 'main.main',
                            'title'        => '运费合计',
                            'cost'         => 0.00,
                            'tax_class_id' => 0,
                            'text'         => $this->currency->format(0.00)
                        )
                    ),
                    'sort_order' => 1,
                    'error'      => false
                );
            } else {
                $this->load->model('extension/extension');

                $results = $this->model_extension_extension->getExtensions('shipping');

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
            }

			$this->session->data['shipping_methods'][0] = $method_data;
            if ($this->cart->hasShipping() && $method_data && $this->session->data['shipping_type']['code'] == '2') {
                $default = array_shift($method_data);
                if ($default) {
                    $method = array_shift($default['quote']);
                    $this->session->data['shipping_method'] = $method;
                }
            }
		}

		$data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$data['text_comments'] = $this->language->get('text_comments');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['button_continue'] = $this->language->get('button_continue');

		if (empty($this->session->data['shipping_methods'][0])) {
			$data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['shipping_methods'][0])) {
			$data['shipping_methods'] = $this->session->data['shipping_methods'][0];
		} else {
			$data['shipping_methods'] = array();
		}

		if (isset($this->session->data['shipping_method']['code'])) {
			$data['code'] = $this->session->data['shipping_method']['code'];
		} else {
			$data['code'] = '';
		}

		if (isset($this->session->data['comment'])) {
			$data['comment'] = $this->session->data['comment'];
		} else {
			$data['comment'] = '';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/shipping_method.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/shipping_method.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/checkout/shipping_method.tpl', $data));
		}
	}

	public function save() {
		$this->load->language('checkout/checkout');

		$json = array();

		// Validate if shipping is required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}

		// Validate if shipping address has been set.
		if (!isset($this->session->data['shipping_address'])) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

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
				$json['redirect'] = $this->url->link('checkout/cart');

				break;
			}
		}

		if (!isset($this->request->post['shipping_method'])) {
			$json['error']['warning'] = $this->language->get('error_shipping');
		} else {
			$shipping = explode('.', $this->request->post['shipping_method']);

			if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][0][$shipping[0]]['quote'][$shipping[1]])) {
				$json['error']['warning'] = $this->language->get('error_shipping');
			}
		}

		if (!$json) {
			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][0][$shipping[0]]['quote'][$shipping[1]];

			$this->session->data['comment'] = strip_tags($this->request->post['comment']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

    public function update() {
        $this->load->language('checkout/checkout');

        $json = array();

        // Validate if shipping is required. If not the customer should not have reached this page.
        if (!$this->cart->hasShipping()) {
            $json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
        }

        // Validate if shipping address has been set.
        if (!isset($this->session->data['shipping_address'])) {
            $json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
        }

        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $json['redirect'] = $this->url->link('checkout/cart');
        }

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
                $json['redirect'] = $this->url->link('checkout/cart');

                break;
            }
        }

        if (!isset($this->request->post['shipping_method']) || !isset($this->request->post['shop_id'])) {
            $json['error']['warning'] = $this->language->get('error_shipping');
        } else {
            $shipping = explode('.', $this->request->post['shipping_method']);
            $shop_id = (int)$this->request->post['shop_id'];

            if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shop_shipping_methods'][$shop_id][$shipping[0]]['quote'][$shipping[1]])) {
                $json['error']['warning'] = $this->language->get('error_shipping');
            }
        }

        if (!$json) {
            $this->session->data['shop_shipping_method'][$shop_id] = $this->session->data['shop_shipping_methods'][$shop_id][$shipping[0]]['quote'][$shipping[1]];

            $cost = 0;
            foreach ($this->session->data['shop_shipping_method'] as $method) {
                $cost += $method['cost'];
            }
            $this->session->data['shipping_method']['cost'] = $cost;
            $this->session->data['shipping_method']['text'] = $this->currency->format($cost);

            $this->session->data['shop_comment'][$shop_id] = strip_tags($this->request->post['comment']);
            $this->session->data['comment'] = '';

            if ($this->request->post['shipping_method'] == 'none.none' || $this->request->post['shipping_method'] == '') {
                $json['error']['warning'] = $this->language->get('error_shipping_method');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}