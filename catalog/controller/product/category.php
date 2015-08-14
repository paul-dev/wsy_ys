<?php
class ControllerProductCategory extends Controller {
    private $allow_price = array(
        '0~1000', '1001~5000', '5001~10000', '10001~100000', '100001'
    );
	public function index() {
		$this->load->language('product/category');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

        $this->load->model('setting/setting');

        $shop_id = $this->config->get('config_store_id');
        if ($shop_id == 0) {
            if (isset($this->request->get['path'])) {
                $parts = explode('_', (string)$this->request->get['path']);
                $category_id = (int)array_pop($parts);
                $_stores = $this->model_catalog_category->getCategoryStores($category_id);
                foreach ($_stores as $_store) {
                    if ((int)$_store['store_id'] !== 0) {
                        $shop_id = $_store['store_id'];
                        break;
                    }
                }
            }
        }

        $shop_setting = $this->model_setting_setting->getSetting('config', $shop_id);
        if (!isset($shop_setting['config_key'])) {
            $shop_id = 0;
        }

        if (isset($this->request->get['price']) && in_array($this->request->get['price'], $this->allow_price)) {
            $filter_price = $this->request->get['price'];
        } else {
            $filter_price = '';
        }

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
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

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_product_limit');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['path'])) {
			$url = '';

            if (!empty($filter_price)) {
                $url .= '&price=' . $filter_price;
            }

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id, $shop_id);

				if ($category_info) {
					if ($shop_id > 0) {
                        // Shop home url.
                        //$href = HTTP_SERVER . 'shop/' . $shop_setting['config_key'] . '/category/?path=' . $path . $url;
                        $href = $this->url->link('product/category', 'path=' . $path . $url);
                    } else {
                        $href = $this->url->link('product/category', 'path=' . $path . $url);
                    }
                    $data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $href
					);
				}
			}
		} else {
			$category_id = 0;
		}

		$category_info = $this->model_catalog_category->getCategory($category_id, $shop_id);

		if ($category_info) {
			$this->document->setTitle($category_info['meta_title']);
			$this->document->setDescription($category_info['meta_description']);
			$this->document->setKeywords($category_info['meta_keyword']);
			$this->document->addLink($this->url->link('product/category', 'path=' . $this->request->get['path']), 'canonical');

			$data['heading_title'] = $category_info['name'];

			$data['text_refine'] = $this->language->get('text_refine');
			$data['text_empty'] = $this->language->get('text_empty');
			$data['text_quantity'] = $this->language->get('text_quantity');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_price'] = $this->language->get('text_price');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_points'] = $this->language->get('text_points');
			$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
			$data['text_sort'] = $this->language->get('text_sort');
			$data['text_limit'] = $this->language->get('text_limit');

			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_list'] = $this->language->get('button_list');
			$data['button_grid'] = $this->language->get('button_grid');

			// Set the last category breadcrumb
            if ($shop_id > 0) {
                // Shop home url.
                //$href = HTTP_SERVER . 'shop/' . $shop_setting['config_key'] . '/category/?path=' . $this->request->get['path'];
                $href = $this->url->link('product/category', 'path=' . $this->request->get['path']);
            } else {
                $href = $this->url->link('product/category', 'path=' . $this->request->get['path']);
            }
			$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $href
			);

			if ($category_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
			} else {
				$data['thumb'] = '';
			}

			$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
			$data['compare'] = $this->url->link('product/compare');

			$url = '';

            if (!empty($filter_price)) {
                $url .= '&price=' . $filter_price;
            }

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['categories'] = array();

			$results = $this->model_catalog_category->getCategories($category_id, $shop_id);

			foreach ($results as $result) {
				$filter_data = array(
					'filter_category_id'  => $result['category_id'],
					'filter_sub_category' => true
				);

                if ($shop_id > 0) {
                    // Shop home url.
                    //$href = HTTP_SERVER . 'shop/' . $shop_setting['config_key'] . '/category/?path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url;
                    $href = $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url);
                } else {
                    $href = $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url);
                }

                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
                }

				$data['categories'][] = array(
					'name'  => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
					'thumb' => $image,
                    'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                    'top' => $result['top'],
                    'href'  => $href
				);
			}

			$data['products'] = array();

			$filter_data = array(
				'filter_category_id' => $category_id,
                'filter_sub_category' => true,
				'filter_filter'      => $filter,
                'filter_price'       => $filter_price,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);

			$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

			$results = $this->model_catalog_product->getProducts($filter_data);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

                //$product_store = $this->model_catalog_product->getProductStores($result['product_id']);
                //$product_store = array_pop($product_store);
                $product_store = $this->model_catalog_product->getProductStore($result['product_id']);
                if ($product_store && isset($product_store['shop_name'])) {
                    // Shop home url.
                    $product_store['shop_url'] = HTTP_SERVER . $product_store['shop_key'];

                    if ($this->customer->isLogged()) {
                        $avatar = $this->model_tool_image->resize('no_image.png', 50, 50);
                        $product_store['custom_field'] = unserialize($product_store['custom_field']);
                        if (isset($product_store['custom_field'][2]) && is_file(DIR_IMAGE . $product_store['custom_field'][2])) {
                            $avatar = $this->model_tool_image->resize($product_store['custom_field'][2], 50, 50);
                        }
                        $product_store['link_live_chat'] = 'javascript:void(0);" onclick="activeLiveChat(this)" data-user="'.$product_store['customer_id'].'" data-name="'.$product_store['fullname'].'" data-avatar="'.$avatar;
                    } else {
                        $product_store['link_live_chat'] = $this->url->link('account/login', '', 'SSL');
                    }

                    //$this->load->model('setting/setting');
                    $shop_data = $this->model_setting_setting->getSetting('config', $product_store['store_id']);

                    $this->load->model('localisation/zone');
                    $product_store['shop_zone'] = '';
                    if (isset($shop_data['config_zone_id'])) {
                        $zone_info = $this->model_localisation_zone->getZone($shop_data['config_zone_id']);
                        if ($zone_info) $product_store['shop_zone'] = $zone_info['name'];
                    }
                    $product_store['shop_city'] = '';
                    if (isset($shop_data['config_city_id'])) {
                        $city_info = $this->model_localisation_zone->getCity($shop_data['config_city_id']);
                        if ($city_info) $product_store['shop_city'] = $city_info['name'];
                    }
                } else {
                    $product_store['shop_name'] = '';
                    $product_store['shop_url'] = '';
                    $product_store['link_live_chat'] = 'javascript:void(0);';
                    $product_store['shop_zone'] = '';
                    $product_store['shop_city'] = '';
                }

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $rating,
                    'shop_name'   => $product_store['shop_name'],
                    'shop_url'    => $product_store['shop_url'],
                    'shop_location' => $product_store['shop_zone'] . $product_store['shop_city'],
                    'link_live_chat' => $product_store['link_live_chat'],
					'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url)
				);
			}

			$url = '';

            if (!empty($filter_price)) {
                $url .= '&price=' . $filter_price;
            }

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

            if ($shop_id > 0) {
                // Shop home url.
                //$href = HTTP_SERVER . 'shop/' . $shop_setting['config_key'] . '/category/?path=' . $this->request->get['path'];
                $href = $this->url->link('product/category', 'path=' . $this->request->get['path']);
            } else {
                $href = $this->url->link('product/category', 'path=' . $this->request->get['path']);
            }

			$data['sorts'] = array();

