<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
        } else {
            $data['logo'] = '';
        }

		$data['text_information'] = $this->language->get('text_information');
		$data['text_service'] = $this->language->get('text_service');
		$data['text_extra'] = $this->language->get('text_extra');
		$data['text_contact'] = $this->language->get('text_contact');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_sitemap'] = $this->language->get('text_sitemap');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_voucher'] = $this->language->get('text_voucher');
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_special'] = $this->language->get('text_special');
		$data['text_account'] = $this->language->get('text_account');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_wishlist'] = $this->language->get('text_wishlist');
		$data['text_newsletter'] = $this->language->get('text_newsletter');

		$this->load->model('catalog/information');

		$data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}

		$data['contact'] = $this->url->link('information/contact');
		$data['return'] = $this->url->link('account/return/add', '', 'SSL');
		$data['sitemap'] = $this->url->link('information/sitemap');
		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['voucher'] = $this->url->link('account/voucher', '', 'SSL');
		$data['affiliate'] = $this->url->link('affiliate/account', '', 'SSL');
		$data['special'] = $this->url->link('product/special');
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');

        $data['link_buyer_newer'] = $this->url->link('information/information', 'information_id=15');
        $data['link_buyer_help'] = $this->url->link('information/information', 'information_id=4');
        $data['link_seller_newer'] = $this->url->link('information/information', 'information_id=8');
        $data['link_seller_service'] = $this->url->link('information/information', 'information_id=12');
        $data['link_seller_help'] = $this->url->link('information/information', 'information_id=13');

        $data['link_about_us'] = $this->url->link('information/information', 'information_id=10');
        $data['link_contact_us'] = $this->url->link('information/information', 'information_id=9');
        $data['link_join_us'] = $this->url->link('information/information', 'information_id=7');


        $data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));

		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = 'http://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}

			$this->model_tool_online->whosonline($ip, $this->customer->getId(), $url, $referer);
		}

        $data['is_logged'] = $this->customer->isLogged();

        // Live chat history.
        $data['chat_users'] = array();
        $data['chat_historys'] = array();
        if ($this->customer->isLogged()) {
            $this->load->model('account/chat');
            $this->load->model('tool/image');
            $results = $this->model_account_chat->getHistoryUsers($this->customer->getId());
            foreach ($results as $to_id => $result) {
                $avatar = $this->model_tool_image->resize('no_image.png', 50, 50);
                $result['custom_field'] = unserialize($result['custom_field']);
                if (isset($result['custom_field'][2]) && is_file(DIR_IMAGE . $result['custom_field'][2])) {
                    $avatar = $this->model_tool_image->resize($result['custom_field'][2], 50, 50);
                }

                $last_date = '';

                $historys = $this->model_account_chat->getHistoryMessages($to_id);
                $messages = array_reverse($historys);
                foreach ($messages as $message) {
                    $data['chat_historys'][$to_id][] = array(
                        'is_self' => $message['from_id'] == $this->customer->getId(),
                        'name' => $result['fullname'],
                        'avatar' => $avatar,
                        'text' => htmlspecialchars($message['text']),
                        'date' => date('Y-m-d H:i', strtotime($message['date_added']))
                    );
                    $last_date = date('Y-m-d H:i', strtotime($message['date_added']));
                }

                $data['chat_users'][] = array(
                    'id' => $to_id,
                    'name' => $result['fullname'],
                    'avatar' => $avatar,
                    'date' => $last_date
                );
            }
        }

        usort($data['chat_users'], function($a, $b){
            if ($a['date'] == $b['date'])
                return 0;
            return ($a['date'] > $b['date']) ? -1 : 1;
        });

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/footer.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/footer.tpl', $data);
		} else {
			return $this->load->view('default/template/common/footer.tpl', $data);
		}
	}
}