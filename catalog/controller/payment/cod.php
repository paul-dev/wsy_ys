<?php
class ControllerPaymentCod extends Controller {
    private $error = array();

    public function index() {
		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['continue'] = $this->url->link('checkout/success');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cod.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/cod.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/cod.tpl', $data);
		}
	}

	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'cod') {
			$this->load->model('checkout/order');

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('cod_order_status_id'));
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

        $this->load->language('payment/cod');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('seller/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->model_seller_setting->editSetting('cod', $this->request->post, $this->customer->getShopId());

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('seller/payment', '', 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_all_zones'] = $this->language->get('text_all_zones');

        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_total'] = $this->language->get('entry_total');
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

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('seller/payment', '', 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/cod/edit', '', 'SSL')
        );

        $data['action'] = $this->url->link('payment/cod/edit', '', 'SSL');

        $data['cancel'] = $this->url->link('seller/payment', '', 'SSL');

        $_settings = $this->model_seller_setting->getSetting('cod', $this->customer->getShopId());

        if (isset($this->request->post['cod_total'])) {
            $data['cod_total'] = $this->request->post['cod_total'];
        } else {
            $data['cod_total'] = array_key_exists('cod_total', $_settings) ? $_settings['cod_total'] : '';
        }

        if (isset($this->request->post['cod_order_status_id'])) {
            $data['cod_order_status_id'] = $this->request->post['cod_order_status_id'];
        } else {
            $data['cod_order_status_id'] = array_key_exists('cod_order_status_id', $_settings) ? $_settings['cod_order_status_id'] : '';
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['cod_geo_zone_id'])) {
            $data['cod_geo_zone_id'] = $this->request->post['cod_geo_zone_id'];
        } else {
            $data['cod_geo_zone_id'] = array_key_exists('cod_geo_zone_id', $_settings) ? $_settings['cod_geo_zone_id'] : '';
        }

        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['cod_status'])) {
            $data['cod_status'] = $this->request->post['cod_status'];
        } else {
            $data['cod_status'] = array_key_exists('cod_status', $_settings) ? $_settings['cod_status'] : '';
        }

        if (isset($this->request->post['cod_sort_order'])) {
            $data['cod_sort_order'] = $this->request->post['cod_sort_order'];
        } else {
            $data['cod_sort_order'] = array_key_exists('cod_sort_order', $_settings) ? $_settings['cod_sort_order'] : '';
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cod_form.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/cod_form.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/payment/cod_form.tpl', $data));
        }
    }
}