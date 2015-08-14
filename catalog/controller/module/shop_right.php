<?php
class ControllerModuleShopRight extends Controller {
	public function index($_settings = array()) {
		$this->load->language('module/shop_right');

        $this->load->model('setting/setting');

        $this->load->model('account/wishlist');
        $this->load->model('seller/shop');

        $this->load->model('tool/image');

        $store_id = $this->config->get('config_store_id');
        if (isset($_settings['productStoreId'])) $store_id = $_settings['productStoreId'];

        if ($this->customer->isLogged()) {
            $this->load->model('seller/shop');
            $shop_data = $this->model_seller_shop->getStore($store_id);
            $avatar = $this->model_tool_image->resize('no_image.png', 50, 50);
            $shop_data['custom_field'] = unserialize($shop_data['custom_field']);
            if (isset($shop_data['custom_field'][2]) && is_file(DIR_IMAGE . $shop_data['custom_field'][2])) {
                $avatar = $this->model_tool_image->resize($shop_data['custom_field'][2], 50, 50);
            }
            $data['link_live_chat'] = 'javascript:void(0);" onclick="activeLiveChat(this)" data-user="'.$shop_data['customer_id'].'" data-name="'.$shop_data['fullname'].'" data-avatar="'.$avatar;
        } else {
            $data['link_live_chat'] = $this->url->link('account/login', '', 'SSL');
        }

        $shop_rating = $this->model_seller_shop->getStoreRatings($store_id);
        $shop_info = $this->model_setting_setting->getSetting('config', $store_id);

        $shop_info['shop_id'] = $store_id;
        
        $shop_info['total_wish'] = $this->model_account_wishlist->getTotalShopWished($store_id);

        $data['shop_info'] = $shop_info;
        $data['shop_rating'] = $shop_rating;
        $data['average_rating'] = $this->model_seller_shop->getAverageRatings();

        if (!empty($shop_info['config_logo']) && is_file(DIR_IMAGE . $shop_info['config_logo'])) {
            $data['shop_logo'] = $this->model_tool_image->resize($shop_info['config_logo'], 50, 50);
        } else {
            $data['shop_logo'] = $this->model_tool_image->resize('no_image.png', 50, 50);
        }

        // Shop home url.
        $data['shop_url'] = HTTP_SERVER . $shop_info['config_key'];

        $this->load->model('localisation/zone');
        $data['shop_zone'] = '';
        if (isset($shop_info['config_zone_id'])) {
            $zone_info = $this->model_localisation_zone->getZone($shop_info['config_zone_id']);
            if ($zone_info) $data['shop_zone'] = $zone_info['name'];
        }
        $data['shop_city'] = '';
        if (isset($shop_info['config_city_id'])) {
            $city_info = $this->model_localisation_zone->getCity($shop_info['config_city_id']);
            if ($city_info) $data['shop_city'] = $city_info['name'];
        }

        $this->load->model('catalog/product');
        $data['total_product'] = $this->model_catalog_product->getTotalProducts(array(
            'filter_store_id' => $store_id
        ));

        $this->load->model('sale/order');
        $data['total_sell'] = $this->model_sale_order->getTotalSellProducts($store_id);
        if (empty($data['total_sell'])) $data['total_sell'] = '0';

        $data['related_products'] = array();
        if (isset($_settings['relatedProducts'])) $data['related_products'] = $_settings['relatedProducts'];

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_register'] = $this->language->get('text_register');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_forgotten'] = $this->language->get('text_forgotten');
		$data['text_account'] = $this->language->get('text_account');
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
        $data['text_seller'] = $this->customer->isSeller() ? $this->language->get('text_seller') : $this->language->get('text_shop');

        $data['logged'] = $this->customer->isLogged();
		$data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['login'] = $this->url->link('account/login', '', 'SSL');
		$data['logout'] = $this->url->link('account/logout', '', 'SSL');
		$data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['edit'] = $this->url->link('account/edit', '', 'SSL');
		$data['password'] = $this->url->link('account/password', '', 'SSL');
		$data['address'] = $this->url->link('account/address', '', 'SSL');
		$data['wishlist'] = $this->url->link('account/wishlist');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['download'] = $this->url->link('account/download', '', 'SSL');
		$data['reward'] = $this->url->link('account/reward', '', 'SSL');
		$data['return'] = $this->url->link('account/return', '', 'SSL');
		$data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		$data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');
		$data['recurring'] = $this->url->link('account/recurring', '', 'SSL');
        $data['url_seller'] = $this->customer->isSeller() ? $this->url->link('seller/home', '', 'SSL') : $this->url->link('seller/shop/add', '', 'SSL');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/shop_right.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/shop_right.tpl', $data);
		} else {
			return $this->load->view('default/template/module/shop_right.tpl', $data);
		}
	}
}