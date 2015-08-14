<?php
class ControllerProductProduct extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('product/product');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$this->load->model('catalog/category');

		if (isset($this->request->get['path'])) {
			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path)
					);
				}
			}

			// Set the last category breadcrumb
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
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

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				$data['breadcrumbs'][] = array(
					'text' => $category_info['name'],
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url)
				);
			}
		}

		$this->load->model('catalog/manufacturer');

		if (isset($this->request->get['manufacturer_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_brand'),
				'href' => $this->url->link('product/manufacturer')
			);

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

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

			if ($manufacturer_info) {
				$data['breadcrumbs'][] = array(
					'text' => $manufacturer_info['name'],
					'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url)
				);
			}
		}

		if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
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

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_search'),
				'href' => $this->url->link('product/search', $url)
			);
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

        $_productStoreId = $this->config->get('config_store_id');

		$this->load->model('catalog/product');

        $_productStore = $this->model_catalog_product->getProductStore($product_id);
        if ($_productStore) {
            $_productStoreId = $_productStore['store_id'];
        }

        $preview = isset($this->request->get['preview']) && $_productStoreId == $this->customer->getShopId();

        $data['is_preview'] = $preview;

        $product_info = $this->model_catalog_product->getProduct($product_id, $preview);

        if ($product_info && $_productStore && $_productStore['shop_status']) {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
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

			$data['breadcrumbs'][] = array(
				'text' => $product_info['name'],
				'href' => $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id'])
			);

            $pageTitle = $this->config->get('config_meta_title');
            if (!empty($product_info['meta_title'])) {
                $pageTitle = $product_info['meta_title'].' - '.$pageTitle;
            } else {
                $pageTitle = $product_info['name'].' - '.$pageTitle;
            }

            $pageDescription = $this->config->get('config_meta_description');
            if (!empty($product_info['meta_description'])) {
                $pageDescription = $product_info['meta_description'].', '.$pageDescription;
            } else {
                $pageDescription = $product_info['name'].', '.$pageDescription;
            }

            $pageKeywords = $this->config->get('config_meta_keyword');
            if (!empty($product_info['meta_keyword'])) {
                $pageKeywords = $product_info['meta_keyword'].' - '.$pageKeywords;
            } else {
                $pageKeywords = $product_info['name'].' - '.$pageKeywords;
            }

			$this->document->setTitle($pageTitle);
			$this->document->setDescription($pageDescription);
			$this->document->setKeywords($pageKeywords);
			$this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');
			$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

			$data['heading_title'] = $product_info['name'];

            if ($preview) $data['heading_title'] .= ' - 预览';

			$data['text_select'] = $this->language->get('text_select');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_reward'] = $this->language->get('text_reward');
			$data['text_points'] = $this->language->get('text_points');
			$data['text_stock'] = $this->language->get('text_stock');
			$data['text_discount'] = $this->language->get('text_discount');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_option'] = $this->language->get('text_option');
			$data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
			$data['text_write'] = $this->language->get('text_write');
			$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
			$data['text_note'] = $this->language->get('text_note');
			$data['text_tags'] = $this->language->get('text_tags');
            $data['text_related'] = $this->language->get('text_related');
            $data['text_popular'] = $this->language->get('text_popular');
			$data['text_loading'] = $this->language->get('text_loading');

			$data['entry_qty'] = $this->language->get('entry_qty');
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_review'] = $this->language->get('entry_review');
			$data['entry_rating'] = $this->language->get('entry_rating');
			$data['entry_good'] = $this->language->get('entry_good');
			$data['entry_bad'] = $this->language->get('entry_bad');
			$data['entry_captcha'] = $this->language->get('entry_captcha');

			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['button_upload'] = $this->language->get('button_upload');
			$data['button_continue'] = $this->language->get('button_continue');

			$this->load->model('catalog/review');

			$data['tab_description'] = $this->language->get('tab_description');
			$data['tab_attribute'] = $this->language->get('tab_attribute');
			$data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);

			$data['product_id'] = (int)$this->request->get['product_id'];
			$data['manufacturer'] = $product_info['manufacturer'];
			$data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
			$data['model'] = $product_info['model'];
			$data['reward'] = $product_info['reward'];
            $data['points'] = $product_info['points'];
            $data['total_wish'] = $product_info['total_wish'];
            $data['total_sell'] = $product_info['total_sell'] ? $product_info['total_sell'] : '0';
            $data['on_sale'] = $product_info['on_sale'];

			if ($product_info['quantity'] <= 0) {
				$data['stock'] = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$data['stock'] = $product_info['quantity'];
			} else {
				$data['stock'] = $this->language->get('text_instock');
			}

			$this->load->model('tool/image');

			if ($product_info['image']) {
				$data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			} else {
				$data['popup'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));;
			}

			if ($product_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
			} else {
				$data['thumb'] =  $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
			}

            if ($product_info['image']) {
                $data['mini'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'));
            } else {
                $data['mini'] =  $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'));
            }

			$data['images'] = array();

			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

			foreach ($results as $result) {
				$data['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
					'mini' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height')),
                    'thumb'  => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'))
				);
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$data['price'] = false;
			}

			if ((float)$product_info['special']) {
				$data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$data['special'] = false;
			}

            $special_data = $this->model_catalog_product->getProductSpecial($this->request->get['product_id']);
            if ($special_data && $special_data['date_end'] <> '0000-00-00 00:00:00') {
                $now = date('Y-m-d H:i:s');
                $data['special_date'] = array();
                $data['special_date']['day'] = $this->DateDiff('d', $now, $special_data['date_end']);
                $now .= ' + ' . $data['special_date']['day'] . ' day';
                $data['special_date']['hour'] = $this->DateDiff('h', $now, $special_data['date_end']);
                $now .= ' + ' . $data['special_date']['hour'] . ' hour';
                $data['special_date']['minute'] = $this->DateDiff('i', $now, $special_data['date_end']);
                $now .= ' + ' . $data['special_date']['minute'] . ' minute';
                $data['special_date']['second'] = $this->DateDiff('s', $now, $special_data['date_end']);
                $data['special_date'] = json_encode($data['special_date']);
            } else {
                $data['special_date'] = false;
            }

            $auction_data = $this->model_catalog_product->getProductAuction($this->request->get['product_id']);
            $data['product_auction'] = $auction_data;
            if ($auction_data && $auction_data['date_end'] <> '0000-00-00 00:00:00') {
                $now = date('Y-m-d H:i:s', time());
                $data['auction_date'] = array();
                $data['auction_date']['day'] = $this->DateDiff('d', $now, $auction_data['date_end']);
                $now .= ' + ' . $data['auction_date']['day'] . ' day';
                $data['auction_date']['hour'] = $this->DateDiff('h', $now, $auction_data['date_end']);
                $now .= ' + ' . $data['auction_date']['hour'] . ' hour';
                $data['auction_date']['minute'] = $this->DateDiff('i', $now, $auction_data['date_end']);
                $now .= ' + ' . $data['auction_date']['minute'] . ' minute';
                $data['auction_date']['second'] = $this->DateDiff('s', $now, $auction_data['date_end']);
                $data['auction_date'] = json_encode($data['auction_date']);
                $data['auction_step'] = $this->currency->format($auction_data['price']);
                $data['auction_price'] = $this->currency->format($auction_data['base_price']);
            } else {
                $data['auction_date'] = false;
            }

			if ($this->config->get('config_tax')) {
				$data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
			} else {
				$data['tax'] = false;
			}

			$discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

			$data['discounts'] = array();

			foreach ($discounts as $discount) {
				$data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')))
				);
			}

			$data['options'] = array();

			foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) {
				$product_option_value_data = array();

				foreach ($option['product_option_value'] as $option_value) {
					if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
						if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
							$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false));
						} else {
							$price = false;
						}

						$product_option_value_data[] = array(
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id'         => $option_value['option_value_id'],
							'name'                    => $option_value['name'],
							'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
							'price'                   => $price,
							'price_prefix'            => $option_value['price_prefix']
						);
					}
				}

				$data['options'][] = array(
					'product_option_id'    => $option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $option['option_id'],
					'name'                 => $option['name'],
					'type'                 => $option['type'],
					'value'                => $option['value'],
					'required'             => $option['required']
				);
			}

			if ($product_info['minimum']) {
				$data['minimum'] = $product_info['minimum'];
			} else {
				$data['minimum'] = 1;
			}

            $data['review_status'] = $this->config->get('config_review_status');

			if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
				$data['review_guest'] = true;
			} else {
				$data['review_guest'] = false;
			}

			if ($this->customer->isLogged()) {
				$data['customer_name'] = $this->customer->getFullName();
			} else {
				$data['customer_name'] = '';
			}

            $this->load->model('catalog/review');
            $data['good_reviews'] = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id'], array(
                'filter_rating' => 5
            ));

            $data['normal_reviews'] = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id'], array(
                'filter_rating' => 3
            ));

            $data['bad_reviews'] = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id'], array(
                'filter_rating' => 1
            ));

            $data['total_reviews'] = (int)$product_info['reviews'];

			$data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
            $data['good_reviews'] = (int)$product_info['good_reviews'];
            $data['good_percent'] = $product_info['reviews'] ? number_format((int)$product_info['good_reviews'] / (int)$product_info['reviews'] * 100, 2).'%' : '100%';
			$data['rating'] = (int)$product_info['rating'];
            $data['short_desc'] = utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, 120) . '...';
			$data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
			$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);

            /*$_stores = $this->model_catalog_product->getProductStores($product_id);
            $_productStoreId = $this->config->get('config_store_id');
            foreach ($_stores as $_store) {
                if ((int)$_store['store_id'] !== 0) {
                    $_productStoreId = $_store['store_id'];
                    break;
                }
            }*/

            $this->load->model('setting/setting');
            $shop_setting = $this->model_setting_setting->getSetting('config', $_productStoreId);
            $data['shop_url'] = $this->url->link('common/home');
            if (isset($shop_setting['config_key'])) {
                // Shop home url.
                $data['shop_url'] = HTTP_SERVER . $shop_setting['config_key'];
            } else {
                $_productStoreId = $this->config->get('config_store_id');
            }

            $this->load->model('seller/shop');
            $data['shop_rating'] = $this->model_seller_shop->getStoreRatings($_productStoreId);
            $date_start = date('Y-m-d H:i:s', strtotime('-90 day'));
            $data['shop_total_reviews'] = $this->model_seller_shop->getStoreTotalReviews($_productStoreId, array(
                'filter_start_date' => $date_start
            ));

			$data['products'] = array();

			$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id'], $_productStoreId);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
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
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}

            $data['popular_products'] = array();

            $results = $this->model_catalog_product->getPopularProducts(6, $_productStoreId);
            $_i = 0;
            foreach ($results as $result) {
                if ($result['product_id'] == $product_id) continue;
                $_i++;
                if ($_i > 5) break;
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], 180, 220);
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', 180, 220);
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

                $data['popular_products'][] = array(
                    'product_id'  => $result['product_id'],
                    'thumb'       => $image,
                    'name'        => $result['name'],
                    'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                    'price'       => $price,
                    'special'     => $special,
                    'tax'         => $tax,
                    'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
                    'rating'      => $rating,
                    'total_wish'  => $result['total_wish'],
                    'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                );
            }

            $data['bestseller_products'] = array();

            $results = $this->model_catalog_product->getBestSellerProducts(6, $_productStoreId);
            $_i = 0;
            foreach ($results as $result) {
                if ($result['product_id'] == $product_id) continue;
                $_i++;
                if ($_i > 5) break;
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], 228, 320);
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', 228, 320);
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

                $data['bestseller_products'][] = array(
                    'product_id'  => $result['product_id'],
                    'thumb'       => $image,
                    'name'        => $result['name'],
                    'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                    'price'       => $price,
                    'special'     => $special,
                    'tax'         => $tax,
                    'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
                    'rating'      => $rating,
                    'total_wish'  => $result['total_wish'] ? $result['total_wish'] : '0',
                    'total_sell'  => $result['total_sell'] ? $result['total_sell'] : '0',
                    'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                );
            }

			$data['tags'] = array();

			if ($product_info['tag']) {
				$tags = explode(',', $product_info['tag']);

				foreach ($tags as $tag) {
					$data['tags'][] = array(
						'tag'  => trim($tag),
						'href' => $this->url->link('product/search', 'tag=' . trim($tag))
					);
				}
			}

            $data['category_tree'] = array();
            $categories = $this->model_catalog_category->getCategoriesByShop($_productStoreId, 0);

            foreach ($categories as $category) {
                // Level 2
                $children_data = array();

                $children = $this->model_catalog_category->getCategoriesByShop($_productStoreId, $category['category_id']);

                foreach ($children as $child) {
                    $filter_data = array(
                        'filter_category_id'  => $child['category_id'],
                        'filter_sub_category' => true
                    );

                    if ($_productStoreId > 0) {
                        // Shop home url.
                        //$href = '/shop/'.$shop_setting['config_key'].'/category/?path=' . $category['category_id'] . '_' . $child['category_id'];
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
                if ($_productStoreId > 0) {
                    // Shop home url.
                    //$href = '/shop/'.$shop_setting['config_key'].'/category/?path=' . $category['category_id'];
                    $href = $this->url->link('product/category', 'path=' . $category['category_id']);
                } else {
                    $href = $this->url->link('product/category', 'path=' . $category['category_id']);
                }

                $data['category_tree'][] = array(
                    'name'     => $category['name'],
                    'children' => $children_data,
                    'column'   => $category['column'] ? $category['column'] : 1,
                    'href'     => $href
                );
            }

            $data['finished_auctions'] = array();
            $product_auctions = $this->model_catalog_product->getProductAuctions(array(
                'filter_product_id' => $this->request->get['product_id'],
                'filter_type' => 'finished',
                'order' => 'DESC'
            ));

            foreach ($product_auctions as $key => $auction) {
                $product_auctions[$key]['base_price'] = $this->currency->format($auction['base_price']);
                $auction_bidding = $this->model_catalog_product->getProductBidding($auction['auction_id']);
                if ($auction_bidding) {
                    $product_auctions[$key]['bidding_price'] = $this->currency->format($auction_bidding['price']);
                    $product_auctions[$key]['bidding_customer'] = $auction_bidding['fullname'];
                    $product_auctions[$key]['bidding_date'] = $auction_bidding['date_added'];
                }
            }

            $data['finished_auctions'] = $product_auctions;

			$data['text_payment_recurring'] = $this->language->get('text_payment_recurring');
			$data['recurrings'] = $this->model_catalog_product->getProfiles($this->request->get['product_id']);

			$this->model_catalog_product->updateViewed($this->request->get['product_id']);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right', array(
                'productStoreId' => $_productStoreId,
                'relatedProducts' => $data['products']
            ));
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header', array('_StoreId_' => $_productStoreId));

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/product.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/product/product.tpl', $data));
			}
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
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

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/product', $url . '&product_id=' . $product_id)
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

	public function review() {
		$this->load->language('product/product');

		$this->load->model('catalog/review');

		$data['text_no_reviews'] = $this->language->get('text_no_reviews');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

        if (isset($this->request->get['t']) && in_array((int)$this->request->get['t'], array(1,3,5))) {
            $rating = (int)$this->request->get['t'];
        } else {
            $rating = 0;
        }

		$data['reviews'] = array();

        $data['good_reviews'] = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id'], array(
            'filter_rating' => 5
        ));

        $data['normal_reviews'] = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id'], array(
            'filter_rating' => 3
        ));

        $data['bad_reviews'] = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id'], array(
            'filter_rating' => 1
        ));

        $data['total_reviews'] = $data['good_reviews'] + $data['normal_reviews'] + $data['bad_reviews'];

        $data['good_percent'] = $data['total_reviews'] ? number_format($data['good_reviews'] / $data['total_reviews'] * 100, 2).'%' : '100%';

        $review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id'], array(
            'filter_rating' => $rating
        ));
        $results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 8, 8, array(
            'filter_rating' => $rating
        ));

        $this->load->model('tool/image');
		foreach ($results as $result) {
			$customer_avatar = $this->model_tool_image->resize('no_image.png', 70, 70);
            if ($result['custom_field']) {
                $customer_avatar = unserialize($result['custom_field']);
                if (isset($customer_avatar[2]) && is_file(DIR_IMAGE . $customer_avatar[2])) {
                    $customer_avatar = $this->model_tool_image->resize($customer_avatar[2], 70, 70);
                }
            }
            $data['reviews'][] = array(
				'author'     => $result['author'],
				'text'       => nl2br($result['text']),
				'rating'     => (int)$result['rating'],
                'avatar'     => $customer_avatar,
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
			);
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 8;
		$pagination->url = $this->url->link('product/product/review', 't='.$rating.'&product_id=' . $this->request->get['product_id'] . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 8) + 1 : 0, ((($page - 1) * 8) > ($review_total - 8)) ? $review_total : ((($page - 1) * 8) + 8), $review_total, ceil($review_total / 8));

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/review.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/review.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/product/review.tpl', $data));
		}
	}

    public function transaction() {
        $this->load->language('product/product');

        $this->load->model('catalog/product');

        $data['text_no_transaction'] = $this->language->get('text_no_transaction');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['transactions'] = array();

        $results_total = $this->model_catalog_product->getTotalProductTransactions($this->request->get['product_id']);
        $results = $this->model_catalog_product->getProductTransactions($this->request->get['product_id'], ($page - 1) * 15, 15);

        $this->load->model('tool/image');
        $this->load->model('tool/upload');
        $this->load->model('account/order');
        foreach ($results as $result) {
            $customer_avatar = $this->model_tool_image->resize('no_image.png', 50, 50);
            if ($result['custom_field']) {
                $customer_avatar = unserialize($result['custom_field']);
                if (isset($customer_avatar[2]) && is_file(DIR_IMAGE . $customer_avatar[2])) {
                    $customer_avatar = $this->model_tool_image->resize($customer_avatar[2], 50, 50);
                }
            }

            $option_data = array();

            $options = $this->model_account_order->getOrderOptions($result['order_id'], $result['order_product_id']);

            foreach ($options as $option) {
                if ($option['type'] != 'file') {
                    $value = $option['value'];
                } else {
                    $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                    if ($upload_info) {
                        $value = $upload_info['name'];
                    } else {
                        $value = '';
                    }
                }

                $option_data[] = array(
                    'name'  => $option['name'],
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                );
            }

            $data['transactions'][] = array(
                'name'       => utf8_substr($result['fullname'], 0, 1) . '***' . utf8_substr($result['fullname'], utf8_strlen($result['fullname'])-1, 1) . ' (匿名)',
                'model'      => $result['model'],
                'quantity'   => $result['quantity'],
                'options'    => $option_data,
                'avatar'     => $customer_avatar,
                'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
            );
        }

        $pagination = new Pagination();
        $pagination->total = $results_total;
        $pagination->page = $page;
        $pagination->limit = 15;
        $pagination->url = $this->url->link('product/product/transaction', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($results_total) ? (($page - 1) * 15) + 1 : 0, ((($page - 1) * 15) > ($results_total - 15)) ? $results_total : ((($page - 1) * 15) + 15), $results_total, ceil($results_total / 15));

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/transaction.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/transaction.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/product/transaction.tpl', $data));
        }
    }

	public function getRecurringDescription() {
		$this->language->load('product/product');
		$this->load->model('catalog/product');

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->post['recurring_id'])) {
			$recurring_id = $this->request->post['recurring_id'];
		} else {
			$recurring_id = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = $this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		$product_info = $this->model_catalog_product->getProduct($product_id);
		$recurring_info = $this->model_catalog_product->getProfile($product_id, $recurring_id);

		$json = array();

		if ($product_info && $recurring_info) {
			if (!$json) {
				$frequencies = array(
					'day'        => $this->language->get('text_day'),
					'week'       => $this->language->get('text_week'),
					'semi_month' => $this->language->get('text_semi_month'),
					'month'      => $this->language->get('text_month'),
					'year'       => $this->language->get('text_year'),
				);

				if ($recurring_info['trial_status'] == 1) {
					$price = $this->currency->format($this->tax->calculate($recurring_info['trial_price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')));
					$trial_text = sprintf($this->language->get('text_trial_description'), $price, $recurring_info['trial_cycle'], $frequencies[$recurring_info['trial_frequency']], $recurring_info['trial_duration']) . ' ';
				} else {
					$trial_text = '';
				}

				$price = $this->currency->format($this->tax->calculate($recurring_info['price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')));

				if ($recurring_info['duration']) {
					$text = $trial_text . sprintf($this->language->get('text_payment_description'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				} else {
					$text = $trial_text . sprintf($this->language->get('text_payment_cancel'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				}

				$json['success'] = $text;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function write() {
		$this->load->language('product/product');

		$json = array(
            'error' => 'Access Denied!'
        );

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
				$json['error'] = $this->language->get('error_rating');
			}

			if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
				$json['error'] = $this->language->get('error_captcha');
			}

			unset($this->session->data['captcha']);

			if (!isset($json['error'])) {
				$this->load->model('catalog/review');

				$this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

    public function auction() {
        $this->load->language('product/product');

        $json = array();

        if (isset($this->request->get['product_id']) && isset($this->request->get['auction_id'])) {
            $this->load->model('catalog/product');
            $auction_data = $this->model_catalog_product->getProductAuction($this->request->get['product_id']);
            if ($auction_data && $auction_data['product_auction_id'] == $this->request->get['auction_id']) {
                $bidding_data = $this->model_catalog_product->getProductBidding($auction_data['product_auction_id']);
                if ($bidding_data) {
                    $json['price_now'] = $this->currency->format($bidding_data['price']);
                    $json['customer_id'] = $bidding_data['customer_id'];
                    $json['customer_name'] = $bidding_data['fullname'];
                    $json['price_step'] = $this->currency->format($bidding_data['price'] + $auction_data['price']);
                    $json['bidding_price'] = $bidding_data['price'] + $auction_data['price'];
                } else {
                    $json['price_now'] = $this->currency->format($auction_data['base_price']);
                    $json['customer_id'] = '';
                    $json['customer_name'] = '';
                    $json['price_step'] = $this->currency->format($auction_data['base_price']);
                    $json['bidding_price'] = $auction_data['base_price'];
                }
            } else {
                $json['error'] = $this->language->get('error_auction');
            }
        } else {
            $json['error'] = $this->language->get('error_param');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function bidding() {
        $this->load->language('product/product');

        $json = array();

        if (!$this->customer->isLogged()) {
            $json['error'] = $this->language->get('error_not_login');
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;
        }

        if (isset($this->request->post['product_id']) && isset($this->request->post['auction_id']) && isset($this->request->post['price'])) {
            $this->load->model('catalog/product');
            $auction_data = $this->model_catalog_product->getProductAuction($this->request->post['product_id']);
            if ($auction_data && $auction_data['product_auction_id'] == $this->request->post['auction_id']) {
                $bidding_data = $this->model_catalog_product->getProductBidding($auction_data['product_auction_id']);
                if ($bidding_data) {
                    if ($this->request->post['price'] == $bidding_data['price'] + $auction_data['price']) {
                        $bidding_id = $this->model_catalog_product->addProductBidding($this->request->post);
                        if ($bidding_id) {
                            $json['success'] = sprintf($this->language->get('success_bidding'), $this->currency->format($this->request->post['price']));
                        } else {
                            $json['error'] = $this->language->get('error_bidding');
                        }
                    } else {
                        $json['error'] = $this->language->get('error_bidding_price');
                    }
                } else {
                    if ($this->request->post['price'] == $auction_data['base_price']) {
                        $bidding_id = $this->model_catalog_product->addProductBidding($this->request->post);
                        if ($bidding_id) {
                            $json['success'] = sprintf($this->language->get('success_bidding'), $this->currency->format($this->request->post['price']));
                        } else {
                            $json['error'] = $this->language->get('error_bidding');
                        }
                    } else {
                        $json['error'] = $this->language->get('error_bidding_price');
                    }
                }
            } else {
                $json['error'] = $this->language->get('error_auction');
            }
        } else {
            $json['error'] = $this->language->get('error_param');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function DateDiff($part, $begin, $end)
    {
        $retval = 0;
        $diff = strtotime($end) - strtotime($begin);
        switch($part)
        {
            case "y": $retval = bcdiv($diff, (60 * 60 * 24 * 365)); break;
            case "m": $retval = bcdiv($diff, (60 * 60 * 24 * 30)); break;
            case "w": $retval = bcdiv($diff, (60 * 60 * 24 * 7)); break;
            case "d": $retval = bcdiv($diff, (60 * 60 * 24)); break;
            case "h": $retval = bcdiv($diff, (60 * 60)); break;
            case "i": $retval = bcdiv($diff, 60); break;
            case "s": $retval = $diff; break;
        }
        return $retval;
    }
}
