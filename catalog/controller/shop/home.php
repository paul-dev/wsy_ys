<?php
class ControllerShopHome extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink(HTTP_SERVER, 'canonical');
		}

        $data['header_top'] = $this->load->controller('shop/header_top');
		$data['column_left'] = $this->load->controller('shop/column_left');
		$data['column_right'] = $this->load->controller('shop/column_right');
		$data['content_top'] = $this->load->controller('shop/content_top');
		$data['content_bottom'] = $this->load->controller('shop/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->config->get('shop_exist') ? $this->load->controller('shop/header') : $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/shop/home.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/shop/home.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/shop/home.tpl', $data));
		}
	}
}