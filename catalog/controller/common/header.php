<?php
class ControllerCommonHeader extends Controller {
	public function index($_settings = array()) {
		$data['title'] = $this->document->getTitle();

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');
		if ($this->config->get('config_google_analytics_status')) {
			$data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
		} else {
			$data['google_analytics'] = '';
		}
		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$data['icon'] = $server . 'image/' . $this->config->get('config_icon');
		} else {
			$data['icon'] = '';
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');

        $this->load->model('account/wishlist');
        $product_wish = $this->model_account_wishlist->getProductWishlist();
        $shop_wish = $this->model_account_wishlist->getShopWishlist();

        $data['text_home'] = $this->language->get('text_home');
        $data['text_shop_home'] = $this->language->get('text_shop_home');
		$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (count($product_wish) + count($shop_wish)));
		$data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFullName(), $this->url->link('account/logout', '', 'SSL'));

        $data['text_seller'] = $this->customer->isSeller() ? $this->language->get('text_seller') : $this->language->get('text_seller_new');
        $data['text_account'] = $this->language->get('text_account');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_all'] = $this->language->get('text_all');

        $data['text_limit_buy'] = $this->language->get('text_limit_buy');
        $data['text_on_sale'] = $this->language->get('text_on_sale');
        $data['text_on_going'] = $this->language->get('text_on_going');
        $data['text_best_shop'] = $this->language->get('text_best_shop');
        $data['text_auction'] = $this->language->get('text_auction');
        $data['text_custom_buy'] = $this->language->get('text_custom_buy');
        $data['text_community'] = $this->language->get('text_community');
        $data['text_benefits'] = $this->language->get('text_benefits');

        $data['link_limit_buy'] = $this->url->link('product/special', 'type=limit');
        $data['link_on_sale'] = $this->url->link('product/special', 'type=infinite');
        $data['link_on_going'] = $this->url->link('product/search', 'type=latest&sort=p.date_added&order=DESC');
        $data['link_best_shop'] = $this->url->link('product/shop');
        $data['link_auction'] = $this->url->link('product/special/auction');
        $data['link_custom_buy'] = $this->url->link('purchase/home');
        $data['link_community'] = 'http://www.zhubaojie.com/';
        $data['link_benefits'] = $this->url->link('product/search', 'type=reward');

        $data['home'] = $this->url->link('common/home');
        $data['shop_url'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$data['logged'] = $this->customer->isLogged();
        $data['nickname'] = $this->customer->getFullName();
        $data['isSeller'] = $this->customer->isSeller();
        $data['shop_id'] = $this->config->get('config_store_id');
        $data['shop_name'] = $this->config->get('config_name');
        $data['shop_key'] = $this->config->get('config_key');
        $data['account'] = $this->url->link('account/account', '', 'SSL');
        $data['url_seller'] = $this->customer->isSeller() ? $this->url->link('seller/home', '', 'SSL') : $this->url->link('seller/shop/add', '', 'SSL');
		$data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['login'] = $this->url->link('account/login', '', 'SSL');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		$data['download'] = $this->url->link('account/download', '', 'SSL');
		$data['logout'] = $this->url->link('account/logout', '', 'SSL');
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');

		$status = true;

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$robots = explode("\n", str_replace(array("\r\n", "\r"), "\n", trim($this->config->get('config_robots'))));

			foreach ($robots as $robot) {
				if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
					$status = false;

					break;
				}
			}
		}

        $this->load->model('tool/image');

		// Menu
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

        if (isset($this->request->get['route'])) {
            $route = (string)$this->request->get['route'];
        } else {
            $route = 'common/home';
        }

        $data['shop_banner'] = '';
        $template = 'common/header.tpl';
        $shop_id = $this->config->get('config_store_id');

        /*if ($route == 'catalog/category' && $shop_id > 0) {
            $_settings['_StoreId_'] = $shop_id;
        }*/

        if (isset($_settings['_StoreId_']) && (int)$_settings['_StoreId_'] > 0) {
            $shop_id = $_settings['_StoreId_'];
            $this->load->model('setting/setting');
            $shop_setting = $this->model_setting_setting->getSetting('config', $shop_id);

            if (isset($shop_setting['config_key'])) {
                if (is_file(DIR_IMAGE . $shop_setting['config_image'])) {
                    $data['shop_banner'] = $server . 'image/' . $shop_setting['config_image'];
                }

                // Shop home url.
                $data['shop_url'] = $server . $shop_setting['config_key'];
                $data['shop_id'] = $shop_id;
                $data['shop_name'] = $shop_setting['config_name'];
                $data['shop_key'] = $shop_setting['config_key'];
                $data['comment_url'] = $this->url->link('shop/comment', 'shop_id='.$shop_id);

                $this->load->model('seller/shop');
                $data['shop_rating'] = $this->model_seller_shop->getStoreRatings($shop_id);
                $data['average_rating'] = $this->model_seller_shop->getAverageRatings();

                $this->load->model('localisation/zone');
                $data['shop_zone'] = '';
                if (isset($shop_setting['config_zone_id'])) {
                    $zone_info = $this->model_localisation_zone->getZone($shop_setting['config_zone_id']);
                    if ($zone_info) $data['shop_zone'] = $zone_info['name'];
                }
                $data['shop_city'] = '';
                if (isset($shop_setting['config_city_id'])) {
                    $city_info = $this->model_localisation_zone->getCity($shop_setting['config_city_id']);
                    if ($city_info) $data['shop_city'] = $city_info['name'];
                }

                $this->load->model('account/wishlist');
                $data['total_wish'] = $this->model_account_wishlist->getTotalShopWished($shop_id);

                $this->load->model('catalog/product');
                $data['total_product'] = $this->model_catalog_product->getTotalProducts(array(
                    'filter_store_id' => $shop_id
                ));

                $this->load->model('sale/order');
                $data['total_sell'] = $this->model_sale_order->getTotalSellProducts($shop_id);
                if (empty($data['total_sell'])) $data['total_sell'] = '0';

                $system_setting = $this->model_setting_setting->getSetting('config', 0);
                $data['name'] = $system_setting['config_name'];

                $shop_data = $this->model_seller_shop->getStore($shop_id);
                $data['shop_create_date'] = date('Y-m-d', strtotime($shop_data['date_added']));

                if ($this->customer->isLogged()) {
                    $avatar = $this->model_tool_image->resize('no_image.png', 50, 50);
                    $shop_data['custom_field'] = unserialize($shop_data['custom_field']);
                    if (isset($shop_data['custom_field'][2]) && is_file(DIR_IMAGE . $shop_data['custom_field'][2])) {
                        $avatar = $this->model_tool_image->resize($shop_data['custom_field'][2], 50, 50);
                    }
                    $data['link_live_chat'] = 'javascript:void(0);" onclick="activeLiveChat(this)" data-user="'.$shop_data['customer_id'].'" data-name="'.$shop_data['fullname'].'" data-avatar="'.$avatar;
                } else {
                    $data['link_live_chat'] = $this->url->link('account/login', '', 'SSL');
                }

                if (is_file(DIR_IMAGE . $system_setting['config_icon'])) {
                    $data['icon'] = $server . 'image/' . $system_setting['config_icon'];
                } else {
                    $data['icon'] = '';
                }

                if (is_file(DIR_IMAGE . $system_setting['config_logo'])) {
                    $data['logo'] = $server . 'image/' . $system_setting['config_logo'];
                } else {
                    $data['logo'] = '';
                }

                $template = 'shop/header.tpl';
            } else {
                $shop_id = 0;
            }
        }

        //$categories = $this->model_catalog_category->getCategories(0);
        $categories = $this->model_catalog_category->getCategoriesByShop($shop_id, 0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategoriesByShop($shop_id, $category['category_id']);

				foreach ($children as $child) {
                    if (!$child['top']) continue;

                    $filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

                    if ($shop_id > 0) {
                        // Shop home url.
                        //$href = '/shop/'.$data['shop_key'].'/category/?path=' . $category['category_id'] . '_' . $child['category_id'];
                        $href = $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']);
                    } else {
                        $href = $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']);
                    }

					$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
						'href'  => $href
					);
				}

				// Level 1
                if ($shop_id > 0) {
                    // Shop home url.
                    //$href = '/shop/'.$data['shop_key'].'/category/?path=' . $category['category_id'];
                    $href = $this->url->link('product/category', 'path=' . $category['category_id']);
                } else {
                    $href = $this->url->link('product/category', 'path=' . $category['category_id']);
                }

				$data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $href
				);
			}
		}

        $data['avatar'] = '';
        if ($this->customer->isLogged()) {
            if ($this->customer->getAvatar() && is_file(DIR_IMAGE . $this->customer->getAvatar())) {
                $data['avatar'] = $this->model_tool_image->resize($this->customer->getAvatar(), 25, 25);
            } else {
                $data['avatar'] = $this->model_tool_image->resize('no_image.png', 25, 25);
            }
        }

		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');

        $data['is_shop_home'] = false;

		// For page specific css
		if (isset($this->request->get['route'])) {
            if ($this->request->get['route'] == 'shop/home') $data['is_shop_home'] = true;

            if (isset($this->request->get['product_id'])) {
				$class = '-' . $this->request->get['product_id'];
			} elseif (isset($this->request->get['path'])) {
				$class = '-' . $this->request->get['path'];
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$class = '-' . $this->request->get['manufacturer_id'];
			} else {
				$class = '';
			}

			$data['class'] = str_replace('/', '-', $this->request->get['route']) . $class;
		} else {
			$data['class'] = 'common-home';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $template)) {
			return $this->load->view($this->config->get('config_template') . '/template/' . $template, $data);
		} else {
			return $this->load->view('default/template/common/header.tpl', $data);
		}
	}
}