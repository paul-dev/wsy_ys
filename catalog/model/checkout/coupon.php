<?php
class ModelCheckoutCoupon extends Model {
	public function getCoupon($code) {
		$status = true;

		$coupon_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon` WHERE code = '" . $this->db->escape($code) . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) AND status = '1'");

		if ($coupon_query->num_rows) {
			if ($coupon_query->row['total'] > $this->cart->getSubTotal($coupon_query->row['store_id'] ? $coupon_query->row['store_id'] : false)) {
				$status = false;
			}

			$coupon_history_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "coupon_history` ch WHERE ch.coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");

			if ($coupon_query->row['uses_total'] > 0 && ($coupon_history_query->row['total'] >= $coupon_query->row['uses_total'])) {
				$status = false;
			}

			if ($coupon_query->row['logged'] && !$this->customer->getId()) {
				$status = false;
			}

			if ($this->customer->getId()) {
				$coupon_history_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "coupon_history` ch WHERE ch.coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "' AND ch.customer_id = '" . (int)$this->customer->getId() . "'");

				if ($coupon_query->row['uses_customer'] > 0 && ($coupon_history_query->row['total'] >= $coupon_query->row['uses_customer'])) {
					$status = false;
				}
			}

			// Products
			$coupon_product_data = array();

			$coupon_product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_product` WHERE coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");

			foreach ($coupon_product_query->rows as $product) {
				$coupon_product_data[] = $product['product_id'];
			}

			// Categories
			$coupon_category_data = array();

			$coupon_category_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_category` cc LEFT JOIN `" . DB_PREFIX . "category_path` cp ON (cc.category_id = cp.path_id) WHERE cc.coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");

			foreach ($coupon_category_query->rows as $category) {
				$coupon_category_data[] = $category['category_id'];
			}

			$product_data = array();

			if ($coupon_product_data || $coupon_category_data || $coupon_query->row['store_id'] > 0) {
				foreach ($this->cart->getProducts() as $product) {
					if (in_array($product['product_id'], $coupon_product_data)) {
						$product_data[] = $product['product_id'];

						continue;
					}

					foreach ($coupon_category_data as $category_id) {
						$coupon_category_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id` = '" . (int)$product['product_id'] . "' AND category_id = '" . (int)$category_id . "'");

						if ($coupon_category_query->row['total']) {
							$product_data[] = $product['product_id'];

							continue;
						}
					}

                    if (!$coupon_product_data && !$coupon_category_data && $product['shop_id'] == $coupon_query->row['store_id']) {
                        $product_data[] = $product['product_id'];
                    }
				}

				if (!$product_data) {
					$status = false;
				}
			}
		} else {
			$status = false;
		}

		if ($status) {
			return array(
				'coupon_id'     => $coupon_query->row['coupon_id'],
				'code'          => $coupon_query->row['code'],
				'name'          => $coupon_query->row['name'],
				'type'          => $coupon_query->row['type'],
				'discount'      => $coupon_query->row['discount'],
				'shipping'      => $coupon_query->row['shipping'],
				'total'         => $coupon_query->row['total'],
				'product'       => $product_data,
				'date_start'    => $coupon_query->row['date_start'],
				'date_end'      => $coupon_query->row['date_end'],
				'uses_total'    => $coupon_query->row['uses_total'],
				'uses_customer' => $coupon_query->row['uses_customer'],
				'status'        => $coupon_query->row['status'],
				'date_added'    => $coupon_query->row['date_added']
			);
		}
	}

    public function getCouponById($coupon_id = 0) {
        $status = true;

        $coupon_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon` WHERE coupon_id = '" . (int)$coupon_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) AND status = '1'");

        if ($coupon_query->num_rows) {
            if ($coupon_query->row['total'] > $this->cart->getSubTotal($coupon_query->row['store_id'] ? $coupon_query->row['store_id'] : false)) {
                $status = false;
            }

            $coupon_history_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "coupon_history` ch WHERE ch.coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");

            if ($coupon_query->row['uses_total'] > 0 && ($coupon_history_query->row['total'] >= $coupon_query->row['uses_total'])) {
                $status = false;
            }

            if ($coupon_query->row['logged'] && !$this->customer->getId()) {
                $status = false;
            }

            if ($this->customer->getId()) {
                $coupon_history_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "coupon_history` ch WHERE ch.coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "' AND ch.customer_id = '" . (int)$this->customer->getId() . "'");

                if ($coupon_query->row['uses_customer'] > 0 && ($coupon_history_query->row['total'] >= $coupon_query->row['uses_customer'])) {
                    $status = false;
                }
            }

            // Products
            $coupon_product_data = array();

            $coupon_product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_product` WHERE coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");

            foreach ($coupon_product_query->rows as $product) {
                $coupon_product_data[] = $product['product_id'];
            }

            // Categories
            $coupon_category_data = array();

            $coupon_category_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_category` cc LEFT JOIN `" . DB_PREFIX . "category_path` cp ON (cc.category_id = cp.path_id) WHERE cc.coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");

            foreach ($coupon_category_query->rows as $category) {
                $coupon_category_data[] = $category['category_id'];
            }

            $product_data = array();

            if ($coupon_product_data || $coupon_category_data || $coupon_query->row['store_id'] > 0) {
                foreach ($this->cart->getProducts() as $product) {
                    if (in_array($product['product_id'], $coupon_product_data)) {
                        $product_data[] = $product['product_id'];

                        continue;
                    }

                    foreach ($coupon_category_data as $category_id) {
                        $coupon_category_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id` = '" . (int)$product['product_id'] . "' AND category_id = '" . (int)$category_id . "'");

                        if ($coupon_category_query->row['total']) {
                            $product_data[] = $product['product_id'];

                            continue;
                        }
                    }

                    if (!$coupon_product_data && !$coupon_category_data && $product['shop_id'] == $coupon_query->row['store_id']) {
                        $product_data[] = $product['product_id'];
                    }
                }

                if (!$product_data) {
                    $status = false;
                }
            }
        } else {
            $status = false;
        }

        if ($status) {
            return array(
                'coupon_id'     => $coupon_query->row['coupon_id'],
                'store_id'      => $coupon_query->row['store_id'],
                'code'          => $coupon_query->row['code'],
                'name'          => $coupon_query->row['name'],
                'type'          => $coupon_query->row['type'],
                'discount'      => $coupon_query->row['discount'],
                'shipping'      => $coupon_query->row['shipping'],
                'total'         => $coupon_query->row['total'],
                'product'       => $product_data,
                'date_start'    => $coupon_query->row['date_start'],
                'date_end'      => $coupon_query->row['date_end'],
                'uses_total'    => $coupon_query->row['uses_total'],
                'uses_customer' => $coupon_query->row['uses_customer'],
                'status'        => $coupon_query->row['status'],
                'date_added'    => $coupon_query->row['date_added']
            );
        }
    }

    public function getCoupons($store_ids = 0) {
        if (!is_array($store_ids)) $store_ids = array($store_ids);
        if (empty($store_ids)) $store_ids = array(0);

        $coupon_valid = array();

        $coupon_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon` WHERE store_id IN ('".join("','", $store_ids)."') AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) AND status = '1'");

        if ($coupon_query->num_rows) {
            foreach ($coupon_query->rows as $coupon_info) {
                $status = true;

                if ($coupon_info['total'] > $this->cart->getSubTotal($coupon_info['store_id'] ? $coupon_info['store_id'] : false)) {
                    $status = false;
                }

                $coupon_history_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "coupon_history` ch WHERE ch.coupon_id = '" . (int)$coupon_info['coupon_id'] . "'");

                if ($coupon_info['uses_total'] > 0 && ($coupon_history_query->row['total'] >= $coupon_info['uses_total'])) {
                    $status = false;
                }

                if ($coupon_info['logged'] && !$this->customer->getId()) {
                    $status = false;
                }

                if ($this->customer->getId()) {
                    $coupon_history_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "coupon_history` ch WHERE ch.coupon_id = '" . (int)$coupon_info['coupon_id'] . "' AND ch.customer_id = '" . (int)$this->customer->getId() . "'");

                    if ($coupon_info['uses_customer'] > 0 && ($coupon_history_query->row['total'] >= $coupon_info['uses_customer'])) {
                        $status = false;
                    }
                }

                // Products
                $coupon_product_data = array();

                $coupon_product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_product` WHERE coupon_id = '" . (int)$coupon_info['coupon_id'] . "'");

                foreach ($coupon_product_query->rows as $product) {
                    $coupon_product_data[] = $product['product_id'];
                }

                // Categories
                $coupon_category_data = array();

                $coupon_category_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_category` cc LEFT JOIN `" . DB_PREFIX . "category_path` cp ON (cc.category_id = cp.path_id) WHERE cc.coupon_id = '" . (int)$coupon_info['coupon_id'] . "'");

                foreach ($coupon_category_query->rows as $category) {
                    $coupon_category_data[] = $category['category_id'];
                }

                $product_data = array();

                if ($coupon_product_data || $coupon_category_data || $coupon_info['store_id'] > 0) {
                    foreach ($this->cart->getProducts() as $product) {
                        if (in_array($product['product_id'], $coupon_product_data)) {
                            $product_data[] = $product['product_id'];

                            continue;
                        }

                        foreach ($coupon_category_data as $category_id) {
                            $coupon_category_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id` = '" . (int)$product['product_id'] . "' AND category_id = '" . (int)$category_id . "'");

                            if ($coupon_category_query->row['total']) {
                                $product_data[] = $product['product_id'];

                                continue;
                            }
                        }

                        if (!$coupon_product_data && !$coupon_category_data && $product['shop_id'] == $coupon_info['store_id']) {
                            $product_data[] = $product['product_id'];
                        }
                    }

                    if (!$product_data) {
                        $status = false;
                    }
                }

                if ($status) {
                    $coupon_valid[$coupon_info['store_id']][] = array(
                        'coupon_id'     => $coupon_info['coupon_id'],
                        'code'          => $coupon_info['code'],
                        'name'          => $coupon_info['name'],
                        'type'          => $coupon_info['type'],
                        'discount'      => $coupon_info['discount'],
                        'shipping'      => $coupon_info['shipping'],
                        'total'         => $coupon_info['total'],
                        'product'       => $product_data,
                        'date_start'    => $coupon_info['date_start'],
                        'date_end'      => $coupon_info['date_end'],
                        'uses_total'    => $coupon_info['uses_total'],
                        'uses_customer' => $coupon_info['uses_customer'],
                        'status'        => $coupon_info['status'],
                        'date_added'    => $coupon_info['date_added']
                    );
                }
            }
        }

        return $coupon_valid;
    }
}