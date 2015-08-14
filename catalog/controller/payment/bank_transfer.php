<?php
class ControllerPaymentBankTransfer extends Controller {
    private $error = array();

    public function index() {
		$this->load->language('payment/bank_transfer');

		$data['text_instruction'] = $this->language->get('text_instruction');
		$data['text_description'] = $this->language->get('text_description');
		$data['text_payment'] = $this->language->get('text_payment');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['bank'] = nl2br($this->config->get('bank_transfer_bank' . $this->config->get('config_language_id')));

		$data['continue'] = $this->url->link('checkout/success');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/bank_transfer.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/bank_transfer.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/bank_transfer.tpl', $data);
		}
	}

	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'bank_transfer') {
			$this->load->language('payment/bank_transfer');

			$this->load->model('checkout/order');

			$comment  = $this->language->get('text_instruction') . "\n\n";
			$comment .= $this->config->get('bank_transfer_bank' . $this->config->get('config_language_id')) . "\n\n";
			$comment .= $this->language->get('text_payment');

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('bank_transfer_order_status_id'), $comment, true);
		}
	}

    public function edit() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('seller/payment', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }
        if (!$this->customer->isSeller()) {
            $this->session->data['redirect'] = $this->url->link('seller/payment', '', 'SSL');

            $this->response->redirect($this->url->link('seller/shop/add', '', 'SSL'));
        }

        $this->load->language('payment/bank_transfer');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('seller/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->model_seller_setting->editSetting('bank_transfer', $this->request->post, $this->customer->getShopId());

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('seller/payment', '', 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_all_zones'] = $this->language->get('text_all_zones');

        $data['entry_bank'] = $this->language->get('entry_bank');
        $data['entry_total'] = $this->language->get('entry_total');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['help_total'] = $this->language->get('help_total');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $this->load->model('localisation/language');

        $languages = $this->model_localisation_language->getLanguages();

        foreach ($languages as $language) {
            if (isset($this->error['bank' . $language['language_id']])) {
                $data['error_bank' . $language['language_id']] = $this->error['bank' . $language['language_id']];
            } else {
                $data['error_bank' . $language['language_id']] = '';
            }
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment_title'),
            'href' => $this->url->link('seller/payment', '', 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/bank_transfer/edit', '', 'SSL')
        );

        $data['action'] = $this->url->link('payment/bank_transfer/edit', '', 'SSL');

        $data['cancel'] = $this->url->link('seller/payment', '', 'SSL');

        $_settings = $this->model_seller_setting->getSetting('bank_transfer', $this->customer->getShopId());

        $this->load->model('localisation/language');

        foreach ($languages as $language) {
            if (isset($this->request->post['bank_transfer_bank' . $language['language_id']])) {
                $data['bank_transfer_bank' . $language['language_id']] = $this->request->post['bank_transfer_bank' . $language['language_id']];
            } else {
                $data['bank_transfer_bank' . $language['language_id']] = array_key_exists('bank_transfer_bank' . $language['language_id'], $_settings) ? $_settings['bank_transfer_bank' . $language['language_id']] : '';
            }
        }

        foreach ($languages as $_language) {
            if ($_language['code'] <> $this->config->get('config_language')) continue;
            $data['languages'][] = $_language;
        }

        if (isset($this->request->post['bank_transfer_total'])) {
            $data['bank_transfer_total'] = $this->request->post['bank_transfer_total'];
        } else {
            $data['bank_transfer_total'] = array_key_exists('bank_transfer_total', $_settings) ? $_settings['bank_transfer_total'] : '';
        }

        if (isset($this->request->post['bank_transfer_order_status_id'])) {
            $data['bank_transfer_order_status_id'] = $this->request->post['bank_transfer_order_status_id'];
        } else {
            $data['bank_transfer_order_status_id'] = array_key_exists('bank_transfer_order_status_id', $_settings) ? $_settings['bank_transfer_order_status_id'] : '';
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['bank_transfer_geo_zone_id'])) {
            $data['bank_transfer_geo_zone_id'] = $this->request->post['bank_transfer_geo_zone_id'];
        } else {
            $data['bank_transfer_geo_zone_id'] = array_key_exists('bank_transfer_geo_zone_id', $_settings) ? $_settings['bank_transfer_geo_zone_id'] : '';
        }

        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['bank_transfer_status'])) {
            $data['bank_transfer_status'] = $this->request->post['bank_transfer_status'];
        } else {
            $data['bank_transfer_status'] = array_key_exists('bank_transfer_status', $_settings) ? $_settings['bank_transfer_status'] : '';
        }

        if (isset($this->request->post['bank_transfer_sort_order'])) {
            $data['bank_transfer_sort_order'] = $this->request->post['bank_transfer_sort_order'];
        } else {
            $data['bank_transfer_sort_order'] = array_key_exists('bank_transfer_sort_order', $_settings) ? $_settings['bank_transfer_sort_order'] : '';
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/bank_transfer_form.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/bank_transfer_form.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/payment/bank_transfer_form.tpl', $data));
        }
    }
}