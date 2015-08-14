<?php
class ControllerModuleShopBlock extends Controller {
	public function index($setting) {
        $this->load->language('module/shop_block');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

        $data['heading_title'] = $setting['name'];
        $data['link'] = $setting['link'];
        $data['thumb'] = $setting['thumb'];

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();

        $sort = 'p.date_added';
        if (isset($this->request->get['sort'])) {
            if ($this->request->get['sort'] == 'hot') $sort = 'p.viewed';
        }

        $filter_preview = false;
        if (isset($this->request->get['preview']) && $this->customer->isSeller() && $this->customer->getShopId() == $this->config->get('config_store_id')) {
            $filter_preview = true;
        }

		$filter_data = array(
			'sort'  => $sort,
			'order' => 'DESC',
			'start' => 0,
			'limit' => $setting['limit'],
            'filter_category_id' => $setting['category'],
            'filter_filter' => $setting['filter'],
            'filter_preview' => $filter_preview
		);

        $data['products'] = array();
		$results = $this->model_catalog_product->getProducts($filter_data);

		if ($results || $data['thumb']) {
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
                    'name'        => $result['name'],
					'full_name'   => $result['name'],
                    'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $rating,
                    'total_wish'  => $result['total_wish'],
                    'total_buyer' => $result['total_buyer'],
                    'total_sell'  => $result['total_sell'],
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/shop_block.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/shop_block.tpl', $data);
			} else {
				return $this->load->view('default/template/module/shop_block.tpl', $data);
			}
		}
	}
}