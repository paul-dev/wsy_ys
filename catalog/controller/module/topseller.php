<?php
class ControllerModuleTopseller extends Controller {
	public function index($setting) {
        $this->load->language('module/topseller');

        $data['heading_title'] = $setting['name'];

        $data['text_tax'] = $this->language->get('text_tax');
        $data['text_shop_title'] = sprintf($this->language->get('text_title'), $setting['name']);

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

        $this->load->model('design/banner');

        $data['banners'] = array();

        if (!$setting['limit']) $setting['limit'] = 10;

        $results = $this->model_design_banner->getBanner($setting['banner_id']);
        $results = array_slice($results, 0, $setting['limit']);

        foreach ($results as $result) {
            if (is_file(DIR_IMAGE . $result['image'])) {
                $data['banners'][] = array(
                    'name' => $result['title'],
                    'href'  => $result['link'],
                    'thumb' => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height'])
                );
            } else {
                $data['banners'][] = array(
                    'name' => $result['title'],
                    'href'  => $result['link'],
                    'thumb' => $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height'])
                );
            }
        }

        $this->load->model('seller/shop');

        $data['shops'] = array();

        if (empty($setting['category'])) $setting['category'] = array();

        $_shops = $this->model_seller_shop->getShops(array(
            'filter_category_id' => join(',', $setting['category']),
            'filter_sub_category' => true,
            'limit' => $setting['limit']
        ));

        foreach ($_shops as $_shop) {
            if ($_shop['shop_image']) {
                $image = $this->model_tool_image->resize($_shop['shop_image'], 50, 50);
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', 50, 50);
            }

            $data['shops'][] = array(
                'shop_name' => $_shop['name'],
                'shop_url' => HTTP_SERVER . $_shop['key'],// Shop home url.
                'shop_image' => $image,
                'total_sell' => $_shop['total_sell'] ? $_shop['total_sell'] : '0',
                'total_product' => $_shop['total_product']
            );
        }

        $data['href_shop_more'] = $this->url->link('product/search', 'type=shop&categories=' . join(',', $setting['category']));

		if ($data['banners']) {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/topseller.tpl')) {
                return $this->load->view($this->config->get('config_template') . '/template/module/topseller.tpl', $data);
            } else {
                return $this->load->view('default/template/module/topseller.tpl', $data);
            }
        }
	}
}