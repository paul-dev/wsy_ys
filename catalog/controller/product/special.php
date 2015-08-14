<?php
class ControllerProductSpecial extends Controller {
    private $allow_price = array(
        '0~1000', '1001~5000', '5001~10000', '10001~100000', '100001'
    );

	public function index() {
		$this->load->language('product/special');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');
        $this->load->model('tool/utils');

        if (isset($this->request->get['type']) && in_array($this->request->get['type'], array('limit', 'infinite'))) {
            $type = $this->request->get['type'];
        } else {
            $type = '';
        }

        if (isset($this->request->get['price']) && in_array($this->request->get['price'], $this->allow_price)) {
            $filter_price = $this->request->get['price'];
        } else {
            $filter_price = '';
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

		$url = '';

        if (isset($this->request->get['type']) && in_array($this->request->get['type'], array('limit', 'infinite'))) {
            $url .= '&type=' . $this->request->get['type'];
        }

        if (!empty($filter_price)) {
            $url .= '&price=' . $filter_price;
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

        switch ($type) {
            case 'limit' :
                $this->document->setTitle($this->language->get('heading_title_limit'));
                $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('heading_title_limit'),
                    'href' => $this->url->link('product/special', $url)
                );
                $data['heading_title'] = $this->language->get('heading_title_limit');
                break;
            case 'infinite' :
                $this->document->setTitle($this->language->get('heading_title_infinite'));
                $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('heading_title_infinite'),
                    'href' => $this->url->link('product/special', $url)
                );
                $data['heading_title'] = $this->language->get('heading_title_infinite');
                break;
            default :
                $this->document->setTitle($this->language->get('heading_title'));
                $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('heading_title'),
                    'href' => $this->url->link('product/special', $url)
                );
                $data['heading_title'] = $this->language->get('heading_title');
                break;
        }

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
		$data['button_list'] = $this->language->get('button_list');
		$data['button_grid'] = $this->language->get('button_grid');
		$data['button_continue'] = $this->language->get('button_continue');
		
		$data['compare'] = $this->url->link('product/compare');

		$data['products'] = array();

		$filter_data = array(
			'filter_type'  => $type,
            'filter_price' => $filter_price,
            'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);

		$product_total = $this->model_catalog_product->getTotalProductSpecials($filter_data);

		$results = $this->model_catalog_product->getProductSpecials($filter_data);

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

                $special_data = $this->model_catalog_product->getProductSpecial($result['product_id']);
                if ($special_data && $special_data['date_end'] <> '0000-00-00 00:00:00') {
                    $now = date('Y-m-d H:i:s');
                    $special_date = array();
                    $special_date['day'] = $this->model_tool_utils->DateDiff('d', $now, $special_data['date_end']);
                    $now .= ' + ' . $special_date['day'] . ' day';
                    $special_date['hour'] = $this->model_tool_utils->DateDiff('h', $now, $special_data['date_end']);
                    $now .= ' + ' . $special_date['hour'] . ' hour';
                    $special_date['minute'] = $this->model_tool_utils->DateDiff('i', $now, $special_data['date_end']);
                    $now .= ' + ' . $special_date['minute'] . ' minute';
                    $special_date['second'] = $this->model_tool_utils->DateDiff('s', $now, $special_data['date_end']);
                    //$special_date = json_encode($data);
                } else {
                    $special_date = false;
                }
			} else {
				$special = false;
                $special_date = false;
			}

            if ($price && $special) {
                $discount = number_format($result['special'] / $result['price'] * 10, 1);
            } else {
                $discount = false;
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
            } else {
                $product_store['shop_name'] = '';
                $product_store['shop_url'] = '';
            }

