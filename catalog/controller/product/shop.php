<?php
class ControllerProductShop extends Controller {
	private $allow_type = array(
        'reward', 'latest', 'shop'
    );

    private $allow_price = array(
        '0~1000', '1001~5000', '5001~10000', '10001~100000', '100001'
    );

    public function index() {
		$this->load->language('product/shop');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

        $data['search_url'] = $this->url->link('product/search');

        $this->request->get['type'] = 'shop';

        if (isset($this->request->get['type']) && in_array($this->request->get['type'], $this->allow_type)) {
            $type = $this->request->get['type'];
            $data['search_url'] = $this->url->link('product/search', 'type='. $type);
        } else {
            $type = '';
        }

        if (isset($this->request->get['price']) && in_array($this->request->get['price'], $this->allow_price)) {
            $filter_price = $this->request->get['price'];
        } else {
            $filter_price = '';
        }

		if (isset($this->request->get['search'])) {
			$search = $this->request->get['search'];
		} else {
			$search = '';
		}

		if (isset($this->request->get['tag'])) {
			$tag = $this->request->get['tag'];
		} elseif (isset($this->request->get['search'])) {
			$tag = $this->request->get['search'];
		} else {
			$tag = '';
		}

		if (isset($this->request->get['description'])) {
			$description = $this->request->get['description'];
		} else {
			$description = '';
		}

		if (isset($this->request->get['category_id'])) {
			$category_id = $this->request->get['category_id'];
		} else {
			$category_id = 0;
		}

		if (isset($this->request->get['sub_category'])) {
			$sub_category = $this->request->get['sub_category'];
		} else {
			$sub_category = '';
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

        $title = $this->language->get('heading_title');

		if (!empty($this->request->get['search'])) {
            $title = $this->language->get('heading_title') .  ' - ' . $this->request->get['search'];
		} elseif (isset($this->request->get['tag'])) {
            $title = $this->language->get('heading_title') .  ' - ' . $this->language->get('heading_tag') . $this->request->get['tag'];
		} else {
            $title = $this->language->get('heading_title');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

        $url = '';

        if (!empty($type)) {
            $url .= '&type=' . $type;
        }

        if (!empty($filter_price)) {
            $url .= '&price=' . $filter_price;
        }

		if (isset($this->request->get['search'])) {
			$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['tag'])) {
			$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['description'])) {
			$url .= '&description=' . $this->request->get['description'];
		}

		if (isset($this->request->get['category_id'])) {
			$url .= '&category_id=' . $this->request->get['category_id'];
		}

		if (isset($this->request->get['sub_category'])) {
			$url .= '&sub_category=' . $this->request->get['sub_category'];
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


        if ($type) {
            $title = $this->language->get('heading_title_'.$type);
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title_'.$type),
                'href' => $this->url->link('product/search', 'type='.$type)
            );
            $data['heading_title'] = '';
        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('product/search', $url)
            );

            if (!empty($this->request->get['search'])) {
                $data['heading_title'] = $this->language->get('heading_title') .  ' - ' . $this->request->get['search'];
            } else {
                $data['heading_title'] = $this->language->get('heading_title');
            }
        }

        $this->document->setTitle($title);

		$data['text_empty'] = $this->language->get('text_empty');
		$data['text_search'] = $this->language->get('text_search');
		$data['text_keyword'] = $this->language->get('text_keyword');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_sub_category'] = $this->language->get('text_sub_category');
		$data['text_quantity'] = $this->language->get('text_quantity');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_model'] = $this->language->get('text_model');
		$data['text_price'] = $this->language->get('text_price');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['text_points'] = $this->language->get('text_points');
		$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
		$data['text_sort'] = $this->language->get('text_sort');
		$data['text_limit'] = $this->language->get('text_limit');

		$data['entry_search'] = $this->language->get('entry_search');
		$data['entry_description'] = $this->language->get('entry_description');

		$data['button_search'] = $this->language->get('button_search');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		$data['button_list'] = $this->language->get('button_list');
		$data['button_grid'] = $this->language->get('button_grid');

		$data['compare'] = $this->url->link('product/compare');

		$this->load->model('catalog/category');

		// 3 Level Category Search
		$data['categories'] = array();

		$categories_1 = $this->model_catalog_category->getCategories(0);

