<?php
class ControllerSellerDesign extends Controller {
	private $error = array();

	public function index() {die('access denied!');
		$this->load->language('design/banner');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/banner');

		$this->getList();
	}

	public function add() {die('access denied!');
		$this->load->language('design/banner');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/banner');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_design_banner->addBanner($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('design/banner', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('seller/design/edit', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }
        if (!$this->customer->isSeller()) {
            $this->session->data['redirect'] = $this->url->link('seller/design/edit', '', 'SSL');

            $this->response->redirect($this->url->link('seller/shop/add', '', 'SSL'));
        }

        $this->load->language('seller/design');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/banner');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_design_banner->editShopBannerImages($this->request->post);

            if (isset($this->request->post['config_design_html_block']) && isset($this->request->post['config_design_html_status'])) {
                $this->load->model('seller/setting');

                $this->model_seller_setting->editSetting('config', array(
                    'config_design_html_block' => $this->request->post['config_design_html_block'],
                    'config_design_html_status' => (int)$this->request->post['config_design_html_status'],
                    'config_block_latest_limit' => (int)$this->request->post['config_block_latest_limit'],
                    'config_block_latest_status' => (int)$this->request->post['config_block_latest_status']
                ), $this->customer->getShopId());
            }

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('seller/design/edit', '', 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {die('access denied!');
		$this->load->language('design/banner');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/banner');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $banner_id) {
				$this->model_design_banner->deleteBanner($banner_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('design/banner', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('design/banner', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		$data['add'] = $this->url->link('design/banner/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('design/banner/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['banners'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$banner_total = $this->model_design_banner->getTotalBanners();

		$results = $this->model_design_banner->getBanners($filter_data);

		foreach ($results as $result) {
			$data['banners'][] = array(
				'banner_id' => $result['banner_id'],
				'name'      => $result['name'],
				'status'    => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'edit'      => $this->url->link('design/banner/edit', 'token=' . $this->session->data['token'] . '&banner_id=' . $result['banner_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('design/banner', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('design/banner', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $banner_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('design/banner', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($banner_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($banner_total - $this->config->get('config_limit_admin'))) ? $banner_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $banner_total, ceil($banner_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/banner_list.tpl', $data));
	}

	protected function getForm() {
        $this->document->addScript('catalog/view/javascript/summernote/summernote.js');
        $this->document->addStyle('catalog/view/javascript/summernote/summernote.css');

        $data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = $this->language->get('heading_title');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_default'] = $this->language->get('text_default');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_link'] = $this->language->get('entry_link');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_filter'] = $this->language->get('entry_filter');
        $data['entry_limit'] = $this->language->get('entry_limit');

        $data['help_category'] = $this->language->get('help_category');
        $data['help_filter'] = $this->language->get('help_filter');

        $data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_banner_add'] = $this->language->get('button_banner_add');
		$data['button_remove'] = $this->language->get('button_remove');

        $data['tab_banner'] = $this->language->get('tab_banner');
        $data['tab_block'] = $this->language->get('tab_block');

        if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['banner_image'])) {
			$data['error_banner_image'] = $this->error['banner_image'];
		} else {
			$data['error_banner_image'] = array();
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('seller_home'),
            'href' => $this->url->link('seller/home', '', 'SSL')
        );

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('seller/design/edit', '', 'SSL')
		);

        $data['action'] = $this->url->link('seller/design/edit', '', 'SSL');

		$data['cancel'] = $this->url->link('seller/design/edit', '', 'SSL');

		if (isset($this->request->get['banner_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			//$banner_info = $this->model_design_banner->getBanner($this->request->get['banner_id']);
            $banner_info = array();
		}

        $this->load->model('tool/image');
        $this->load->model('catalog/filter');
        $this->load->model('seller/category');

        $blocks = $this->model_design_banner->getShopBlocks($this->customer->getShopId());
        for ($i=0; $i<5; $i++) {
            if (isset($this->request->post['block'][$i+1]['name'])) {
                $data['block'][$i+1]['name'] = $this->request->post['block'][$i+1]['name'];
            } elseif (!empty($blocks[$i])) {
                $data['block'][$i+1]['name'] = $blocks[$i]['name'];
            } else {
                $data['block'][$i+1]['name'] = '';
            }

            if (isset($this->request->post['block'][$i+1]['link'])) {
                $data['block'][$i+1]['link'] = $this->request->post['block'][$i+1]['link'];
            } elseif (!empty($blocks[$i])) {
                $data['block'][$i+1]['link'] = $blocks[$i]['link'];
            } else {
                $data['block'][$i+1]['link'] = '';
            }

            if (isset($this->request->post['block'][$i+1]['sort'])) {
                $data['block'][$i+1]['sort'] = $this->request->post['block'][$i+1]['sort'];
            } elseif (!empty($blocks[$i])) {
                $data['block'][$i+1]['sort'] = $blocks[$i]['sort_order'];
            } else {
                $data['block'][$i+1]['sort'] = '';
            }

            if (isset($this->request->post['block'][$i+1]['limit'])) {
                $data['block'][$i+1]['limit'] = $this->request->post['block'][$i+1]['limit'];
            } elseif (!empty($blocks[$i])) {
                $data['block'][$i+1]['limit'] = $blocks[$i]['limit'];
            } else {
                $data['block'][$i+1]['limit'] = '';
            }

            if (isset($this->request->post['block'][$i+1]['status'])) {
                $data['block'][$i+1]['status'] = $this->request->post['block'][$i+1]['status'];
            } elseif (!empty($blocks[$i])) {
                $data['block'][$i+1]['status'] = $blocks[$i]['status'];
            } else {
                $data['block'][$i+1]['status'] = '';
            }

            if (isset($this->request->post['block'][$i+1]['image']) && is_file(DIR_IMAGE . $this->request->post['block'][$i+1]['image'])) {
                $data['block'][$i+1]['thumb'] = $this->model_tool_image->resize($this->request->post['block'][$i+1]['image'], 100, 100);
                $data['block'][$i+1]['image'] = $this->request->post['block'][$i+1]['image'];
            } elseif (!empty($blocks[$i]) && is_file(DIR_IMAGE . $blocks[$i]['image'])) {
                $data['block'][$i+1]['thumb'] = $this->model_tool_image->resize($blocks[$i]['image'], 100, 100);
                $data['block'][$i+1]['image'] = $blocks[$i]['image'];
            } else {
                $data['block'][$i+1]['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
                $data['block'][$i+1]['image'] = '';
            }

            if (isset($this->request->post['block'][$i+1]['filter'])) {
                $filters = $this->request->post['block'][$i+1]['filter'];
            } elseif (!empty($blocks[$i])) {
                $filters = $blocks[$i]['filter'];
            } else {
                $filters = array();
            }

            if (!is_array($filters)) $filters = explode(',', $filters);
            $data['block'][$i+1]['filters'] = array();

            foreach ($filters as $filter_id) {
                $filter_info = $this->model_catalog_filter->getFilter($filter_id);

                if ($filter_info) {
                    $data['block'][$i+1]['filters'][] = array(
                        'filter_id' => $filter_info['filter_id'],
                        'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
                    );
                }
            }

            if (isset($this->request->post['block'][$i+1]['category'])) {
                $categories = $this->request->post['block'][$i+1]['category'];
            } elseif (!empty($blocks[$i])) {
                $categories = $blocks[$i]['category'];
            } else {
                $categories = array();
            }

            if (!is_array($categories)) $categories = explode(',', $categories);
            $data['block'][$i+1]['categories'] = array();

            foreach ($categories as $category_id) {
                $category_info = $this->model_seller_category->getCategory($category_id);

                if ($category_info) {
                    $data['block'][$i+1]['categories'][$category_info['category_id']] = array(
                        'category_id' => $category_info['category_id'],
                        'name' => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
                    );
                }
            }
        }

		//$data['token'] = $this->session->data['token'];

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$this->load->model('localisation/language');

        $_languages = $this->model_localisation_language->getLanguages();
        foreach ($_languages as $_language) {
            if ($_language['code'] <> $this->config->get('config_language')) continue;
            $data['languages'][] = $_language;
        }

		if (isset($this->request->post['banner_image'])) {
			$banner_images = $this->request->post['banner_image'];
		} else {
			$banner_images = $this->model_design_banner->getShopBannerImages($this->customer->getShopId());
		}

		$data['banner_images'] = array();

		foreach ($banner_images as $banner_image) {
			if (is_file(DIR_IMAGE . $banner_image['image'])) {
				$image = $banner_image['image'];
				$thumb = $banner_image['image'];
			} else {
				$image = '';
				$thumb = 'no_image.png';
			}

			$data['banner_images'][] = array(
				'banner_image_description' => $banner_image['banner_image_description'],
				'link'                     => $banner_image['link'],
				'image'                    => $image,
				'thumb'                    => $this->model_tool_image->resize($thumb, 100, 100),
				'sort_order'               => $banner_image['sort_order']
			);
		}

        $data['block_categories'] = array();

        $this->load->model('seller/setting');

        $store_info = $this->model_seller_setting->getSetting('config', $this->customer->getShopId());

        // Shop home url.
        $data['preview_url'] = HTTP_SERVER . $store_info['config_key'] . '&preview=1';

        if (isset($this->request->post['config_design_html_block'])) {
            $data['config_design_html_block'] = $this->request->post['config_design_html_block'];
        } elseif (isset($store_info['config_design_html_block'])) {
            $data['config_design_html_block'] = $store_info['config_design_html_block'];
        } else {
            $data['config_design_html_block'] = '';
        }

        if (isset($this->request->post['config_design_html_status'])) {
            $data['config_design_html_status'] = $this->request->post['config_design_html_status'];
        } elseif (isset($store_info['config_design_html_status'])) {
            $data['config_design_html_status'] = $store_info['config_design_html_status'];
        } else {
            $data['config_design_html_status'] = '';
        }

        if (isset($this->request->post['config_block_latest_limit'])) {
            $data['config_latest_limit'] = $this->request->post['config_block_latest_limit'];
        } elseif (isset($store_info['config_block_latest_limit'])) {
            $data['config_latest_limit'] = $store_info['config_block_latest_limit'];
        } else {
            $data['config_latest_limit'] = '';
        }

        if (isset($this->request->post['config_block_latest_status'])) {
            $data['config_latest_status'] = $this->request->post['config_block_latest_status'];
        } elseif (isset($store_info['config_block_latest_status'])) {
            $data['config_latest_status'] = $store_info['config_block_latest_status'];
        } else {
            $data['config_latest_status'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/design.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/design.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/seller/design.tpl', $data));
        }
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'design/banner')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (isset($this->request->post['banner_image'])) {
			foreach ($this->request->post['banner_image'] as $banner_image_id => $banner_image) {
				foreach ($banner_image['banner_image_description'] as $language_id => $banner_image_description) {
					if ((utf8_strlen($banner_image_description['title']) < 2) || (utf8_strlen($banner_image_description['title']) > 64)) {
						$this->error['banner_image'][$banner_image_id][$language_id] = $this->language->get('error_title');
					}
				}
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'design/banner')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}