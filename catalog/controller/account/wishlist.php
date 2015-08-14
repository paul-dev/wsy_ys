<?php
class ControllerAccountWishList extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/wishlist', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/wishlist');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		if (!isset($this->session->data['wishlist'])) {
			$this->session->data['wishlist'] = array();
		}

        $this->load->model('account/wishlist');
        $product_wish = $this->model_account_wishlist->getProductWishlist();
        $shop_wish = $this->model_account_wishlist->getShopWishlist();

		if (isset($this->request->get['remove'])) {
			$key = explode('_', $this->request->get['remove']);

			if (count($key) == 2) {
                if ($key[0] == 'p') {
                    $this->model_account_wishlist->removeProductWishlist($key[1]);
                }
                if ($key[0] == 's') {
                    $this->model_account_wishlist->removeShopWishlist($key[1]);
                }
            }

			$this->session->data['success'] = $this->language->get('text_remove');

			$this->response->redirect($this->url->link('account/wishlist'));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/wishlist')
		);

        $data['heading_title'] = $this->language->get('heading_title');
        $data['product_title'] = $this->language->get('product_title');
        $data['shop_title'] = $this->language->get('shop_title');

		$data['text_empty'] = $this->language->get('text_empty');

		$data['column_image'] = $this->language->get('column_image');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_stock'] = $this->language->get('column_stock');
		$data['column_price'] = $this->language->get('column_price');
        $data['column_action'] = $this->language->get('column_action');

        $data['column_shop_name'] = $this->language->get('column_shop_name');
        $data['column_shop_logo'] = $this->language->get('column_shop_logo');
        $data['column_shop_owner'] = $this->language->get('column_shop_owner');
        $data['column_shop_address'] = $this->language->get('column_shop_address');
        $data['column_shop_level'] = $this->language->get('column_shop_level');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['products'] = array();

		foreach ($product_wish as $_product) {
			$product_info = $this->model_catalog_product->getProduct($_product['product_id']);

			if ($product_info) {
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_wishlist_width'), $this->config->get('config_image_wishlist_height'));
				} else {
					$image = false;
				}

				if ($product_info['quantity'] <= 0) {
					$stock = $product_info['stock_status'];
				} elseif ($this->config->get('config_stock_display')) {
					$stock = $product_info['quantity'];
				} else {
					$stock = $this->language->get('text_instock');
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				$data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'thumb'      => $image,
					'name'       => $product_info['name'],
					'model'      => $product_info['model'],
					'stock'      => $stock,
					'price'      => $price,
					'special'    => $special,
					'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
					'remove'     => $this->url->link('account/wishlist', 'remove=p_' . $product_info['product_id'])
				);
			}
		}

        $data['shops'] = array();

        $this->load->model('setting/setting');

        foreach($shop_wish as $_shop) {
            $shop_info = $this->model_setting_setting->getSetting('config', $_shop['store_id']);
            if ($shop_info) {
                if ($shop_info['config_logo'] && is_file(DIR_IMAGE . $shop_info['config_logo'])) {
                    $image = $this->model_tool_image->resize($shop_info['config_logo'], $this->config->get('config_image_wishlist_width'), $this->config->get('config_image_wishlist_height'));
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_wishlist_width'), $this->config->get('config_image_wishlist_height'));
                }
            }

            $data['shops'][] = array(
                'shop_id' => $_shop['store_id'],
                'shop_name' => $shop_info['config_name'],
                'thumb' => $image,
                'shop_owner' => $shop_info['config_owner'],
                'shop_address' => $shop_info['config_address'],
                'shop_url' => HTTP_SERVER . $shop_info['config_key'],// Shop home url.
                'remove' => $this->url->link('account/wishlist', 'remove=s_' . $_shop['store_id'])
            );
        }

		$data['continue'] = $this->url->link('account/account', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/wishlist.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/wishlist.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/wishlist.tpl', $data));
		}
	}

	public function add() {
		$this->load->language('account/wishlist');

		$json = array();

		if (!isset($this->session->data['wishlist'])) {
			$this->session->data['wishlist'] = array();
		}

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

        if (isset($this->request->post['shop_id'])) {
            $shop_id = $this->request->post['shop_id'];
        } else {
            $shop_id = 0;
        }

		$this->load->model('catalog/product');

        $this->load->model('account/wishlist');

        $this->load->model('seller/shop');

        $product_wish = $this->model_account_wishlist->getProductWishlist();
        $shop_wish = $this->model_account_wishlist->getShopWishlist();

		$product_info = $this->model_catalog_product->getProduct($product_id);
        $shop_info = $this->model_seller_shop->getStore($shop_id);

        $total_wishlist = count($product_wish) + count($shop_wish);

		if ($product_info) {
			if (!array_key_exists($product_id, $product_wish)) {
				if ($this->customer->isLogged()) {
                    $this->model_account_wishlist->addProductWishlist($product_id);
                    $total_wishlist++;
                    $json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . (int)$this->request->post['product_id']), $product_info['name'], $this->url->link('account/wishlist'));
				} else {
					$json['info'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'), $this->url->link('product/product', 'product_id=' . (int)$this->request->post['product_id']), $product_info['name'], $this->url->link('account/wishlist'));
				}
			} else {
				$json['info'] = sprintf($this->language->get('text_exists'), $this->url->link('product/product', 'product_id=' . (int)$this->request->post['product_id']), $product_info['name'], $this->url->link('account/wishlist'));
			}

			$json['total'] = sprintf($this->language->get('text_wishlist'), $total_wishlist);
		} elseif ($shop_id && $shop_info) {
            if (!array_key_exists($shop_id, $shop_wish)) {
                if ($this->customer->isLogged()) {
                    $this->model_account_wishlist->addShopWishlist($shop_id);
                    $total_wishlist++;
                    // Shop home url.
                    $json['success'] = sprintf($this->language->get('text_success'), HTTP_SERVER . $shop_info['key'], $shop_info['name'], $this->url->link('account/wishlist'));
                } else {
                    // Shop home url.
                    $json['info'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'), HTTP_SERVER . $shop_info['config_key'], $shop_info['config_name'], $this->url->link('account/wishlist'));
                }
            } else {
                // Shop home url.
                $json['info'] = sprintf($this->language->get('text_exists'), HTTP_SERVER . $shop_info['key'], $shop_info['name'], $this->url->link('account/wishlist'));
            }

            $json['total'] = sprintf($this->language->get('text_wishlist'), $total_wishlist);
        }
        $product_info = $this->model_catalog_product->getProduct($product_id);
        $json['total_wish'] = $product_info['total_wish'];
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove() {

	}
}