/*			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $href . '&sort=p.sort_order&order=ASC' . $url
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $href . '&sort=pd.name&order=ASC' . $url
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $href . '&sort=pd.name&order=DESC' . $url
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $href . '&sort=p.price&order=ASC' . $url
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $href . '&sort=p.price&order=DESC' . $url
			);

			if ($this->config->get('config_review_status')) {
				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $href . '&sort=rating&order=DESC' . $url
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $href . '&sort=rating&order=ASC' . $url
				);
			}

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $href . '&sort=p.model&order=ASC' . $url
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $href . '&sort=p.model&order=DESC' . $url
			);*/

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_viewed_desc'),
                'value' => 'p.viewed-DESC',
                'href'  => $href . '&sort=p.viewed&order=DESC' . $url
            );
            $data['sorts'][] = array(
                'text'  => $this->language->get('text_sell_desc'),
                'value' => 'total_sell-DESC',
                'href'  => $href . '&sort=total_sell&order=DESC' . $url
            );
            $data['sorts'][] = array(
                'text'  => $this->language->get('text_date_desc'),
                'value' => 'p.date_added-DESC',
                'href'  => $href . '&sort=p.date_added&order=DESC' . $url
            );

			$url = '';

            if (!empty($filter_price)) {
                $url .= '&price=' . $filter_price;
            }

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get('config_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $href . $url . '&limit=' . $value
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

            $data['filter_prices'] = array();
            $data['filter_prices'][] = array(
                'text'  => '全部',
                'value' => '',
                'href'  => $href . $url
            );
            $data['filter_prices'][] = array(
                'text'  => '0~1000',
                'value' => '0~1000',
                'href'  => $href . '&price=0~1000' . $url
            );
            $data['filter_prices'][] = array(
                'text'  => '1001~5000',
                'value' => '1001~5000',
                'href'  => $href . '&price=1001~5000' . $url
            );
            $data['filter_prices'][] = array(
                'text'  => '5001~10000',
                'value' => '5001~10000',
                'href'  => $href . '&price=5001~10000' . $url
            );
            $data['filter_prices'][] = array(
                'text'  => '10001~100000',
                'value' => '10001~100000',
                'href'  => $href . '&price=10001~100000' . $url
            );
            $data['filter_prices'][] = array(
                'text'  => '10万以上',
                'value' => '100001',
                'href'  => $href . '&price=100001' . $url
            );

            $data['filter_price'] = $filter_price;

            $url = '';

            if (!empty($filter_price)) {
                $url .= '&price=' . $filter_price;
            }

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $href . $url . '&page={page}';

			$data['pagination'] = $pagination->render();

            //$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
            $data['results'] = sprintf($this->language->get('text_pagination_short'), ceil($product_total / $limit));
            $data['results'] .= ' 到 <input type="text" name="input_page_jump" value="'.$page.'" maxlength="4" class="form-control" style="width: 30px; text-align: center; display: inline; line-height: 30px; padding: 0px; height: 30px;" /> 页 <button id="button-page-jump" data-url="'.$href . $url.'" class="btn btn-primary">Go</button>';

			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');

            $column_setting = array(
                'filter_category_id' => $category_info['category_id'],
                'filter_name' => $category_info['name'],
                'filter_categories' => $data['categories']
            );

			$data['column_left'] = $this->load->controller('common/column_left', array('_StoreId_' => $shop_id));
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top', $column_setting);
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header', array('_StoreId_' => $shop_id));

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/category.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/category.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/product/category.tpl', $data));
			}
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/category', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
	}
}