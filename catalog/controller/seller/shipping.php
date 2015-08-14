<?php
class ControllerSellerShipping extends Controller {
	private $error = array();

	public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('seller/shipping', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }
        if (!$this->customer->isSeller()) {
            $this->session->data['redirect'] = $this->url->link('seller/shipping', '', 'SSL');

            $this->response->redirect($this->url->link('seller/shop/add', '', 'SSL'));
        }

        $this->load->language('seller/shipping');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/extension');

		$this->getList();
	}

	public function install() {die('access denied!');
		$this->load->language('seller/shipping');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/extension');

		if ($this->validate()) {
			$this->model_extension_extension->install('shipping', $this->request->get['extension']);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'shipping/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'shipping/' . $this->request->get['extension']);

			// Call install method if it exsits
			$this->load->controller('shipping/' . $this->request->get['extension'] . '/install');

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('seller/shipping', '', 'SSL'));
		}

		$this->getList();
	}

	public function uninstall() {die('access denied!');
		$this->load->language('seller/shipping');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/extension');

		if ($this->validate()) {
			$this->model_extension_extension->uninstall('shipping', $this->request->get['extension']);

			$this->load->model('setting/setting');

			$this->model_setting_setting->deleteSetting($this->request->get['extension']);

			// Call uninstall method if it exsits
			$this->load->controller('shipping/' . $this->request->get['extension'] . '/uninstall');

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('seller/shipping', '', 'SSL'));
		}

		$this->getList();
	}

	public function getList() {
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('seller/shipping', '', 'SSL')
		);
				
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_install'] = $this->language->get('button_install');
		$data['button_uninstall'] = $this->language->get('button_uninstall');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$this->load->model('extension/extension');

		$extensions = $this->model_extension_extension->getInstalled('shipping');

		foreach ($extensions as $key => $value) {
			if (!file_exists(DIR_APPLICATION . 'controller/shipping/' . $value . '.php')) {
				//$this->model_extension_extension->uninstall('shipping', $value);

				unset($extensions[$key]);
			}
		}

        $this->load->model('seller/setting');

		$data['extensions'] = array();

		$files = glob(DIR_APPLICATION . 'controller/shipping/*.php');

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');
                if (!in_array($extension, $extensions)) continue;

				$this->load->language('shipping/' . $extension);

                $_settings = $this->model_seller_setting->getSetting($extension, $this->customer->getShopId());
                $_status = 0;
                if (array_key_exists($extension . '_status', $_settings)) {
                    $_status = is_array($_settings[$extension . '_status']) ? array_shift($_settings[$extension . '_status']) : $_settings[$extension . '_status'];
                }
                $_sort = '';
                if (array_key_exists($extension . '_sort_order', $_settings)) {
                    $_sort = is_array($_settings[$extension . '_sort_order']) ? array_shift($_settings[$extension . '_sort_order']) : $_settings[$extension . '_sort_order'];
                }

				$data['extensions'][] = array(
					'name'       => $this->language->get('text_title'),
					'status'     => $_status ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'sort_order' => $_sort,
					'install'    => $this->url->link('seller/shipping/install', 'extension=' . $extension, 'SSL'),
					'uninstall'  => $this->url->link('seller/shipping/uninstall', 'extension=' . $extension, 'SSL'),
					'installed'  => in_array($extension, $extensions),
					'edit'       => $this->url->link('shipping/' . $extension . '/edit', '', 'SSL')
				);
			}
		}

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/shipping.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/shipping.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/seller/shipping.tpl', $data));
        }
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'seller/shipping')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}