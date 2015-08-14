<?php
class ModelCatalogProduct extends Model {
	public function updateViewed($product_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
	}

	public function getProduct($product_id, $preview = false) {
		$sql = "SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT SUM(op.quantity) AS total FROM " . DB_PREFIX . "order_product AS op INNER JOIN  " . DB_PREFIX . "order AS o ON op.order_id = o.order_id WHERE o.parent_id > 0 AND o.order_status_id IN (2,3,5,15) AND op.product_id = '" . (int)$product_id . "') AS total_sell, (SELECT COUNT(DISTINCT o.customer_id) AS total FROM " . DB_PREFIX . "order_product AS op INNER JOIN  " . DB_PREFIX . "order AS o ON op.order_id = o.order_id AND o.store_id > 0 AND o.order_status_id > 0 WHERE op.product_id = '" . (int)$product_id . "') AS total_buyer, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_customer WHERE product_id = '" . (int)$product_id . "') AS total_wish, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00 00:00:00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00 00:00:00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r3 WHERE r3.product_id = p.product_id AND r3.status = '1' AND r3.rating = '5' GROUP BY r3.product_id) AS good_reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
        if ($preview === false) {
            $sql .= " AND p.status = '1' AND p2s.on_sale = '1'";
        }
        $query = $this->db->query($sql);

		if ($query->num_rows) {
			return array(
				'product_id'       => $query->row['product_id'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'location'         => $query->row['location'],
				'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
                'good_reviews'     => $query->row['good_reviews'] ? $query->row['good_reviews'] : 0,
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
                'status'           => $query->row['status'],
                'on_sale'          => $query->row['on_sale'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed'],
                'total_wish'       => $query->row['total_wish'] ? $query->row['total_wish'] : 0,
                'total_buyer'      => $query->row['total_buyer'] ? $query->row['total_buyer'] : 0,
                'total_sell'       => $query->row['total_sell'] ? $query->row['total_sell'] : 0
			);
		} else {
			return false;
		}
	}

	public function getProducts($data = array()) {
        if (!isset($data['filter_store_id'])) {
            $data['filter_store_id'] = $this->config->get('config_store_id');
        }

        $sql = "SELECT p.product_id, p2s.store_id, (SELECT SUM(op.quantity) as total FROM " . DB_PREFIX . "order_product op INNER JOIN " . DB_PREFIX . "order o ON op.order_id=o.order_id AND o.parent_id>0 AND o.order_status_id IN (2,3,5,15) WHERE op.product_id=p.product_id) AS total_sell, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00 00:00:00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00 00:00:00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

            //$sql .= " INNER JOIN " . DB_PREFIX . "category c ON p2c.category_id = c.category_id AND c.status = 1";
            $sql .= " INNER JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";

			/*if (!empty($data['filter_filter'])) {
				//$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";

                $filters = explode(',', $data['filter_filter']);

                foreach ($filters as $filter_id) {
                    $sql .= " INNER JOIN " . DB_PREFIX . "product_filter pf_".(int)$filter_id." ON (p.product_id = pf_".(int)$filter_id.".product_id) AND pf_".(int)$filter_id.".filter_id = '".(int)$filter_id."'";
                }
			}*/
			/* else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}*/
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

        if (!empty($data['filter_filter'])) {
            $implode = array();
            $filters = explode(',', $data['filter_filter']);

            foreach ($filters as $filter_id) {
                $implode[] = (int)$filter_id;
                //filter filter id AND
                $sql .= " INNER JOIN " . DB_PREFIX . "product_filter pf_".(int)$filter_id." ON (p.product_id = pf_".(int)$filter_id.".product_id) AND pf_".(int)$filter_id.".filter_id = '".(int)$filter_id."'";
            }

            //filter filter id OR
            //$sql .= " INNER JOIN " . DB_PREFIX . "product_filter pf ON (p.product_id = pf.product_id) AND pf.filter_id IN (" . implode(',', $implode) . ")";
        }

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "store s ON p2s.store_id=s.store_id WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2s.on_sale=1 AND p.date_available <= NOW()";

        if (isset($data['filter_preview']) && $data['filter_preview'] === true) {
            //shop preview
        } else {
            $sql .= " AND p.status = '1'";
        }

		if (!empty($data['filter_category_id'])) {
            $implode = array();
            foreach (explode(',', $data['filter_category_id']) as $category_id) {
                $implode[] = (int)$category_id;
            }

            if (!empty($data['filter_sub_category'])) {
                //$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
                $sql .= " AND cp.path_id IN (" . implode(',', $implode) . ")";
			} else {
                //$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
                $sql .= " AND p2c.category_id IN (" . implode(',', $implode) . ")";
			}

			/*if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}*/
		}

        if ((int)$data['filter_store_id'] > 0) {
            $sql .= " AND p2s.store_id = '" . (int)$data['filter_store_id'] . "'";
        } else {
            $sql .= " AND p2s.store_id > 0";
        }

        $sql .= " AND s.status = '1'";

        if (!empty($data['filter_type'])) {
            switch ($data['filter_type']) {
                case 'reward' :
                    //$sql .= " AND p2s.store_id = '" . (int)$data['filter_store_id'] . "' AND p.points > 0";
                    $sql .= "' AND p.points > 0";
                    break;
                case 'shop' :
                    //$sql .= " AND p2s.store_id > 0";
                    if (!empty($data['filter_name'])) {
                        $sql .= " AND s.name LIKE '%" .$this->db->escape($data['filter_name']). "%'";
                    }
                    break;
                default :
                    //$sql .= " AND p2s.store_id = '" . (int)$data['filter_store_id'] . "'";
            }
        } else {
            //$sql .= " AND p2s.store_id = '" . (int)$data['filter_store_id'] . "'";
        }

        if (!empty($data['filter_price'])) {
            $prices = explode('~', $data['filter_price']);
            if (count($prices) == 2) {
                $sql .= " AND p.price >= " . (int)$prices[0] . " AND p.price <= " . (int)$prices[1];
            } else {
                $sql .= " AND p.price >= " . (int)$prices[0];
            }
        }

		if ((empty($data['filter_type']) || $data['filter_type'] <> 'shop') && (!empty($data['filter_name']) || !empty($data['filter_tag']))) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

        if (!empty($data['filter_type']) && $data['filter_type'] == 'shop') {
            $sql .= " GROUP BY p2s.store_id";
        } else {
            $sql .= " GROUP BY p.product_id";
        }


		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added',
            'p.viewed',
            'total_sell'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id'], isset($data['filter_preview']) ? $data['filter_preview'] : false);
		}

		return $product_data;
	}

	public function getProductSpecials($data = array()) {
		$sql = "SELECT DISTINCT ps.product_id, (SELECT SUM(op.quantity) as total FROM " . DB_PREFIX . "order_product op INNER JOIN " . DB_PREFIX . "order o ON op.order_id=o.order_id AND o.parent_id>0 AND o.order_status_id IN (2,3,5,15) WHERE op.product_id=p2s.product_id) AS total_sell, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "store s ON p2s.store_id=s.store_id WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.on_sale = '1' AND s.status = '1' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00 00:00:00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00 00:00:00' OR ps.date_end > NOW()))";

        if ((int)$this->config->get('config_store_id') > 0) {
            $sql .= " AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
        } else {
            $sql .= " AND p2s.store_id > 0";
        }

        if (isset($data['filter_type'])) {
            switch ($data['filter_type']) {
                case 'limit' :
                    $sql .= " AND ps.date_end <> '0000-00-00 00:00:00'";
                    break;
                case 'infinite' :
                    $sql .= " AND ps.date_end = '0000-00-00 00:00:00'";
                    break;
            }
        }

        if (!empty($data['filter_price'])) {
            $prices = explode('~', $data['filter_price']);
            if (count($prices) == 2) {
                $sql .= " AND p.price >= " . (int)$prices[0] . " AND p.price <= " . (int)$prices[1];
            } else {
                $sql .= " AND p.price >= " . (int)$prices[0];
            }
        }

        //$sql .= " GROUP BY ps.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order',
            'p.date_added',
            'p.viewed',
            'total_sell'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

    public function getProductAuctions($data = array()) {
        $sql = "SELECT pa.*, (SELECT COUNT(*) as total FROM " . DB_PREFIX . "product_bidding pd WHERE pd.product_auction_id=pa.product_auction_id) as total_bidding, (SELECT if(MAX(pd1.price), MAX(pd1.price), pa.base_price) as price FROM " . DB_PREFIX . "product_bidding pd1 WHERE pd1.product_auction_id=pa.product_auction_id) as max_bidding, (SELECT SUM(op.quantity) as total FROM " . DB_PREFIX . "order_product op INNER JOIN " . DB_PREFIX . "order o ON op.order_id=o.order_id AND o.parent_id>0 AND o.order_status_id IN (2,3,5,15) WHERE op.product_id=p2s.product_id) AS total_sell, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = pa.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product_auction pa LEFT JOIN " . DB_PREFIX . "product p ON (pa.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "store s ON p2s.store_id=s.store_id WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.on_sale = '1' AND s.status = '1' AND pa.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'";

        if ((int)$this->config->get('config_store_id') > 0) {
            $sql .= " AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
        } else {
            $sql .= " AND p2s.store_id > 0";
        }

        if (isset($data['filter_type']) && !empty($data['filter_type'])) {
            switch ($data['filter_type']) {
                case 'incoming' :
                    $sql .= " AND pa.date_start > NOW() AND pa.date_end > NOW()";
                    break;
                case 'bidding' :
                    $sql .= " AND pa.date_start < NOW() AND pa.date_end > NOW()";
                    break;
                case 'finished' :
                    $sql .= " AND pa.date_end < NOW()";
                    break;
                default :
                    $sql .= " AND pa.date_end > NOW()";
                    break;
            }
        } else {
            //$sql .= " AND pa.date_end > NOW()";
        }

        if (isset($data['filter_product_id'])) {
            $sql .= " AND pa.product_id = '".(int)$data['filter_product_id']."'";
        }

        if (!empty($data['filter_price'])) {
            $prices = explode('~', $data['filter_price']);
            if (count($prices) == 2) {
                $sql .= " AND p.price >= " . (int)$prices[0] . " AND p.price <= " . (int)$prices[1];
            } else {
                $sql .= " AND p.price >= " . (int)$prices[0];
            }
        }

        //$sql .= " GROUP BY pa.product_id";

        $sort_data = array(
            'pd.name',
            'p.model',
            'pa.base_price',
            'rating',
            'p.sort_order',
            'pa.date_start',
            'pa.date_end',
            'p.date_added',
            'p.viewed',
            'total_sell',
            'total_bidding',
            'max_bidding'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
                $sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
            } else {
                $sql .= " ORDER BY " . $data['sort'];
            }
        } else {
            $sql .= " ORDER BY pa.date_end";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC, LCASE(pd.name) DESC";
        } else {
            $sql .= " ASC, LCASE(pd.name) ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $product_data = array();

        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $product = $this->getProduct($result['product_id']);
            $product['auction_id'] = $result['product_auction_id'];
            $product['base_price'] = $result['base_price'];
            $product['auction_start'] = $result['date_start'];
            $product['auction_end'] = $result['date_end'];
            $product['total_bidding'] = $result['total_bidding'];
            $product['max_bidding'] = $result['max_bidding'];
            $product_data[] = $product;
        }

        return $product_data;
    }

	public function getLatestProducts($limit) {
		$product_data = $this->cache->get('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$product_data) {
			$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p2s.on_sale=1 ORDER BY p.date_added DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getPopularProducts($limit, $store_id = 0) {
		if ($store_id == 0) $store_id = $this->config->get('config_store_id');
        $product_data = array();

		$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$store_id . "' AND p2s.on_sale=1 ORDER BY p.viewed DESC, p.date_added DESC LIMIT " . (int)$limit);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getBestSellerProducts($limit, $store_id = 0) {
		if ($store_id == 0) $store_id = $this->config->get('config_store_id');

        $product_data = $this->cache->get('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$store_id . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$product_data) {
			$product_data = array();

			$query = $this->db->query("SELECT op.product_id, SUM(op.quantity) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$store_id . "' AND p2s.on_sale=1 GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getProductAttributes($product_id) {
		$product_attribute_group_data = array();

		$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

		foreach ($product_attribute_group_query->rows as $product_attribute_group) {
			$product_attribute_data = array();

			$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");

			foreach ($product_attribute_query->rows as $product_attribute) {
				$product_attribute_data[] = array(
					'attribute_id' => $product_attribute['attribute_id'],
					'name'         => $product_attribute['name'],
					'text'         => $product_attribute['text']
				);
			}

			$product_attribute_group_data[] = array(
				'attribute_group_id' => $product_attribute_group['attribute_group_id'],
				'name'               => $product_attribute_group['name'],
				'attribute'          => $product_attribute_data
			);
		}

		return $product_attribute_group_data;
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'name'                    => $product_option_value['name'],
					'image'                   => $product_option_value['image'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}

	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

		return $query->rows;
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getProductRelated($product_id, $store_id = 0) {
		if ($store_id == 0) $store_id = $this->config->get('config_store_id');

        $product_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$store_id . "' AND p2s.on_sale=1 LIMIT 4");

		foreach ($query->rows as $result) {
			$product_data[$result['related_id']] = $this->getProduct($result['related_id']);
		}

		return $product_data;
	}

	public function getProductLayoutId($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function getCategories($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}

	public function getTotalProducts($data = array()) {
		if (!isset($data['filter_store_id'])) {
            $data['filter_store_id'] = $this->config->get('config_store_id');
        }

        if (!empty($data['filter_type']) && $data['filter_type'] == 'shop') {
            $sql = "SELECT COUNT(DISTINCT p2s.store_id) AS total";
        } else {
            $sql = "SELECT COUNT(DISTINCT p.product_id) AS total";
        }

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

            $sql .= " INNER JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";

			/*if (!empty($data['filter_filter'])) {
				//$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
                $filters = explode(',', $data['filter_filter']);

                foreach ($filters as $filter_id) {
                    $sql .= " INNER JOIN " . DB_PREFIX . "product_filter pf_".(int)$filter_id." ON (p.product_id = pf_".(int)$filter_id.".product_id) AND pf_".(int)$filter_id.".filter_id = '".(int)$filter_id."'";
                }
			}*/
			/* else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}*/
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

        if (!empty($data['filter_filter'])) {
            $implode = array();
            $filters = explode(',', $data['filter_filter']);

            foreach ($filters as $filter_id) {
                $implode[] = (int)$filter_id;
                //filter filter id AND
                $sql .= " INNER JOIN " . DB_PREFIX . "product_filter pf_".(int)$filter_id." ON (p.product_id = pf_".(int)$filter_id.".product_id) AND pf_".(int)$filter_id.".filter_id = '".(int)$filter_id."'";
            }

            //filter filter id OR
            //$sql .= " INNER JOIN " . DB_PREFIX . "product_filter pf ON (p.product_id = pf.product_id) AND pf.filter_id IN (" . implode(',', $implode) . ")";
        }

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "store s ON p2s.store_id=s.store_id WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p2s.on_sale=1 AND p.date_available <= NOW()";

		if (!empty($data['filter_category_id'])) {
            $implode = array();
            foreach (explode(',', $data['filter_category_id']) as $category_id) {
                $implode[] = (int)$category_id;
            }

            if (!empty($data['filter_sub_category'])) {
                //$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
                $sql .= " AND cp.path_id IN (" . implode(',', $implode) . ")";
			} else {
                //$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
                $sql .= " AND p2c.category_id IN (" . implode(',', $implode) . ")";
			}

			/*if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}*/
		}

        if ((int)$data['filter_store_id'] > 0) {
            $sql .= " AND p2s.store_id = '" . (int)$data['filter_store_id'] . "'";
        } else {
            $sql .= " AND p2s.store_id > 0";
        }

        $sql .= " AND s.status = '1'";

        if (!empty($data['filter_type'])) {
            switch ($data['filter_type']) {
                case 'reward' :
                    $sql .= "' AND p.points > 0";
                    break;
                case 'shop' :
                    //$sql .= " AND p2s.store_id > 0";
                    if (!empty($data['filter_name'])) {
                        $sql .= " AND s.name LIKE '%" .$this->db->escape($data['filter_name']). "%'";
                    }
                    break;
                default :
                    //$sql .= " AND p2s.store_id = '" . (int)$data['filter_store_id'] . "'";
            }
        } else {
            //$sql .= " AND p2s.store_id = '" . (int)$data['filter_store_id'] . "'";
        }

        if (!empty($data['filter_price'])) {
            $prices = explode('~', $data['filter_price']);
            if (count($prices) == 2) {
                $sql .= " AND p.price >= " . (int)$prices[0] . " AND p.price <= " . (int)$prices[1];
            } else {
                $sql .= " AND p.price >= " . (int)$prices[0];
            }
        }

        if ((empty($data['filter_type']) || $data['filter_type'] <> 'shop') && (!empty($data['filter_name']) || !empty($data['filter_tag']))) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getProfiles($product_id) {
		return $this->db->query("SELECT `pd`.* FROM `" . DB_PREFIX . "product_recurring` `pp` JOIN `" . DB_PREFIX . "recurring_description` `pd` ON `pd`.`language_id` = " . (int)$this->config->get('config_language_id') . " AND `pd`.`recurring_id` = `pp`.`recurring_id` JOIN `" . DB_PREFIX . "recurring` `p` ON `p`.`recurring_id` = `pd`.`recurring_id` WHERE `product_id` = " . (int)$product_id . " AND `status` = 1 AND `customer_group_id` = " . (int)$this->config->get('config_customer_group_id') . " ORDER BY `sort_order` ASC")->rows;
	}

	public function getProfile($product_id, $recurring_id) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "recurring` `p` JOIN `" . DB_PREFIX . "product_recurring` `pp` ON `pp`.`recurring_id` = `p`.`recurring_id` AND `pp`.`product_id` = " . (int)$product_id . " WHERE `pp`.`recurring_id` = " . (int)$recurring_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . (int)$this->config->get('config_customer_group_id'))->row;
	}

	public function getTotalProductSpecials($data = array()) {
		$sql = "SELECT COUNT(DISTINCT ps.product_id) AS total FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "store s ON p2s.store_id=s.store_id WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.on_sale = '1' AND s.status = '1' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00 00:00:00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00 00:00:00' OR ps.date_end > NOW()))";

        if ((int)$this->config->get('config_store_id') > 0) {
            $sql .= " AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
        } else {
            $sql .= " AND p2s.store_id > 0";
        }

        if (isset($data['filter_type'])) {
            switch ($data['filter_type']) {
                case 'limit' :
                    $sql .= " AND ps.date_end <> '0000-00-00 00:00:00'";
                    break;
                case 'infinite' :
                    $sql .= " AND ps.date_end = '0000-00-00 00:00:00'";
                    break;
            }
        }

        if (!empty($data['filter_price'])) {
            $prices = explode('~', $data['filter_price']);
            if (count($prices) == 2) {
                $sql .= " AND p.price >= " . (int)$prices[0] . " AND p.price <= " . (int)$prices[1];
            } else {
                $sql .= " AND p.price >= " . (int)$prices[0];
            }
        }

        $query = $this->db->query($sql);

        if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}

    public function getTotalProductAuctions() {
        $sql = "SELECT COUNT(pa.product_id) AS total FROM " . DB_PREFIX . "product_auction pa LEFT JOIN " . DB_PREFIX . "product p ON (pa.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "store s ON p2s.store_id=s.store_id WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.on_sale = '1' AND s.status = '1' AND pa.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'";

        if ((int)$this->config->get('config_store_id') > 0) {
            $sql .= " AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
        } else {
            $sql .= " AND p2s.store_id > 0";
        }

        if (isset($data['filter_type']) && !empty($data['filter_type'])) {
            switch ($data['filter_type']) {
                case 'incoming' :
                    $sql .= " AND pa.date_start > NOW() AND pa.date_end > NOW()";
                    break;
                case 'bidding' :
                    $sql .= " AND pa.date_start < NOW() AND pa.date_end > NOW()";
                    break;
                case 'finished' :
                    $sql .= " AND pa.date_end < NOW()";
                    break;
                default :
                    $sql .= " AND pa.date_end > NOW()";
                    break;
            }
        } else {
            //$sql .= " AND pa.date_end > NOW()";
        }

        if (isset($data['filter_product_id'])) {
            $sql .= " AND pa.product_id = '".(int)$data['filter_product_id']."'";
        }

        if (!empty($data['filter_price'])) {
            $prices = explode('~', $data['filter_price']);
            if (count($prices) == 2) {
                $sql .= " AND p.price >= " . (int)$prices[0] . " AND p.price <= " . (int)$prices[1];
            } else {
                $sql .= " AND p.price >= " . (int)$prices[0];
            }
        }

        $query = $this->db->query($sql);

        if (isset($query->row['total'])) {
            return $query->row['total'];
        } else {
            return 0;
        }
    }

    public function getProductSpecial($product_id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = '".(int)$product_id."' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00 00:00:00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00 00:00:00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1";
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getProductAuction($product_id) {
        $sql = "SELECT pa.*, p.price AS product_price FROM " . DB_PREFIX . "product_auction pa INNER JOIN " . DB_PREFIX . "product p ON pa.product_id = p.product_id WHERE pa.product_id = '".(int)$product_id."' AND pa.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((pa.date_start = '0000-00-00 00:00:00' OR pa.date_start < NOW()) AND (pa.date_end > NOW())) ORDER BY pa.priority ASC, pa.date_end ASC LIMIT 1";
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getProductBidding($auction_id) {
        $sql = "SELECT pb.*, c.fullname FROM " . DB_PREFIX . "product_bidding pb LEFT JOIN " . DB_PREFIX . "customer c ON pb.customer_id = c.customer_id WHERE pb.product_auction_id = '".(int)$auction_id."' ORDER BY pb.product_bidding_id DESC LIMIT 1";
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function addProductBidding($data) {
        $sql = "INSERT INTO " . DB_PREFIX . "product_bidding SET product_auction_id = '".(int)$data['auction_id']."', product_id = '".(int)$data['product_id']."', customer_id = '".(int)$this->customer->getId()."', price = '".(float)$data['price']."', date_added = NOW()";
        $this->db->query($sql);
        $bidding_id = $this->db->getLastId();

        return $bidding_id;
    }

    public function getProductStores($product_id) {
        $query = $this->db->query("SELECT p2s.*, s.name AS shop_name, s.key AS shop_key FROM " . DB_PREFIX . "product_to_store AS p2s LEFT JOIN " . DB_PREFIX . "store AS s ON p2s.store_id = s.store_id WHERE p2s.product_id = '" . (int)$product_id . "' ORDER BY p2s.store_id ASC");
        return $query->rows;
    }

    public function getProductStore($product_id) {
        $query = $this->db->query("SELECT p2s.*, s.name AS shop_name, s.key AS shop_key, s.customer_id AS customer_id, s.status AS shop_status, c.fullname, c.custom_field, (SELECT st.value FROM " . DB_PREFIX . "setting AS st WHERE st.store_id = p2s.store_id AND st.code = 'config' AND st.key='config_icon') AS shop_image, (SELECT st2.value FROM " . DB_PREFIX . "setting AS st2 WHERE st2.store_id = p2s.store_id AND st2.code = 'config' AND st2.key='config_logo') AS shop_logo, (SELECT st1.value FROM " . DB_PREFIX . "setting AS st1 WHERE st1.store_id = p2s.store_id AND st1.code = 'config' AND st1.key='config_comment') AS shop_comment FROM " . DB_PREFIX . "product_to_store AS p2s LEFT JOIN " . DB_PREFIX . "store AS s ON p2s.store_id = s.store_id LEFT JOIN " . DB_PREFIX . "customer AS c ON s.customer_id = c.customer_id WHERE p2s.store_id > 0 AND p2s.product_id = '" . (int)$product_id . "' ORDER BY p2s.store_id ASC LIMIT 1");
        return $query->row;
    }

    public function getTotalProductsByDownloadId($download_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_download WHERE download_id = '" . (int)$download_id . "'");

        return $query->row['total'];
    }

    public function getProductTransactions($product_id, $start = 0, $limit = 20, $filter = array()) {
        $sql = "SELECT op.*, o.date_added, c.fullname, c.custom_field FROM " . DB_PREFIX . "order_product op INNER JOIN " . DB_PREFIX . "order o ON op.order_id = o.order_id LEFT JOIN " . DB_PREFIX . "customer c ON o.customer_id = c.customer_id WHERE o.parent_id > 0 AND o.order_status_id IN (2,3,5,15) AND op.product_id = '".(int)$product_id."'";

        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 20;
        }

        $sql .= " ORDER BY o.date_added DESC LIMIT " . (int)$start . "," . (int)$limit;

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalProductTransactions($product_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN " . DB_PREFIX . "order o ON op.order_id = o.order_id WHERE o.store_id > 0 AND o.order_status_id IN (2,3,5,15) AND op.product_id = '".(int)$product_id."' ORDER BY o.date_added");

        return $query->row['total'];
    }
}
