<?php
class ControllerModuleCategory extends Controller {
	public function index($_setting = array()) {
		$this->load->language('module/category');

		$data['heading_title'] = $this->language->get('heading_title');

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		if (isset($parts[0])) {
			$data['category_id'] = $parts[0];
		} else {
			$data['category_id'] = 0;
		}

		if (isset($parts[1])) {
			$data['child_id'] = $parts[1];
		} else {
			$data['child_id'] = 0;
		}

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

        $shop_id = $this->config->get('config_store_id');
        if (isset($_setting['_StoreId_'])) $shop_id = $_setting['_StoreId_'];
        $this->load->model('setting/setting');
        $shop_setting = $this->model_setting_setting->getSetting('config', $shop_id);
        if (!isset($shop_setting['config_key'])) $shop_id = 0;

        $data['is_shop'] = $shop_id > 0;

		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategoriesByShop($shop_id, 0);

		foreach ($categories as $category) {
			$children_data = array();

			//if ($category['category_id'] == $data['category_id']) {
				$children = $this->model_catalog_category->getCategoriesByShop($shop_id, $category['category_id']);

				foreach($children as $child) {
					$filter_data = array('filter_category_id' => $child['category_id'], 'filter_sub_category' => true);

                    if ($shop_id > 0) {
                        // Shop home url.
                        //$href = '/shop/'.$shop_setting['config_key'].'/category/?path=' . $category['category_id'] . '_' . $child['category_id'];
                        $href = $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']);
                    } else {
                        $href = $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']);
                    }

					$children_data[] = array(
						'category_id' => $child['category_id'], 
						'name' => $child['name'] . (1 <> 1 && $this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
						'href' => $href
					);
				}
			//}

			$filter_data = array(
				'filter_category_id'  => $category['category_id'],
				'filter_sub_category' => true
			);

            if ($shop_id > 0) {
                // Shop home url.
                //$href = '/shop/'.$shop_setting['config_key'].'/category/?path=' . $category['category_id'];
                $href = $this->url->link('product/category', 'path=' . $category['category_id']);
            } else {
                $href = $this->url->link('product/category', 'path=' . $category['category_id']);
            }

            $category['name'] = utf8_substr($category['name'], 0, 11);
			$data['categories'][] = array(
				'category_id' => $category['category_id'],
				'name'        => $category['name'] . (1 <> 1 && $this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
				'children'    => $children_data,
				'href'        => $href
			);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/category.tpl', $data);
		} else {
			return $this->load->view('default/template/module/category.tpl', $data);
		}
	}
}