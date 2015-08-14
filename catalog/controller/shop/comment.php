<?php
class ControllerShopComment extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink(HTTP_SERVER, 'canonical');
		}

        if (!$this->config->get('shop_exist')) {
            $this->response->redirect($this->url->link('shop/home'));
        }

        $shop_id = $this->config->get('config_store_id');
        // Shop home url.
        $data['shop_url'] = HTTP_SERVER . $this->config->get('config_key');
        $data['shop_id'] = $shop_id;
        $data['shop_key'] = $this->config->get('config_key');
        $data['shop_name'] = $this->config->get('config_name');

        $this->load->model('seller/shop');
        $data['shop_rating'] = $this->model_seller_shop->getStoreRatings($shop_id);
        $data['average_rating'] = $this->model_seller_shop->getAverageRatings();
        $data['total_reviews'] = $this->model_seller_shop->getStoreTotalReviews($shop_id);
        $data['total_all'] = $this->model_seller_shop->getStoreTotalReviews($shop_id, array(
            'filter_start_date' => '0000-00-00 00:00:00'
        ));
        $data['score_rating'] = $this->model_seller_shop->getStoreTotalScores($shop_id);

        $this->load->model('localisation/zone');
        $data['shop_zone'] = '';
        if ($this->config->get('config_zone_id')) {
            $zone_info = $this->model_localisation_zone->getZone($this->config->get('config_zone_id'));
            if ($zone_info) $data['shop_zone'] = $zone_info['name'];
        }
        $data['shop_city'] = '';
        if ($this->config->get('config_city_id')) {
            $city_info = $this->model_localisation_zone->getCity($this->config->get('config_city_id'));
            if ($city_info) $data['shop_city'] = $city_info['name'];
        }

        $this->load->model('account/wishlist');
        $data['total_wish'] = $this->model_account_wishlist->getTotalShopWished($shop_id);

        $this->load->model('catalog/product');
        $data['total_product'] = $this->model_catalog_product->getTotalProducts(array(
            'filter_store_id' => $shop_id
        ));

        $this->load->model('sale/order');
        $data['total_sell'] = $this->model_sale_order->getTotalSellProducts($shop_id);
        if (empty($data['total_sell'])) $data['total_sell'] = '0';

        $shop_data = $this->model_seller_shop->getStore($shop_id);
        $data['shop_create_date'] = date('Y-m-d', strtotime($shop_data['date_added']));

        //$data['header_top'] = $this->load->controller('shop/header_top');
		$data['column_left'] = $this->load->controller('shop/column_left');
		$data['column_right'] = $this->load->controller('shop/column_right');
		//$data['content_top'] = $this->load->controller('shop/content_top');
		//$data['content_bottom'] = $this->load->controller('shop/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->config->get('shop_exist') ? $this->load->controller('shop/header') : $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/shop/comment.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/shop/comment.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/shop/comment.tpl', $data));
		}
	}

    public function review() {
        $this->load->language('product/product');

        $this->load->model('catalog/review');

        $data['text_no_reviews'] = $this->language->get('text_no_reviews');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['reviews'] = array();

        $review_total = $this->model_catalog_review->getTotalReviewsByStoreId($this->request->get['shop_id']);
        $results = $this->model_catalog_review->getReviewsByStoreId($this->request->get['shop_id'], ($page - 1) * 8, 8);

        $this->load->model('tool/image');
        foreach ($results as $result) {
            $customer_avatar = $this->model_tool_image->resize('no_image.png', 70, 70);
            if ($result['custom_field']) {
                $customer_avatar = unserialize($result['custom_field']);
                if (isset($customer_avatar[2]) && is_file(DIR_IMAGE . $customer_avatar[2])) {
                    $customer_avatar = $this->model_tool_image->resize($customer_avatar[2], 70, 70);
                }
            }
            $data['reviews'][] = array(
                'author'     => $result['author'],
                'text'       => nl2br($result['text']),
                'rating'     => (int)$result['rating'],
                'avatar'     => $customer_avatar,
                'product'    => $result['name'],
                'model'      => $result['model'],
                'href'       => $this->url->link('product/product', 'product_id='.$result['product_id']),
                'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
            );
        }

        $pagination = new Pagination();
        $pagination->total = $review_total;
        $pagination->page = $page;
        $pagination->limit = 8;
        $pagination->url = $this->url->link('shop/comment/review', 'shop_id=' . $this->request->get['shop_id'] . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 8) + 1 : 0, ((($page - 1) * 8) > ($review_total - 8)) ? $review_total : ((($page - 1) * 8) + 8), $review_total, ceil($review_total / 8));

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/shop/review_list.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/shop/review_list.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/shop/review_list.tpl', $data));
        }
    }
}