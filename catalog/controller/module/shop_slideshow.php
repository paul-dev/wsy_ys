<?php
class ControllerModuleShopSlideshow extends Controller {
	public function index($setting) {
		static $module = 0;

		$this->load->model('design/banner');
		$this->load->model('tool/image');
		
		$this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.carousel.css');
		$this->document->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');

		$data['banners'] = array();

		$results = $this->model_design_banner->getShopBannerImages($this->config->get('config_store_id'));

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$data['banners'][] = array(
					'title' => $result['title'],
					'link'  => $result['link'],
					'image' => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height'])
				);
			}
		}

		$data['module'] = $module++;

        /*$slideshow_shop = 'slideshow';
        if (isset($this->request->get['route']) && $this->request->get['route'] == 'shop/home') {
            $slideshow_shop .= '_shop';
        }*/

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/shop_slideshow.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/shop_slideshow.tpl', $data);
		} else {
			return $this->load->view('default/template/module/shop_slideshow.tpl', $data);
		}
	}
}