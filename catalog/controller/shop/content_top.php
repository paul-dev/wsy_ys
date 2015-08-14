<?php
class ControllerShopContentTop extends Controller {
	public function index() {
		$this->load->model('design/layout');
		
		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = 'shop/home';
		}

		$layout_id = 0;

		if ($route == 'product/category' && isset($this->request->get['path'])) {
			$this->load->model('catalog/category');
			
			$path = explode('_', (string)$this->request->get['path']);

			$layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));
		}

		if ($route == 'product/product' && isset($this->request->get['product_id'])) {
			$this->load->model('catalog/product');
			
			$layout_id = $this->model_catalog_product->getProductLayoutId($this->request->get['product_id']);
		}

		if ($route == 'information/information' && isset($this->request->get['information_id'])) {
			$this->load->model('catalog/information');
			
			$layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->get['information_id']);
		}

		if (!$layout_id) {
			$layout_id = $this->model_design_layout->getLayout($route);
		}

		if (!$layout_id) {
			$layout_id = $this->config->get('config_layout_id');
		}
		
		$this->load->model('extension/module');

		$data['modules'] = array();
		
		$modules = $this->model_design_layout->getLayoutModules($layout_id, 'content_top');

		foreach ($modules as $module) {
			$part = explode('.', $module['code']);

			if (isset($part[0]) && $this->config->get($part[0] . '_status')) {
				$data['modules'][] = $this->load->controller('module/shop_' . $part[0]);
			}
			
			if (isset($part[1])) {
				$setting_info = $this->model_extension_module->getModule($part[1]);
				
				if ($setting_info && $setting_info['status']) {
					$data['modules'][] = $this->load->controller('module/shop_' . $part[0], $setting_info);
				}
			}
		}

        $this->load->model('tool/image');
        $this->load->model('design/banner');

        if ($route == 'shop/home') {
            $blocks = $this->model_design_banner->getShopBlocks($this->config->get('config_store_id'));
            foreach ($blocks as $_block) {
                if (empty($_block['status'])) continue;
                $setting = array(
                    'limit' => $_block['limit'],
                    'width' => '200',
                    'height' => '200'
                );
                $setting['name'] = $_block['name'];
                $setting['link'] = $_block['link'];
                if (!empty($_block['image']) && is_file(DIR_IMAGE . $_block['image'])) {
                    //$setting['thumb'] = $this->model_tool_image->resize($_block['image'], 950, 410);
                    $setting['thumb'] = $this->model_tool_image->resize($_block['image']);
                } else {
                    $setting['thumb'] = '';
                }
                $setting['category'] = $_block['category'];
                $setting['filter'] = $_block['filter'];

                $data['modules'][] = $this->load->controller('module/shop_block', $setting);
            }
        }

        $data['shop_exist'] = $this->config->get('shop_exist');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/shop/content_top.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/shop/content_top.tpl', $data);
		} else {
			return $this->load->view('default/template/shop/content_top.tpl', $data);
		}
	}
}