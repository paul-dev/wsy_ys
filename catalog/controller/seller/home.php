<?php
class ControllerSellerHome extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('seller/home', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
        if (!$this->customer->isSeller()) {
            $this->session->data['redirect'] = $this->url->link('seller/home', '', 'SSL');

            $this->response->redirect($this->url->link('seller/shop/add', '', 'SSL'));
        }

		$this->load->language('seller/home');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_seller'),
			'href' => $this->url->link('seller/home', '', 'SSL')
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_my_account'] = $this->language->get('text_my_account');
		$data['text_my_orders'] = $this->language->get('text_my_orders');
		$data['text_my_newsletter'] = $this->language->get('text_my_newsletter');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_password'] = $this->language->get('text_password');
		$data['text_address'] = $this->language->get('text_address');
		$data['text_wishlist'] = $this->language->get('text_wishlist');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_reward'] = $this->language->get('text_reward');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_newsletter'] = $this->language->get('text_newsletter');
		$data['text_recurring'] = $this->language->get('text_recurring');

		$data['edit'] = $this->url->link('account/edit', '', 'SSL');
		$data['password'] = $this->url->link('account/password', '', 'SSL');
		$data['address'] = $this->url->link('account/address', '', 'SSL');
		$data['wishlist'] = $this->url->link('account/wishlist');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['download'] = $this->url->link('account/download', '', 'SSL');
		$data['return'] = $this->url->link('account/return', '', 'SSL');
		$data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		$data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');
		$data['recurring'] = $this->url->link('account/recurring', '', 'SSL');

        $data['text_shop_info'] = $this->language->get('text_shop_info');
        $data['text_shop_name'] = $this->language->get('text_shop_name');
        $data['text_shop_url'] = $this->language->get('text_shop_url');
        $data['text_shop_key'] = $this->language->get('text_shop_key');

        if ($this->config->get('reward_status')) {
			$data['reward'] = $this->url->link('account/reward', '', 'SSL');
		} else {
			$data['reward'] = '';
		}

        $this->load->model('setting/setting');

        $this->load->model('account/wishlist');
        $this->load->model('seller/shop');

        $store_id = $this->customer->getShopId();

        $shop_rating = $this->model_seller_shop->getStoreRatings($store_id);
        $shop_info = $this->model_setting_setting->getSetting('config', $store_id);

        $shop_info['shop_id'] = $store_id;

        $shop_info['total_wish'] = $this->model_account_wishlist->getTotalShopWished($store_id);
        $shop_info['total_reviews'] = $this->model_seller_shop->getStoreTotalReviews($store_id, array(
            'filter_start_date' => '0000-00-00 00:00:00'
        ));

        $data['shop_info'] = $shop_info;
        $data['shop_rating'] = $shop_rating;

        $this->load->model('tool/image');
        if (!empty($shop_info['config_logo']) && is_file(DIR_IMAGE . $shop_info['config_logo'])) {
            $data['shop_logo'] = $this->model_tool_image->resize($shop_info['config_logo'], 50, 50);
        } else {
            $data['shop_logo'] = $this->model_tool_image->resize('no_image.png', 50, 50);
        }

        // Shop home url.
        $data['shop_url'] = HTTP_SERVER . $shop_info['config_key'];

        $this->load->model('localisation/zone');
        $data['shop_zone'] = '';
        if ($shop_info['config_zone_id']) {
            $zone_info = $this->model_localisation_zone->getZone($shop_info['config_zone_id']);
            if ($zone_info) $data['shop_zone'] = $zone_info['name'];
        }

        $this->load->model('seller/order');
        $data['orders'] = array();

        $filter_data = array(
            'filter_order_status'  => '2,15',
            'sort'                 => 'o.date_added',
            'order'                => 'DESC',
            'start'                => 0,
            'limit'                => 5
        );

        $results = $this->model_seller_order->getOrders($filter_data);

        foreach ($results as $result) {
            $data['orders'][] = array(
                'order_id'      => $result['order_id'],
                'customer'      => $result['customer'],
                'status'        => $result['status'],
                'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'date_added'    => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
                'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
                'shipping_code' => $result['shipping_code'],
                'view'          => $this->url->link('seller/order/info', 'order_id=' . $result['order_id'], 'SSL'),
                'edit'          => $this->url->link('seller/order/edit', 'order_id=' . $result['order_id'], 'SSL'),
                'delete'        => $this->url->link('seller/order/delete', 'order_id=' . $result['order_id'], 'SSL')
            );
        }

        $data['this_month']['total_order'] = $this->model_seller_order->getTotalOrders(array(
            'filter_date_added_start' => date('Y-m-01'),
            'filter_date_added_end' => date('Y-m-d', strtotime(date('Y-m-01') . ' +1 month -1 day')),
        ));
        $data['this_month']['total_complete'] = $this->model_seller_order->getTotalOrdersByCompleteStatus(array(
            'filter_date_added_start' => date('Y-m-01'),
            'filter_date_added_end' => date('Y-m-d', strtotime(date('Y-m-01') . ' +1 month -1 day')),
        ));
        $result = $this->model_seller_order->getTotalOrdersTotal(array(
            'filter_date_added_start' => date('Y-m-01'),
            'filter_date_added_end' => date('Y-m-d', strtotime(date('Y-m-01') . ' +1 month -1 day')),
        ));
        $data['this_month']['order_total'] = $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']);
        $result = $this->model_seller_order->getTotalOrdersTotalByCompleteStatus(array(
            'filter_date_added_start' => date('Y-m-01'),
            'filter_date_added_end' => date('Y-m-d', strtotime(date('Y-m-01') . ' +1 month -1 day')),
        ));
        $data['this_month']['complete_total'] = $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']);

        $data['last_month']['total_order'] = $this->model_seller_order->getTotalOrders(array(
            'filter_date_added_start' => date('Y-m-01', strtotime(date('Y-m-01') . ' -1 day')),
            'filter_date_added_end' => date('Y-m-d', strtotime(date('Y-m-01') . ' -1 day')),
        ));
        $data['last_month']['total_complete'] = $this->model_seller_order->getTotalOrdersByCompleteStatus(array(
            'filter_date_added_start' => date('Y-m-01', strtotime(date('Y-m-01') . ' -1 day')),
            'filter_date_added_end' => date('Y-m-d', strtotime(date('Y-m-01') . ' -1 day')),
        ));
        $result = $this->model_seller_order->getTotalOrdersTotal(array(
            'filter_date_added_start' => date('Y-m-01', strtotime(date('Y-m-01') . ' -1 day')),
            'filter_date_added_end' => date('Y-m-d', strtotime(date('Y-m-01') . ' -1 day')),
        ));
        $data['last_month']['order_total'] = $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']);
        $result = $this->model_seller_order->getTotalOrdersTotalByCompleteStatus(array(
            'filter_date_added_start' => date('Y-m-01', strtotime(date('Y-m-01') . ' -1 day')),
            'filter_date_added_end' => date('Y-m-d', strtotime(date('Y-m-01') . ' -1 day')),
        ));
        $data['last_month']['complete_total'] = $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']);

        $data['old_month']['total_order'] = $this->model_seller_order->getTotalOrders(array(
            'filter_date_added_start' => date('Y-m-01', strtotime(date('Y-m-01') . ' -1 month -1 day')),
            'filter_date_added_end' => date('Y-m-d', strtotime(date('Y-m-01') . ' -1 month -1 day')),
        ));
        $data['old_month']['total_complete'] = $this->model_seller_order->getTotalOrdersByCompleteStatus(array(
            'filter_date_added_start' => date('Y-m-01', strtotime(date('Y-m-01') . ' -1 month -1 day')),
            'filter_date_added_end' => date('Y-m-d', strtotime(date('Y-m-01') . ' -1 month -1 day')),
        ));
        $result = $this->model_seller_order->getTotalOrdersTotal(array(
            'filter_date_added_start' => date('Y-m-01', strtotime(date('Y-m-01') . ' -1 month -1 day')),
            'filter_date_added_end' => date('Y-m-d', strtotime(date('Y-m-01') . ' -1 month -1 day')),
        ));
        $data['old_month']['order_total'] = $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']);
        $result = $this->model_seller_order->getTotalOrdersTotalByCompleteStatus(array(
            'filter_date_added_start' => date('Y-m-01', strtotime(date('Y-m-01') . ' -1 month -1 day')),
            'filter_date_added_end' => date('Y-m-d', strtotime(date('Y-m-01') . ' -1 month -1 day')),
        ));
        $data['old_month']['complete_total'] = $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/home.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/home.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/home.tpl', $data));
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
}