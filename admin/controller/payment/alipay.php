<?php 
class ControllerPaymentAlipay extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/alipay');

		$this->document->settitle($this->language->get('heading_title'));
		
		if (isset($this->error['secrity_code'])) {
			$data['error_secrity_code'] = $this->error['secrity_code'];
		} else {
			$data['error_secrity_code'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['partner'])) {
			$data['error_partner'] = $this->error['partner'];
		} else {
			$data['error_partner'] = '';
		}
		
		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_payment'),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=payment/alipay&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('alipay', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		
		$data['entry_seller_email'] = $this->language->get('entry_seller_email');
		$data['entry_security_code'] = $this->language->get('entry_security_code');
		$data['entry_partner'] = $this->language->get('entry_partner');
		$data['entry_trade_type'] = $this->language->get('entry_trade_type');
		$data['entry_anti_phishing'] = $this->language->get('entry_anti_phishing');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
 		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}


		$data['action'] = HTTPS_SERVER . 'index.php?route=payment/alipay&token=' . $this->session->data['token'];
		
		$data['cancel'] =  HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];
		
		if (isset($this->request->post['alipay_seller_email'])) {
			$data['alipay_seller_email'] = $this->request->post['alipay_seller_email'];
		} else {
			$data['alipay_seller_email'] = $this->config->get('alipay_seller_email');
		}

		if (isset($this->request->post['alipay_security_code'])) {
			$data['alipay_security_code'] = $this->request->post['alipay_security_code'];
		} else {
			$data['alipay_security_code'] = $this->config->get('alipay_security_code');
		}

		if (isset($this->request->post['alipay_partner'])) {
			$data['alipay_partner'] = $this->request->post['alipay_partner'];
		} else {
			$data['alipay_partner'] = $this->config->get('alipay_partner');
		}		

		if (isset($this->request->post['alipay_trade_type'])) {
			$data['alipay_trade_type'] = $this->request->post['alipay_trade_type'];
		} else {
			$data['alipay_trade_type'] = $this->config->get('alipay_trade_type');
		}
		
	/*	if (isset($this->request->post['alipay_anti_phishing'])) {
			$data['alipay_anti_phishing'] = $this->request->post['alipay_anti_phishing'];
		} else {
			$data['alipay_anti_phishing'] = $this->config->get('alipay_anti_phishing');
		}
		*/
		if (isset($this->request->post['alipay_order_status_id'])) {
			$data['alipay_order_status_id'] = $this->request->post['alipay_order_status_id'];
		} else {
			$data['alipay_order_status_id'] = $this->config->get('alipay_order_status_id');
		} 

		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			
		$this->load->model('localisation/geo_zone');
										
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['alipay_status'])) {
			$data['alipay_status'] = $this->request->post['alipay_status'];
		} else {
			$data['alipay_status'] = $this->config->get('alipay_status');
		}
		
		if (isset($this->request->post['alipay_sort_order'])) {
			$data['alipay_sort_order'] = $this->request->post['alipay_sort_order'];
		} else {
			$data['alipay_sort_order'] = $this->config->get('alipay_sort_order');
		}
		
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/alipay.tpl', $data));
		
		//$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}


	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/alipay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['alipay_seller_email']) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if (!$this->request->post['alipay_security_code']) {
			$this->error['secrity_code'] = $this->language->get('error_secrity_code');
		}

		if (!$this->request->post['alipay_partner']) {
			$this->error['partner'] = $this->language->get('error_partner');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>