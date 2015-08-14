<?php
class ControllerModuleSeller extends Controller {
	public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('seller/home', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        /*if (!$this->customer->isSeller()) {
            $this->session->data['redirect'] = $this->url->link('seller/home', '', 'SSL');

            $this->response->redirect($this->url->link('account/account', '', 'SSL'));
        }*/

        $this->load->language('module/seller');

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
        $data['text_image'] = $this->language->get('text_image');
        $data['text_download'] = $this->language->get('text_download');
		$data['text_reward'] = $this->language->get('text_reward');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_newsletter'] = $this->language->get('text_newsletter');
        $data['text_recurring'] = $this->language->get('text_recurring');
        $data['text_shop'] = $this->customer->isSeller() ? $this->language->get('text_shop_edit') : $this->language->get('text_shop_add');
        $data['text_product'] = $this->language->get('text_product');
        $data['text_category'] = $this->language->get('text_category');
        $data['text_option'] = $this->language->get('text_option');
        $data['text_shipping'] = $this->language->get('text_shipping');
        $data['text_coupon'] = $this->language->get('text_coupon');
        $data['text_payment'] = $this->language->get('text_payment');
        $data['text_design'] = $this->language->get('text_design');
        $data['text_download'] = $this->language->get('text_download');
        $data['text_return'] = $this->language->get('text_return');

        $data['active_class'] = array();
        $_parts = explode('/', $this->request->get['route']);
        switch ($_parts[1]) {
            case 'shop' :
                $data['text_shop'] = '> '.$data['text_shop'];
                break;
            case 'category' :
                $data['text_category'] = '> '.$data['text_category'];
                break;
            case 'option' :
                $data['text_option'] = '> '.$data['text_option'];
                break;
            case 'product' :
                $data['text_product'] = '> '.$data['text_product'];
                if (isset($this->request->get['filter_type']) && $this->request->get['filter_type'] == '1') {
                    $data['active_class']['product']['on_sale'] = 'active';
                } elseif (isset($this->request->get['filter_type']) && $this->request->get['filter_type'] == '0') {
                    $data['active_class']['product']['un_sale'] = 'active';
                } elseif (isset($this->request->get['filter_status']) && $this->request->get['filter_status'] == '0') {
                    $data['active_class']['product']['un_approve'] = 'active';
                } else {
                    $data['active_class']['product']['all'] = 'active';
                }
                break;
            case 'order' :
                $data['text_order'] = '> '.$data['text_order'];
                if (isset($this->request->get['filter_order_status']) && $this->request->get['filter_order_status'] == '1') {
                    $data['active_class']['order']['1'] = 'active';
                } elseif (isset($this->request->get['filter_order_status']) && $this->request->get['filter_order_status'] == '2') {
                    $data['active_class']['order']['2'] = 'active';
                } elseif (isset($this->request->get['filter_order_status']) && $this->request->get['filter_order_status'] == '15') {
                    $data['active_class']['order']['15'] = 'active';
                } elseif (isset($this->request->get['filter_order_status']) && $this->request->get['filter_order_status'] == '3') {
                    $data['active_class']['order']['3'] = 'active';
                } elseif (isset($this->request->get['filter_order_status']) && $this->request->get['filter_order_status'] == '5') {
                    $data['active_class']['order']['5'] = 'active';
                } else {
                    $data['active_class']['order']['all'] = 'active';
                }
                break;
            case 'shipping' :
                $data['text_shipping'] = '> '.$data['text_shipping'];
                break;
            case 'coupon' :
                $data['text_coupon'] = '> '.$data['text_coupon'];
                break;
            case 'payment' :
                $data['text_payment'] = '> '.$data['text_payment'];
                break;
            case 'design' :
                $data['text_design'] = '> '.$data['text_design'];
                break;
            case 'download' :
                $data['text_download'] = '> '.$data['text_download'];
                break;
            case 'image' :
                $data['text_image'] = '> '.$data['text_image'];
                break;
            case 'return' :
                $data['text_return'] = '> '.$data['text_return'];
                break;
        }

        if (preg_match('/^shipping\/\w+\/edit\/?/i', $this->request->get['route'])) {
            $data['text_shipping'] = '> '.$data['text_shipping'];
        }
        if (preg_match('/^payment\/\w+\/edit\/?/i', $this->request->get['route'])) {
            $data['text_payment'] = '> '.$data['text_payment'];
        }

        $data['logged'] = $this->customer->isLogged();
        $data['isSeller'] = $this->customer->isSeller();
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
        $data['url_shop'] = $this->url->link('seller/shop/edit', '', 'SSL');
        $data['url_product'] = $this->url->link('seller/product', '', 'SSL');
        $data['url_order'] = $this->url->link('seller/order', '', 'SSL');
        $data['url_category'] = $this->url->link('seller/category', '', 'SSL');
        $data['url_option'] = $this->url->link('seller/option', '', 'SSL');
        $data['url_shipping'] = $this->url->link('seller/shipping', '', 'SSL');
        $data['url_coupon'] = $this->url->link('seller/coupon', '', 'SSL');
        $data['url_payment'] = $this->url->link('seller/payment', '', 'SSL');
        $data['url_design'] = $this->url->link('seller/design/edit', '', 'SSL');
        $data['url_download'] = $this->url->link('seller/download', '', 'SSL');
        $data['url_image'] = $this->url->link('seller/image', '', 'SSL');
        $data['url_return'] = $this->url->link('seller/return', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/seller.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/seller.tpl', $data);
		} else {
			return $this->load->view('default/template/module/seller.tpl', $data);
		}
	}
}