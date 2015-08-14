<?php
class ControllerShippingFree extends Controller {
	private $error = array();

    public function index() {
        die('access denied!');
    }

	public function edit() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('seller/shipping', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }
        if (!$this->customer->isSeller()) {
            $this->session->data['redirect'] = $this->url->link('seller/shipping', '', 'SSL');

            $this->response->redirect($this->url->link('seller/shop/add', '', 'SSL'));
        }

        $this->load->language('shipping/free');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('seller/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') /*&& $this->validate()*/) {
			$this->model_seller_setting->editSetting('free', $this->request->post, $this->customer->getShopId());

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('seller/shipping', '', 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');

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
			'text' => $this->language->get('text_shipping'),
			'href' => $this->url->link('seller/shipping', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('shipping/free/edit', '', 'SSL')
		);

		$data['action'] = $this->url->link('shipping/free/edit', '', 'SSL');

		$data['cancel'] = $this->url->link('seller/shipping', '', 'SSL');

        $_settings = $this->model_seller_setting->getSetting('free', $this->customer->getShopId());

		if (isset($this->request->post['free_total'])) {
			$data['free_total'] = $this->request->post['free_total'];
		} else {
			$data['free_total'] = array_key_exists('free_total', $_settings) ? $_settings['free_total'] : '';
		}

		if (isset($this->request->post['free_geo_zone_id'])) {
			$data['free_geo_zone_id'] = $this->request->post['free_geo_zone_id'];
		} else {
            $data['free_geo_zone_id'] = array_key_exists('free_geo_zone_id', $_settings) ? $_settings['free_geo_zone_id'] : '';
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['free_status'])) {
			$data['free_status'] = $this->request->post['free_status'];
		} else {
            $data['free_status'] = array_key_exists('free_status', $_settings) ? $_settings['free_status'] : '';
		}

		if (isset($this->request->post['free_sort_order'])) {
			$data['free_sort_order'] = $this->request->post['free_sort_order'];
		} else {
            $data['free_sort_order'] = array_key_exists('free_sort_order', $_settings) ? $_settings['free_sort_order'] : '';
		}

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/shipping/free.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/shipping/free.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/shipping/free.tpl', $data));
        }
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/free')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}