		foreach ($categories_1 as $category_1) {
			$level_2_data = array();

			$categories_2 = $this->model_catalog_category->getCategories($category_1['category_id']);

			foreach ($categories_2 as $category_2) {
				$level_3_data = array();

				$categories_3 = $this->model_catalog_category->getCategories($category_2['category_id']);

				foreach ($categories_3 as $category_3) {
					$level_3_data[] = array(
						'category_id' => $category_3['category_id'],
						'name'        => $category_3['name'],
					);
				}

				$level_2_data[] = array(
					'category_id' => $category_2['category_id'],
					'name'        => $category_2['name'],
					'children'    => $level_3_data
				);
			}

			$data['categories'][] = array(
				'category_id' => $category_1['category_id'],
				'name'        => $category_1['name'],
				'children'    => $level_2_data
			);
		}

		$data['products'] = array();

		if (1 == 1 || !empty($type) || isset($this->request->get['search']) || isset($this->request->get['tag'])) {
			$filter_data = array(
				'filter_type'         => $type,
                'filter_name'         => $search,
				'filter_tag'          => $tag,
                'filter_price'        => $filter_price,
				'filter_description'  => $description,
				'filter_category_id'  => $category_id,
				'filter_sub_category' => $sub_category,
				'sort'                => $sort,
				'order'               => $order,
				'start'               => ($page - 1) * $limit,
				'limit'               => $limit
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

                    if ($product_store['shop_logo']) {
                        $product_store['shop_logo'] = $this->model_tool_image->resize($product_store['shop_logo'], 50, 50);
                    } else {
                        $product_store['shop_logo'] = $this->model_tool_image->resize('placeholder.png', 50, 50);
                    }

                    if ($product_store['shop_image']) {
                        $product_store['shop_image'] = $this->model_tool_image->resize($product_store['shop_image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                    } else {
                        $product_store['shop_image'] = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                    }

                    $product_store['shop_comment'] = $product_store['shop_comment'] ? utf8_substr(strip_tags(html_entity_decode($product_store['shop_comment'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..' : '店主好懒，没有描述';

                    $this->load->model('catalog/product');
                    $product_store['total_product'] = $this->model_catalog_product->getTotalProducts(array(
                        'filter_store_id' => $product_store['store_id']
                    ));

                    $this->load->model('sale/order');
                    $product_store['total_sell'] = $this->model_sale_order->getTotalSellProducts($product_store['store_id']);
                    if (empty($product_store['total_sell'])) $product_store['total_sell'] = '0';

                    $this->load->model('account/wishlist');
                    $product_store['total_wish'] = $this->model_account_wishlist->getTotalShopWished($product_store['store_id']);

                    $this->load->model('seller/shop');
                    $product_store['ratings'] = $this->model_seller_shop->getStoreRatings($product_store['store_id']);
                } else {
                    $product_total--;
                    continue;
                    $product_store['shop_name'] = '';
                    $product_store['shop_url'] = '';
                    $product_store['shop_logo'] = '';
                    $product_store['shop_image'] = '';
                    $product_store['shop_comment'] = '';
                    $product_store['total_product'] = '';
                    $product_store['total_sell'] = '';
                    $product_store['total_wish'] = '';
                    $product_store['ratings'] = array();
                }

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'points'      => $type == 'reward' ? $result['points'] : '0',
                    'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $rating,
                    'shop_info'   => $product_store,
                    'shop_name'   => $product_store['shop_name'],
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url)
				);
			}

			$url = '';

            if (!empty($type)) {
                $url .= '&type=' . $type;
            }

            if (!empty($filter_price)) {
                $url .= '&price=' . $filter_price;
            }

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['sorts'] = array();
            /*
          			$data['sorts'][] = array(
                            'text'  => $this->language->get('text_default'),
                            'value' => 'p.sort_order-ASC',
                            'href'  => $this->url->link('product/search', 'sort=p.sort_order&order=ASC' . $url)
                        );

                        $data['sorts'][] = array(
                            'text'  => $this->language->get('text_name_asc'),
                            'value' => 'pd.name-ASC',
                            'href'  => $this->url->link('product/search', 'sort=pd.name&order=ASC' . $url)
                        );

                        $data['sorts'][] = array(
                            'text'  => $this->language->get('text_name_desc'),
                            'value' => 'pd.name-DESC',
                            'href'  => $this->url->link('product/search', 'sort=pd.name&order=DESC' . $url)
                        );

                        $data['sorts'][] = array(
                            'text'  => $this->language->get('text_price_asc'),
                            'value' => 'p.price-ASC',
                            'href'  => $this->url->link('product/search', 'sort=p.price&order=ASC' . $url)
                        );

                        $data['sorts'][] = array(
                            'text'  => $this->language->get('text_price_desc'),
                            'value' => 'p.price-DESC',
                            'href'  => $this->url->link('product/search', 'sort=p.price&order=DESC' . $url)
                        );

                        if ($this->config->get('config_review_status')) {
                            $data['sorts'][] = array(
                                'text'  => $this->language->get('text_rating_desc'),
                                'value' => 'rating-DESC',
                                'href'  => $this->url->link('product/search', 'sort=rating&order=DESC' . $url)
                            );

                            $data['sorts'][] = array(
                                'text'  => $this->language->get('text_rating_asc'),
                                'value' => 'rating-ASC',
                                'href'  => $this->url->link('product/search', 'sort=rating&order=ASC' . $url)
                            );
                        }

                        $data['sorts'][] = array(
                            'text'  => $this->language->get('text_model_asc'),
                            'value' => 'p.model-ASC',
                            'href'  => $this->url->link('product/search', 'sort=p.model&order=ASC' . $url)
                        );

                        $data['sorts'][] = array(
                            'text'  => $this->language->get('text_model_desc'),
                            'value' => 'p.model-DESC',
                            'href'  => $this->url->link('product/search', 'sort=p.model&order=DESC' . $url)
                        );
            */

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_viewed_desc'),
                'value' => 'p.viewed-DESC',
                'href'  => $this->url->link('product/search', '&sort=p.viewed&order=DESC' . $url)
            );
            $data['sorts'][] = array(
                'text'  => $this->language->get('text_sell_desc'),
                'value' => 'total_sell-DESC',
                'href'  => $this->url->link('product/search', '&sort=total_sell&order=DESC' . $url)
            );
            $data['sorts'][] = array(
                'text'  => $this->language->get('text_date_desc'),
                'value' => 'p.date_added-DESC',
                'href'  => $this->url->link('product/search', '&sort=p.date_added&order=DESC' . $url)
            );

			$url = '';

            if (!empty($type)) {
                $url .= '&type=' . $type;
            }

            if (!empty($filter_price)) {
                $url .= '&price=' . $filter_price;
            }

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
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
					'href'  => $this->url->link('product/search', $url . '&limit=' . $value)
				);
			}

            $url = '';

            if (!empty($type)) {
                $url .= '&type=' . $type;
            }

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['tag'])) {
                $url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['description'])) {
                $url .= '&description=' . $this->request->get['description'];
            }

            if (isset($this->request->get['category_id'])) {
                $url .= '&category_id=' . $this->request->get['category_id'];
            }

            if (isset($this->request->get['sub_category'])) {
                $url .= '&sub_category=' . $this->request->get['sub_category'];
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
                'href'  => $this->url->link('product/search', $url)
            );
            $data['filter_prices'][] = array(
                'text'  => '0~1000',
                'value' => '0~1000',
                'href'  => $this->url->link('product/search', '&price=0~1000' . $url)
            );
            $data['filter_prices'][] = array(
                'text'  => '1001~5000',
                'value' => '1001~5000',
                'href'  => $this->url->link('product/search', '&price=1001~5000' . $url)
            );
            $data['filter_prices'][] = array(
                'text'  => '5001~10000',
                'value' => '5001~10000',
                'href'  => $this->url->link('product/search', '&price=5001~10000' . $url)
            );
            $data['filter_prices'][] = array(
                'text'  => '10001~100000',
                'value' => '10001~100000',
                'href'  => $this->url->link('product/search', '&price=10001~100000' . $url)
            );
            $data['filter_prices'][] = array(
                'text'  => '10万以上',
                'value' => '100001',
                'href'  => $this->url->link('product/search', '&price=100001' . $url)
            );

            $data['filter_price'] = $filter_price;

			$url = '';

            if (!empty($type)) {
                $url .= '&type=' . $type;
            }

            if (!empty($filter_price)) {
                $url .= '&price=' . $filter_price;
            }

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
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
			$pagination->url = $this->url->link('product/search', $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			//$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
            $data['results'] = sprintf($this->language->get('text_pagination_short'), ceil($product_total / $limit));
            $data['results'] .= ' 到 <input type="text" name="input_page_jump" value="'.$page.'" maxlength="4" class="form-control" style="width: 30px; text-align: center; display: inline; line-height: 30px; padding: 0px; height: 30px;" /> 页 <button id="button-page-jump" data-url="'.$this->url->link('product/search', $url).'" class="btn btn-primary">Go</button>';

        }

		$data['search'] = $search;
		$data['description'] = $description;
		$data['category_id'] = $category_id;
		$data['sub_category'] = $sub_category;

        $data['sort'] = $sort;
		$data['order'] = $order;
		$data['limit'] = $limit;

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

        switch ($type) {
            case 'shop' :
                $template = 'shop';
                break;
            default :
                $template = 'search';
                break;
        }

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/'.$template.'.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/'.$template.'.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/product/'.$template.'.tpl', $data));
		}
	}
}