			$data['products'][] = array(
				'product_id'  => $result['product_id'],
				'thumb'       => $image,
				'name'        => $result['name'],
				'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
				'price'       => $price,
				'special'     => $special,
                'special_date' => $special_date,
                'discount'    => $discount,
				'tax'         => $tax,
				'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
				'rating'      => $rating,
                'shop_name'   => $product_store['shop_name'],
                'shop_url'   => $product_store['shop_url'],
				'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url)
			);
		}

		$url = '';

        if (isset($this->request->get['type']) && in_array($this->request->get['type'], array('limit', 'infinite'))) {
            $url .= '&type=' . $this->request->get['type'];
        }

        if (!empty($filter_price)) {
            $url .= '&price=' . $filter_price;
        }

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$data['sorts'] = array();

		/*$data['sorts'][] = array(
			'text'  => $this->language->get('text_default'),
			'value' => 'p.sort_order-ASC',
			'href'  => $this->url->link('product/special', 'sort=p.sort_order&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_name_asc'),
			'value' => 'pd.name-ASC',
			'href'  => $this->url->link('product/special', 'sort=pd.name&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_name_desc'),
			'value' => 'pd.name-DESC',
			'href'  => $this->url->link('product/special', 'sort=pd.name&order=DESC' . $url)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_price_asc'),
			'value' => 'ps.price-ASC',
			'href'  => $this->url->link('product/special', 'sort=ps.price&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_price_desc'),
			'value' => 'ps.price-DESC',
			'href'  => $this->url->link('product/special', 'sort=ps.price&order=DESC' . $url)
		);

		if ($this->config->get('config_review_status')) {
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_desc'),
				'value' => 'rating-DESC',
				'href'  => $this->url->link('product/special', 'sort=rating&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_asc'),
				'value' => 'rating-ASC',
				'href'  => $this->url->link('product/special', 'sort=rating&order=ASC' . $url)
			);
		}

		$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/special', 'sort=p.model&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_model_desc'),
			'value' => 'p.model-DESC',
			'href'  => $this->url->link('product/special', 'sort=p.model&order=DESC' . $url)
		);*/

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_viewed_desc'),
            'value' => 'p.viewed-DESC',
            'href'  => $this->url->link('product/special', '&sort=p.viewed&order=DESC' . $url)
        );
        $data['sorts'][] = array(
            'text'  => $this->language->get('text_sell_desc'),
            'value' => 'total_sell-DESC',
            'href'  => $this->url->link('product/special', '&sort=total_sell&order=DESC' . $url)
        );
        $data['sorts'][] = array(
            'text'  => $this->language->get('text_date_desc'),
            'value' => 'p.date_added-DESC',
            'href'  => $this->url->link('product/special', '&sort=p.date_added&order=DESC' . $url)
        );

		$url = '';

        if (isset($this->request->get['type']) && in_array($this->request->get['type'], array('limit', 'infinite'))) {
            $url .= '&type=' . $this->request->get['type'];
        }

        if (!empty($filter_price)) {
            $url .= '&price=' . $filter_price;
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
				'href'  => $this->url->link('product/special', $url . '&limit=' . $value)
			);
		}

        $url = '';

        if (isset($this->request->get['type']) && in_array($this->request->get['type'], array('limit', 'infinite'))) {
            $url .= '&type=' . $this->request->get['type'];
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
            'href'  => $this->url->link('product/special', $url)
        );
        $data['filter_prices'][] = array(
            'text'  => '0~1000',
            'value' => '0~1000',
            'href'  => $this->url->link('product/special', '&price=0~1000' . $url)
        );
        $data['filter_prices'][] = array(
            'text'  => '1001~5000',
            'value' => '1001~5000',
            'href'  => $this->url->link('product/special', '&price=1001~5000' . $url)
        );
        $data['filter_prices'][] = array(
            'text'  => '5001~10000',
            'value' => '5001~10000',
            'href'  => $this->url->link('product/special', '&price=5001~10000' . $url)
        );
        $data['filter_prices'][] = array(
            'text'  => '10001~100000',
            'value' => '10001~100000',
            'href'  => $this->url->link('product/special', '&price=10001~100000' . $url)
        );
        $data['filter_prices'][] = array(
            'text'  => '10万以上',
            'value' => '100001',
            'href'  => $this->url->link('product/special', '&price=100001' . $url)
        );

        $data['filter_price'] = $filter_price;

		$url = '';

        if (isset($this->request->get['type']) && in_array($this->request->get['type'], array('limit', 'infinite'))) {
            $url .= '&type=' . $this->request->get['type'];
        }

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

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('product/special', $url . '&page={page}');

		$data['pagination'] = $pagination->render();

		//$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
        $data['results'] = sprintf($this->language->get('text_pagination_short'), ceil($product_total / $limit));
        $data['results'] .= ' 到 <input type="text" name="input_page_jump" value="'.$page.'" maxlength="4" class="form-control" style="width: 30px; text-align: center; display: inline; line-height: 30px; padding: 0px; height: 30px;" /> 页 <button id="button-page-jump" data-url="'.$this->url->link('product/special', $url).'" class="btn btn-primary">Go</button>';

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['limit'] = $limit;

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
        $data['header_top'] = $this->load->controller('common/header_top');

        $template = 'special.tpl';
        if (isset($this->request->get['type']) && in_array($this->request->get['type'], array('limit', 'infinite'))) {
            $template = 'special_' . $this->request->get['type'] . '.tpl';
        }

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/'.$template)) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/'.$template, $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/product/'.$template, $data));
		}
	}

    public function auction() {
        $this->load->language('product/special');

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        if (isset($this->request->get['type']) && in_array($this->request->get['type'], array('incoming', 'bidding', 'finished'))) {
            $type = $this->request->get['type'];
        } else {
            $type = '';
        }

        if (isset($this->request->get['price']) && in_array($this->request->get['price'], $this->allow_price)) {
            $filter_price = $this->request->get['price'];
        } else {
            $filter_price = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'pa.date_start';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
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

        $url = '';

        if (isset($this->request->get['type']) && in_array($this->request->get['type'], array('incoming', 'bidding', 'finished'))) {
            $url .= '&type=' . $this->request->get['type'];
        }

        if (!empty($filter_price)) {
            $url .= '&price=' . $filter_price;
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
            'text' => $this->language->get('heading_title_auction'),
            'href' => $this->url->link('product/special/auction')
        );

        switch ($type) {
            case 'incoming' :
                $this->document->setTitle($this->language->get('heading_title_incoming') . ' - ' . $this->language->get('heading_title_auction'));
                $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('heading_title_incoming'),
                    'href' => $this->url->link('product/special/auction', $url)
                );
                $data['heading_title'] = $this->language->get('heading_title_incoming');
                break;
            case 'bidding' :
                $this->document->setTitle($this->language->get('heading_title_bidding') . ' - ' . $this->language->get('heading_title_auction'));
                $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('heading_title_bidding'),
                    'href' => $this->url->link('product/special/auction', $url)
                );
                $data['heading_title'] = $this->language->get('heading_title_bidding');
                break;
            case 'finished' :
                $this->document->setTitle($this->language->get('heading_title_finished') . ' - ' . $this->language->get('heading_title_auction'));
                $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('heading_title_finished'),
                    'href' => $this->url->link('product/special/auction', $url)
                );
                $data['heading_title'] = $this->language->get('heading_title_finished');
                break;
            default :
                $this->document->setTitle($this->language->get('heading_title_all') . ' - ' . $this->language->get('heading_title_auction'));
                $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('heading_title_all'),
                    'href' => $this->url->link('product/special/auction', $url)
                );
                $data['heading_title'] = $this->language->get('heading_title_all');
                break;
        }

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
        $data['button_list'] = $this->language->get('button_list');
        $data['button_grid'] = $this->language->get('button_grid');
        $data['button_continue'] = $this->language->get('button_continue');

        $data['compare'] = $this->url->link('product/compare');

        $data['products'] = array();

        $filter_data = array(
            'filter_type'  => $type,
            'filter_price' => $filter_price,
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $limit,
            'limit' => $limit
        );

        $product_total = $this->model_catalog_product->getTotalProductAuctions($filter_data);

        $results = $this->model_catalog_product->getProductAuctions($filter_data);

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
            } else {
                $product_store['shop_name'] = '';
                $product_store['shop_url'] = '';
            }

            /*$bidding_data = $this->model_catalog_product->getProductBidding($result['auction_id']);
            if ($bidding_data) {
                $bidding_price = $this->currency->format($this->tax->calculate($bidding_data['price'], $result['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $bidding_price = $this->currency->format($this->tax->calculate($result['base_price'], $result['tax_class_id'], $this->config->get('config_tax')));
            }*/

            if ($result['max_bidding']) {
                $bidding_price = $this->currency->format($this->tax->calculate($result['max_bidding'], $result['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $bidding_price = $this->currency->format($this->tax->calculate($result['base_price'], $result['tax_class_id'], $this->config->get('config_tax')));
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
                'base_price'  => $this->currency->format($this->tax->calculate($result['base_price'], $result['tax_class_id'], $this->config->get('config_tax'))),
                'bidding_price' => $bidding_price,
                'total_bidding' => $result['total_bidding'],
                'auction_start' => $result['auction_start'],
                'auction_end' => $result['auction_end'],
                'shop_name'   => $product_store['shop_name'],
                'shop_url'   => $product_store['shop_url'],
                'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url)
            );
        }

        $url = '';

        if (isset($this->request->get['type']) && in_array($this->request->get['type'], array('incoming', 'bidding', 'finished'))) {
            $url .= '&type=' . $this->request->get['type'];
        }

        if (!empty($filter_price)) {
            $url .= '&price=' . $filter_price;
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }

        $data['sorts'] = array();

        /*$data['sorts'][] = array(
            'text'  => $this->language->get('text_default'),
            'value' => 'p.sort_order-ASC',
            'href'  => $this->url->link('product/special/auction', 'sort=p.sort_order&order=ASC' . $url)
        );

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_name_asc'),
            'value' => 'pd.name-ASC',
            'href'  => $this->url->link('product/special/auction', 'sort=pd.name&order=ASC' . $url)
        );

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_name_desc'),
            'value' => 'pd.name-DESC',
            'href'  => $this->url->link('product/special/auction', 'sort=pd.name&order=DESC' . $url)
        );

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_price_asc'),
            'value' => 'pa.base_price-ASC',
            'href'  => $this->url->link('product/special/auction', 'sort=pa.base_price&order=ASC' . $url)
        );

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_price_desc'),
            'value' => 'pa.base_price-DESC',
            'href'  => $this->url->link('product/special/auction', 'sort=pa.base_price&order=DESC' . $url)
        );

        if ($this->config->get('config_review_status')) {
            $data['sorts'][] = array(
                'text'  => $this->language->get('text_rating_desc'),
                'value' => 'rating-DESC',
                'href'  => $this->url->link('product/special/auction', 'sort=rating&order=DESC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_rating_asc'),
                'value' => 'rating-ASC',
                'href'  => $this->url->link('product/special/auction', 'sort=rating&order=ASC' . $url)
            );
        }

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_start_asc'),
            'value' => 'pa.date_start-ASC',
            'href'  => $this->url->link('product/special/auction', 'sort=pa.date_start&order=ASC' . $url)
        );

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_start_desc'),
            'value' => 'pa.date_start-DESC',
            'href'  => $this->url->link('product/special/auction', 'sort=pa.date_start&order=DESC' . $url)
        );

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_end_asc'),
            'value' => 'pa.date_end-ASC',
            'href'  => $this->url->link('product/special/auction', 'sort=pa.date_end&order=ASC' . $url)
        );

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_end_desc'),
            'value' => 'pa.date_end-DESC',
            'href'  => $this->url->link('product/special/auction', 'sort=pa.date_end&order=DESC' . $url)
        );*/

        $data['sorts'][] = array(
            'text'  => '默认',
            'value' => 'pa.date_start-DESC',
            'href'  => $this->url->link('product/special/auction', '&sort=pa.date_start&order=DESC' . $url)
        );
        $data['sorts'][] = array(
            'text'  => '出价次数<i class="icon fa fa-arrow-down"></i>',
            'value' => 'total_bidding-DESC',
            'href'  => $this->url->link('product/special/auction', '&sort=total_bidding&order=DESC' . $url)
        );
        $data['sorts'][] = array(
            'text'  => '价格<i class="icon fa fa-arrow-down"></i>',
            'value' => 'max_bidding-DESC',
            'href'  => $this->url->link('product/special/auction', '&sort=max_bidding&order=DESC' . $url)
        );

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

        $data['types'] = array();
        $data['types'][] = array(
            'text'  => $this->language->get('text_all'),
            'value' => '',
            'href'  => $this->url->link('product/special/auction', $url)
        );
        $data['types'][] = array(
            'text'  => $this->language->get('text_bidding'),
            'value' => 'bidding',
            'href'  => $this->url->link('product/special/auction', 'type=bidding' . $url)
        );
        $data['types'][] = array(
            'text'  => $this->language->get('text_incoming'),
            'value' => 'incoming',
            'href'  => $this->url->link('product/special/auction', 'type=incoming' . $url)
        );
        $data['types'][] = array(
            'text'  => $this->language->get('text_finished'),
            'value' => 'finished',
            'href'  => $this->url->link('product/special/auction', 'type=finished' . $url)
        );

        $url = '';

        if (isset($this->request->get['type']) && in_array($this->request->get['type'], array('incoming', 'bidding', 'finished'))) {
            $url .= '&type=' . $this->request->get['type'];
        }

        if (!empty($filter_price)) {
            $url .= '&price=' . $filter_price;
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
                'href'  => $this->url->link('product/special/auction', $url . '&limit=' . $value)
            );
        }

        $url = '';

        if (isset($this->request->get['type']) && in_array($this->request->get['type'], array('incoming', 'bidding', 'finished'))) {
            $url .= '&type=' . $this->request->get['type'];
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
            'href'  => $this->url->link('product/special/auction', $url)
        );
        $data['filter_prices'][] = array(
            'text'  => '0~1000',
            'value' => '0~1000',
            'href'  => $this->url->link('product/special/auction', '&price=0~1000' . $url)
        );
        $data['filter_prices'][] = array(
            'text'  => '1001~5000',
            'value' => '1001~5000',
            'href'  => $this->url->link('product/special/auction', '&price=1001~5000' . $url)
        );
        $data['filter_prices'][] = array(
            'text'  => '5001~10000',
            'value' => '5001~10000',
            'href'  => $this->url->link('product/special/auction', '&price=5001~10000' . $url)
        );
        $data['filter_prices'][] = array(
            'text'  => '10001~100000',
            'value' => '10001~100000',
            'href'  => $this->url->link('product/special/auction', '&price=10001~100000' . $url)
        );
        $data['filter_prices'][] = array(
            'text'  => '10万以上',
            'value' => '100001',
            'href'  => $this->url->link('product/special/auction', '&price=100001' . $url)
        );

        $data['filter_price'] = $filter_price;

        $url = '';

        if (isset($this->request->get['type']) && in_array($this->request->get['type'], array('incoming', 'bidding', 'finished'))) {
            $url .= '&type=' . $this->request->get['type'];
        }

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

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('product/special/auction', $url . '&page={page}');

        $data['pagination'] = $pagination->render();

        //$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
        $data['results'] = sprintf($this->language->get('text_pagination_short'), ceil($product_total / $limit));
        $data['results'] .= ' 到 <input type="text" name="input_page_jump" value="'.$page.'" maxlength="4" class="form-control" style="width: 30px; text-align: center; display: inline; line-height: 30px; padding: 0px; height: 30px;" /> 页 <button id="button-page-jump" data-url="'.$this->url->link('product/special/auction', $url).'" class="btn btn-primary">Go</button>';

        $data['type'] = $type;
        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['limit'] = $limit;

        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        $data['header_top'] = $this->load->controller('common/header_top');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/special_auction.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/special_auction.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/product/special_auction.tpl', $data));
        }
    }
}
