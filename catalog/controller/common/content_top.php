<?php
class ControllerCommonContentTop extends Controller {
    /**
     * @return mixed
     */
    public function index($_settings = array()) {
		$this->load->model('design/layout');
		
		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = 'common/home';
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

        $inner_modules = array();

		foreach ($modules as $module) {
			$part = explode('.', $module['code']);
			
			if (isset($part[0]) && $this->config->get($part[0] . '_status')) {
				$data['modules'][] = $this->load->controller('module/' . $part[0]);
			}
			
			if (isset($part[1])) {
				$setting_info = $this->model_extension_module->getModule($part[1]);
				
				if ($setting_info && $setting_info['status']) {
					$setting_info = array_merge($setting_info, $_settings);

                    $key = $part[0] . '-' . $part[1];
                    if (isset($setting_info['template'])) {
                        $setting_info['custom_template'] = $setting_info['template'];
                    }
                    if (isset($setting_info['inner']) && (int)$setting_info['inner'] == 1) {
                        $inner_modules[$part[0]][] = $this->load->controller('module/' . $part[0], $setting_info);
                        continue;
                    } else {
                        if (array_key_exists($part[0], $inner_modules)) {
                            $setting_info['custom_module'] = $inner_modules[$part[0]];
                        }
                    }
                    /*if ($part[0] == 'slideshow' && !array_key_exists('slideshow', $data['modules'])) {
                        $setting_info['custom_template'] = 'slideshow_top_right.tpl';
                    } elseif ($part[0] == 'slideshow') {
                        $setting_info['custom_module'] = $data['modules'][$part[0]];
                    }*/

                    $setting_info['module_id'] = $part[1];

                    $data['modules'][$key] = $this->load->controller('module/' . $part[0], $setting_info);
				}
			}
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/content_top.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/content_top.tpl', $data);
		} else {
			return $this->load->view('default/template/common/content_top.tpl', $data);
		}
	}
}