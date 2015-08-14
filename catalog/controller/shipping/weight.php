<?php
class ControllerShippingWeight extends Controller {
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

        $this->load->language('shipping/weight');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('seller/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_seller_setting->editSetting('weight', $this->request->post, $this->customer->getShopId());

			$this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('seller/shipping', '', 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_rate'] = $this->language->get('entry_rate');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_rate'] = $this->language->get('help_rate');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

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
			'href' => $this->url->link('shipping/weight/edit', '', 'SSL')
		);

		$data['action'] = $this->url->link('shipping/weight/edit', '', 'SSL');

		$data['cancel'] = $this->url->link('seller/shipping', '', 'SSL');

        $_settings = $this->model_seller_setting->getSetting('weight', $this->customer->getShopId());

        $this->load->model('localisation/geo_zone');

		$geo_zones = $this->model_localisation_geo_zone->getGeoZones();

		foreach ($geo_zones as $geo_zone) {
			if (isset($this->request->post['weight_' . $geo_zone['geo_zone_id'] . '_rate'])) {
				$data['weight_' . $geo_zone['geo_zone_id'] . '_rate'] = $this->request->post['weight_' . $geo_zone['geo_zone_id'] . '_rate'];
			} elseif (isset($_settings['weight_' . $geo_zone['geo_zone_id'] . '_rate'])) {
				$data['weight_' . $geo_zone['geo_zone_id'] . '_rate'] = $_settings['weight_' . $geo_zone['geo_zone_id'] . '_rate'];
			} else {
                $data['weight_' . $geo_zone['geo_zone_id'] . '_rate'] = '';
            }

            // Express
            if (isset($this->request->post['weight_express_' . $geo_zone['geo_zone_id'] . '_first_weight'])) {
                $data['weight_express_' . $geo_zone['geo_zone_id'] . '_first_weight'] = $this->request->post['weight_express_' . $geo_zone['geo_zone_id'] . '_first_weight'];
            } elseif (isset($_settings['weight_express_' . $geo_zone['geo_zone_id'] . '_first_weight'])) {
                $data['weight_express_' . $geo_zone['geo_zone_id'] . '_first_weight'] = $_settings['weight_express_' . $geo_zone['geo_zone_id'] . '_first_weight'];
            } else {
                $data['weight_express_' . $geo_zone['geo_zone_id'] . '_first_weight'] = '';
            }
            if (isset($this->request->post['weight_express_' . $geo_zone['geo_zone_id'] . '_first_price'])) {
                $data['weight_express_' . $geo_zone['geo_zone_id'] . '_first_price'] = $this->request->post['weight_express_' . $geo_zone['geo_zone_id'] . '_first_price'];
            } elseif (isset($_settings['weight_express_' . $geo_zone['geo_zone_id'] . '_first_price'])) {
                $data['weight_express_' . $geo_zone['geo_zone_id'] . '_first_price'] = $_settings['weight_express_' . $geo_zone['geo_zone_id'] . '_first_price'];
            } else {
                $data['weight_express_' . $geo_zone['geo_zone_id'] . '_first_price'] = '';
            }
            if (isset($this->request->post['weight_express_' . $geo_zone['geo_zone_id'] . '_next_weight'])) {
                $data['weight_express_' . $geo_zone['geo_zone_id'] . '_next_weight'] = $this->request->post['weight_express_' . $geo_zone['geo_zone_id'] . '_next_weight'];
            } elseif (isset($_settings['weight_express_' . $geo_zone['geo_zone_id'] . '_next_weight'])) {
                $data['weight_express_' . $geo_zone['geo_zone_id'] . '_next_weight'] = $_settings['weight_express_' . $geo_zone['geo_zone_id'] . '_next_weight'];
            } else {
                $data['weight_express_' . $geo_zone['geo_zone_id'] . '_next_weight'] = '';
            }
            if (isset($this->request->post['weight_express_' . $geo_zone['geo_zone_id'] . '_next_price'])) {
                $data['weight_express_' . $geo_zone['geo_zone_id'] . '_next_price'] = $this->request->post['weight_express_' . $geo_zone['geo_zone_id'] . '_next_price'];
            } elseif (isset($_settings['weight_express_' . $geo_zone['geo_zone_id'] . '_next_price'])) {
                $data['weight_express_' . $geo_zone['geo_zone_id'] . '_next_price'] = $_settings['weight_express_' . $geo_zone['geo_zone_id'] . '_next_price'];
            } else {
                $data['weight_express_' . $geo_zone['geo_zone_id'] . '_next_price'] = '';
            }

            // Postage
            if (isset($this->request->post['weight_postage_' . $geo_zone['geo_zone_id'] . '_first_weight'])) {
                $data['weight_postage_' . $geo_zone['geo_zone_id'] . '_first_weight'] = $this->request->post['weight_postage_' . $geo_zone['geo_zone_id'] . '_first_weight'];
            } elseif (isset($_settings['weight_postage_' . $geo_zone['geo_zone_id'] . '_first_weight'])) {
                $data['weight_postage_' . $geo_zone['geo_zone_id'] . '_first_weight'] = $_settings['weight_postage_' . $geo_zone['geo_zone_id'] . '_first_weight'];
            } else {
                $data['weight_postage_' . $geo_zone['geo_zone_id'] . '_first_weight'] = '';
            }
            if (isset($this->request->post['weight_express_' . $geo_zone['geo_zone_id'] . '_first_price'])) {
                $data['weight_postage_' . $geo_zone['geo_zone_id'] . '_first_price'] = $this->request->post['weight_postage_' . $geo_zone['geo_zone_id'] . '_first_price'];
            } elseif (isset($_settings['weight_postage_' . $geo_zone['geo_zone_id'] . '_first_price'])) {
                $data['weight_postage_' . $geo_zone['geo_zone_id'] . '_first_price'] = $_settings['weight_postage_' . $geo_zone['geo_zone_id'] . '_first_price'];
            } else {
                $data['weight_postage_' . $geo_zone['geo_zone_id'] . '_first_price'] = '';
            }
            if (isset($this->request->post['weight_express_' . $geo_zone['geo_zone_id'] . '_next_weight'])) {
                $data['weight_postage_' . $geo_zone['geo_zone_id'] . '_next_weight'] = $this->request->post['weight_postage_' . $geo_zone['geo_zone_id'] . '_next_weight'];
            } elseif (isset($_settings['weight_postage_' . $geo_zone['geo_zone_id'] . '_next_weight'])) {
                $data['weight_postage_' . $geo_zone['geo_zone_id'] . '_next_weight'] = $_settings['weight_postage_' . $geo_zone['geo_zone_id'] . '_next_weight'];
            } else {
                $data['weight_postage_' . $geo_zone['geo_zone_id'] . '_next_weight'] = '';
            }
            if (isset($this->request->post['weight_express_' . $geo_zone['geo_zone_id'] . '_next_price'])) {
                $data['weight_postage_' . $geo_zone['geo_zone_id'] . '_next_price'] = $this->request->post['weight_postage_' . $geo_zone['geo_zone_id'] . '_next_price'];
            } elseif (isset($_settings['weight_postage_' . $geo_zone['geo_zone_id'] . '_next_price'])) {
                $data['weight_postage_' . $geo_zone['geo_zone_id'] . '_next_price'] = $_settings['weight_postage_' . $geo_zone['geo_zone_id'] . '_next_price'];
            } else {
                $data['weight_postage_' . $geo_zone['geo_zone_id'] . '_next_price'] = '';
            }

            // Ems
            if (isset($this->request->post['weight_ems_' . $geo_zone['geo_zone_id'] . '_first_weight'])) {
                $data['weight_ems_' . $geo_zone['geo_zone_id'] . '_first_weight'] = $this->request->post['weight_ems_' . $geo_zone['geo_zone_id'] . '_first_weight'];
            } elseif (isset($_settings['weight_ems_' . $geo_zone['geo_zone_id'] . '_first_weight'])) {
                $data['weight_ems_' . $geo_zone['geo_zone_id'] . '_first_weight'] = $_settings['weight_ems_' . $geo_zone['geo_zone_id'] . '_first_weight'];
            } else {
                $data['weight_ems_' . $geo_zone['geo_zone_id'] . '_first_weight'] = '';
            }
            if (isset($this->request->post['weight_ems_' . $geo_zone['geo_zone_id'] . '_first_price'])) {
                $data['weight_ems_' . $geo_zone['geo_zone_id'] . '_first_price'] = $this->request->post['weight_ems_' . $geo_zone['geo_zone_id'] . '_first_price'];
            } elseif (isset($_settings['weight_ems_' . $geo_zone['geo_zone_id'] . '_first_price'])) {
                $data['weight_ems_' . $geo_zone['geo_zone_id'] . '_first_price'] = $_settings['weight_ems_' . $geo_zone['geo_zone_id'] . '_first_price'];
            } else {
                $data['weight_ems_' . $geo_zone['geo_zone_id'] . '_first_price'] = '';
            }
            if (isset($this->request->post['weight_ems_' . $geo_zone['geo_zone_id'] . '_next_weight'])) {
                $data['weight_ems_' . $geo_zone['geo_zone_id'] . '_next_weight'] = $this->request->post['weight_ems_' . $geo_zone['geo_zone_id'] . '_next_weight'];
            } elseif (isset($_settings['weight_ems_' . $geo_zone['geo_zone_id'] . '_next_weight'])) {
                $data['weight_ems_' . $geo_zone['geo_zone_id'] . '_next_weight'] = $_settings['weight_ems_' . $geo_zone['geo_zone_id'] . '_next_weight'];
            } else {
                $data['weight_ems_' . $geo_zone['geo_zone_id'] . '_next_weight'] = '';
            }
            if (isset($this->request->post['weight_ems_' . $geo_zone['geo_zone_id'] . '_next_price'])) {
                $data['weight_ems_' . $geo_zone['geo_zone_id'] . '_next_price'] = $this->request->post['weight_ems_' . $geo_zone['geo_zone_id'] . '_next_price'];
            } elseif (isset($_settings['weight_ems_' . $geo_zone['geo_zone_id'] . '_next_price'])) {
                $data['weight_ems_' . $geo_zone['geo_zone_id'] . '_next_price'] = $_settings['weight_ems_' . $geo_zone['geo_zone_id'] . '_next_price'];
            } else {
                $data['weight_ems_' . $geo_zone['geo_zone_id'] . '_next_price'] = '';
            }

			if (isset($this->request->post['weight_' . $geo_zone['geo_zone_id'] . '_status'])) {
				$data['weight_' . $geo_zone['geo_zone_id'] . '_status'] = $this->request->post['weight_' . $geo_zone['geo_zone_id'] . '_status'];
			} elseif (isset($_settings['weight_' . $geo_zone['geo_zone_id'] . '_status'])) {
				$data['weight_' . $geo_zone['geo_zone_id'] . '_status'] = $_settings['weight_' . $geo_zone['geo_zone_id'] . '_status'];
			} else {
                $data['weight_' . $geo_zone['geo_zone_id'] . '_status'] = '0';
            }
		}

		$data['geo_zones'] = $geo_zones;

		if (isset($this->request->post['weight_tax_class_id'])) {
			$data['weight_tax_class_id'] = $this->request->post['weight_tax_class_id'];
		} elseif (isset($_settings['weight_tax_class_id'])) {
			$data['weight_tax_class_id'] = $_settings['weight_tax_class_id'];
		} else {
            $data['weight_tax_class_id'] = '';
        }

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['weight_status'])) {
			$data['weight_status'] = $this->request->post['weight_status'];
		} elseif (isset($_settings['weight_status'])) {
			$data['weight_status'] = $_settings['weight_status'];
		} else {
            $data['weight_status'] = '0';
        }

		if (isset($this->request->post['weight_sort_order'])) {
			$data['weight_sort_order'] = $this->request->post['weight_sort_order'];
		} elseif (isset($_settings['weight_sort_order'])) {
			$data['weight_sort_order'] = $_settings['weight_sort_order'];
		} else {
            $data['weight_sort_order'] = '';
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/shipping/weight.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/shipping/weight.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/shipping/weight.tpl', $data));
        }
	}

	protected function validate() {
		/*if (!$this->user->hasPermission('modify', 'shipping/weight')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}*/

		return !$this->error;
	}
}